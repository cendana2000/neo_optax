<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'produk/ProdukModel' 				 => 'produk',
			'produk/ProduksatuanModel'			 => 'produksatuan',
			'produk/ProdukbarcodeModel'			 => 'produkbarcode',
			'kategori/KategoriModel' => 'kategori',
		));
	}

	public function index()
	{
		$where['barang_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'produk', 'table')
		);
	}

	public function index_barcode()
	{
		$this->response(
			$this->select_dt(varPost(), 'produk', 'table')
		);
	}

	public function index_produk_barcode()
	{
		$this->response(
			$this->select_dt(varPost(), 'produk', 'produk_barcode', false, varPost('tfilter'))
		);
	}

	public function read($value = '')
	{
		$barang = $this->produk->read(varPost());
		$satuan = [];
		if ($barang) {
			$satuan = $this->produksatuan->select(array('filters_static' => array('barang_satuan_parent' => $barang['barang_id']), 'sort_static' => 'barang_satuan_order asc'));
		}
		$barang['satuan'] = $satuan['data'];
		$this->response($barang);
	}

	public function single_read($value = '')
	{
		$produk = $this->produk->read(varPost());
		$this->response($produk);
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->produkbarcode->read(array('produk_barcode_kode' => $data['q']));
			if (isset($barcode['produk_kode'])) $data['q'] = $barcode['produk_kode'];
		}
		$where = ($data['fdata']['produk_supplier_id']) ? 'produk_supplier_id = "' . $data['fdata']['produk_supplier_id'] . '" AND ' : '';
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(produk_id) total FROM pos_produk WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR produk_kode like "' . $data['q'] . '%") ')->result_array();
		$return = $this->db->query('SELECT produk_id as id, concat(produk_kode, " - ", barang_nama) as text, produk_is_konsinyasi as saved FROM v_pos_produk WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR produk_kode like "' . $data['q'] . '%") ORDER BY barang_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	function select($value = '')
	{
		$this->response($this->produk->select(array('filters_static' => varPost(), 'sort_static' => 'barang_nama asc')));
	}

	public function store()
	{
		$data = varPost();
		$detail = [];
		$data['barang_kode'] = ($data['barang_kode']) ? $data['barang_kode'] : $this->barang->gen_kode(false, $data['kategori_kode']);
		$data['barang_user'] = $this->session->userdata('user_username');
		$data['barang_aktif'] = '1';
		$data['barang_is_konsinyasi'] = (!isset($data['barang_is_konsinyasi']) ? '1' : '0');
		$id = gen_uuid($this->produk->get_table());
		$new_data = $data;
		$new_data['barang_satuan'] = $data['barang_satuan'][1];
		$new_data['barang_satuan_kode'] = $data['barang_satuan_kode'][1];
		$new_data['barang_harga'] = $data['barang_satuan_harga_jual'][1];
		$new_data['barang_disc'] = $data['barang_satuan_disc'][1];
		$new_data['barang_satuan_opt'] = $data['barang_satuan_kode'][2];
		$new_data['barang_satuan_opt_kode'] = $data['barang_satuan_kode'][2];
		$new_data['barang_harga_opt'] = $data['barang_satuan_harga_jual'][2];
		$new_data['barang_satuan_opt2_kode'] = $data['barang_satuan_kode'][3];
		$new_data['barang_harga_opt2'] = $data['barang_satuan_harga_jual'][3];
		$new_data['barang_yearly'] = 1;
		$detail = [];
		$kategori_p = $this->getParent($data['barang_kategori_barang']);
		$new_data['barang_kategori_parent'] = $kategori_p;
		foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
			$detail[] = [
				'barang_satuan_id' 			=> gen_uuid($this->produksatuan->get_table()),
				'barang_satuan_parent' 		=> $id,
				'barang_satuan_satuan_id' 	=> $value,
				'barang_satuan_kode' 		=> $data['barang_satuan_kode'][$key],
				'barang_satuan_konversi' 	=> $data['barang_satuan_konversi'][$key],
				'barang_satuan_harga_beli'	=> $data['barang_satuan_harga_beli'][$key],
				'barang_satuan_keuntungan'	=> $data['barang_satuan_keuntungan'][$key],
				'barang_satuan_harga_jual'	=> $data['barang_satuan_harga_jual'][$key],
				'barang_satuan_disc'		=> $data['barang_satuan_disc'][$key],
				'barang_satuan_order'		=> $key,
			];
		}

		$data = array_merge($data, $new_data);
		/*print_r($data);*/
		$operation = $this->produk->insert($id, $data, function ($res) use ($detail) {
			$update = $this->db->insert_batch('pos_barang_satuan', $detail);
			// $res_detail = $this->barangsatuan->update($data['barang_satuan_id'][$key], $detail);				
			if (!$update) {
				$res['message'] = 'Berhasil menyimpan dengan error[satuan gagal diperbarui]';
			}
		});
		$this->response($operation);
	}


	public function update()
	{
		$data = varPost();
		$data['produk_is_konsinyasi'] = (!isset($data['produk_is_konsinyasi']) ? '1' : '0');
		$new_data = $data;
		$new_data['produk_satuan'] = $data['produk_satuan'][1];
		$new_data['produk_satuan_kode'] = $data['produk_satuan_kode'][1];
		$new_data['produk_harga'] = $data['produk_satuan_harga_jual'][1];
		$new_data['produk_disc'] = $data['produk_satuan_disc'][1];
		$new_data['produk_satuan_opt'] = $data['produk_satuan_kode'][2];
		$new_data['produk_satuan_opt_kode'] = $data['produk_satuan_kode'][2];
		$new_data['produk_harga_opt'] = $data['produk_satuan_harga_jual'][2];
		$new_data['produk_satuan_opt2_kode'] = $data['produk_satuan_kode'][3];
		$new_data['produk_harga_opt2'] = $data['produk_satuan_harga_jual'][3];
		$new_data['produk_yearly'] = 1;
		$detail = $insert_detail = [];
		$kategori_p = $this->getParent($data['produk_kategori_produk']);
		$new_data['produk_kategori_parent'] = $kategori_p;
		// print_r($kategori);exit;
		// $dt = $res['record'];
		foreach ($data['produk_satuan_satuan_id'] as $key => $value) {
			$set = [
				'produk_satuan_id' 			=> $data['produk_satuan_id'][$key],
				'produk_satuan_parent' 		=> $data['produk_id'],
				'produk_satuan_satuan_id' 	=> $value,
				'produk_satuan_kode' 		=> $data['produk_satuan_kode'][$key],
				'produk_satuan_konversi' 	=> $data['produk_satuan_konversi'][$key],
				'produk_satuan_harga_beli'	=> $data['produk_satuan_harga_beli'][$key],
				'produk_satuan_keuntungan'	=> $data['produk_satuan_keuntungan'][$key],
				'produk_satuan_harga_jual'	=> $data['produk_satuan_harga_jual'][$key],
				'produk_satuan_disc'		=> $data['produk_satuan_disc'][$key],
				'produk_satuan_order'		=> $key,
			];
			$detail[] = $set;
			if (!$data['produk_satuan_id'][$key] && $value) {
				$insert_detail[] = array_merge($set, ['produk_satuan_id' => gen_uuid($this->produksatuan->get_table())]);
			}
		}
		// print_r($detail);exit;
		$data = array_merge($data, $new_data);
		$operation = $this->produk->update(varPost('id', varExist($data, $this->produk->get_primary(true))), $data, function (&$res) use ($detail, $insert_detail) {
			$update = $this->db->update_batch('pos_produk_satuan', $detail, 'produk_satuan_id');
			// $res_detail = $this->produksatuan->update($data['produk_satuan_id'][$key], $detail);				
			if (!$update) {
				$res['message'] = 'Berhasil menyimpan dengan error[satuan gagal diperbarui]';
			}
			if ($insert_detail) {
				$inset_detail = $this->db->insert_batch('pos_produk_satuan', $insert_detail);
			}
		});
		$this->response($operation);
	}
	public function getParent($kategori)
	{
		// echo '<p>'.$kategori.'</p>';
		$db_kategori = $this->db->select('kategori_barang_id,kategori_barang_parent')->get_where('pos_kategori', ['kategori_barang_id' => $kategori])->result_array();
		$parent = [];
		/*if($db_kategori[0]['kategori_barang_parent'] <> '#'){
			$parent[] = $db_kategori[0]['kategori_barang_parent'];
			$new = $this->getParent($db_kategori[0]['kategori_barang_parent']);
			if($new) $parent = array_merge($parent,$new);
		}*/
		return ($db_kategori[0]['kategori_barang_parent'] ?? null);
	}
	public function new($kategori)
	{
		print_r($this->getParent($kategori));
	}
	public function destroy()
	{
		$data = varPost();
		$operation = $this->produk->delete(varPost('id', varExist($data, $this->produk->get_primary(true))));
		$this->response($operation);
	}

	public function delete_barcode()
	{
		$data = varPost();
		// print_r($data);exit;
		$operation = $this->produkbarcode->delete($data['produk_barcode_id']);
		$this->response($operation);
	}

	public function list_satuan($value = '')
	{
		$data = varPost();
		$operation = $this->produksatuan->select(array('filters_static' => array('produk_satuan_parent' => $data['produk_id']), 'sort_static' => 'produk_satuan_order asc'));
		$this->response($operation);
	}
	public function list_satuan_harga($value = '')
	{
		$data = varPost();
		$harga = $this->db->select('pos_produk_satuan.*, pos_produk.produk_harga_pokok')
			->where(['produk_satuan_parent' => $data['produk_id']])
			->join('pos_produk', 'produk_id = produk_satuan_parent', 'left')
			->order_by('produk_satuan_order')
			->get('pos_produk_satuan')->result_array();
		$this->response(['data' => $harga]);
	}
	public function get_barcode()
	{
		$data = varPost();
		$barcode = $this->produkbarcode->read($data);
		$this->response($barcode);
	}
	public function delete()
	{
		$data['barang_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->produk->update($data['id'], $data);
		print_r($operation);
		exit;
		$this->response($operation);
	}
	public function createBarcode()
	{
		$data = varPost();
		$data['produk_barcode_tanggal'] = date('Y-m-d H:i:s');
		$operation = $this->produkbarcode->insert(gen_uuid($this->produkbarcode->get_table()), $data);
		$this->response($operation);
	}
}

/* End of file Barang.php */
/* Location: ./application/modules/Barang/controllers/Barang.php */
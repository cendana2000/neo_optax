<?php

use Mpdf\Tag\B;

defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends Base_Controller
{
	protected $db;
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'barang/BarangModel' 				 => 'barang',
			'barang/BarangsatuanModel'			 => 'barangsatuan',
			'barang/BarangbarcodeModel'			 => 'barangbarcode',
			'kategori/KategoriModel' 			 => 'kelompokbarang',
			'satuan/SatuanModel' 			 	 => 'satuan',
		));
	}

	public function index()
	{
		$where['barang_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'barang', 'table', true, $where)
		);
	}

	public function index_barcode()
	{
		$this->response(
			$this->select_dt(varPost(), 'barangbarcode', 'table', false, varPost('tfilter'))
		);
	}

	public function index_barang_barcode()
	{
		$this->response(
			$this->select_dt(varPost(), 'barang', 'barang_barcode', false, varPost('tfilter'))
		);
	}

	public function read($value = '')
	{
		$data['barang_id'] = varPost('barang_id');
		$barang = $this->barang->read($data);
		$satuan = [];
		if ($barang) {
			$satuan = $this->barangsatuan->select(array('filters_static' => array('barang_satuan_parent' => $barang['barang_id']), 'sort_static' => 'barang_satuan_order asc'));
		}
		$barang['satuan'] = $satuan['data'];
		$this->response($barang);
	}

	public function mobile_read($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		$isMobile = false;
		$jenisUsaha = '';

		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user['session_db']);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$isMobile = true;

			$kode_toko = explode('_', varPost('mobileDb'));
			$toko = $this->dbmp
				->where('toko_kode', $kode_toko[1])
				->get('v_pajak_toko')
				->row_array();
			$getJenis = $this->dbmp
				->where('jenis_nama', $toko['jenis_nama'])
				->get('pajak_jenis')
				->row_array();
			$getJenisParent = $this->dbmp
				->where('jenis_id', $getJenis['jenis_parent'])
				->get('pajak_jenis')
				->row_array();

			$jenisUsaha = $getJenisParent['jenis_nama'];
		}

		$valSearch = trim(varPost('valSearch'));
		$valOrder = trim(varPost('valOrder'));
		$limitProduk = $this->input->post('limitProduk');
		$page = varPost('page');
		$row = 12;
		$limit = "";
		$order = '';

		if (isset($page) && $page != null) {
			$countlimit = ($page - 1) * $row;
			$limit = "LIMIT $row OFFSET $countlimit";
		}

		$filter = "WHERE barang_deleted_at IS NULL AND pos_barang.barang_kode is not null";
		if ($jenisUsaha == 'PAJAK HOTEL' || $jenisUsaha == 'PAJAK HIBURAN') {
			$filter = "WHERE pos_barang.barang_deleted_at IS NULL AND pos_barang.barang_kode is not null AND pos_barang.barang_aktif = '2' AND pos_barang.barang_aktif <> '3'";
		}

		if (isset($valOrder) && $valOrder != NULL) {
			$order = "ORDER BY $valOrder DESC";
			if ($valOrder == "tersedia") {
				$order = "";
				$filter .= " AND barang_stok > 0";
			} else if ($valOrder == "kosong") {
				$order = "";
				$filter .= " AND barang_stok = 0";
			} else if ($valOrder == "semua") {
				$order = "";
			} else if ($valOrder == "barang_stok_kecil") {
				$order = "ORDER BY barang_stok ASC";
			} else if ($valOrder == "rental_booking") {
				$order = "";
				$filter .= " AND barang_aktif = '2'";
			} else if ($valOrder == "rental_booked") {
				$order = "";
				$filter .= " AND barang_aktif = '3'";
			}
		}

		if ($valSearch != NULL) {
			$filter .= " AND (
				lower(barang_nama) LIKE lower('%" . $valSearch . "%') 
				OR lower(barang_kode) LIKE lower('%" . $valSearch . "%') 
				OR lower(barang_barcode_kode) LIKE lower('%" . $valSearch . "%')
			)";
		}

		// Limit produk from mobile
		if ($limitProduk != NULL) {
			$limit = "LIMIT 9 OFFSET $limitProduk";
		} else if ($limitProduk == NULL) {
			// $limit = "LIMIT 9 OFFSET 0";
		}

		$selectThumbnail = 'barang_thumbnail';
		if ($isMobile) {
			$selectThumbnail = 'CASE 
        WHEN barang_thumbnail IS NULL THEN NULL
        ELSE CONCAT(\'' . base_url() . '\', barang_thumbnail) 
    	END as barang_thumbnail';
		}

		$query = "SELECT barang_id as id, barang_kode, barang_nama, $selectThumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok as stok_now, total_jual, barang_stok_min, jenis_include_stok, barang_kategori_barang, barang_aktif
			FROM pos_barang 
			LEFT JOIN pos_barang_satuan 
				ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
			LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) as total_jual FROM pos_penjualan_detail GROUP BY penjualan_detail_barang_id)	as jual
				ON penjualan_detail_barang_id = barang_id
			LEFT JOIN pos_jenis 
				ON pos_barang.barang_jenis_barang = pos_jenis.jenis_id
			LEFT JOIN pos_barang_barcode 
				ON pos_barang.barang_id = pos_barang_barcode.barang_barcode_parent
			$filter
			GROUP BY barang_id, barang_kode,barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok, barang_stok_min, jenis_include_stok, jual.total_jual, barang_kategori_barang, barang_aktif, pos_barang.barang_created_at
			$order
		-- $limit
		";

		$return = $this->db->query($query)->result_array();
		$total = count($return);
		return $this->response(array('items' => $return, 'total_count' => $total));
	}

	public function delete()
	{
		$data = varPost();
		$data['barang_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->barang->update($data['id'], $data);
		$this->response($operation);
	}

	public function single_read($value = '')
	{
		$barang = $this->barang->read(varPost());
		$this->response($barang);
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id']) ? 'barang_supplier_id = "' . $data['fdata']['barang_supplier_id'] . '" AND ' : '';
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR barang_kode like "' . $data['q'] . '%") ')->result_array();

		$return = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text, barang_is_konsinyasi as saved FROM v_pos_barang WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR barang_kode like "' . $data['q'] . '%") ORDER BY barang_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	function select($value = '')
	{
		$this->response($this->barang->select(array('filters_static' => varPost(), 'sort_static' => 'barang_nama asc')));
	}

	public function generateThumbnail($filename, $location)
	{
		// $loc = preg_replace('/^.\//', '',$location);
		$source_path = $location . $filename;
		$target_path = $location . 'thumbnail/';

		$config = array(
			'image_library' => 'gd2',
			'source_image' => $source_path,
			'new_image' => $target_path,
			'maintain_ratio' => TRUE,
			'create_thumb' => TRUE,
			'thumb_marker' => '',
			'width' => 150,
			'height' => 150,
		);

		// var_dump($config);

		$this->load->library('image_lib');

		$this->image_lib->initialize($config);

		if (!$this->image_lib->resize()) {
			// var_dump($this->image_lib->display_errors());
			$status = "failed";
			$message = $this->image_lib->display_errors();
		} else {
			// var_dump("succed");
			$status = "success";
			$message = "Success generate thumbnail.";
			// print_r($this->image_lib->data());
			// die();
		}

		return (object)[
			"status" => $status,
			"message" => $message,
			"path" => ltrim($target_path, '.') . $filename,
		];
	}

	function uploadImage()
	{
		$file = $_FILES['thumbnail']['name'];
		$config['upload_path']  = './assets/master/product/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = 2048;
		$config['file_name'] = uniqid('produk_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

		$this->upload->initialize($config);

		if (!$this->upload->do_upload('thumbnail')) {
			$uploadstatus = "failed";
			$uploadmessage = $this->upload->display_errors('', '');
		} else {
			$uploadstatus = "success";
			$uploadmessage = "Success upload gambar";

			$uploadedImage = $this->upload->data();
			$genthumbnail = $this->generateThumbnail($uploadedImage['file_name'], $config['upload_path']);

			$uploadpath = ltrim($config['upload_path'], '.') . $uploadedImage['file_name'];
		}

		return array(
			"uploadpath" => $uploadpath,
			"genthumbnail" => $genthumbnail,
			"uploadstats" => (object)[
				"status" => $uploadstatus,
				"message" => $uploadmessage,
			]
		);
	}

	public function store()
	{
		$data = varPost();

		// Transactional
		$this->db->trans_begin();

		// barang nama set to uppercase
		$data['barang_nama'] = strtoupper($data['barang_nama']);

		// Setup Rental Produk
		$dc_jenis_barang = $this->db->get_where('pos_jenis', ['jenis_id' => $data['barang_jenis_barang']])->row_array();
		if ($dc_jenis_barang['jenis_include_stok'] == 2) {
			$data['barang_aktif'] = 2;
		} else {
			$data['barang_aktif'] = 1;
		}

		// UPLOAD FILE & CREATE THUMBNAIL
		$uploadImage = $this->uploadImage();
		$genthumbnail = $uploadImage['genthumbnail'];
		$uploadpath = $uploadImage['uploadpath'];

		$data['barang_harga_beli'] =  $data['barang_satuan_harga_beli'][1];

		$kategori = $this->db->get_where('pos_kategori', ['kategori_barang_id' => $data['barang_kategori_barang']])->row_array()['kategori_barang_kode'];

		$detail = [];
		$data['barang_kode'] = ($data['barang_kode']) ? $data['barang_kode'] : $this->barang->gen_kode(false, $kategori);
		$data['barang_user'] = $this->session->userdata('user_username');
		$data['barang_is_konsinyasi'] = (!isset($data['barang_is_konsinyasi']) ? '1' : '0');
		$id = gen_uuid($this->barang->get_table());
		$new_data = $data;
		$new_data['barang_satuan'] = ($data['barang_satuan'][1] == NULL) ? 0 : $data['barang_satuan'][1];
		$new_data['barang_satuan_kode'] = ($data['barang_satuan_kode'][1] == NULL) ? 0 : $data['barang_satuan_kode'][1];
		$new_data['barang_harga'] = ($data['barang_satuan_harga_jual'][1] == NULL) ? 0 : $data['barang_satuan_harga_jual'][1];
		$new_data['barang_disc'] = ($data['barang_satuan_disc'][1] == NULL) ? 0 : $data['barang_satuan_disc'][1];
		$new_data['barang_satuan_opt'] = ($data['barang_satuan_kode'][2] == NULL) ? 0 : $data['barang_satuan_kode'][2];
		$new_data['barang_satuan_opt_kode'] = ($data['barang_satuan_kode'][2] == NULL) ? 0 : $data['barang_satuan_kode'][2];
		$new_data['barang_harga_opt'] = ($data['barang_satuan_harga_jual'][2] == NULL) ? 0 : $data['barang_satuan_harga_jual'][2];
		$new_data['barang_satuan_opt2_kode'] =  ($data['barang_satuan_kode'][3] == NULL) ? 0 : $data['barang_satuan_kode'][3];
		$new_data['barang_harga_opt2'] = ($data['barang_satuan_harga_jual'][3] == NULL) ? 0 : $data['barang_satuan_harga_jual'][3];
		$new_data['barang_yearly'] = 1;
		$new_data['barang_stok'] = 0;
		if (isset($genthumbnail) && $genthumbnail->status == 'success') {
			$new_data['barang_thumbnail'] = $genthumbnail->path;
		}
		if (isset($uploadpath)) {
			$new_data['barang_image'] = $uploadpath;
		}
		$new_data['barang_created_at'] = date('Y-m-d H:i:s');
		$detail = [];
		$kategori_p = $this->getParent($data['barang_kategori_barang']);
		$new_data['barang_kategori_parent'] = $kategori_p;
		foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
			if ($value['barang_satuan_satuan_id'] == null || $value['barang_satuan_satuan_id'] == 'null') {
				continue;
			} else {
				$detail[] = [
					'barang_satuan_id' 			=> gen_uuid($this->barangsatuan->get_table()),
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
		}


		if (isset($data['barang_barcode'])) {
			$isexistbarcode = $this->barangbarcode->read(array('barang_barcode_kode' => $data['barang_barcode']));
			if (empty($isexistbarcode)) {
				$data_barcode = array(
					'barang_barcode_parent' => $id,
					'barang_barcode_kode' => $data['barang_barcode'],
					'barang_barcode_tanggal' => date('Y-m-d H:i:s'),
				);
				$insertbarcode = $this->barangbarcode->insert(gen_uuid($this->barangbarcode->get_table()), $data_barcode);
			} else {
				$new_data['barang_barcode'] = null;
			}
		}


		$data = array_merge($data, $new_data);
		$data = cVarNull($data);

		$operation = $this->barang->insert($id, $data, function ($res) use ($detail) {
			$update = $this->db->insert_batch('pos_barang_satuan', $detail);
			if (!$update) {
				$res['message'] = 'Berhasil menyimpan dengan error[satuan gagal diperbarui]';
			}
		});

		// END Transactional
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$this->response($operation);
	}

	public function store_custom_menu()
	{
		$data = varPost();

		$customMenu = $data['data'];

		$this->db->delete('pos_barang_custom_menu', [
			'barang_custom_menu_barang_id' => $data['barang_id'],
		]);
		foreach ($customMenu as $key => $value) {
			$this->db->insert('pos_barang_custom_menu', [
				'barang_custom_menu_barang_id' => $data['barang_id'],
				'barang_custom_menu_custom_menu_id' => $value,
			]);
		}

		$this->response(['success' => true, 'message' => 'Berhasil menyimpan custom menu']);
	}

	public function store_sc()
	{
		$data = varPost();

		// Transactional
		$this->db->trans_begin();

		$data['barang_stok'] = 0;

		foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
			if ($key == 1) {
				$data['barang_satuan_kode'] = $this->db->get_where('pos_satuan', ['satuan_id' => $value])->row_array()['satuan_kode'];
				$data['barang_persen_untung'] = $data['barang_satuan_keuntungan'][$key];
			}
		}

		$data['barang_harga_beli'] =  $data['barang_satuan_harga_beli'][1];

		$kategori = $this->db->get_where('pos_kategori', ['kategori_barang_id' => $data['barang_kategori_barang']])->row_array()['kategori_barang_kode'];

		$detail = [];
		$data['barang_kode'] = ($data['barang_kode']) ? $data['barang_kode'] : $this->barang->gen_kode(false, $kategori);

		$data['barang_user'] = $this->session->userdata('user_username');
		$data['barang_aktif'] = '1';
		$id = gen_uuid($this->barang->get_table());
		$new_data = $data;
		$new_data['barang_yearly'] = 1;
		if (isset($genthumbnail) && $genthumbnail->status == 'success') {
			$new_data['barang_thumbnail'] = $genthumbnail->path;
		}
		if (isset($uploadpath)) {
			$new_data['barang_image'] = $uploadpath;
		}
		$new_data['barang_created_at'] = date('Y-m-d H:i:s');
		$detail = [];
		$kategori_p = $this->getParent($data['barang_kategori_barang']);
		$new_data['barang_kategori_parent'] = $kategori_p;
		foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
			if ($value['barang_satuan_satuan_id'] == null || $value['barang_satuan_satuan_id'] == 'null') {
				continue;
			} else {
				$detail[] = [
					'barang_satuan_id' 			=> gen_uuid($this->barangsatuan->get_table()),
					'barang_satuan_parent' 		=> $id,
					'barang_satuan_satuan_id' 	=> $value,
					'barang_satuan_kode' 		=> $data['barang_satuan_kode'],
					'barang_satuan_konversi' 	=> $data['barang_satuan_konversi'][$key],
					'barang_satuan_harga_beli'	=> $data['barang_satuan_harga_beli'][$key],
					'barang_satuan_keuntungan'	=> $data['barang_satuan_keuntungan'][$key],
					'barang_satuan_harga_jual'	=> $data['barang_satuan_harga_jual'][$key],
					'barang_satuan_disc'		=> $data['barang_satuan_disc'][$key],
					'barang_satuan_order'		=> $key,
				];
			}
		}


		if (isset($data['barang_barcode'])) {
			$isexistbarcode = $this->barangbarcode->read(array('barang_barcode_kode' => $data['barang_barcode']));
			if (empty($isexistbarcode)) {
				$data_barcode = array(
					'barang_barcode_parent' => $id,
					'barang_barcode_kode' => $data['barang_barcode'],
					'barang_barcode_tanggal' => date('Y-m-d H:i:s'),
				);
				$insertbarcode = $this->barangbarcode->insert(gen_uuid($this->barangbarcode->get_table()), $data_barcode);
			} else {
				$new_data['barang_barcode'] = null;
			}
		}


		$data = array_merge($data, $new_data);
		$data = cVarNull($data);
		/*print_r($data);*/
		$operation = $this->barang->insert($id, $data, function ($res) use ($detail) {
			$update = $this->db->insert_batch('pos_barang_satuan', $detail);
			// $res_detail = $this->barangsatuan->update($data['barang_satuan_id'][$key], $detail);				
			if (!$update) {
				$res['message'] = 'Berhasil menyimpan dengan error[satuan gagal diperbarui]';
			}
		});

		// END Transactional
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$this->response($operation);
	}

	public function store_mobile()
	{
		$data = varPost();

		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$data = varPost();
			unset($data['mobileDb']);

			// Transactional
			$this->db->trans_begin();

			$data['barang_stok'] = 0;

			foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
				if ($key == 1) {
					$data['barang_satuan_kode'] = $this->db->get_where('pos_satuan', ['satuan_id' => $value])->row_array()['satuan_kode'];
					$data['barang_persen_untung'] = $data['barang_satuan_keuntungan'][$key];
				}
			}

			$data['barang_harga_beli'] =  $data['barang_satuan_harga_beli'][1];

			$kategori = $this->db->get_where('pos_kategori', ['kategori_barang_id' => $data['barang_kategori_barang']])->row_array()['kategori_barang_kode'];

			$detail = [];
			$data['barang_kode'] = ($data['barang_kode']) ? $data['barang_kode'] : $this->barang->gen_kode(false, $kategori);

			$data['barang_user'] = $this->session->userdata('user_username');
			$data['barang_aktif'] = '1';
			$id = gen_uuid($this->barang->get_table());
			$new_data = $data;
			$new_data['barang_yearly'] = 1;
			if (isset($genthumbnail) && $genthumbnail->status == 'success') {
				$new_data['barang_thumbnail'] = $genthumbnail->path;
			}
			if (isset($uploadpath)) {
				$new_data['barang_image'] = $uploadpath;
			}
			$new_data['barang_created_at'] = date('Y-m-d H:i:s');
			$detail = [];
			$kategori_p = $this->getParent($data['barang_kategori_barang']);
			$new_data['barang_kategori_parent'] = $kategori_p;

			$detail = [
				'barang_satuan_id' 			=> gen_uuid($this->barangsatuan->get_table()),
				'barang_satuan_parent' 		=> $id,
				'barang_satuan_satuan_id' 	=> $data['barang_satuan_satuan_id_1'],
				'barang_satuan_kode' 		=> $this->db->get_where('pos_satuan', ['satuan_id' => $data['barang_satuan_satuan_id_1']])->row_array()['satuan_kode'],
				'barang_satuan_konversi' 	=> 1,
				'barang_satuan_harga_beli'	=> $data['barang_satuan_harga_beli_1'],
				'barang_satuan_keuntungan' => ($data['barang_satuan_harga_beli_1'] == 0) ? 0 : ($data['barang_satuan_harga_jual_1'] - $data['barang_satuan_harga_beli_1']) / $data['barang_satuan_harga_beli_1'] * 100,
				'barang_satuan_harga_jual'	=> $data['barang_satuan_harga_jual_1'],
				'barang_satuan_disc'		=> 0,
				'barang_satuan_order'		=> 1,
			];


			if (isset($data['barang_barcode'])) {
				$isexistbarcode = $this->barangbarcode->read(array('barang_barcode_kode' => $data['barang_barcode']));
				if (empty($isexistbarcode)) {
					$data_barcode = array(
						'barang_barcode_parent' => $id,
						'barang_barcode_kode' => $data['barang_barcode'],
						'barang_barcode_tanggal' => date('Y-m-d H:i:s'),
					);
					$insertbarcode = $this->barangbarcode->insert(gen_uuid($this->barangbarcode->get_table()), $data_barcode);
				} else {
					$new_data['barang_barcode'] = null;
				}
			}

			$data = array_merge($data, $new_data);
			$data = cVarNull($data);
			$data['barang_id'] = $id;

			$data['barang_satuan'] = $data['barang_satuan_satuan_id_1'];
			$data['barang_harga_beli'] = $data['barang_satuan_harga_beli_1'];
			$data['barang_harga'] = $data['barang_satuan_harga_jual_1'];
			$data['barang_harga_pokok'] = $data['barang_satuan_harga_jual_1'];
			$data['barang_satuan_kode'] = $this->db->get_where('pos_satuan', ['satuan_id' => $data['barang_satuan_satuan_id_1']])->row_array()['satuan_kode'];
			$data['barang_stok_min'] = 0;

			unset($data['barang_satuan_satuan_id_1']);
			unset($data['barang_satuan_harga_beli_1']);
			unset($data['barang_satuan_harga_jual_1']);

			// print_r($data);
			// exit;


			$this->db->insert('pos_barang', $data);
			$this->db->insert('pos_barang_satuan', $detail);

			$operation = [
				'success' => true,
				'message' => 'Berhasil menambahkan produk baru',
				'data' => $data,
				'detail' => $detail
			];

			// END Transactional
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$operation = [
					'success' => false,
					'message' => 'Terjadi kesalahan, silahkan hubungi administrator',
				];
			} else {
				$this->db->trans_commit();
			}

			$this->response($operation);
		}
	}



	public function update()
	{
		$data = varPost();

		// Transactional
		$this->db->trans_begin();

		$uploadImage = $this->uploadImage();
		$genthumbnail = $uploadImage['genthumbnail'];
		$uploadpath = $uploadImage['uploadpath'];

		$data['barang_harga_beli'] =  $data['barang_satuan_harga_beli'][1];

		$data['barang_is_konsinyasi'] = (!isset($data['barang_is_konsinyasi']) ? '1' : '0');
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
		if (isset($genthumbnail) && $genthumbnail->status == 'success') {
			$new_data['barang_thumbnail'] = $genthumbnail->path;
		}
		if (isset($uploadpath)) {
			$new_data['barang_image'] = $uploadpath;
		}
		$detail = $insert_detail = [];
		$kategori_p = $this->getParent($data['barang_kategori_barang']);
		$new_data['barang_kategori_parent'] = $kategori_p;
		$new_data['barang_updated_at'] = date('Y-m-d H:i:s');

		// Destroy satuan lama
		$this->db->where('barang_satuan_parent', $data['barang_id']);
		$this->db->delete('pos_barang_satuan');

		foreach ($data['barang_satuan_satuan_id'] as $key => $value) {
			$set = [
				'barang_satuan_id' 			=> gen_uuid($this->barangsatuan->get_table()),
				'barang_satuan_parent' 		=> $data['barang_id'],
				'barang_satuan_satuan_id' 	=> $value,
				'barang_satuan_kode' 		=> $data['barang_satuan_kode'][$key],
				'barang_satuan_konversi' 	=> $data['barang_satuan_konversi'][$key],
				'barang_satuan_harga_beli'	=> $data['barang_satuan_harga_beli'][$key],
				'barang_satuan_keuntungan'	=> $data['barang_satuan_keuntungan'][$key],
				'barang_satuan_harga_jual'	=> $data['barang_satuan_harga_jual'][$key],
				'barang_satuan_disc'		=> $data['barang_satuan_disc'][$key],
				'barang_satuan_order'		=> $key,
			];
			if ($data['barang_satuan_satuan_id'][$key] != '' || $data['barang_satuan_satuan_id'][$key] != NULL) {
				$this->db->insert('pos_barang_satuan', $set);
			}
		}
		$data = array_merge($data, $new_data);

		$data = cVarNull($data);

		$operation = $this->barang->update(varPost('id', varExist($data, $this->barang->get_primary(true))), $data);

		// END Transactional
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

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
		$operation = $this->barang->delete(varPost('id', varExist($data, $this->barang->get_primary(true))));
		$this->response($operation);
	}

	public function delete_barcode()
	{
		$data = varPost();
		$operation = $this->barangbarcode->delete($data['barang_barcode_id']);
		$this->response($operation);
	}

	public function list_satuan($value = '')
	{
		$data = varPost();

		$operation = $this->barangsatuan->select(array('filters_static' => array('barang_satuan_parent' => $data['barang_id']), 'sort_static' => 'barang_satuan_order asc'));
		$this->response($operation);
	}
	public function list_satuan_harga($value = '')
	{
		$data = varPost();
		$harga = $this->db->select('pos_barang_satuan.*, pos_barang.barang_harga_pokok')
			->where(['barang_satuan_parent' => $data['barang_id']])
			->join('pos_barang', 'barang_id = barang_satuan_parent', 'left')
			->order_by('barang_satuan_order')
			->get('pos_barang_satuan')->result_array();
		$this->response(['data' => $harga]);
	}
	public function get_barcode()
	{
		$data = varPost();
		$barcode = $this->barangbarcode->read($data);
		$this->response($barcode);
	}
	public function createBarcode()
	{
		$data = varPost();

		$isexistbarcode = $this->barangbarcode->read(array('barang_barcode_kode' => $data['barang_barcode_kode']));
		if (empty($isexistbarcode)) {
			$data['barang_barcode_tanggal'] = date('Y-m-d H:i:s');
			$operation = $this->barangbarcode->insert(gen_uuid($this->barangbarcode->get_table()), $data);
		} else {
			$operation = array(
				"success" => false,
				"message" => "Kode barcode sudah digunakan."
			);
		}


		$this->response($operation);
	}

	public function get_template()
	{
		try {
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Set Header
			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'eaeaea',
					],
					'endColor' => [
						'argb' => 'eaeaea',
					],
				],
			];
			$sheet->mergeCells('A1:J1');
			$sheet->setCellValue('A1', 'IMPORT 	PRODUK');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			// Set Table Header
			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'eaeaea',
					],
					'endColor' => [
						'argb' => 'eaeaea',
					],
				],
			];
			$sheet->getStyle('A4:J4')->applyFromArray($styleArray);
			$sheet->setCellValue('A4', 'No');
			$sheet->setCellValue('B4', 'Barang Kode');
			$sheet->setCellValue('C4', 'Barang Nama');
			$sheet->setCellValue('D4', 'Barang Jenis');
			$sheet->setCellValue('E4', 'Barang Kategori');
			$sheet->setCellValue('F4', 'Barang Satuan');
			$sheet->setCellValue('G4', 'Barang Stok Min');
			$sheet->setCellValue('H4', 'Barang Harga Beli');
			$sheet->setCellValue('I4', 'Barang Harga Jual');
			$sheet->setCellValue('J4', 'Barang Harga Pokok');

			$sheet->freezePane('A5');

			foreach (range('A', 'J') as $columnID) {
				$sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

			// Set Borders First Row
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			// data
			$dataJenis = $this->db->query("SELECT string_agg(DISTINCT jenis_nama ,',') AS result  FROM pos_jenis WHERE jenis_deleted_at IS NULL")->row_array()['result'];
			$dataKategori = $this->db->query("SELECT string_agg(DISTINCT kategori_barang_nama ,',') AS result  FROM pos_kategori WHERE kategori_barang_aktif = '1'")->row_array()['result'];
			$dataSatuan = $this->db->query("SELECT string_agg(DISTINCT satuan_kode ,',') AS result  FROM pos_satuan WHERE satuan_deleted_at IS NULL")->row_array()['result'];

			$sheet->setCellValue('A5', 1);

			$blankrow = 10 + 5;
			for ($i = 5; $i <= $blankrow; $i++) {
				$sheet->getStyle('A' . $i . ':J' . $i)->applyFromArray($styleArray);

				// Set first Number

				// Set Jenis option
				$validation = $sheet->getCell('D' . $i)->getDataValidation();
				$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
				$validation->setFormula1('"' . $dataJenis . '"');
				$validation->setAllowBlank(false);
				$validation->setShowDropDown(true);
				$validation->setShowInputMessage(true);
				$validation->setShowErrorMessage(true);
				$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
				$validation->setErrorTitle('Invalid option');
				$validation->setError('Select one from the drop down list.');

				// Set Kategori Option
				$validation = $sheet->getCell('E' . $i)->getDataValidation();
				$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
				$validation->setFormula1('"' . $dataKategori . '"');
				$validation->setAllowBlank(false);
				$validation->setShowDropDown(true);
				$validation->setShowInputMessage(true);
				$validation->setShowErrorMessage(true);
				$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
				$validation->setErrorTitle('Invalid option');
				$validation->setError('Select one from the drop down list.');


				// Set Satuan Option
				$validation = $sheet->getCell('F' . $i)->getDataValidation();
				$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
				$validation->setFormula1('"' . $dataSatuan . '"');
				$validation->setAllowBlank(false);
				$validation->setShowDropDown(true);
				$validation->setShowInputMessage(true);
				$validation->setShowErrorMessage(true);
				$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
				$validation->setErrorTitle('Invalid option');
				$validation->setError('Select one from the drop down list.');
			}

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'produk_template_' . date('d-m-y-H:i:s') . '.xlsx';
			$file = FCPATH . 'assets/laporan/' . $filename;
			$writer->save($file);

			$this->response([
				'success' => true,
				'file' => $filename
			]);
		} catch (\Throwable $th) {
			$this->response([
				'success' => false,
			]);
		}
	}

	public function import()
	{
		// upload file
		$new_name = 'barang_import' . date('d-m-y-H-i-s') . '.xlsx';
		$config['upload_path']  = FCPATH . 'assets/laporan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size'] = 10000;
		$config['file_name'] = $new_name;

		$this->upload->initialize($config);
		if ($this->upload->do_upload('file_import')) {

			$excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$file_import = FCPATH . 'assets/laporan/' . $new_name;
			$spreadSheet  = $excel->load($file_import);
			$dataDetail = $spreadSheet->getActiveSheet()->toArray();
			$batch = [];
			$batchDetailSatuan = [];


			// Create Dictionary Jenis
			$dictJenis = $this->db->get_where('pos_jenis', ['jenis_deleted_at' => NULL])->result_array();

			// Create Dictionary Kategori
			$dictKategori = $this->db->get_where('pos_kategori', ['kategori_barang_aktif' => '1'])->result_array();

			// Create Dictionary Satuan
			$dictSatuan = $this->db->get_where('pos_satuan', ['satuan_deleted_at' => NULL])->result_array();


			// Insert Detail Realisasi
			unset($dataDetail[0], $dataDetail['1'], $dataDetail['2'], $dataDetail['3']);

			foreach ($dataDetail as $key => $value) {
				if ($value[1] == null) {
					break;
				}
				$id_barang = gen_uuid($this->barang->get_table());
				$batch[] = [
					'barang_id' => $id_barang,
					'barang_kode' => $value[1],
					'barang_nama' => strtoupper($value[2]),
					'barang_stok' => 0,
					'barang_jenis_barang' => $this->fillDictionary($dictJenis, 'jenis_nama', $value[3])['jenis_id'],
					'barang_kategori_barang' => $this->fillDictionary($dictKategori, 'kategori_barang_nama', $value[4])['kategori_barang_id'],
					'barang_satuan_kode' => $this->fillDictionary($dictSatuan, 'satuan_kode', $value[5])['satuan_kode'],
					'barang_stok_min' => $value[6],
					'barang_harga_beli' => $value[7],
					'barang_harga' => $value[8],
					'barang_harga_pokok' => $value[9],
					'barang_created_at' => date('Y-m-d H:i:s'),
					'barang_aktif' => 1,
					'barang_yearly' => 1,
					'barang_awal' => 1,
					'barang_disc' => 1,
				];

				$batchDetailSatuan[] = [
					'barang_satuan_id' => gen_uuid($this->satuan->get_table()),
					'barang_satuan_parent' => $id_barang,
					'barang_satuan_satuan_id' => $this->fillDictionary($dictSatuan, 'satuan_kode', $value[5])['satuan_id'],
					'barang_satuan_kode' => $value[5],
					'barang_satuan_konversi' => 1,
					'barang_satuan_harga_beli' => $value[7],
					'barang_satuan_harga_jual' => $value[8],
					'barang_satuan_keuntungan' => round($value[7] / $value[8] * 100),
				];
			}
			$this->db->insert_batch('pos_barang', $batch);
			$this->db->insert_batch('pos_barang_satuan', $batchDetailSatuan);

			// if ($this->db->insert_batch('pos_barang', $batch)) {
			// 	$this->db->insert_batch('pos_barang_satuan', $batchDetailSatuan);
			// }
			$response = [
				'success' => true,
				'message' => 'Successfully saved data.',
			];
		} else {
			$response = [
				'success' => false,
			];
		}

		$this->response($response);
	}

	public function fillDictionary($data, $column, $valSearch)
	{
		$result = array();
		foreach ($data as $key => $value) {
			if ($value[$column] == $valSearch) {
				$result = $data[$key];
			}
		}

		return $result;
	}

	public function setAktif()
	{
		$dataPost = varPost();

		if ($this->barang->setAktif($dataPost)) {
			$this->response([
				'success' => true,
			]);
		} else {
			$this->response([
				'success' => true,
				'message' => 'Set aktif barang gagal',
			]);
		}
	}


	public function getCurrentPrice()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$user['session_db'] = varPost('mobileDb');
			$this->session->set_userdata($user);
			$this->response($this->db->get_where('pos_barang', ['barang_id' => varPost('barang_id')])->row_array());
		}
	}
}

/* End of file Barang.php */
/* Location: ./application/modules/Barang/controllers/Barang.php */
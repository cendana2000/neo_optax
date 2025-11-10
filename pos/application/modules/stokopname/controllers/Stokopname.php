<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stokopname extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'barang/barangModel' 		=> 'barang',
			'barang/barangsatuanModel'	=> 'barangsatuan',
			'barang/barangbarcodeModel'	=> 'barangbarcode',
			'stokopnameModel' 			=> 'stokopname',
			'stokopnamedetailModel' 	=> 'stokopnamedetail',
			'stokkartu/stokkartuModel' 	=> 'stokkartu',
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(), 'stokopname', 'table')
		);
	}

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(), 'stokopnamedetail', 'table', true, varPost('beli'))
		);
	}

	public function barang_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE concat(barang_kode, barang_nama) like "%' . $data['q'] . '%"')->result_array();

		$return = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text, TO_BASE64(concat(barang_satuan,"||",satuan_kode,"||",IFNULL(barang_harga, 0),"||",IFNULL(barang_stok, 0))) saved FROM v_pos_barang WHERE concat(barang_kode, barang_nama) like "%' . $data['q'] . '%" LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	function read($value = '')
	{
		$opname = $this->stokopname->read(varPost());
		$html = '';
		$opnamedetail = [];
		$row = 0;
		if (isset($opname['opname_id'])) {
			$opnamedetail = $this->stokopnamedetail->select(['filters_static' => ['opname_detail_parent' => $opname['opname_id']], 'sort_static' => 'opname_detail_order']);
			foreach ($opnamedetail['data'] as $key => $value) {
				$row = $key + 1;
				$html .= '<tr class="barang_' . $row . '">
						<td scope="row">
							<input type="hidden" class="txt-search" name="txt_search[' . $row . ']" id="txt_search_' . $row . '" value="' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '">
							<input type="hidden" class="form-control" name="opname_detail_id[' . $row . ']" id="opname_detail_id_' . $row . '" value="' . $value['opname_detail_id'] . '">
							<select class="form-control barang_id" name="opname_detail_barang_id[' . $row . ']" id="opname_detail_barang_id_' . $row . '" data-id="' . $row . '" style="width: 100%;white-space: nowrap" onchange="setSatuan(' . $row . ')">
								<option value="' . $value['opname_detail_barang_id'] . '">' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '</option>
							</select>
						</td>
						<td>
							<input class="form-control" type="hidden" name="opname_detail_satuan_id[' . $row . ']" id="opname_detail_satuan_id_' . $row . '" value="' . $value['opname_detail_satuan_id'] . '">
							<input class="form-control" type="text" name="opname_detail_satuan_kode[' . $row . ']" id="opname_detail_satuan_kode_' . $row . '" readonly="" value="' . $value['opname_detail_satuan_kode'] . '">
						</td>
						<td><input class="form-control nominal" type="text" name="opname_detail_harga[' . $row . ']" id="opname_detail_harga_' . $row . '" readonly="" value="' . $value['opname_detail_harga'] . '"></td>
						<td><input class="form-control number data" type="text" name="opname_detail_qty_data[' . $row . ']" id="opname_detail_qty_data_' . $row . '" readonly="" value="' . $value['opname_detail_qty_data'] . '"></td>
						<td><input class="form-control number qty" type="text" name="opname_detail_qty_fisik[' . $row . ']" id="opname_detail_qty_fisik_' . $row . '" value="' . $value['opname_detail_qty_fisik'] . '" onkeyup="countRow(' . $row . ')"></td>
						<td><input class="form-control number koreksi" type="text" name="opname_detail_qty_koreksi[' . $row . ']" id="opname_detail_qty_koreksi_' . $row . '" value="' . $value['opname_detail_qty_koreksi'] . '" readonly=""></td>
						<td><input class="form-control nominal nilai" type="text" name="opname_detail_nilai[' . $row . ']" id="opname_detail_nilai_' . $row . '" value="' . $value['opname_detail_nilai'] . '" readonly=""></td>
						<td><a href="javascript:;" data-id="' . $row . '" class="btn btn-light-warning btn-sm" onclick="remRow(this)" title="Hapus">
								<span class="la la-trash"></span> </a></td>
					</tr>';
			}
		}
		$opname['html'] = $html;
		$opname['detail'] = $opnamedetail;
		$this->response($opname);
	}

	function read_detail($value = '')
	{
		$this->response($this->stokopnamedetail->read(varPost()));
	}

	public function get_opname($value = '')
	{
		$opname = $this->stokopname->read(varPost());
		if (isset($opname['opname_id'])) {
			$detail = $this->stokopnamedetail->select(array('filters_static' => array('opname_detail_parent' => $opname['opname_id']), 'sort_static' => 'opname_detail_opname'));
			$opname['detail'] = $detail['data'];
		}
		$this->response($opname);
	}

	public function store()
	{
		$data = varPost();
		$data['opname_kode'] = $this->stokopname->gen_kode();
		$data['opname_user_id'] = $this->session->userdata('pegawai_id');
		$data['opname_user'] = $this->session->userdata('user_username');
		$data['opname_aktif'] = '1';
		$operation = $this->stokopname->insert(gen_uuid($this->stokopname->get_table()), $data, function ($res) use ($data) {
			$dt = $data;
			$detail = [];
			foreach ($data['opname_detail_barang_id'] as $key => $value) {
				$detail = [
					'opname_detail_id' 			=> gen_uuid($this->stokopnamedetail->get_table()),
					'opname_detail_parent' 		=> $res['record']['opname_id'],
					'opname_detail_barang_id'	=> $value,
					'opname_detail_satuan_id' 	=> $data['opname_detail_satuan_id'][$key],
					'opname_detail_satuan_kode' => $data['opname_detail_satuan_kode'][$key],
					'opname_detail_harga' 		=> $data['opname_detail_harga'][$key],
					'opname_detail_qty_data' 	=> $data['opname_detail_qty_data'][$key],
					'opname_detail_qty_fisik' 	=> $data['opname_detail_qty_fisik'][$key],
					'opname_detail_qty_koreksi' => $data['opname_detail_qty_koreksi'][$key],
					'opname_detail_nilai' 		=> $data['opname_detail_nilai'][$key],
					'opname_detail_tanggal' 	=> $data['opname_tanggal'],
					'opname_detail_order' 		=> $key,
				];
				$det_opr = $this->db->insert('pos_stock_opname_detail', $detail);
				// $det_opr = $this->stokopnamedetail->insert(gen_uuid($this->stokopnamedetail->get_table()),$detail);
				if (!$det_opr) $error[] = ['cannot insert dt ' . $value => $detail];
				else {
					$masuk = $keluar = 0;
					if ($data['opname_detail_qty_koreksi'][$key] > 0) {
						$masuk = $data['opname_detail_qty_koreksi'][$key];
					} else {
						$keluar = abs($data['opname_detail_qty_koreksi'][$key]);
					}
					$kartu = $this->stokkartu->insert_kartu([
						'kartu_id' 			=> $detail['opname_detail_id'],
						'kartu_tanggal' 	=> $data['opname_tanggal'],
						'kartu_barang_id' 	=> $value,
						'kartu_satuan_id' 	=> $data['opname_detail_satuan_id'][$key],
						'kartu_stok_masuk' 	=> $masuk,
						'kartu_stok_keluar' => $keluar,
						'kartu_stok_akhir' 	=> $data['opname_detail_qty_fisik'][$key],
						'kartu_transaksi' 	=> 'Opname',
						'kartu_harga'		=> $data['opname_detail_harga'][$key],
						'kartu_transaksi_kode' => $data['opname_kode'],
						'kartu_user' 		=> $data['opname_user_id'],
						'kartu_created_at' 	=> date('Y-m-d H:i:s'),
						'kartu_keterangan' 	=> 'On Insert',
					], 'O');
					if (!$kartu) $error[] = [$kartu, $data['opname_kode'], $value];
				}
			}
		});
		$this->response($operation);
	}

	public function update2()
	{
		$data = varPost();
		$last_detail = $this->stokopnamedetail->select(array('filters_static' => array('opname_detail_parent' => $data['opname_id']), 'sort_static' => 'opname_detail_order asc'))['data'];
		$delete = $last_detail;
		$operation = $this->stokopname->update($data['opname_id'], $data, function (&$res) use ($data, $delete, $last_detail) {
			$detail = $id_detail == [];
			$dt = $res['record'];
			foreach ($data['opname_detail_barang_id'] as $key => $value) {
				$detail = [
					'opname_detail_parent' 		=> $res['record']['opname_id'],
					'opname_detail_barang_id'	=> $value,
					'opname_detail_satuan_id' 	=> $data['opname_detail_satuan_id'][$key],
					'opname_detail_satuan_kode' => $data['opname_detail_satuan_kode'][$key],
					'opname_detail_harga' 		=> $data['opname_detail_harga'][$key],
					'opname_detail_qty_data' 	=> $data['opname_detail_qty_data'][$key],
					'opname_detail_qty_fisik' 	=> $data['opname_detail_qty_fisik'][$key],
					'opname_detail_qty_koreksi' => $data['opname_detail_qty_koreksi'][$key],
					'opname_detail_nilai' 		=> $data['opname_detail_nilai'][$key],
					'opname_detail_tanggal' 	=> $data['opname_tanggal'],
					'opname_detail_order' 		=> $key,
				];
				$kartu = [
					'kartu_id' 				=> $data['opname_detail_id'][$key],
					'kartu_tanggal' 		=> $dt['opname_tanggal'],
					'kartu_barang_id' 		=> $value,
					'kartu_satuan_id' 		=> $data['opname_detail_satuan_id'][$key],
					'kartu_stok_masuk' 		=> 0,
					'kartu_stok_keluar' 	=> 0,
					'kartu_stok_akhir' 		=> $data['opname_detail_qty_fisik'][$key],
					'kartu_transaksi' 		=> 'Opname',
					'kartu_harga'			=> $data['opname_detail_harga'][$key],
					'kartu_transaksi_kode' 	=> $dt['opname_kode'],
					'kartu_user' 			=> $dt['opname_user_id'],
					'kartu_created_at' 		=> date('Y-m-d H:i:s'),
					'kartu_keterangan' 		=> 'On Updated',
				];

				foreach ($last_detail as $i => $v) {
					if ($v['opname_detail_id'] == $data['opname_detail_id'][$key]) {
						unset($delete[$i]);
					}
				}
				$res_detail = $this->stokopnamedetail->update($data['opname_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$res_detail = $this->stokopnamedetail->insert(gen_uuid($this->stokopnamedetail->get_table()), $detail);
					if ($res_detail['success'] == true) {
						$id_detail[] = $res_detail['id'];
						$kartu['kartu_id'] = $res_detail['id'];
						$kartu['kartu_keterangan'] = 'Insert On Updated';
						$xkartu = $this->stokkartu->insert_kartu($kartu, 'O');
					}
				} else {
					$xkartu = $this->stokkartu->update_kartu($kartu, 'O');
					$id_detail[] = $res_detail['id'];
				}
			}
			$id = implode('","', $id_detail);
			$res['id_detail'] = $id;
			foreach ($delete as $n => $value) {
				$del = $this->stokopnamedetail->delete($value['opname_detail_id']);
				if ($del['success']) {
					$kartu = [
						'kartu_id' 				=> $value['opname_detail_id'],
						'kartu_barang_id'		=> $value['opname_detail_barang_id'],
						'kartu_stok_akhir' 		=> 0,
						'kartu_stok_awal' 		=> 0,
						'kartu_transaksi' 		=> 'Opname',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					// $this->db->delete('pos_pembelian_barang_detail', array('opname_detail_id' => $value['opname_detail_id']));
					$this->stokkartu->update_kartu($kartu, 'B');
				}
			}
		});
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$last_detail = $this->stokopnamedetail->select(array('filters_static' => array('opname_detail_parent' => $data['opname_id']), 'sort_static' => 'opname_detail_order asc'))['data'];
		$delete = $last_detail;
		$operation = $this->stokopname->update($data['opname_id'], $data, function (&$res) use ($data, $delete, $last_detail) {
			$detail = $id_detail == [];
			$dt = $res['record'];
			foreach ($data['opname_detail_barang_id'] as $key => $value) {
				$detail = [
					'opname_detail_parent' 		=> $res['record']['opname_id'],
					'opname_detail_barang_id'	=> $value,
					'opname_detail_satuan_id' 	=> $data['opname_detail_satuan_id'][$key],
					'opname_detail_satuan_kode' => $data['opname_detail_satuan_kode'][$key],
					'opname_detail_harga' 		=> $data['opname_detail_harga'][$key],
					'opname_detail_qty_data' 	=> $data['opname_detail_qty_data'][$key],
					'opname_detail_qty_fisik' 	=> $data['opname_detail_qty_fisik'][$key],
					'opname_detail_qty_koreksi' => $data['opname_detail_qty_koreksi'][$key],
					'opname_detail_nilai' 		=> $data['opname_detail_nilai'][$key],
					'opname_detail_tanggal' 	=> $data['opname_tanggal'],
					'opname_detail_order' 		=> $key,
				];

				$masuk = $keluar = 0;
				if ($data['opname_detail_qty_koreksi'][$key] > 0) {
					$masuk = $data['opname_detail_qty_koreksi'][$key];
				} else {
					$keluar = abs($data['opname_detail_qty_koreksi'][$key]);
				}
				$kartu = [
					'kartu_id' 				=> $data['opname_detail_id'][$key],
					'kartu_tanggal' 		=> $data['opname_tanggal'],
					'kartu_barang_id' 		=> $value,
					'kartu_satuan_id' 		=> $data['opname_detail_satuan_id'][$key],
					'kartu_stok_masuk' 		=> $masuk,
					'kartu_stok_keluar' 	=> $keluar,
					'kartu_stok_akhir' 		=> $data['opname_detail_qty_fisik'][$key],
					'kartu_transaksi' 		=> 'Opname',
					'kartu_harga'			=> $data['opname_detail_harga'][$key],
					'kartu_transaksi_kode' 	=> $res['record']['opname_kode'],
					'kartu_user' 			=> $res['record']['opname_user_id'],
					'kartu_created_at' 		=> date('Y-m-d H:i:s'),
					'kartu_keterangan' 		=> 'On Updated',
				];

				foreach ($last_detail as $i => $v) {
					if ($v['opname_detail_id'] == $data['opname_detail_id'][$key]) {
						unset($delete[$i]);
					}
				}
				$res_detail = $this->stokopnamedetail->update($data['opname_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$res_detail = $this->stokopnamedetail->insert(gen_uuid($this->stokopnamedetail->get_table()), $detail);
					if ($res_detail['success'] == true) {
						$id_detail[] = $res_detail['id'];
						$kartu['kartu_id'] = $res_detail['id'];
						$kartu['kartu_keterangan'] = 'Insert On Updated';
						$xkartu = $this->stokkartu->insert_kartu($kartu, 'O');
					}
				} else {
					$xkartu = $this->stokkartu->update_kartu($kartu, 'O');
					$id_detail[] = $res_detail['id'];
				}
			}
			$id = implode('","', $id_detail);
			$res['id_detail'] = $id;
			foreach ($delete as $n => $value) {
				$del = $this->stokopnamedetail->delete($value['opname_detail_id']);
				if ($del['success']) {
					$kartu = [
						'kartu_id' 				=> $value['opname_detail_id'],
						'kartu_barang_id'		=> $value['opname_detail_barang_id'],
						'kartu_stok_awal' 		=> 0,
						'kartu_stok_akhir' 		=> $value['opname_detail_qty_fisik'],
						'kartu_transaksi' 		=> 'Opname',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					// $this->db->delete('pos_pembelian_barang_detail', array('opname_detail_id' => $value['opname_detail_id']));
					$this->stokkartu->update_kartu($kartu, 'B');
				}
			}
		});
		$this->response($operation);
	}
	public function getKelompok()
	{
		$data = varPost();
		$data['jenis_include_stok'] = 1;

		$barang = $this->barang->select(array('filters_static' => $data, 'sort_static' => 'barang_kode asc'));
		$html = '';
		foreach ($barang['data'] as $key => $value) {
			$row = $key + 1;
			$html .= '<tr class="barang_' . $row . '">
					<td scope="row">
						<input type="hidden" class="txt-search" name="txt_search[' . $row . ']" id="txt_search_' . $row . '" value="' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '">
						<input type="hidden" class="form-control" name="opname_detail_id[' . $row . ']" id="opname_detail_id_' . $row . '">
						<select class="form-control barang_id" name="opname_detail_barang_id[' . $row . ']" id="opname_detail_barang_id_' . $row . '" data-id="' . $row . '" style="width: 100%;white-space: nowrap" onchange="setSatuan(' . $row . ')">
							<option value="' . $value['barang_id'] . '">' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '</option>
						</select>
					</td>
					<td>
						<input class="form-control" type="hidden" name="opname_detail_satuan_id[' . $row . ']" id="opname_detail_satuan_id_' . $row . '" value="' . $value['barang_satuan'] . '">
						<input class="form-control" type="text" name="opname_detail_satuan_kode[' . $row . ']" id="opname_detail_satuan_kode_' . $row . '" readonly="" value="' . $value['barang_satuan_kode'] . '">
					</td>
					<td><input class="form-control number" type="text" name="opname_detail_harga[' . $row . ']" id="opname_detail_harga_' . $row . '" readonly="" value="' . $value['barang_harga_pokok'] . '"></td>
					<td><input class="form-control number data" type="text" name="opname_detail_qty_data[' . $row . ']" id="opname_detail_qty_data_' . $row . '" readonly="" value="' . $value['barang_stok'] . '"></td>
					<td><input class="form-control number qty" type="text" name="opname_detail_qty_fisik[' . $row . ']" id="opname_detail_qty_fisik_' . $row . '" onkeyup="countRow(' . $row . ')"></td>
					<td><input class="form-control number koreksi" type="text" name="opname_detail_qty_koreksi[' . $row . ']" id="opname_detail_qty_koreksi_' . $row . '" readonly=""></td>
					<td><input class="form-control number nilai" type="text" name="opname_detail_nilai[' . $row . ']" id="opname_detail_nilai_' . $row . '" readonly=""></td>
					<td><a href="javascript:;" data-id="' . $row . '" class="btn btn-light-warning btn-sm" onclick="remRow(this)" title="Hapus">
							<span class="la la-trash"></span> </a></td>
				</tr>';
		}
		$this->response(array(
			'success' 	=> true,
			'html' 		=> $html,
			'barang' 	=> $barang['data']
		));
	}
	public function get_detail()
	{
		$data = varPost();
		$this->response($this->stokopnamedetail->select(array('filters_static' => $data, 'sort_static' => 'opname_detail_order asc')));
	}


	public function destroy()
	{
		$data = varPost();
		$last_detail = $this->stokopnamedetail->select(array('filters_static' => array('opname_detail_parent' => $data['id']), 'sort_static' => 'opname_detail_order asc'))['data'];
		foreach ($last_detail as $key => $value) {
			$kartu = [
				'kartu_id' 				=> $value['opname_detail_id'],
				'kartu_barang_id'		=> $value['opname_detail_barang_id'],
				'kartu_stok_awal' 		=> 0,
				'kartu_stok_masuk' 		=> 0,
				'kartu_stok_keluar'		=> 0,
				'kartu_sto	k_akhir' 		=> $value['opname_detail_qty_fisik'],
				'kartu_transaksi' 		=> 'Opname',
				'kartu_keterangan' 		=> 'Deleted On Updated',
			];
			$this->stokkartu->update_kartu($kartu, 'O');
		}
		$operation = $this->stokopnamedetail->delete(array('opname_detail_parent' => $data['id']));
		$operation = $this->stokopname->delete(varPost('id', varExist($data, $this->stokopname->get_primary(true))));
		$this->response($operation);
	}

	public function cetak($value = '')
	{
		if ($value) {
			$kategori = $this->db->select('kategori_barang_kode, kategori_barang_nama, kategori_barang_id')
				// ->where('kategori_barang_parent =', '#')
				->order_by('kategori_barang_kode')
				->get('pos_kategori')
				->result_array();

			// print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;
			$data = $this->db->where('opname_id', $value)
				->get('v_pos_stock_opname')
				->row_array();
			$detail = $this->db->where('opname_detail_parent', $value)
				->order_by('opname_detail_order')
				->get('v_pos_stock_opname_detail')
				->result_array();

			// print_r('<pre>');print_r($kategori);print_r('</pre>');exit;


			$html = '<style>
				*, table, p, li{
					line-height:1.5;
				}
				.kop{
					text-align: center;
					display:block;
					margin:0 auto;
				}
				.kop h1{
					font-size: 10px;
				}

				.left{
					padding:2px;
				}

				.right{

					text-align:right;
					padding: 2px;
				}
				.t-center{
					vertical-align:middle!important;
					text-align:center;
					background-color : #5a8ed1;
				}

				.divider{
					border-right: 1px solid black;
				}

				.laporan td {
					border: 1px solid black;
					border-collapse: collapse;
					padding:0px 10px;
				}

				.ttd{
					border: 1px solid black;
					border-collapse: collapse;
					padding : 0px 3px;
					text-align:center;
					vertical-align:top;
				}

				.ttd td {
					border : 0px 1px solid black;
					border-collapse: collapse;
					padding:0px 3px;
					height:40px;
				}

				.ttd .top{
					text-align:center;
					vertical-align:top;
					border-right : 1px solid black;
					border-collapse: collapse;
				}

				.ttd .bottom{
					text-align:center;
					vertical-align:bottom;
					border-right : 1px solid black;
					border-collapse: collapse;
				}

				.laporan .total {
					border-top: 1px solid black;
					border-bottom: 1px solid black;
					border-collapse: collapse;
					padding: 0px 10px;
				}	

				table{
					border-collapse: collapse;
					width:100%;
				}
				.laporan th {
					border: 1px solid black;
					border-collapse: collapse;
				}
			</style>';

			$html .= '<table style="width:100%;">
				<tr>
					<td class="left">
						<p>POS PTPIS</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI KOREKSI STOK BARANG </h4><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							
					</td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['opname_tanggal'] ? date("d/m/Y", strtotime($data['opname_tanggal']))  : "-") . '</td>
					<td class="right">Kelompok Barang : </td>
				</tr>
				<tr>
					<td>No. Transaksi : ' . ($data['opname_kode'] ? $data['opname_kode'] : "-") . '</td>
					<td class="right">' . ($data['kategori_barang_nama'] ? $data['kategori_barang_nama'] : "-") . '</td>
				</tr>
			</table>
			<br>			
			<table class="laporan" cellspacing=0 style="width:100%; bopname-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode</th>
					<th class="t-center">Nama Barang</th>
					<th class="t-center">Stok</th>
					<th class="t-center">(+)</th>
					<th class="t-center">(-)</th>
					<th class="t-center">Fisik</th>
					<th class="t-center">Nilai</th>
				</tr>';
			foreach ($kategori as $k => $v) {
				$head_kategori = '<tr style="background-color:#d5d5d5">
						<td colspan="8">' . ($v['kategori_barang_nama']) . ' ( ' . ($v['kategori_barang_kode']) . ' )</td>
					</tr>';
				$body = '';
				$sub_qty_data = $sub_qty_min = $sub_qty_plus = $sub_qty_fisik = $sub_nominal = 0;

				foreach ($detail as $key => $value) {
					// echo $v['kategori_barang_id'] . "=>" . $value['barang_kategori_barang'];
					// echo '<br>';
					if ($v['kategori_barang_id'] == $value['barang_kategori_barang']) {
						$body .= '<tr>
								<td>' . ($key + 1) . '</td>
								<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
								<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
								<td class="right">' . ($value['opname_detail_qty_data'] ? $value['opname_detail_qty_data'] : "-") . '</td>
								<td>' . ($value['opname_detail_qty_koreksi'] > 0 ? $value['opname_detail_qty_koreksi'] : "") . '</td>
								<td>' . ($value['opname_detail_qty_koreksi'] < 0 ? intval($value['opname_detail_qty_koreksi']) : "") . '</td>
								<td class="right">' . $value['opname_detail_qty_fisik'] . '</td>
								<td class="right">' . number_format($value['opname_detail_nilai']) . '</td>
							</tr>';
						$sub_qty_data 	+= $value['opname_detail_qty_data'];
						if ($value['opname_detail_qty_koreksi'] < 0) $sub_qty_min 	+= intval($value['opname_detail_qty_koreksi']);
						else $sub_qty_plus 	+= $value['opname_detail_qty_koreksi'];
						$sub_qty_fisik 	+= $value['opname_detail_qty_fisik'];
						$sub_nominal 	+= $value['opname_detail_nilai'];
					}
				}
				$foot_kategori = '<tr style="background-color:#d5d5d5">
							<td colspan="3">Sub Total</td>
							<td class="right">' . $sub_qty_data . '</td>
							<td>' . $sub_qty_plus . '</td>
							<td>' . $sub_qty_min . '</td>
							<td class="right">' . $sub_qty_fisik . '</td>
							<td class="right">' . number_format($sub_nominal) . '</td>
						</tr>';
				if ($body != '') {
					$html .= $head_kategori . $body . $foot_kategori;
					// echo $head_kategori.$body.$foot_kategori;exit;
				}
			}
			// }

			$html .= '<tr>
					<td colspan="3" class="total">Total</td>
					<td class="total right">' . $data['opname_total_qty_data'] . '</td>
					<td class="total">' . ($data['opname_total_qty_koreksi'] > 0 ? $data['opname_total_qty_koreksi'] : "") . '</td>
					<td class="total">' . ($data['opname_total_qty_koreksi'] < 0 ? $data['opname_total_qty_koreksi'] : "") . '</td>
					<td class="total right">' . $data['opname_total_qty_fisik'] . '</td>
					<td class="total right">' . number_format($data['opname_total_nilai']) . '</td>
				</tr>
			</table>
			<br>
			<br>
			<table style="width:500px;" class="ttd">
				<tr>
					<td class="top">Dibuat :</td>
					<td class="top">Disetujui :</td>
					<td class="top">Diterima :</td>
				</tr>
				<tr>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table>
			';

			// print_r('<pre>');print_r($html);print_r('</pre>');exit;
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4',
				'file_name'     => 'BUKTI STOCK OPNAME',
				'title'         => 'BUKTI STOCK OPNAME',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}

		$where = ($data['fdata']['barang_kategori_barang']) ? 'barang_kategori_barang = \'' . $data['fdata']['barang_kategori_barang'] . '\' AND' : '';
		$where = 'barang_deleted_at is null AND';
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';

		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE ' . $where . ' (barang_nama like \'' . $data['q'] . '%\' OR barang_kode like \'' . $data['q'] . '%\') ')->result_array();

		$return = $this->db->query('SELECT barang_id as id, concat(barang_kode, \' - \', barang_nama) as text, barang_stok as saved FROM v_pos_barang WHERE jenis_include_stok = 1 AND ' . $where . ' (barang_nama like \'' . $data['q'] . '%\' OR barang_kode like \'' . $data['q'] . '%\') ORDER BY barang_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}
}

/* End of file Stokopname.php */
/* Location: ./application/modules/Transaksipembelian/controllers/Transaksipembelian.php */
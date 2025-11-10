<?php
defined('BASEPATH') or exit('No direct script access allowed');

//old Supplier
// class Supplier extends Base_Controller
// {

// 	public function __construct()
// 	{
// 		parent::__construct();
// 		//Do your magic here
// 		$this->load->model(array(
// 			'supplier/supplierModel' => 'supplier',
// 			'supplier/salesModel' => 'sales'
// 		));
// 	}

// 	public function index()
// 	{
// 		$where['supplier_deleted_at'] = null;
// 		$this->response(
// 			$this->select_dt(varPost(), 'supplier', 'table', true, $where)
// 		);
// 	}

// 	function read($value = '')
// 	{
// 		$supplier = $this->supplier->read(varPost());
// 		$sales = $this->sales->select(array('filters_static' => array('sales_supplier_id' => $supplier['supplier_id']), 'sort_static' => 'sales_order'));
// 		$supplier['sales'] = $sales;
// 		$this->response($supplier);
// 	}

// 	function select($value = '')
// 	{
// 		$this->response($this->supplier->select(array('filters_static' => ['supplier_deleted_at' => null])));
// 	}

// 	public function select_ajax($value = '')
// 	{
// 		$data = varPost();
// 		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
// 		$total = $this->db->query('SELECT count(supplier_id) total FROM pos_supplier WHERE supplier_deleted_at IS NULL AND concat(supplier_kode, supplier_nama) like "%' . $data['q'] . '%"')->result_array();

// 		$return = $this->db->query('SELECT supplier_id as id, concat(supplier_kode, " - ", supplier_nama) as text, supplier_kode FROM pos_supplier WHERE supplier_deleted_at IS NULL AND concat(supplier_kode, supplier_nama) like "%' . $data['q'] . '%" LIMIT ' . $data['page'] . $data['limit'])->result_array();
// 		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
// 	}


// 	public function get_sales()
// 	{
// 		$data = varPost();
// 		$operation = $this->sales->select(array(
// 			'filters_static' => array(
// 				'sales_supplier_id' =>  $data['supplier_id'],
// 				'sales_nama like "%' . $data['q'] . '%"' => null
// 			),
// 			'sort_static' 	 => 'sales_nama asc'
// 		));
// 		foreach ($operation['data'] as $key => $value) {
// 			$text[] = [$value['sales_nama'], $value['sales_id']];
// 		}
// 		$this->response($text);
// 	}


// 	public function store()
// 	{
// 		$data = varPost();
// 		$sales = [];
// 		$response = $this->supplier->insert(gen_uuid($this->supplier->get_table()), $data, function ($res) use ($data) {
// 			foreach ($data['sales_nama'] as $key => $value) {
// 				$dt = $res['record'];
// 				$detail = [
// 					'sales_id' 			=> gen_uuid($this->sales->get_table()),
// 					'sales_supplier_id' => $dt['supplier_id'],
// 					'sales_nama' 		=> $value,
// 					'sales_telp' 		=> $data['sales_telp'][$key],
// 					'sales_hp' 			=> $data['sales_hp'][$key],
// 					'sales_keterangan'	=> $data['sales_keterangan'][$key],
// 					'sales_order'		=> $key,
// 				];
// 				$sales[] = $this->sales->insert(gen_uuid($this->sales->get_table()), $detail);
// 			}
// 		});
// 		$response['sales'] = $sales;
// 		$this->response($response);
// 	}


// 	public function update()
// 	{
// 		$data = varPost();

// 		echo json_encode($data);
// 		exit;

// 		$sales = [];
// 		$update = array(
// 			'supplier_id' => $data['supplier_id'],
// 			'supplier_kode' => $data['supplier_kode'],
// 			'supplier_nama' => $data['supplier_nama'],
// 			'supplier_alamat' => $data['supplier_alamat'],
// 			'supplier_telp' => $data['supplier_telp'],
// 			'supplier_rekening' => $data['supplier_rekening'],
// 		);
// 		$response = $this->supplier->update(varPost('id', varExist($data, $this->supplier->get_primary(true))), $update, function ($res) use ($data) {
// 			$delete = $this->sales->delete(array('sales_supplier_id' => $data['supplier_id']));
// 			foreach ($data['sales_nama'] as $key => $value) {
// 				$dt = $res['record'];
// 				$detail = [
// 					'sales_id' 			=> gen_uuid($this->sales->get_table()),
// 					'sales_supplier_id' => $dt['supplier_id'],
// 					'sales_nama' 		=> $value,
// 					'sales_telp' 		=> $data['sales_telp'][$key],
// 					'sales_hp' 			=> $data['sales_hp'][$key],
// 					'sales_keterangan'	=> $data['sales_keterangan'][$key],
// 					'sales_order'		=> $key,
// 				];
// 				$opt = $this->sales->insert(gen_uuid($this->sales->get_table()), $detail);
// 				$sales[] = $opt;
// 			}
// 		});
// 		$response['sales'] = $sales;
// 		$this->response($response);
// 	}


// 	public function destroy()
// 	{
// 		$data = varPost();
// 		$operation = $this->supplier->delete(varPost('id', varExist($data, $this->supplier->get_primary(true))));
// 		$this->response($operation);
// 	}

// 	public function getHeader()
// 	{
// 		$html = '<style>
// 			*, table, p, li{
// 				line-height:1.5;
// 				font-size:9px;
// 			}
// 			.kop{
// 				text-align: center;
// 				display:block;
// 				margin:0 auto;
// 			}
// 			.kop h5{
// 				font-size: 9px;
// 			}

// 			.left{
// 				padding:2px;
// 			}

// 			.right{

// 				text-align:right;
// 				padding: 2px;
// 			}
// 			.t-center{
// 				vertical-align:middle!important;
// 				text-align:center;
// 				background-color : #5a8ed1;
// 			}
// 			.t-block{
// 				background-color : #ccc;
// 			}

// 			.divider{
// 				border-right: 1px solid black;
// 			}

// 			.laporan td {
// 				border: 1px solid black;
// 				border-collapse: collapse;
// 				padding:0px 10px;
// 				line-height:18px;
// 			}

// 			.ttd{
// 				border: 1px solid black;
// 				border-collapse: collapse;
// 				padding : 0px 3px;
// 				text-align:center;
// 				vertical-align:top;
// 			}

// 			.ttd td {
// 				border : 0px 1px solid black;
// 				border-collapse: collapse;
// 				padding:0px 3px;
// 				height:40px;
// 			}

// 			.ttd .top{
// 				text-align:center;
// 				vertical-align:top;
// 				border-right : 1px solid black;
// 				border-collapse: collapse;
// 			}

// 			.ttd .bottom{
// 				text-align:center;
// 				vertical-align:bottom;
// 				border-right : 1px solid black;
// 				border-collapse: collapse;
// 			}

// 			.laporan .total {
// 				border-top: 1px solid black;
// 				border-bottom: 1px solid black;
// 				border-collapse: collapse;
// 				padding: 0px 10px;
// 			}	

// 			table{
// 				border-collapse: collapse;
// 				width:100%;
// 			}
// 			.laporan th {
// 				border: 1px solid black;
// 				border-collapse: collapse;
// 			}
// 		</style>';
// 		$html  .= '
// 			<table style="width:100%;">
// 			<tr>
// 				<td class="left">
// 					<p>POS PTPIS</p>
// 					<p>--- -------</p>
// 				</td>
// 				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
// 			</tr>
// 			<tr>
// 				<td colspan="2" class="kop">
// 					<h5>DATA SUPPLIER </h5><br>
// 				</td>
// 			</tr>
// 		</table>
// 		<br>
// 	   ';
// 		$html .= '
// 			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
// 				<tr>
// 					<th class="t-center">No.</th>
// 					<th class="t-center">Kode Supplier</th>
// 					<th class="t-center">Nama Supplier</th>
// 					<th class="t-center">Telp Supplier</th>
// 					<th class="t-center">Alamat Supplier</th>\
// 				</tr>
// 		';

// 		return $html;
// 	}

// 	public function preview()
// 	{
// 		$data = varPost();


// 		$where = [];

// 		if (!empty($data['supplier1'])) {
// 			if (!empty($data['supplier2'])) {
// 				$where["supplier_id between '" . $data['supplier1'] . "' AND '" . $data['supplier2'] . "' "] = null;
// 			} else {
// 				$where['supplier_id'] = $data['supplier1'];
// 			}
// 		}

// 		$supplier = $this->supplier->select(array('filters_static' => $where));

// 		$html = $this->getHeader();

// 		$no = 1;
// 		foreach ($supplier['data'] as $value) {
// 			$html .= '
// 				<tr>
// 					<td>' . ($no++) . '</td>
// 					<td>' . $value['supplier_kode'] . '</td>
// 					<td>' . $value['supplier_nama'] . '</td>
// 					<td>' . $value['supplier_telp'] . '</td>
// 					<td>' . $value['supplier_alamat'] . '</td>
// 				</tr>
// 			';
// 		}

// 		$html .= '</table>';
// 		createPdf(array(
// 			'data'          => $html,
// 			'json'          => true,
// 			'paper_size'    => 'A4',
// 			'file_name'     => 'Daftar Supplier',
// 			'title'         => 'Daftar Supplier',
// 			'stylesheet'    => './assets/laporan/print.css',
// 			'margin'        => '10 5 10 5',
// 			// 'font_face'     => 'cour',
// 			'font_size'     => '10',
// 			'footer'        => 'Hal. {PAGENO} dari {nb}',
// 		));
// 	}
// 	public function delete()
// 	{
// 		$data = varPost();
// 		$data['supplier_deleted_at'] = date("Y-m-d H:i:s");
// 		$operation = $this->supplier->update($data['id'], $data);
// 		$this->response($operation);
// 	}
// }

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LogTransaksi extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'upload/uploadModel' => 'realisasi',
			'upload/uploadDetailModel' => 'realisasiDetail',
			'realisasipajak/RealisasipajakModel' => 'realisasipajak',
			'realisasipajak/RealisasipajakdetailModel' => 'realisasipajakdetail',
		));
	}

	public function index()
	{
		$data 								 = varPost();
		$user 								 = $this->session->userdata();
		$where['realisasi_deleted_at'] 		 = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['wajibpajak_npwpd'];

		// print_r($user);

		if ($data['filterBulan'] != null) {
			$data = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $data[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $data[0] . '\''] = null;
		}

		$opr = $this->select_dt(varPost(), 'realisasi', 'table', true, $where);

		$get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		sum(realisasi_pajak) as total_pajak,
		sum(realisasi_sub_total) as total_subtotal,
		sum(realisasi_total) as total_total,")
			->where($where)
			->get('pajak_realisasi')
			->row();

		$opr['sumtotal'] = $get_total;
		$opr['tarif'] = (int)$this->db->select("jenis_tarif")
			->where("jenis_id", $user["wajibpajak_sektor_nama"])
			->get('pajak_jenis')
			->row()
			->jenis_tarif;
		$opr['npwpd'] = $user['wajibpajak_npwpd'];
		$this->response(
			$opr
		);
	}

	function read($value = '')
	{
		$this->response($this->realisasi->read(varPost()));
	}

	function select($value = '')
	{
		$user = $this->session->userdata();

		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['wajibpajak_npwpd'];
		$this->response($this->realisasi->select(array('filters_static' => $where)));
	}

	public function store()
	{
		$data = varPost();
		// print_r('<pre>');print_r($data);print_r('</pre>');exit;
		$user = $this->session->userdata();
		$wp_id = $user['wajibpajak_id'];
		$npwpd = $user['wajibpajak_npwpd'];
		$periode = $data['periode_upload'];
		$sum_subtotal = $data['sum_subtotal'];
		$sum_service = $data['sum_service'];
		$sum_tax = $data['sum_tax'];
		$sum_total = $data['sum_total'];

		$sum_subtotal = preg_replace('/\D/', '', $sum_subtotal);
		$sum_service = preg_replace('/\D/', '', $sum_service);
		$sum_tax = preg_replace('/\D/', '', $sum_tax);
		$sum_total = preg_replace('/\D/', '', $sum_total);

		$realisasi_id = gen_uuid($this->realisasi->get_table());

		foreach ($data['time'] as $key => $value) {
			$kode = $data['receiptno'][$key];
			$subtotal = $data['subtotal'][$key];
			$tax = $data['tax'][$key];
			$total = $data['total'][$key];
			$service = $data['service'][$key];
			$subtotal = preg_replace('/\D/', '', $subtotal);
			$tax = preg_replace('/\D/', '', $tax);
			$total = preg_replace('/\D/', '', $total);
			$service = preg_replace('/\D/', '', $service);
			$batch[] = [
				'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
				'realisasi_detail_npwpd' => $user['wajibpajak_npwpd'],
				'realisasi_detail_parent' => $realisasi_id,
				'realisasi_detail_time' => $value,
				'realisasi_detail_penjualan_kode' => $kode,
				'realisasi_detail_sub_total' => $subtotal,
				'realisasi_detail_jasa' => $service,
				'realisasi_detail_pajak' => $tax,
				'realisasi_detail_total' => $total,
			];
		}
		// die(json_encode($batch));
		$this->db->insert_batch('pajak_realisasi_detail', $batch);

		// Insert data parent laporan realisasi
		$this->db->insert('pajak_realisasi', [
			'realisasi_id' => $realisasi_id,
			'realisasi_no' => 0,
			'realisasi_wajibpajak_id' => $wp_id,
			'realisasi_wajibpajak_npwpd' => $npwpd,
			'realisasi_tanggal' => $periode,
			'realisasi_sub_total' => $sum_subtotal,
			'realisasi_jasa' => $sum_service,
			'realisasi_pajak' => $sum_tax,
			'realisasi_total' => $sum_total,
			'realisasi_created_at' => date("Y-m-d H:i:s")
		]);

		log_activity('Input form rekap transaksi');

		$response = [
			'success' => true,
			'message' => 'Successfully saved data.',
			'id' => $realisasi_id
		];

		$this->response($response);
	}


	public function update()
	{
		$data = varPost();
		$ops = $this->realisasi->update(varPost('id', varExist($data, $this->realisasi->get_primary(true))), $data);
		log_activity('Ubah laporan realisasi ' . $ops['realisasi_no']);
		$this->response($ops);
	}

	public function delete()
	{
		$data = varPost();
		$data['realisasi_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->realisasi->update($data['id'], $data);
		log_activity('Menghapus laporan realisasi ' . $operation['realisasi_no']);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->realisasi->delete(varPost('id', varExist($data, $this->realisasi->get_primary(true))));
		log_activity('Menghapus laporan realisasi ' . $operation['realisasi_no']);
		$this->response($operation);
	}
}

/* End of file Supplier.php */
/* Location: ./application/modules/Supplier/controllers/Supplier.php */
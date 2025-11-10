<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'supplier/supplierModel' => 'supplier',
			'supplier/salesModel' => 'sales'
		));
	}

	public function index()
	{
		$where['supplier_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'supplier', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$supplier = $this->supplier->read(varPost());
		$sales = $this->sales->select(array('filters_static' => array('sales_supplier_id' => $supplier['supplier_id']), 'sort_static' => 'sales_order'));
		$supplier['sales'] = $sales;
		$this->response($supplier);
	}

	function select($value = '')
	{
		$this->response($this->supplier->select(array('filters_static' => ['supplier_deleted_at' => null])));
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(supplier_id) total FROM pos_supplier WHERE supplier_deleted_at IS NULL AND concat(supplier_kode, supplier_nama) like "%' . $data['q'] . '%"')->result_array();

		$return = $this->db->query('SELECT supplier_id as id, concat(supplier_kode, " - ", supplier_nama) as text, supplier_kode FROM pos_supplier WHERE supplier_deleted_at IS NULL AND concat(supplier_kode, supplier_nama) like "%' . $data['q'] . '%" LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}


	public function get_sales()
	{
		$data = varPost();
		$operation = $this->sales->select(array(
			'filters_static' => array(
				'sales_supplier_id' =>  $data['supplier_id'],
				'sales_nama like "%' . $data['q'] . '%"' => null
			),
			'sort_static' 	 => 'sales_nama asc'
		));
		foreach ($operation['data'] as $key => $value) {
			$text[] = [$value['sales_nama'], $value['sales_id']];
		}
		$this->response($text);
	}


	public function store()
	{
		$data = varPost();
		$sales = [];
		$response = $this->supplier->insert(gen_uuid($this->supplier->get_table()), $data, function ($res) use ($data) {
			foreach ($data['sales_nama'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'sales_id' 			=> gen_uuid($this->sales->get_table()),
					'sales_supplier_id' => $dt['supplier_id'],
					'sales_nama' 		=> $value,
					'sales_telp' 		=> $data['sales_telp'][$key],
					'sales_hp' 			=> $data['sales_hp'][$key],
					'sales_keterangan'	=> $data['sales_keterangan'][$key],
					'sales_order'		=> $key,
				];
				$sales[] = $this->sales->insert(gen_uuid($this->sales->get_table()), $detail);
			}
		});
		$response['sales'] = $sales;
		$this->response($response);
	}


	public function update()
	{
		$data = varPost();

		echo json_encode($data);
		exit;

		$sales = [];
		$update = array(
			'supplier_id' => $data['supplier_id'],
			'supplier_kode' => $data['supplier_kode'],
			'supplier_nama' => $data['supplier_nama'],
			'supplier_alamat' => $data['supplier_alamat'],
			'supplier_telp' => $data['supplier_telp'],
			'supplier_rekening' => $data['supplier_rekening'],
		);
		$response = $this->supplier->update(varPost('id', varExist($data, $this->supplier->get_primary(true))), $update, function ($res) use ($data) {
			$delete = $this->sales->delete(array('sales_supplier_id' => $data['supplier_id']));
			foreach ($data['sales_nama'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'sales_id' 			=> gen_uuid($this->sales->get_table()),
					'sales_supplier_id' => $dt['supplier_id'],
					'sales_nama' 		=> $value,
					'sales_telp' 		=> $data['sales_telp'][$key],
					'sales_hp' 			=> $data['sales_hp'][$key],
					'sales_keterangan'	=> $data['sales_keterangan'][$key],
					'sales_order'		=> $key,
				];
				$opt = $this->sales->insert(gen_uuid($this->sales->get_table()), $detail);
				$sales[] = $opt;
			}
		});
		$response['sales'] = $sales;
		$this->response($response);
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->supplier->delete(varPost('id', varExist($data, $this->supplier->get_primary(true))));
		$this->response($operation);
	}

	public function getHeader()
	{
		$html = '<style>
			*, table, p, li{
				line-height:1.5;
				font-size:9px;
			}
			.kop{
				text-align: center;
				display:block;
				margin:0 auto;
			}
			.kop h5{
				font-size: 9px;
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
			.t-block{
				background-color : #ccc;
			}

			.divider{
				border-right: 1px solid black;
			}

			.laporan td {
				border: 1px solid black;
				border-collapse: collapse;
				padding:0px 10px;
				line-height:18px;
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
		$html  .= '
			<table style="width:100%;">
			<tr>
				<td class="left">
					<p>POS PTPIS</p>
					<p>--- -------</p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h5>DATA SUPPLIER </h5><br>
				</td>
			</tr>
		</table>
		<br>
	   ';
		$html .= '
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode Supplier</th>
					<th class="t-center">Nama Supplier</th>
					<th class="t-center">Telp Supplier</th>
					<th class="t-center">Alamat Supplier</th>\
				</tr>
		';

		return $html;
	}

	public function preview()
	{
		$data = varPost();


		$where = [];

		if (!empty($data['supplier1'])) {
			if (!empty($data['supplier2'])) {
				$where["supplier_id between '" . $data['supplier1'] . "' AND '" . $data['supplier2'] . "' "] = null;
			} else {
				$where['supplier_id'] = $data['supplier1'];
			}
		}

		$supplier = $this->supplier->select(array('filters_static' => $where));

		$html = $this->getHeader();

		$no = 1;
		foreach ($supplier['data'] as $value) {
			$html .= '
				<tr>
					<td>' . ($no++) . '</td>
					<td>' . $value['supplier_kode'] . '</td>
					<td>' . $value['supplier_nama'] . '</td>
					<td>' . $value['supplier_telp'] . '</td>
					<td>' . $value['supplier_alamat'] . '</td>
				</tr>
			';
		}

		$html .= '</table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Daftar Supplier',
			'title'         => 'Daftar Supplier',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
			'footer'        => 'Hal. {PAGENO} dari {nb}',
		));
	}
	public function delete()
	{
		$data = varPost();
		$data['supplier_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->supplier->update($data['id'], $data);
		$this->response($operation);
	}
}

/* End of file Supplier.php */
/* Location: ./application/modules/Supplier/controllers/Supplier.php */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'penjualan/PenjualanModel' => 'penjualan',
		));
	}

	public function index()
	{
		// $where['penjualan_deleted_at'] = null;
		// $this->response(
		// 	$this->select_dt(varPost(), 'penjualan', 'table', true, $where)
		// );
		// $where['penjualan_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'penjualan', 'table')
		);
	}

	function read($value = '')
	{
		$penjualan = $this->penjualan->read(varPost());
		$sales = $this->sales->select(array('filters_static' => array('sales_penjualan_id' => $penjualan['penjualan_id']), 'sort_static' => 'sales_order'));
		$penjualan['sales'] = $sales;
		$this->response($penjualan);
	}

	function select($value = '')
	{
		$this->response($this->penjualan->select(array('filters_static' => varPost())));
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(penjualan_id) total FROM ms_penjualan WHERE concat(penjualan_kode, penjualan_nama) like "%' . $data['q'] . '%"')->result_array();

		$return = $this->db->query('SELECT penjualan_id as id, concat(penjualan_kode, " - ", penjualan_nama) as text, penjualan_kode FROM ms_penjualan WHERE concat(penjualan_kode, penjualan_nama) like "%' . $data['q'] . '%" LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}


	public function get_sales()
	{
		$data = varPost();
		$operation = $this->sales->select(array(
			'filters_static' => array(
				'sales_penjualan_id' =>  $data['penjualan_id'],
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
		$response = $this->penjualan->insert(gen_uuid($this->penjualan->get_table()), $data, function ($res) use ($data) {
			foreach ($data['sales_nama'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'sales_id' 			=> gen_uuid($this->sales->get_table()),
					'sales_penjualan_id' => $dt['penjualan_id'],
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
		$sales = [];
		$update = array(
			'penjualan_id' => $data['penjualan_id'],
			'penjualan_kode' => $data['penjualan_kode'],
			'penjualan_nama' => $data['penjualan_nama'],
			'penjualan_alamat' => $data['penjualan_alamat'],
			'penjualan_telp' => $data['penjualan_telp'],
			'penjualan_rekening' => $data['penjualan_rekening'],
		);
		$response = $this->penjualan->update(varPost('id', varExist($data, $this->penjualan->get_primary(true))), $update, function ($res) use ($data) {
			$delete = $this->sales->delete(array('sales_penjualan_id' => $data['penjualan_id']));
			foreach ($data['sales_nama'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'sales_id' 			=> gen_uuid($this->sales->get_table()),
					'sales_penjualan_id' => $dt['penjualan_id'],
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
		$operation = $this->penjualan->delete(varPost('id', varExist($data, $this->penjualan->get_primary(true))));
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
					<p>KPRI EKO KAPTI</p>
					<p><u>KANTOR KEMENAG KAB.MALANG</u></p>
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

		if (!empty($data['penjualan1'])) {
			if (!empty($data['penjualan2'])) {
				$where["penjualan_id between '" . $data['penjualan1'] . "' AND '" . $data['penjualan2'] . "' "] = null;
			} else {
				$where['penjualan_id'] = $data['penjualan1'];
			}
		}

		$penjualan = $this->penjualan->select(array('filters_static' => $where));

		$html = $this->getHeader();

		$no = 1;
		foreach ($penjualan['data'] as $value) {
			$html .= '
				<tr>
					<td>' . ($no++) . '</td>
					<td>' . $value['penjualan_kode'] . '</td>
					<td>' . $value['penjualan_nama'] . '</td>
					<td>' . $value['penjualan_telp'] . '</td>
					<td>' . $value['penjualan_alamat'] . '</td>
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
		$data['penjualan_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->penjualan->update($data['id'], $data);
		$this->response($operation);
	}

}

/* End of file penjualan.php */
/* Location: ./application/modules/penjualan/controllers/penjualan.php */
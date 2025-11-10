<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orderpembelian extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'orderpembelianModel' 		=> 'orderpembelian',
			'orderpembeliandetailModel' => 'orderpembeliandetail',
			'OrderpembeliandetailpembayaranModel' => 'orderpembeliandetailpembayaran'
		));
	}

	public function index()
	{
		$data = varPost();
		// print_r($data['data1']);
		// exit();
		// $this->response(
		// 	$this->select_dt($data, 'orderpembelian', 'table', false, array(
		// 		'order_tanggal BETWEEN "' . $data['data1'] . '" AND "' . $data['data2'] . ' "' => null, 'order_aktif' => 1
		// 	))
		// );

		$where['order_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'orderpembelian', 'table', true, $where)
		);
	}

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(), 'orderpembeliandetail', 'table', true, varPost('beli'))
		);
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(order_id) total FROM v_pos_order_pembelian WHERE order_aktif = 1 like "%' . $data['q'] . '%"')->result_array();

		$return = $this->db->query('SELECT order_id as id, concat(order_kode," - ", supplier_nama) as text, order_kode FROM v_pos_order_pembelian WHERE order_aktif = 1 like "%' . $data['q'] . '%" LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	function read($value = '')
	{
		$pembelian = $this->orderpembelian->read(varPost());
		$this->db->where('order_detail_parent', varPost('order_id'));
		$pembelian['barang'] = $this->db->get('v_pos_order_pembelian_detail')->result_array();

		$this->response($pembelian);
	}

	function read_detail($value = '')
	{
		$this->response($this->orderpembeliandetail->read(varPost()));
	}

	public function get_order($value = '')
	{
		$data = varPost();
		$order = $this->orderpembelian->read($data);
		if (isset($order['order_id'])) {
			$detail = $this->orderpembeliandetail->select(array('filters_static' => array('order_detail_parent' => $order['order_id']), 'sort_static' => 'order_detail_order'));
			$order['detail'] = $detail['data'];
		}
		$this->response($order);
	}

	public function store()
	{
		$data = varPost();
		$data['order_kode'] = $this->orderpembelian->gen_kode_pembelian();
		$data['order_user'] = $this->session->userdata('user_id');
		$data['order_aktif'] = '1';

		$operation = $this->orderpembelian->insert(gen_uuid($this->orderpembelian->get_table()), $data, function ($res) use ($data) {
			$detail = [];
			$detail_pembayaran = [];
			foreach ($data['order_detail_barang_id'] as $key => $value) {
				$detail = [
					'order_detail_parent' 		=> $res['record']['order_id'],
					'order_detail_barang_id'	=> $value,
					'order_detail_satuan' 		=> $data['order_detail_satuan'][$key],
					'order_detail_qty' 			=> $data['order_detail_qty'][$key],
					'order_detail_qty_barang' 	=> $data['order_detail_qty_barang'][$key],
					'order_detail_harga' 		=> $data['order_detail_harga'][$key],
					'order_detail_harga_barang' => $data['order_detail_harga_barang'][$key],
					'order_detail_jumlah' 		=> $data['order_detail_jumlah'][$key],
					'order_detail_order' 		=> $key,
				];
				$det_opr = $this->orderpembeliandetail->insert(gen_uuid($this->orderpembeliandetail->get_table()), $detail);
				if (!$det_opr['success']) $res['res'][] = $det_opr;
			}

			foreach ($data['order_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'order_detail_pembayaran_id' => $value,
					'order_detail_pembayaran_parent' => $res['record']['order_id'],
					'order_detail_pembayaran_tanggal' => $data['order_detail_pembayaran_tanggal'][$key],
					'order_detail_pembayaran_cara_bayar' => $data['order_detail_pembayaran_cara_bayar'][$key],
				];
				$det_opr_pembayaran = $this->orderpembeliandetailpembayaran->insert(gen_uuid($this->orderpembeliandetailpembayaran->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}
		});
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$operation = $this->orderpembelian->update($data['order_id'], $data, function (&$res) use ($data) {
			$detail = $id_detail == [];
			foreach ($data['order_detail_barang_id'] as $key => $value) {
				$detail = [
					'order_detail_parent' 		=> $res['record']['order_id'],
					'order_detail_barang_id'	=> $value,
					'order_detail_satuan' 		=> $data['order_detail_satuan'][$key],
					'order_detail_qty' 			=> $data['order_detail_qty'][$key],
					'order_detail_qty_barang' 	=> $data['order_detail_qty_barang'][$key],
					'order_detail_harga' 		=> $data['order_detail_harga'][$key],
					'order_detail_harga_barang' => $data['order_detail_harga_barang'][$key],
					'order_detail_jumlah' 		=> $data['order_detail_jumlah'][$key],
					'order_detail_order' 		=> $key,
				];
				$res_detail = $this->orderpembeliandetail->update($data['order_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$res_detail = $this->orderpembeliandetail->insert(gen_uuid($this->orderpembeliandetail->get_table()), $detail);
					if ($res_detail['success']) $id_detail[] = $res_detail['id'];
				} else {
					$id_detail[] = $res_detail['id'];
				}
			}
			$res['id_detail'] = $id;
			$id = implode('","', $id_detail);
			$this->orderpembeliandetail->delete(array(
				'order_detail_id not in("' . $id . '")' => null,
				'order_detail_parent' => $res['record']['order_id']
			));
		});
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->orderpembeliandetail->select(array('filters_static' => $data, 'sort_static' => 'order_detail_order asc')));
	}

	public function delete()
	{
		$data = varPost();
		$data['order_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->orderpembelian->update($data['id'], $data);
		$this->response($operation);
	}


	public function destroy()
	{
		$data = varPost();
		//$operation = $this->orderpembelian->delete(varPost('id', varExist($data, $this->orderpembelian->get_primary(true))));
		//$operation = $this->orderpembeliandetail->delete(array('pembelian_detail_parent'=>$data['id']));
		$operation = $this->orderpembelian->update(varPost('id'), array('order_aktif' => 0));
		$this->response($operation);
	}

	public function cetak($value = '')
	{
		if ($value) {
			$data = $this->db->where('order_id', $value)
				->get('v_pos_order_pembelian')
				->row_array();
			$detail = $this->db->where('order_detail_parent', $value)
				->get('v_pos_order_pembelian_detail')
				->result_array();
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
							<h4> BUKTI ORDER PEMBELIAN BARANG </h4><br>
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
					<td>Tanggal Transaksi : ' . ($data['order_tanggal'] ? date("d/m/Y", strtotime($data['order_tanggal']))  : "-") . '</td>
					<td class="right">Supplier : ' . ($data['supplier_kode'] ? $data['supplier_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td>No. Order : ' . ($data['order_kode'] ? $data['order_kode'] : "-") . '</td>
					<td class="right">' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Tanggal Pengiriman : ' . ($data['order_tanggal_dikirim'] ? date("d/m/Y", strtotime($data['order_tanggal_dikirim'])) : "-") . '</td>
					<td class="right">' . ($data['supplier_alamat'] ? $data['supplier_alamat'] : "-") . ' / ' . ($data['supplier_telp'] ? $data['supplier_telp'] : "-") . '</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode</th>
					<th class="t-center">Nama Barang</th>
					<th class="t-center">Satuan</th>
					<th class="t-center">Harga</th>
					<th class="t-center">Qty</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Sisa Stok</th>
				</tr>';


			foreach ($detail as $key => $value) {
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
						<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
						<td>' . ($value['barang_satuan_kode'] ? $value['barang_satuan_kode'] : "") . '</td>
						<td class="right">' . ($value['order_detail_harga'] ? number_format($value['order_detail_harga'], 2) : "") . '</td>
						<td class="right">' . ($value['order_detail_qty'] ? number_format($value['order_detail_qty']) : "-") . '</td>
						<td class="right">' . ($value['order_detail_jumlah'] ? number_format($value['order_detail_jumlah'], 2) : "") . '</td>
						<td class="right">' . ($value['barang_stok'] ? number_format($value['barang_stok']) : "0") . '</td>
					</tr>';
			}


			$html .= '<tr>
					<td colspan="5" class="total">Total</td>
					<td class="total" class="right">' . number_format($data['order_total_qty']) . '</td>
					<td class="total" class="right">' . number_format($data['order_total'], 2) . '</td>
					<td class="total"></td>
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
			// print_r($html);
			// exit();
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4',
				'file_name'     => 'BUKTI PEMBELIAN',
				'title'         => 'BUKTI PEMBELIAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}
}

/* End of file Orderpembelian.php */
/* Location: ./application/modules/Transaksipembelian/controllers/Transaksipembelian.php */
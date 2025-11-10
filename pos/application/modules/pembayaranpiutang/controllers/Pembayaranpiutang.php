<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaranpiutang extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here 
		$this->load->model(array(
			'PembayaranpiutangModel' 		=> 'pembayaran',
			'PembayaranpiutangdetailModel' => 'pembayarandetail',
			'PembayaranpiutangdetailpembayaranModel' 	=> 'multipayment',
			'transaksipenjualan/TransaksipenjualanModel' => 'transaksipenjualan',
		));
	}

	public function index()
	{
		$var = varPost();

		$this->response(
			$this->select_dt($var, 'pembayaran', 'table', true, array(
				'pembayaran_piutang_aktif' => '1',
			))
		);
	}


	public function index2()
	{
		$var = varPost();
		$where = [
			'pembayaran_piutang_aktif' => '1',
		];
		if (!empty($var['tanggal1'])) {
			$where['pembayaran_piutang_tanggal BETWEEN \'' . $var['tanggal1'] . '\' AND \'' . $var['tanggal2'] . '\' '] = null;
		}
		$this->response(
			$this->select_dt($var, 'pembayaran', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->pembayaran->read(varPost()));
	}

	public function store()
	{
		$data = varPost();

		$data['pembayaran_piutang_kode'] = $this->pembayaran->gen_kode_pembayaran();
		$data['pembayaran_piutang_user'] = $this->session->userdata('user_id');
		$data['pembayaran_piutang_aktif'] = '1';
		$data['pembayaran_piutang_created_at'] = date('Y-m-d');
		$data['pembayaran_piutang_invoice'] = $this->pembayaran->gen_invoice_pembayaran();
		$error = [];

		$sales = $this->db->select('sales_id')
			->get_where('pos_sales', array(
				'sales_nama' 		=> $data['pembayaran_piutang_sales'],
				'sales_supplier_id' => $data['pembayaran_piutang_supplier_id'],
			))
			->result_array();
		if (count($sales) < 1) {
			$this->db->insert('pos_sales', array(
				'sales_id' 			=> gen_uuid($this->pembayaran->get_table()),
				'sales_supplier_id' => $data['pembayaran_piutang_supplier_id'],
				'sales_nama' 		=> $data['pembayaran_piutang_sales'],
			));
		}

		// Update sisa baya penjualan
		foreach ($data['pembayaran_piutang_detail_penjualan_id'] as $key => $value) {
			$this->db->where('penjualan_id', $value);
			$data_penjualan = $this->db->get('pos_penjualan')->row_array();
			$piutang_now = $data_penjualan['penjualan_bayar_sisa'] - $data['pembayaran_piutang_bayar'];
			$bayar_now	= $data_penjualan['penjualan_total_bayar'] + $data['pembayaran_piutang_bayar'];

			$this->db->set('penjualan_total_bayar', $bayar_now);
			$this->db->set('penjualan_bayar_sisa', $piutang_now);
			$this->db->where('penjualan_id', $value);
			$this->db->update('pos_penjualan');
		}

		$data = cVarNull($data);

		$operation = $this->pembayaran->insert(gen_uuid($this->pembayaran->get_table()), $data, function ($res) use ($data) {
			// get keperluan update rental
			$penjualan_id = '';
			foreach ($data['pembayaran_piutang_detail_penjualan_id'] as $key => $value) {
				$penjualan_id =  $key;
			}
			$barang_id = $this->db->get_where('pos_penjualan_detail', ['penjualan_detail_parent' => $penjualan_id])->row_array()['penjualan_detail_barang_id'];
			$dc_barang = $this->db->get_where('v_pos_barang', ['barang_id' => $barang_id])->row_array();

			if ($dc_barang['jenis_include_stok'] == 2) {
				$this->db->set('barang_aktif', 2);
				$this->db->where('barang_id', $barang_id);
				$this->db->update('pos_barang');
			}


			$detail = [];
			// Multi Payment
			foreach ($data['pembayaran_piutang_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'pembayaran_piutang_detail_pembayaran_id' => $value,
					'pembayaran_piutang_detail_pembayaran_parent' => $res['record']['pembayaran_piutang_id'],
					'pembayaran_piutang_detail_pembayaran_tanggal' => $data['pembayaran_piutang_detail_pembayaran_tanggal'][$key],
					'pembayaran_piutang_detail_pembayaran_cara_bayar' => $data['pembayaran_piutang_detail_pembayaran_cara_bayar'][$key],
					'pembayaran_piutang_detail_pembayaran_total' => $data['pembayaran_piutang_detail_pembayaran_total'][$key],
				];
				$detail_pembayaran = cVarNull($detail_pembayaran);
				$det_opr_pembayaran = $this->multipayment->insert(gen_uuid($this->multipayment->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}


			// handle detail
			foreach ($data['pembayaran_piutang_detail_id'] as $key => $value) {
				$detail = [
					'pembayaran_piutang_detail_parent' => $res['record']['pembayaran_piutang_id'],
					'pembayaran_piutang_detail_penjualan_id' => $data['pembayaran_piutang_detail_penjualan_id'][$key],
					'pembayaran_piutang_detail_jatuh_tempo'	=> $data['pembayaran_piutang_detail_jatuh_tempo'][$key],
					'pembayaran_piutang_detail_tagihan' 	=> $data['pembayaran_piutang_detail_tagihan'][$key],
					'pembayaran_piutang_detail_retur' 		=> $data['pembayaran_piutang_detail_retur'][$key],
					'pembayaran_piutang_detail_potongan' 	=> $data['pembayaran_piutang_detail_potongan'][$key],
					'pembayaran_piutang_detail_sisa' 		=> $data['pembayaran_piutang_detail_sisa'][$key],
					'pembayaran_piutang_detail_bayar' 		=> $data['pembayaran_piutang_detail_bayar'][$key],
				];
				$tag = $data['pembayaran_piutang_detail_tagihan'][$key] - $data['pembayaran_piutang_detail_retur'][$key];
				// bayar tidak boleh melebihi tagihan
				$bayar = intval($data['pembayaran_piutang_detail_bayar'][$key]);
				$sisa = $tag - $bayar;
				$detail = cVarNull($detail);
				$det_opr = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()), $detail);
			}
		});
		$operation['error'] = $error;
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();

		// Update sisa baya penjualan
		foreach ($data['pembayaran_piutang_detail_penjualan_id'] as $key => $value) {
			$this->db->where('penjualan_id', $value);
			$data_penjualan = $this->db->get('pos_penjualan')->row_array();
			$piutang_now = $data_penjualan['penjualan_total_kredit'] - $data['pembayaran_piutang_bayar'];;
			$bayar_now	= $data['pembayaran_piutang_bayar'];

			$this->db->set('penjualan_total_bayar', $bayar_now);
			$this->db->set('penjualan_bayar_sisa', $piutang_now);
			$this->db->where('penjualan_id', $value);
			$this->db->update('pos_penjualan');
		}

		// Destroy pembayaran lama 
		$this->db->where('pembayaran_piutang_detail_pembayaran_parent', $data['pembayaran_piutang_id']);
		$this->db->delete('pos_pembayaran_piutang_pembayaran_detail');
		// Handle detail pembayaran  
		foreach ($data['pembayaran_piutang_detail_pembayaran_cara_bayar'] as $key => $value) {
			$detail_pembayaran = [
				'pembayaran_piutang_detail_pembayaran_id' => $value,
				'pembayaran_piutang_detail_pembayaran_parent' => $data['pembayaran_piutang_id'],
				'pembayaran_piutang_detail_pembayaran_tanggal' => $data['pembayaran_piutang_detail_pembayaran_tanggal'][$key],
				'pembayaran_piutang_detail_pembayaran_cara_bayar' => $data['pembayaran_piutang_detail_pembayaran_cara_bayar'][$key],
				'pembayaran_piutang_detail_pembayaran_total' => $data['pembayaran_piutang_detail_pembayaran_total'][$key],
			];

			if ($detail_pembayaran['pembayaran_piutang_detail_pembayaran_tanggal'] != '' || $detail_pembayaran['pembayaran_piutang_detail_pembayaran_tanggal'] != NULL) {
				$det_opr_pembayaran = $this->multipayment->insert(gen_uuid($this->multipayment->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}
		}

		// // Destroy detail lama
		$this->db->where('pembayaran_piutang_detail_parent', $data['pembayaran_piutang_id']);
		$this->db->delete('pos_pembayaran_piutang_detail');

		// // handle detail beli
		foreach ($data['pembayaran_piutang_detail_penjualan_id'] as $key => $value) {
			$detail_beli = [
				'pembayaran_piutang_detail_parent' 		 => $data['pembayaran_piutang_id'],
				'pembayaran_piutang_detail_penjualan_id' => $data['pembayaran_piutang_detail_penjualan_id'][$key],
				'pembayaran_piutang_detail_jatuh_tempo'	 => $data['pembayaran_piutang_detail_jatuh_tempo'][$key],
				'pembayaran_piutang_detail_tagihan' 	 => $data['pembayaran_piutang_detail_tagihan'][$key],
				'pembayaran_piutang_detail_retur' 		 => $data['pembayaran_piutang_detail_retur'][$key],
				'pembayaran_piutang_detail_potongan' 	 => $data['pembayaran_piutang_detail_potongan'][$key],
				'pembayaran_piutang_detail_sisa' 		 => $data['pembayaran_piutang_detail_sisa'][$key],
				'pembayaran_piutang_detail_bayar' 		 => $data['pembayaran_piutang_detail_bayar'][$key],
			];
			$det_opr_beli = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()), $detail_beli);
			if (!$det_opr_beli['success']) $res['res'][] = $det_opr_beli;
		}

		$operation = $this->pembayaran->update($data['pembayaran_piutang_id'], $data);
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->pembayarandetail->select(array('filters_static' => $data)));
	}

	public function get_detail_pembayaran()
	{
		$data = varPost();
		$this->response($this->multipayment->select(array('filters_static' => $data)));
	}


	public function destroy()
	{
		$data = varPost();
		$detail = $this->pembayarandetail->select(array('filters_static' => array('pembayaran_piutang_detail_parent' => $data['id'])))['data'];
		$operation = $this->pembayaran->update(varPost('id'), array('pembayaran_piutang_aktif' => 0));
		foreach ($detail as $key => $value) {
			$this->update_penjualan($value['pembayaran_piutang_detail_penjualan_id']);
		}
		$jurnal = $this->jurnal->read(['jurnal_umum_reference_id' => varPost('id')]);
		if ($jurnal['jurnal_umum_reference_id']) {
			$jurnal['jurnal_umum_reference'] = 'delete';
			$jurnal['jurnal_umum_status'] = 'deactive';
			$this->jurnal->edit_jurnal([], [], $jurnal);
		}
		$this->response($operation);
	}

	public function update_penjualan($id)
	{
		$update = $this->db->query('UPDATE pos_penjualan LEFT JOIN (SELECT ifnull(SUM(pembayaran_piutang_detail_bayar),0) bayar_jumlah, 
			ifnull(SUM(pembayaran_piutang_detail_retur),0) retur_jumlah, ifnull(SUM(pembayaran_piutang_detail_potongan),0) potongan_jumlah, 
			pembayaran_piutang_detail_penjualan_id FROM pos_pembayaran_piutang_detail 
			LEFT JOIN pos_pembayaran_piutang on pembayaran_piutang_id = pembayaran_piutang_detail_parent 
			WHERE pembayaran_piutang_aktif = "1" and pembayaran_piutang_detail_penjualan_id ="' . $id . '" GROUP BY pembayaran_piutang_detail_penjualan_id) as bayar 
			on pembayaran_piutang_detail_penjualan_id = penjualan_id set penjualan_total_bayar = ifnull(bayar_jumlah, 0),
			WHERE penjualan_id ="' . $id . '"');
		return $update;
	}

	public function loaddetail()
	{
		$data = varPost();
		$no = 1;
		$join =
			$detail = $this->pembayarandetail->select(array('filters_static' => array(
				'pembayaran_piutang_detail_parent' => $data['pembayaran_piutang_detail_parent']
			)));
		$html = '<table cellspacing="0" cellpadding="2" style="width:90%">
			<thead>
				<tr>
					<td>No</td>
					<td>No Beli</td>
					<td>Tgl Beli</td>
					<td>Total</td>
					<td>Retur</td>
					<td>Potongan</td>
					<td>Bayar</td>
				</tr>
			</thead>
			';
		$html .= '<tbody>';
		foreach ($detail['data'] as $key => $value) {
			$html .= '<tr>
						<td>' . $no++ . '</td>
						<td>' . $value['penjualan_kode'] . '</td>
						<td>' . $value['penjualan_tanggal'] . '</td>
						<td>' . $value['pembayaran_piutang_detail_tagihan'] . '</td>
						<td>' . $value['pembayaran_piutang_detail_retur'] . '</td>
						<td>' . $value['pembayaran_piutang_detail_potongan'] . '</td>
						<td>' . $value['pembayaran_piutang_detail_bayar'] . '</td>
						</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		echo json_encode(array(
			'success' 	=> true,
			'html' 		=> $html
		));
	}

	public function cetak($value = '')
	{
		if ($value) {
			$user = $this->session->userdata();
			$data = $this->db->where('pembayaran_piutang_id', $value)
				->get('v_pos_pembayaran_piutang')
				->row_array();
			$detail = $this->db->where('pembayaran_piutang_detail_parent', $value)
				->get('v_pos_pembayaran_piutang_detail')
				->result_array();
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
						<p>' . $user['toko_nama'] . '</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI PEMBAYARAN FAKTUR penjualan </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['pembayaran_piutang_tanggal'] ? date("d/m/Y", strtotime($data['pembayaran_piutang_tanggal']))  : "-") . '</td>
					<td>No Bukti : ' . ($data['pembayaran_piutang_kode'] ? $data['pembayaran_piutang_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td class="">Supplier : ' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
					<td class="">Akun : ' . ($data['akun_nama'] ? $data['akun_kode'] . ' - ' . $data['akun_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Alamat: ' . ($data['supplier_alamat'] ? $data['supplier_alamat'] : "-") . '</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">No Faktur</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Retur</th>
					<th class="t-center">Bayar</th>
					<th class="t-center">Sisa</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			foreach ($detail as $key => $value) {
				$percentase = 5;
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['penjualan_kode'] ? $value['penjualan_kode'] : "-") . '</td>
						<td>' . ($value['pembayaran_piutang_detail_tagihan'] ? number_format($value['pembayaran_piutang_detail_tagihan'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['pembayaran_piutang_detail_retur'] ? number_format($value['pembayaran_piutang_detail_retur'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['pembayaran_piutang_detail_bayar'] ? number_format($value['pembayaran_piutang_detail_bayar'], 2, ',', '.')  : "-") . '</td>
						<td>' . (number_format(($value['pembayaran_piutang_detail_tagihan'] - $value['pembayaran_piutang_detail_retur'] - $value['pembayaran_piutang_detail_bayar']), 2, ',', '.')) . '</td>
					</tr>';
				$totalJml += $value['pembayaran_piutang_detail_bayar'];
				// $totalQty += $value['pembayaran_piutang_detail_qty'];
			}


			$html .= '<tr>
					<td colspan="4" class="total">Total</td>
					<td class="total">' . number_format($totalJml, 2, ',', '.') . '</td>
					<td></td>
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
					<td class="bottom">NURS</td>
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
				'paper_size'    => 'A4-L',
				'file_name'     => 'FAKTUR MUTASI BARANG',
				'title'         => 'FAKTUR MUTASI BARANG',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}
	public function print_nota($value = '')
	{
		$user = $this->session->userdata();
		$data = $this->db->where('pembayaran_piutang_id', $value)
			->get('v_pos_pembayaran_piutang')
			->row_array();
		$detail = $this->db->where('pembayaran_piutang_detail_parent', $value)
			->get('v_pos_pembayaran_piutang_detail')
			->result_array();
		// $jurnal = $this->db->select('akun_kode, akun_nama, jurnal_umum_nobukti, jurnal_umum_detail_debit, jurnal_umum_detail_kredit')
		// 	->where('jurnal_umum_reference_id', $value)
		// 	->order_by('jurnal_umum_detail_no', 'ASC')
		// 	->get('v_ak_jurnal_umum_detail_laporan')
		// 	->result_array();
		$jurnal = [];
		$hari = date('D', strtotime($data['pembayaran_piutang_tanggal']));
		$namahari = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		$tgl = phpChgDate(date('Y-m-d', strtotime($data['pembayaran_piutang_tanggal'])));
		$harini = date('d F Y');
		$huruf = $this->terbilang($data['pembayaran_piutang_bayar']);

		$header = '
			<div style="width:89.3%; border:1px solid #000;padding:5px;margin-right:50px;margin-left:17px">
				<table cellpadding="0" cellspacing="0" align="left"  border="0" class="" style="display:inline-table;border:1px solid black;width:9.4cm!important" rotate="-90.0deg">
					    <tr>
					      <td style="text-align:center; line-height:14px;padding:4px;" >
					        <p style="font-size:15px;font-weight:bold;">
					        ' . $user['toko_nama'] . '
					        </p>
					      </td>
					    </tr>
				</table>
			</div>

			<div style="margin-left:96px; margin-top:-362px;">
			  <table cellspacing="0" style="width:91%;border:1px solid black; line-height:16px">
			  	<tr>
			  		<td style="width:25%;padding:3px;font-size:11px;border-right:1px solid black">No. : ' . $data['pembayaran_piutang_kode'] . ' </td>
			  		<td style="padding:3px;font-size:11px;border-right:1px solid black;text-align:center"><b>BUKTI KAS KELUAR</b></td>
			  	</tr>
			  </table>
			  <table cellspacing="0" style="width:91%" cellpadding="4">
			  	<tr>
			  		<td style="padding-top:11px;width:20%;font-size:11px;border-left:1px solid black;">Dibayarkan kepada</td>
			  		<td style="padding-top:11px;width:3%;font-size:11px;">:</td>
			  		<td style="padding-top:11px;width:67%;font-size:11px;" colspan="2">' . $data['supplier_nama'] . '</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Banyaknya Uang</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:67%;font-size:11px;" colspan="2">' . $huruf . ' Rupiah</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Untuk Pembayaran</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:67%;font-size:11px;" colspan="2"> Pembayaran barang dagangan No. Faktur :</td></tr>';
		foreach ($detail as $key => $value) {
			// if($key==0){
			// 	$header .= $value['pembayaran_piutang_detail_penjualan_kode'].', <i style="float:right">JT.'.date('d/m/Y',strtotime($value['pembayaran_piutang_detail_jatuh_tempo'])).'</i></td>';				  		
			// }else{
			// }
			// <td style="border-left:1px solid black;" colspan="2"></td>
			$header .= '<tr>
	  					<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;">' . $value['penjualan_kode'] . ' (<i style="float:right;font-size:11px;">JT.' . date('d/m/Y', strtotime($value['pembayaran_piutang_detail_jatuh_tempo'])) . '</i>) &nbsp; : ' . number_format($value['pembayaran_piutang_detail_tagihan']) . '</td>
		  				<td style="font-size:11px; width:21%">' . ($value['pembayaran_piutang_detail_potongan'] ? '( Potongan ) : ' . number_format($value['pembayaran_piutang_detail_potongan']) : '') . '</td> 
		  				<td style="font-size:11px;">' . ($value['pembayaran_piutang_detail_retur'] ? '( Retur ) : ' . number_format($value['pembayaran_piutang_detail_retur']) : '') . '</td>
		
		  				</tr>';
			// if($value['pembayaran_piutang_detail_potongan'] || $value['pembayaran_piutang_detail_retur']){
			// 	$pot = $value['pembayaran_piutang_detail_potongan']+$value['pembayaran_piutang_detail_retur'];
			// 	$header .= '<tr>
			// 		<td style="border-left:1px solid black;" colspan="2"></td>
			// 		<td style="font-size:11px;text-align:right">Potongan</td>
			// 		<td style="font-size:11px;">: ('.number_format($pot).')</td>
			// 	</tr>';	
			// }

		}
		if ($data['akun_parent'] == '1112') {
			$header .= '<tr>
		  				<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;border-right:">Transfer ' . $data['akun_nama'] . ', No. Ref.' . ($data['pembayaran_piutang_referensi'] ? $data['pembayaran_piutang_referensi'] : '-') . '</td>
		  				<td style="font-size:11px;"></td>
		  			</tr>';
		}
		$header .= '<tr>
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;">' . $data['pembayaran_piutang_keterangan'] . '</td>
	  			</tr>';
		$header .= '<tr >
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;"></td>
	  			</tr>';


		$header .= '</table>
			  <table cellspacing="0" style="width:92%" cellpadding="4">
			  <tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Terbilang</td>
			  		<td style="width:5%;font-size:11px;">:</td>
			  		<td style="width:18%;font-size:11px;border:1px solid black;" colspan="2"> Rp. ' . number_format($data['pembayaran_piutang_bayar'], 0, '', '.') . '</td>
			  		<td style="width:47%;font-size:11px;"></td>
			  	</tr>
			  </table>';
		$shadow = '<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				</table>';
		$footer = '		
				<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Malang, </td>
				  		<td colspan="2" style="width:30%;font-size:11px;border-top:1px solid black;text-align:left">' . $tgl . '</td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Analis</td>
				  		<td style="width:10%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Rek.</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Debet</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Kredit</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Mengetahui,</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Dibayar</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Diterima</td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[0]['akun_kode']) ? $jurnal[0]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[0]['jurnal_umum_detail_debit']) ? number_format($jurnal[0]['jurnal_umum_detail_debit']) : '<span style="color:#fff"></span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[0]['jurnal_umum_detail_kredit']) ? number_format($jurnal[0]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center">Kasir</td>
				  		<td style="width:15%;font-size:11px;text-align:center">oleh</td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[1]['akun_kode']) ? $jurnal[1]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[1]['jurnal_umum_detail_debit']) ? number_format($jurnal[1]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[1]['jurnal_umum_detail_kredit']) ? number_format($jurnal[1]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[2]['akun_kode']) ? $jurnal[2]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[2]['jurnal_umum_detail_debit']) ? number_format($jurnal[2]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[2]['jurnal_umum_detail_kredit']) ? number_format($jurnal[2]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[3]['akun_kode']) ? $jurnal[3]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[3]['jurnal_umum_detail_debit']) ? number_format($jurnal[3]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[3]['jurnal_umum_detail_kredit']) ? number_format($jurnal[3]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-bottom:1px solid black;border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;text-align:center"></td>
				  	</tr>
				  </table>
				</div>
			';
		createPdf(array(
			'data'          => $header . $shadow . $footer,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Kwitansi',
			'title'         => 'Kwitansi',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '5 5 0 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10'
		));
	}
	public function print_tanda_terima($value = '')
	{
		$data = varPost();
		$pembayaran = $this->db->where('pembayaran_piutang_id', $data['pembayaran_piutang_id'])
			->get('v_pos_pembayaran_piutang')
			->row_array();
		// print_r($pembayaran);exit;
		$detail = $this->db->where('pembayaran_piutang_detail_parent', $data['pembayaran_piutang_id'])
			->get('v_pos_pembayaran_piutang_detail')
			->result_array();
		$html = '
		<style>
			*{
				font-size:10px;
			}
			h3{
				font-size:15px
			}
			h1{
				font-size:18px; 
			}

			
		</style>
		<div style="border:1px solid #000; padding: 5px">
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:10%"><img src="' . base_url('assets/base_image/eka_border.png') . '" alt="Logo" width="100"></td>
					<td colspan="4" style="vertical-align:top;line-height:20px; padding:6px 4px; width:88%"><h3>KPRI EKO KAPTI</h3><p>Kantor Kementerian Agama Kab Malang</p><p>Jl. Kolonel Sugiono No. 39 Gadang-Malang, Telp.834 894</p></td>
				</tr>
				<tr>
					<td colspan="5" style="text-align:center;"></td>
				</tr>
				<hr style="margin-bottom:0;" border="2"><hr style="margin-top:2px">
				<tr>
					<td colspan="5" style="text-align:center;"><h1>TANDA TERIMA</h1></td>
				</tr>
				<tr>
					<td colspan="4" style="width:80%!important"></td>
					<td style="width:20%">Tangal. ' . date('d/m/Y', strtotime($pembayaran['pembayaran_piutang_tanggal_invoice'])) . '</td>
				</tr>
			</table>
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:20%!important" >Telah diterima dari </td>
					<td style="width:2%">:</td>
					<td style="width:75%!important" colspan="2">' . $pembayaran['supplier_nama'] . '</td>
				</tr>
				<tr>
					<td >Berupa </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2">Invoice No. ' . $pembayaran['pembayaran_piutang_invoice'] . '</td>
				</tr>
				<tr>
					<td >Keperluan </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2"> Pembayaran Faktur No. </td>';
		foreach ($detail as $key => $value) {
			if ($key > 0) {
				$html .= '<tr><td colspan="3"></td>';
			}
			$html .= '<td>' . $value['penjualan_faktur'] . ', JT. ' . date('d/m/Y', strtotime($value['penjualan_jatuh_tempo'])) . ', Senilai Rp.' . number_format($value['pembayaran_piutang_detail_tagihan'], 0, ',', '.') . '</td></tr>';
		}
		$html .= '
				<tr>
					<td style="padding-bottom:50px">Keterangan </td>
					<td style="padding-bottom:50px">:</td>
					<td style="width:75%!important;padding-bottom:50px" colspan="2">' . $pembayaran['pembayaran_piutang_keterangan'] . '</td>
				</tr>
				<tr>
					<td style="text-align:center;padding-bottom:50px">Pengirim</td>
					<td colspan="2"></td>
					<td style="text-align:center;padding-bottom:50px">Penerima</td>
				</tr>
				<tr>
					<td style="text-align:center">' . ($pembayaran['pembayaran_piutang_sales'] ? $pembayaran['pembayaran_piutang_sales'] : '-') . '</td>
					<td colspan="2"></td>
					<td style="text-align:center">' . ($pembayaran['pegawai_nama'] ? $pembayaran['pegawai_nama'] : '-') . '</td>
				</tr>
			</table>
		</div>';
		// echo $html;exit;

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A5-L',
			'file_name'     => 'Tanda Terima',
			'title'         => 'Tanda Terima',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '5 5 0 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10'
		));
	}
}

/* End of file Pembayaran.php */
/* Location: ./application/modules/Pembayaran/controllers/Pembayaran.php */
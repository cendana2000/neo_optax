<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanpembelian extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksipembelian/TransaksipembelianModel' => 'transaksipembelian',
			'transaksipembelian/TransaksipembeliandetailModel' => 'transaksipembeliandetail',
			'supplier/SupplierModel' => 'supplier'
		));
	}

	public function header($txt, $hal)
	{
		return '
		<table>
			<tr>
				<td>' . $txt . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">TGL.</th>
				<th class="t-center">No. Fak</th>
				<th class="t-center">Item</th>
				<th class="t-center">JT.</th>
				<th class="t-center">PLG.</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">TUNAI</th>
				<th class="t-center">KREDIT</th>
			</tr>';
	}

	public function get_laporan()
	{
		$data = varPost();
		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.6;
				font-size:11px;
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
		$dtCaption = '';
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] : '');
			$filter = ['to_char(pembelian_tanggal, \'YYYY-MM\') BETWEEN \'' . $data['bulan'] . '\' AND \'' . $data['bulan_akhir'] . '\'' => null];
		} else {
			$filter = ['to_date(cast(pembelian_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$filter['pembelian_deleted_at'] = NULL;
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>---- ------- ----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN FAKTUR PEMBELIAN BARANG</h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">Tanggal</th>
				<th class="t-center">No. Faktur</th>
				<th class="t-center">Item Qty</th>
				<th class="t-center">Jatuh Tempo.</th>
				<th class="t-center">Supplier</th>
				<th class="t-center">Tunai</th>
				<th class="t-center">Kredit</th>
			</tr>';
		$pembelian = $this->db->select('pembelian_tanggal, pembelian_kode, pembelian_jumlah_item, pembelian_jatuh_tempo,  supplier_kode, supplier_nama, pembelian_bayar_opsi, pembelian_bayar_jumlah, pembelian_bayar_sisa, pembelian_bayar_grand_total')
			->from('v_pos_pembelian_barang')
			->where($filter)
			->order_by('pembelian_tanggal asc, pembelian_kode asc')
			->get()->result_array();

		// die(json_encode($pembelian));

		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		foreach ($pembelian as $key => $value) {
			if ($tgl == $value['pembelian_tanggal']) $tanggal = '';
			else $tgl = $tanggal = $value['pembelian_tanggal'];
			$item += intval($value['pembelian_jumlah_item']);
			$tunai += intval(($value['pembelian_bayar_opsi'] == 'T' ? $value['pembelian_bayar_grand_total'] : 0));
			$kredit += intval(($value['pembelian_bayar_opsi'] == 'K' ? $value['pembelian_bayar_grand_total'] : 0));
			$html .= '<tr>
					<td class="right">' . $this->content_date($tanggal) . '</td>
					<td>' . $value['pembelian_kode'] . '</td>
					<td>' . $value['pembelian_jumlah_item'] . '</td>
					<td>' . ($value['pembelian_jatuh_tempo'] ? date("d-m-Y", strtotime($value['pembelian_jatuh_tempo'])) : '-') . '</td>
					<td>' . $value['supplier_kode'] . '-' . $value['supplier_nama'] . '</td>
					<td class="right">' . number_format(($value['pembelian_bayar_opsi'] == 'T' ? $value['pembelian_bayar_grand_total'] : 0)) . '</td>
					<td class="right">' . number_format(($value['pembelian_bayar_opsi'] == 'K' ? $value['pembelian_bayar_grand_total'] : 0)) . '</td>
				</tr>';
			$no++;
			if ($hal == 1) $total = 60;
			else $total = 80;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
			}
		}
		$html .= '<tr>
				<td colspan="2">TOTAL</td>
				<td>' . number_format($item) . '</td>
				<td colspan="2"></td>
				<td class="right">' . number_format($tunai) . '</td>
				<td class="right">' . number_format($kredit) . '</td>
			</tr>';
		$html .= '</table>
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
			</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Faktur Pembelian Barang',
			'title'         => 'Laporan Faktur Pembelian Barang',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}
	public function konsinyasi()
	{
		$data = varPost();
		$bulan = explode('-', $data['bulan']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
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
		$dtCaption = '';
		$filter = ['pembelian_is_konsinyasi' => '1'];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter['DATE_FORMAT(pembelian_tanggal, "%Y-%m") ='] = $data['bulan'];
		} else {
			$filter['DATE_FORMAT(pembelian_tanggal, "%Y-%m-%d") ='] = $data['tanggal'];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$filter['pembelian_deleted_at'] = NULL;
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>KANTOR REMENAG KAB.MALANG</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN FAKTUR PEMBELIAN BARANG KONSINYASI </h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">TGL.</th>
				<th class="t-center">NO. FAK</th>
				<th class="t-center">ITEM</th>
				<th class="t-center">JT.</th>
				<th class="t-center">PLG.</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">TUNAI</th>
				<th class="t-center">KREDIT</th>
			</tr>';
		$pembelian = $this->db->select('pembelian_tanggal, pembelian_kode, pembelian_jumlah_item, pembelian_jatuh_tempo,  supplier_kode, supplier_nama, pembelian_bayar_opsi, pembelian_bayar_jumlah, pembelian_bayar_sisa')
			->from('v_pos_pembelian_barang')
			->where($filter)
			->order_by('pembelian_tanggal', 'asc')
			->get()->result_array();
		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		foreach ($pembelian as $key => $value) {
			if ($tgl == $value['pembelian_tanggal']) $tanggal = '';
			else $tgl = $tanggal = $value['pembelian_tanggal'];
			$item += intval($value['pembelian_jumlah_item']);
			$tunai += intval($value['pembelian_bayar_jumlah']);
			$kredit += intval($value['pembelian_bayar_sisa']);
			$html .= '<tr>
					<td>' . (($tanggal) ? date('d/m/Y', strtotime($tanggal)) : '') . '</td>
					<td>' . $value['pembelian_kode'] . '</td>
					<td>' . $value['pembelian_jumlah_item'] . '</td>
					<td>' . date("d-m-Y", strtotime($value['pembelian_jatuh_tempo'])) . '</td>
					<td>' . $value['supplier_kode'] . '</td>
					<td>' . $value['supplier_nama'] . '</td>
					<td>' . number_format($value['pembelian_bayar_jumlah']) . '</td>
					<td>' . number_format($value['pembelian_bayar_sisa']) . '</td>
				</tr>';
			$no++;
			if ($hal == 1) $total = 60;
			else $total = 80;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
			}
		}
		$html .= '<tr>
				<td colspan="2">TOTAL</td>
				<td>' . number_format($item) . '</td>
				<td colspan="3"></td>
				<td>' . number_format($tunai) . '</td>
				<td>' . number_format($kredit) . '</td>
			</tr>';
		$html .= '</table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Faktur Pembelian Barang Konsinyasi',
			'title'         => 'Laporan Faktur Pembelian Barang Konsinyasi',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	function content_date($tanggal)
	{
		if ($tanggal != '') {
			$x_tanggal = explode("-", $tanggal);
			$y = $x_tanggal[0];
			$m = $x_tanggal[1];
			$d = $x_tanggal[2];
			$date = $d . "-" . $m . "-" . $y;
			return $date;
		} else return $tanggal;
	}

	public function get_laporan_supplier()
	{
		$data = varPost();

		$supplier = $this->supplier->read(['supplier_id' => $data['supplier_id']]);
		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.6;
				font-size:11px;
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
		$dtCaption = '';
		$filter = [
			'pembelian_supplier_id' => $supplier['supplier_id'],
		];
		if (varPost('periode') == 'bulan') {
			// $dtCaption = 'Bulan : '.phpChgMonth(intval($bulan[1])).' '.$bulan[0];
			// $filter['DATE_FORMAT(pembelian_tanggal, "%Y-%m") = '] = $data['bulan'];

			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan_akhir[1])) . ' ' . $bulan_akhir[0] : '');
			$filter['to_char(pembelian_tanggal, \'YYYY-MM\') BETWEEN \'' . $data['bulan'] . '\' AND \'' . $data['bulan_akhir'] . '\''] = null;
		} else {
			$filter['to_date(cast(pembelian_tanggal as TEXT), \'YYYY-MM-DD\') = '] = $data['tanggal'];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$filter['pembelian_deleted_at'] = NULL;
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ------ ----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN FAKTUR PEMBELIAN BARANG</h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
			</tr>
			<tr>
				<td>Nama Supplier : ' . $supplier["supplier_nama"] . '</td>
			</tr>
			<tr>
				<td>Kode Supplier : ' . $supplier["supplier_kode"] . '</td>
			</tr>
			<tr>
				<td>Alamat Supplier : ' . $supplier["supplier_alamat"] . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No. Fak</th>
				<th class="t-center">TGL.</th>
				<th class="t-center">JT.</th>
				<th class="t-center">Jumlah.</th>
				<th class="t-center">Rtr+Pot</th>
				<th class="t-center">Bayar</th>
				<th class="t-center">Sisa</th>
				<th class="t-center">No Bayar</th>
				<th class="t-center">Tanggal Bayar</th>
			</tr>';
		$pembelian = $this->db
			->select('pembelian_tanggal,pembelian_bayar_opsi, pembelian_kode, pembelian_jatuh_tempo, pembelian_bayar_jumlah, pos_pembelian_barang.pembelian_bayar_sisa, pembelian_bayar_grand_total,pembayaran_detail_retur, string_agg(DISTINCT pembayaran_kode, \', \') AS pembayaran_kode, pembayaran_tanggal, pembayaran_detail_potongan')
			->from('pos_pembelian_barang')
			->join('pos_pembayaran_detail', 'pembayaran_detail_pembelian_id = pembelian_id', 'left')
			->join('pos_pembayaran', 'pembayaran_detail_parent = pembayaran_id  and pembayaran_aktif = \'1\' ', 'left')
			->where($filter)
			->group_by('pembelian_kode,pembelian_tanggal, pembelian_bayar_opsi, pembelian_jatuh_tempo, pembelian_bayar_jumlah, pembelian_bayar_sisa, pembelian_bayar_grand_total, pembayaran_detail_retur,pembayaran_tanggal, pembayaran_detail_potongan')
			->order_by('pembelian_tanggal asc, pembelian_kode asc')
			->get()
			->result_array();

		$jumlah = $retur = $utang  = 0;
		foreach ($pembelian as $row) {
			$jumlah += $row['pembelian_bayar_grand_total'];
			$nretur = ($row['pembayaran_detail_retur'] + $row['pembayaran_detail_potongan']);
			$retur += $nretur;
			$bayar = ($row["pembelian_bayar_opsi"] == 'T') ? $row["pembelian_bayar_grand_total"] : $row["pembelian_bayar_jumlah"];
			$sisa = ($row['pembelian_bayar_grand_total'] - $nretur - $bayar);
			// echo $sisa.'='.$row['pembelian_bayar_grand_total'].'-'.$nretur;exit;
			$utang += $sisa;
			$tbayar += $bayar;

			if (empty($row["pembelian_jatuh_tempo"])) {
				$row["pembelian_jatuh_tempo"] = '-';
			} else {
				$row["pembelian_jatuh_tempo"] = date('d-m-Y', strtotime($row["pembelian_jatuh_tempo"]));
			}

			if (empty($row["pembayaran_tanggal"])) {
				$row["pembayaran_tanggal"] = '-';
			} else {
				$row["pembayaran_tanggal"] = date('d-m-Y', strtotime($row["pembayaran_tanggal"]));
			}

			if (empty($row["pembayaran_kode"])) {
				$row["pembayaran_kode"] = '-';
			} else {
				$row["pembayaran_kode"] = $row["pembayaran_kode"];
			}
			// $jatuh_tempo = date('d-m-Y', strtotime($row["pembelian_jatuh_tempo"])) == '' ? date('d-m-Y', strtotime($row["pembelian_jatuh_tempo"])) : '-';
			$html .= '<tr>
					<td>' . $row["pembelian_kode"] . '</td>
					<td>' . date('d-m-Y', strtotime($row["pembelian_tanggal"])) . '</td>
					<td>' .	$row["pembelian_jatuh_tempo"] . '</td>
					<td style="text-align:right">' . number_format($row["pembelian_bayar_grand_total"], 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($nretur, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($bayar, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($row['pembelian_bayar_sisa'], 0, '', '.') . '</td>
					<td>' . $row["pembayaran_kode"] . '</td>
					<td>' . $row["pembayaran_tanggal"] . '</td>
				</tr>';
		}

		$html .= '<tr>
					<td class="left" colspan="3">Total</td>
					<td style="text-align:right">' . number_format($jumlah, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($retur, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($tbayar, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($utang, 0, '', '.') . '</td>
					<td></td>
					<td></td>
				</tr></table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Faktur Pembelian Barang',
			'title'         => 'Laporan Faktur Pembelian Barang',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			'font_size'     => '10',
		));
	}

	public function get_laporan_rekap()
	{
		$data = varPost();
		$supplier = $this->supplier->read(['supplier_id' => $data['supplier_id']]);
		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.6;
				font-size:11px;
				// font-family: Arial, Helvetica, sans-serif;
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
		$dtCaption = '';
		// 'pembelian_supplier_id' => $supplier['supplier_id'],
		$filter = [];
		if (varPost('periode') == 'bulan') {
			// $dtCaption = 'Bulan : '.phpChgMonth(intval($bulan[1])).' '.$bulan[0];
			// $filter['DATE_FORMAT(pembelian_tanggal, "%Y-%m") = '] = $data['bulan'];

			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan_akhir[1])) . ' ' . $bulan_akhir[0] : '');
			$filter['to_char(pembelian_tanggal, \'YYYY-MM\') BETWEEN \'' . $data['bulan'] . '\' AND \'' . $data['bulan_akhir'] . '\''] = null;
		} else {
			$filter['to_date(cast(pembelian_tanggal as TEXT), \'YYYY-MM-DD\') = '] = $data['tanggal'];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$filter['pembelian_deleted_at'] = NULL;
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>----- ------------- -----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN REKAP PEMBELIAN BARANG</h4><br>
				</td>
			</tr>
			
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No.</th>
				<th class="t-center">Kode</th>
				<th class="t-center">Supplier</th>
				<th class="t-center">Jumlah.</th>
				<th class="t-center">Rtr+Pot</th>
				<th class="t-center">Bayar</th>
				<th class="t-center">Sisa</th>
			</tr>';
		/*<th class="t-center">No Bayar</th>
				<th class="t-center">Tanggal Bayar</th>*/
		$pembelian = $this->db
			->select('supplier_kode, supplier_nama, sum(pembelian_bayar_jumlah) pembelian_bayar_jumlah, sum(pembelian_bayar_sisa) pembelian_bayar_sisa, sum(pembelian_bayar_grand_total) pembelian_bayar_grand_total, sum(case when(pembelian_bayar_opsi=\'T\') then pembelian_bayar_grand_total else 0 end) bayar_tunai, sum(pembayaran_detail_retur) pembayaran_detail_retur, sum(pembayaran_detail_potongan) pembayaran_detail_potongan')
			->from('pos_pembelian_barang')
			->join('pos_supplier', 'pembelian_supplier_id = supplier_id', 'left')
			->join('pos_pembayaran_detail', 'pembayaran_detail_pembelian_id = pembelian_id', 'left')
			->join('pos_pembayaran', 'pembayaran_detail_parent = pembayaran_id  and pembayaran_aktif = \'1\' ', 'left')
			->where($filter)
			->group_by('pembelian_supplier_id, pos_supplier.supplier_kode, pos_supplier.supplier_nama')
			->order_by('supplier_kode asc')
			->get()
			->result_array();
		$jumlah = $retur = $utang  = 0;
		$n = 1;
		foreach ($pembelian as $row) {
			$jumlah += $row['pembelian_bayar_grand_total'];
			$nretur = ($row['pembayaran_detail_retur'] + $row['pembayaran_detail_potongan']);
			$retur += $nretur;
			$bayar = $row["pembelian_bayar_jumlah"] + $row["bayar_tunai"];
			$sisa = ($row['pembelian_bayar_grand_total'] - $nretur - $bayar);
			// echo $sisa.'='.$row['pembelian_bayar_grand_total'].'-'.$nretur;exit;
			$utang += $sisa;
			$tbayar += $bayar;
			$html .= '<tr>
					<td>' . $n . '</td>
					<td>' . $row["supplier_kode"] . '</td>
					<td>' . $row["supplier_nama"] . '</td>
					<td style="text-align:right">' . number_format($row["pembelian_bayar_grand_total"], 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($nretur, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($bayar, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($sisa, 0, '', '.') . '</td>
				</tr>';
			$n++;
		}

		$html .= '<tr>
					<td class="left" colspan="3">Total</td>
					<td style="text-align:right">' . number_format($jumlah, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($retur, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($tbayar, 0, '', '.') . '</td>
					<td style="text-align:right">' . number_format($utang, 0, '', '.') . '</td>
				</tr></table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Rekap Pembelian Barang',
			'title'         => 'Rekap Pembelian Barang',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function spreadsheet_laporan()
	{
		$data = varPost();

		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] : '');
			$filter = ['to_char(pembelian_tanggal, \'YYYY-MM\') BETWEEN \'' . $data['bulan'] . '\' AND \'' . $data['bulan_akhir'] . '\'' => null];
		} else {
			$filter = ['to_date(cast(pembelian_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$ops = $this->db->select('pembelian_tanggal, pembelian_kode, pembelian_jumlah_item, pembelian_jatuh_tempo,  supplier_kode, supplier_nama, pembelian_bayar_opsi, pembelian_bayar_jumlah, pembelian_bayar_sisa, pembelian_bayar_grand_total')
			->from('v_pos_pembelian_barang')
			->where($filter)
			->order_by('pembelian_tanggal asc, pembelian_kode asc')
			->get()->result_array();

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
			$sheet->mergeCells('A1:H1');
			$sheet->setCellValue('A1', 'LAPORAN PEMBELIAN');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'H') as $columnID) {
				$sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

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
			$sheet->getStyle('A2:H2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'NO');
			$sheet->setCellValue('B2', 'TANGGAL');
			$sheet->setCellValue('C2', 'NO FAKTUR');
			$sheet->setCellValue('D2', 'ITEM QTY');
			$sheet->setCellValue('E2', 'JATUH TEMPO');
			$sheet->setCellValue('F2', 'SUPPLIER');
			$sheet->setCellValue('G2', 'TUNAI');
			$sheet->setCellValue('H2', 'KREDIT');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$no = 2;
			foreach ($ops as $key => $value) {

				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['pembelian_tanggal']);
				$sheet->setCellValue('C' . $no, $value['pembelian_kode']);
				$sheet->setCellValue('D' . $no, $value['pembelian_jumlah_item']);
				$sheet->setCellValue('E' . $no, $value['pembelian_jatuh_tempo']);
				$sheet->setCellValue('F' . $no, $value['supplier_kode']);
				$sheet->setCellValue('G' . $no,  number_format(($value['pembelian_bayar_opsi'] == 'T' ? $value['pembelian_bayar_grand_total'] : 0)));
				$sheet->setCellValue('H' . $no, number_format(($value['pembelian_bayar_opsi'] == 'K' ? $value['pembelian_bayar_grand_total'] : 0)));
			}
			$sheet->getStyle('A3:H' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanpembelian-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_pembelian/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_pembelian/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_pembelian/' . $filename;
			$writer->save($file);

			$this->response([
				'success' => true,
				'file' => $filename
			]);
		} catch (\Throwable $th) {
			print_r('<pre>');
			print_r($th);
			print_r('</pre>');
			exit;
			$this->response([
				'success' => false,
				'message' => $th
			]);
		}
	}
}

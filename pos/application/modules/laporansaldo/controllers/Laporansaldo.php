<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporansaldo extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksipenjualan/TransaksipenjualanModel' 		=> 'transaksipenjualan',
			'transaksipenjualan/TransaksipenjualandetailModel' 	=> 'transaksipenjualandetail',
			'postingsaldo/PostingsaldoModel' 					=> 'Postingsaldo',
			'postingsaldo/PostingsaldodetailModel' 				=> 'Postingsaldodetail',
			'kategori/kategoriModel' 				=> 'kategori',
		));
	}

	public function index()
	{
		$var = varPost();
		$where = ['posting_detail_bulan' => $var['posting_detail_bulan']];
		if ($var['posting_detail_kategori_id']) $where['posting_detail_kategori_id'] = $var['posting_detail_kategori_id'];
		$this->response($this->select_dt($var, 'Postingsaldodetail', 'table', false, $where));
	}

	public function sum_recursive($data, $parent = '#')
	{
		foreach ($data as $key => &$record) {
			$child_rec = $this->sum_recursive($data, $record['akun_id']);
		}
	}
	public function get_kelompok($value = '')
	{
		$kategori = $this->kategori->select(array(
			'filters_static' => array(
				'kategori_barang_parent <> \'#\'' => null,
				'kategori_barang_aktif' => '1',
			),
			'sort_static' => 'kategori_barang_kode asc'
		));
		$this->response($kategori);
	}

	public function get_laporan_detail()
	{
		$data = varPost();
		$where = ['posting_detail_bulan' => $data['bulan']];
		if ($data['posting_detail_kategori_id']) $where['posting_detail_kategori_id'] = $data['posting_detail_kategori_id'];
		$detail = $this->Postingsaldodetail->select(['filters_static' => $where, 'sort_static' => 'barang_kode asc']);
		$bln = explode('-', $data['bulan']);
		$bln[1] = intval($bln[1]);
		$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		$html = '<style>
			*, table, p, li{
				line-height:1.5;
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
			.noborder{
				border:none;
			}
		</style>
		<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> LAPORAN PERINCIAN SALDO</h4><br>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p> Bulan : ' . $bulan[$bln[1]] . ' ' . $bln[0] . '</p>
				</td>
			</tr>
		</table>
		<hr style="border-top:1px solid black">
		<br>
		<table class="laporan"  id="table-saldodetail">
			<thead>
				<tr>
					<th style="width:7%;">No.</th>
					<th style="width:11%">Kode</th>
					<th style="width:30%">Barang</th>
					<th style="width:8%;">Awal</th>
					<th style="width:8%;">Masuk</th>
					<th style="width:8%;">Keluar</th>
					<th style="width:8%;">Koreksi</th>
					<th style="width:10%;">Stok</th>
					<th style="width:15%;">HPP</th>
					<th style="width:15%;">Nilai</th>
				</tr>
			</thead>
			<tbody>';
		$awal = $masuk = $keluar = $opname = $stok = $nilai = 0;
		foreach ($detail['data'] as $key => $value) {
			$html .= '<tr>
				<td>' . ($key + 1) . '</td>
				<td>' . $value['barang_kode'] . '</td>
				<td>' . $value['barang_nama'] . '</td>
				<td style="text-align:right">' . $value['posting_detail_awal_stok'] . '</td>
				<td style="text-align:right">' . $value['saldo_masuk'] . '</td>
				<td style="text-align:right">' . $value['saldo_keluar'] . '</td>
				<td style="text-align:right">' . $value['posting_detail_opname_qty'] . '</td>
				<td style="text-align:right">' . $value['posting_detail_akhir_stok'] . '</td>
				<td style="text-align:right">' . number_format($value['posting_detail_hpp'], 0, ',', '.') . '</td>
				<td style="text-align:right">' . number_format($value['posting_detail_akhir_nilai'], 0, ',', '.') . '</td>
			</tr>';
			$awal += $value['posting_detail_awal_stok'];
			$masuk += $value['saldo_masuk'];
			$keluar += $value['saldo_keluar'];
			$opname += $value['posting_detail_opname_qty'];
			$stok += $value['posting_detail_akhir_stok'];
			// += $value['posting_detail_hpp'];
			$nilai += $value['posting_detail_akhir_nilai'];
		}
		if ($detail['total'] == 0) $html .= '<tr><td colspan="10">Tidak terdapat detail posting!.</td></tr>';
		else {

			$html .= '<tr>
				<td colspan="3">Total</td>
				<td style="text-align:right">' . $awal . '</td>
				<td style="text-align:right">' . $masuk . '</td>
				<td style="text-align:right">' . $keluar . '</td>
				<td style="text-align:right">' . $opname . '</td>
				<td style="text-align:right">' . $stok . '</td>
				<td></td>
				<td style="text-align:right">' . number_format($nilai, 0, ',', '.') . '</td>
			</tr>';
		}
		$html .= '</tbody></table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Rincian Saldo Saldo',
			'title'         => 'Laporan Rincian Saldo Saldo',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function get_laporan()
	{
		$data = varPost();
		if (isset($data['posting_id'])) {
			$posting = $this->Postingsaldo->read($data['posting_id']);
			$data['bulan'] = $posting['posting_bulan'];
		}
		$bulan = explode('-', $data['bulan']);
		// print_r($data);exit;
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.5;
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
			.noborder{
				border:none;
			}
		</style>';
		$dtCaption = '';
		$bln = explode('-', $data['bulan']);
		$bln[1] = intval($bln[1]);
		$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> LAPORAN NILAI SALDO</h4><br>
					<h5> ' . $bulan[$bln[1]] . ' ' . $bln[0] . ' </h5>
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
				<th class="t-center">No.</th>
				<th class="t-center">GRUP BARANG</th>
				<th class="t-center">ITEM</th>
				<th class="t-center">SISA</th>
				<th class="t-center">NILAI</th>
			</tr>';
		// $postingdetail = $this->Postingsaldodetail->read(['posting_detail_bulan' => $data['bulan']]);
		$posting = $this->Postingsaldo->read(['posting_bulan' => $data['bulan']]);
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND pos_kategori.wajibpajak_id=' . $this->db->escape($wp_id);
		}
		if (isset($posting['posting_bulan'])) {
			$kategori = $this->db->query('SELECT kategori_barang_kode, kategori_barang_nama, kategori_barang_parent, COUNT(posting_detail_kategori_id) AS item, SUM(posting_detail_akhir_stok) as stok, SUM(posting_detail_akhir_nilai) as nilai 
				FROM pos_kategori 
				LEFT JOIN pos_barang ON kategori_barang_id = case when(barang_kategori_parent != \'' . '#' . '\') then barang_kategori_parent else barang_kategori_barang end
				LEFT JOIN pos_posting_saldo_detail ON barang_id=posting_detail_barang_id AND posting_detail_bulan= \'' . $data['bulan'] . '\'
				WHERE kategori_barang_parent = \'' . '#' . '\' ' . $where . '
				GROUP BY kategori_barang_kode, kategori_barang_nama, pos_kategori.kategori_barang_parent
				ORDER BY kategori_barang_kode')->result_array();
		} else {
			$kategori = $this->db->query('SELECT kategori_barang_kode, kategori_barang_nama, kategori_barang_parent, COUNT(barang_kategori_barang) AS item, SUM(barang_stok) stok, SUM(barang_stok*barang_harga_pokok) as nilai 
			FROM pos_kategori 
				LEFT JOIN pos_barang ON kategori_barang_id = case when(barang_kategori_parent != \'' . '#' . '\') then barang_kategori_parent else barang_kategori_barang end
				WHERE kategori_barang_parent = \'' . '#' . '\' ' . $where . '
				GROUP BY kategori_barang_kode, kategori_barang_nama, pos_kategori.kategori_barang_parent
				ORDER BY kategori_barang_kode')->result_array();
		}
		$item = $stok = $nilai = 0;
		foreach ($kategori as $key => $value) {
			$html .= '<tr>
					<td>' . ($key + 1) . '</td>
					<td>' . $value['kategori_barang_nama'] . '</td>
					<td class="right">' . number_format($value['item']) . '</td>
					<td class="right">' . number_format($value['stok']) . '</td>
					<td class="right">' . number_format($value['nilai']) . '</td>
				</tr>';
			$n = $key + 1;
			$item += intval($value['item']);
			$stok += intval($value['stok']);
			$nilai += intval($value['nilai']);
		}
		if (count($kategori) == 0) {
			$html .= '<tr><td colspan="5" style="text-align:center">Belum ada record transaksi!</td></tr>';
		}
		$html .= '<tr>
				<td colspan="2">TOTAL</td>
				<td class="right">' . number_format($item) . '</td>
				<td class="right">' . number_format($stok) . '</td>
				<td class="right">' . number_format($nilai) . '</td>
			</tr>';
		// if($posting){
		// 	$html .= '<tr>
		// 			<td>'.($n+1).'</td>
		// 			<td>Persedian Photobox</td>
		// 			<td class="right"></td>
		// 			<td class="right"></td>
		// 			<td class="right">'.number_format($posting['posting_persediaan_photobox']).'</td>
		// 		</tr>';	
		// 	$html .= '<tr>
		// 			<td>'.($n+2).'</td>
		// 			<td>Persedian Photocopy</td>
		// 			<td class="right"></td>
		// 			<td class="right"></td>
		// 			<td class="right">'.number_format($posting['posting_persediaan_photocopy']).'</td>
		// 		</tr>';	
		// }
		$html .= '</table>
			<br>
			<br>
			<br>
			<table style="width:500px;" class="ttd">
				<tr>
					<td class="top">Dibuat :</td>
					<td class="top">Disetujui :</td>
					<td class="top">Diterima :</td>
				</tr>
				<tr>
					<td class="bottom">' . $data['pegawai_nama'] . '</td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table><div style="page-break-after: always"></div>';
		$html .= $this->rekap_harian($data);
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Nilai Saldo',
			'title'         => 'Laporan Nilai Saldo',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function rekap_harian($data)
	{
		$hal = 1;
		$bulan = date('n', strtotime($data['bulan'] . '-01'));
		$bulan_nama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

		$html = '
		<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> REKAP TRANSAKSI HARIAN</h4><br>
					<h5> ' . $bulan_nama[$bulan] . ' ' . date('Y', strtotime($data['bulan'] . '-01')) . ' </h5>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p> Bulan : ' . $bulan_nama[$bulan] . '</p>
				</td>
			</tr>
		</table>
		<hr style="border-top:1px solid black">
		<br>
		<table cellspacing=0 style="width:100%;border:none" >
		';

		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}
		// print_r('<pre>');print_r($data['bulan']);print_r('</pre>');exit;
		$penjualan = $this->db->query('SELECT 
			penjualan_tanggal, 
			COUNT(case when(penjualan_total_bayar_tunai>0) then penjualan_total_bayar_tunai else null end) total_tunai, 
			sum(penjualan_total_bayar_tunai) tunai, 
			sum(penjualan_total_potongan) potongan, 
			sum(penjualan_total_kembalian) kembalian, 
			COUNT(case when((penjualan_total_bayar_tunai<penjualan_total_grand)) then penjualan_total_bayar_tunai else null end) total_kredit, 
			sum(penjualan_total_kredit) kredit, penjualan_user_nama 
		FROM v_pos_penjualan 
		WHERE to_char(penjualan_tanggal, \'YYYY-MM\') = \'' . $data['bulan'] . '\'  ' . $where . '
		GROUP BY penjualan_tanggal, penjualan_user_nama')->row_array();
		// echo $this->db->last_query();
		// print_r($penjualan);exit;
		$no = 1;
		$tunai = $penjualan['tunai'] - $penjualan['kembalian'] + $penjualan['potongan'];
		$kredit = ($penjualan['kredit']);
		$html .= '<tr>
					<td class="noborder">Penjualan Tunai</td>
					<td class="noborder" style="width:5%">Rp.</td>
					<td class="noborder">' . number_format($tunai) . '</td>
					<td class="noborder" style="width:3%">(</td>
					<td class="noborder" style="width:15%">' . $penjualan['total_tunai'] . ' Nbk</td>
					<td class="noborder" style="width:3%">)</td>
				</tr><tr>
					<td class="noborder">Penjualan Kredit</td>
					<td class="noborder">Rp.</td>
					<td class="noborder">' . number_format($kredit) . '</td>
					<td class="noborder">(</td>
					<td class="noborder">' . $penjualan['total_kredit'] . ' Nbk</td>
					<td class="noborder">)</td>
				</tr><tr>
					<td class="noborder">Total Penjualan</td>
					<td class="noborder">Rp.</td>
					<td class="noborder">' . number_format(($tunai + $kredit)) . '</td>
					<td class="noborder">(</td>
					<td class="noborder">' . ($penjualan['total_tunai'] + $penjualan['total_kredit']) . ' Nbk</td>
					<td class="noborder">)</td>
				</tr>';

		$pembelian = $this->db->query('SELECT pembelian_tanggal, 
			COUNT(case when(pembelian_bayar_opsi=\'T\') then pembelian_bayar_grand_total else null end) total_tunai, 
			sum(case when(pembelian_bayar_opsi=\'T\') then pembelian_bayar_grand_total else null end) tunai, 
			COUNT(case when(pembelian_bayar_opsi=\'K\') then  pembelian_bayar_grand_total else null end) total_kredit, 
			sum(case when(pembelian_bayar_opsi=\'K\') then  pembelian_bayar_grand_total else null end) kredit 
		FROM pos_pembelian_barang 
		WHERE to_char(pembelian_tanggal, \'YYYY-MM\') = \'' . $data['bulan'] . '\' ' . $where . '
		GROUP BY pembelian_tanggal')->result_array();
		$nilai = $pembelian[0]['tunai'] + $pembelian[0]['kredit'];
		$nota = $pembelian[0]['total_tunai'] + $pembelian[0]['total_kredit'];

		$html .= '<tr>
			<td class="noborder">Pembelian Tunai</td>
			<td class="noborder">Rp.</td>
			<td class="noborder">' . (number_format($pembelian[0]['tunai'])) . '</td>
			<td class="noborder">(</td>
			<td class="noborder">' . (number_format($pembelian[0]['total_tunai'])) . ' Nbk</td>
			<td class="noborder">)</td>
		</tr><tr>
			<td class="noborder">Pembelian Kredit</td>
			<td class="noborder">Rp.</td>
			<td class="noborder">' . (number_format($pembelian[0]['kredit'])) . '</td>
			<td class="noborder">(</td>
			<td class="noborder">' . (number_format($pembelian[0]['total_kredit'])) . ' Nbk</td>
			<td class="noborder">)</td>
		</tr><tr>
			<td class="noborder">TOTAL Pembelian</td>
			<td class="noborder">Rp.</td>
			<td class="noborder">' . number_format($nilai) . '</td>
			<td class="noborder">(</td>
			<td class="noborder">' . number_format($nota) . ' Nbk </td>
			<td class="noborder">)</td>
		</tr>';
		$returpembelian = $this->db->query('SELECT 
			COUNT(retur_pembelian_total) total, 
			sum(retur_pembelian_total) nilai 
		FROM pos_retur_pembelian_barang 
		WHERE to_char(retur_pembelian_tanggal, \'YYYY-MM\') = \'' . $data['bulan'] . '\' ' . $where . ' ')->result_array();
		$html .= '<tr>
			<td class="noborder">Retur Pembelian</td>
			<td class="noborder">Rp.</td>
			<td class="noborder">' . (number_format($returpembelian[0]['nilai'])) . ')</td>
			<td class="noborder">(</td>
			<td class="noborder">' . (number_format($returpembelian[0]['total'])) . '</td>
			<td class="noborder">)</td>
		</tr>';
		$html .= '</table>';
		return $html;
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
		$bln = explode('-', $data['bulan']);
		$bln[1] = intval($bln[1]);
		$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> LAPORAN NILAI SALDO BARANG KONSINYASI</h4><br>
					<h5> ' . $bulan[$bln[1]] . ' ' . $bln[0] . ' </h5>
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
				<th class="t-center">No.</th>
				<th class="t-center">GRUP BARANG</th>
				<th class="t-center">ITEM</th>
				<th class="t-center">SISA</th>
				<th class="t-center">NILAI</th>
			</tr>';
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND pos_barang.wajibpajak_id=' . $this->db->escape($wp_id);
		}
		$kategori = $this->db->query('SELECT kategori_barang_kode, kategori_barang_nama, kategori_barang_parent, COUNT(barang_id) AS item, SUM(barang_stok) as stok, SUM(penjualan_detail_subtotal) as nilai FROM pos_kategori 
			LEFT JOIN pos_barang ON barang_kategori_barang = kategori_barang_id AND barang_is_konsinyasi = "1"
			LEFT JOIN pos_penjualan_detail ON penjualan_detail_barang_id = barang_id AND DATE_FORMAT(penjualan_detail_tanggal, "%Y-%m") = "' . $data['bulan'] . '" 
			WHERE kategori_barang_is_konsinyasi = "1" ' . $where . '
			GROUP BY kategori_barang_kode, kategori_barang_nama
			ORDER BY kategori_barang_kode')->result_array();
		if (count($kategori) == 0) {
			$html .= '<tr><td colspan="5" style="text-align:center">Belum ada record transaksi!</td></tr>';
		}
		foreach ($kategori as $key => $value) {
			$html .= '<tr>
					<td>' . ($key + 1) . '</td>
					<td>' . $value['kategori_barang_nama'] . '</td>
					<td class="right">' . number_format($value['item']) . '</td>
					<td class="right">' . number_format($value['stok']) . '</td>
					<td class="right">' . number_format($value['nilai']) . '</td>
				</tr>';
		}
		$html .= '<tr>
				<td colspan="2">TOTAL</td>
				<td class="right"> ' . number_format($item) . '</td>
				<td class="right">' . number_format($stok) . '</td>
				<td class="right">' . number_format($nilai) . '</td>
			</tr>';
		$html .= '</table><div style="page-break-after: always"></div>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Nilai Saldo Konsinyasi',
			'title'         => 'Laporan Nilai Saldo Konsinyasi',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function header_kredit($txt, $hal)
	{
		return '<table>
			<tr>
				<td>' . $txt . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No.</th>
				<th class="t-center">KTA</th>
				<th class="t-center">WLY</th>
				<th class="t-center">NAMA</th>
				<th class="t-center">NOTA.</th>
				<th class="t-center">VCR</th>
				<th class="t-center">KREDIT</th>
				<th class="t-center">BLN</th>
				<th class="t-center">TAGIH</th>
				<th class="t-center">CICILAN</th>
				<th class="t-center">JASA</th>
			</tr>';
	}

	public function get_daftar_kredit($rec, $dtCaption)
	{
		$hal = 1;
		$html = '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> DAFTAR PENJUALAN KREDIT (USP) </h4><br>
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
				<th class="t-center">No.</th>
				<th class="t-center">KTA</th>
				<th class="t-center">WLY</th>
				<th class="t-center">NAMA</th>
				<th class="t-center">NOTA.</th>
				<th class="t-center">VCR</th>
				<th class="t-center">KREDIT</th>
				<th class="t-center">BLN</th>
				<th class="t-center">TAGIH</th>
				<th class="t-center">CICILAN</th>
				<th class="t-center">JASA</th>
			</tr>';

		$item = $k = $kredit = 0;
		$no = $total = 1;
		foreach ($rec as $key => $value) {
			if ($value['penjualan_total_bayar_voucher'] || $value['penjualan_total_kredit']) {
				$item++;
				$voucher += intval($value['penjualan_total_bayar_voucher']);
				$kredit += intval($value['penjualan_total_kredit']);
				$html .= '<tr>
						<td>' . ($k++) . '</td>
						<td>' . $value['anggota_kode'] . '</td>
						<td>' . $value['grup_gaji_kode'] . '</td>
						<td>' . $value['anggota_nama'] . '</td>
						<td>' . $value['penjualan_kode'] . '</td>
						<td>' . number_format($value['penjualan_total_bayar_voucher']) . '</td>
						<td>' . number_format($value['penjualan_total_kredit']) . '</td>
						<td>' . $value['penjualan_total_cicilan_qty'] . '</td>
						<td>' . date('d/m/Y', strtotime($value['penjualan_jatuh_tempo'])) . '</td>
						<td>' . number_format($value['penjualan_total_cicilan']) . '</td>
						<td>' . $value['penjualan_total_jasa'] . '</td>
					</tr>';
				$no++;
				if ($hal == 1) $total = 60;
				else $total = 80;
				if ($no > $total) {
					$no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header_kredit($dtCaption, $hal);
				}
			}
		}
		if ($k == 0) $html .= '<tr><td colspan="11" style="text-align:center">Belum ada transaksi kredit!</td></tr>';
		$html .= '<tr>
				<td colspan="5">TOTAL</td>
				<td>' . number_format($voucher) . '</td>
				<td>' . number_format($kredit) . '</td>
				<td>=</td>
				<td>' . number_format($voucher + $kredit) . '</td>
				<td colspan="2"></td>
			</tr>';
		$html .= '</table>';
		return $html;
	}

	public function spreadsheet_laporan()
	{
		$data = varPost();

		$posting = $this->Postingsaldo->read(['posting_bulan' => $data['bulan']]);
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND pos_kategori.wajibpajak_id=' . $this->db->escape($wp_id);
		}
		if (isset($posting['posting_bulan'])) {
			$kategori = $this->db->query('SELECT kategori_barang_kode, kategori_barang_nama, kategori_barang_parent, COUNT(posting_detail_kategori_id) AS item, SUM(posting_detail_akhir_stok) as stok, SUM(posting_detail_akhir_nilai) as nilai 
				FROM pos_kategori 
				LEFT JOIN pos_barang ON kategori_barang_id = case when(barang_kategori_parent != \'' . '#' . '\') then barang_kategori_parent else barang_kategori_barang end
				LEFT JOIN pos_posting_saldo_detail ON barang_id=posting_detail_barang_id AND posting_detail_bulan= \'' . $data['bulan'] . '\'
				WHERE kategori_barang_parent = \'' . '#' . '\' ' . $where . '
				GROUP BY kategori_barang_kode, kategori_barang_nama, pos_kategori.kategori_barang_parent
				ORDER BY kategori_barang_kode')->result_array();
		} else {
			$kategori = $this->db->query('SELECT kategori_barang_kode, kategori_barang_nama, kategori_barang_parent, COUNT(barang_kategori_barang) AS item, SUM(barang_stok) stok, SUM(barang_stok*barang_harga_pokok) as nilai 
			FROM pos_kategori 
				LEFT JOIN pos_barang ON kategori_barang_id = case when(barang_kategori_parent != \'' . '#' . '\') then barang_kategori_parent else barang_kategori_barang end
				WHERE kategori_barang_parent = \'' . '#' . '\' ' . $where . '
				GROUP BY kategori_barang_kode, kategori_barang_nama, pos_kategori.kategori_barang_parent
				ORDER BY kategori_barang_kode')->result_array();
		}

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
			$sheet->mergeCells('A1:E1');
			$sheet->setCellValue('A1', 'LAPORAN NILAI SALDO');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'E') as $columnID) {
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
			$sheet->getStyle('A2:E2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'NO');
			$sheet->setCellValue('B2', 'GRUP BARANG');
			$sheet->setCellValue('C2', 'ITEM');
			$sheet->setCellValue('D2', 'SISA');
			$sheet->setCellValue('E2', 'NILAI');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$no = 2;
			$item = $stok = $nilai = 0;
			foreach ($kategori as $key => $value) {

				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['kategori_barang_nama']);
				$sheet->setCellValue('C' . $no, number_format($value['item']));
				$sheet->setCellValue('D' . $no, number_format($value['stok']));
				$sheet->setCellValue('E' . $no, number_format($value['nilai']));

				$item += intval($value['item']);
				$stok += intval($value['stok']);
				$nilai += intval($value['nilai']);
			}
			$no = $no + 1;
			$sheet->setCellValue('A' . $no, 'TOTAL')->mergeCells('A' . $no . ':' . 'B' . $no);
			$sheet->setCellValue('C' . $no, number_format($item));
			$sheet->setCellValue('D' . $no, number_format($stok));
			$sheet->setCellValue('E' . $no, number_format($nilai));
			$sheet->getStyle('A3:E' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporansaldo-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_saldo/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_saldo/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_saldo/' . $filename;
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

	public function spreadsheet_perincian_laporan()
	{
		$data = varPost();
		$where = ['posting_detail_bulan' => $data['bulan']];
		if ($data['posting_detail_kategori_id']) $where['posting_detail_kategori_id'] = $data['posting_detail_kategori_id'];
		$detail = $this->Postingsaldodetail->select(['filters_static' => $where, 'sort_static' => 'barang_kode asc']);
		$bln = explode('-', $data['bulan']);
		$bln[1] = intval($bln[1]);
		$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

		$awal = $masuk = $keluar = $opname = $stok = $nilai = 0;

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
			$sheet->setCellValue('A1', 'LAPORAN NILAI SALDO');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'J') as $columnID) {
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
			$sheet->getStyle('A2:J2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'NO');
			$sheet->setCellValue('B2', 'KODE');
			$sheet->setCellValue('C2', 'BARANG');
			$sheet->setCellValue('D2', 'AWAL');
			$sheet->setCellValue('E2', 'MASUK');
			$sheet->setCellValue('F2', 'KELUAR');
			$sheet->setCellValue('G2', 'KOREKSI');
			$sheet->setCellValue('H2', 'STOK');
			$sheet->setCellValue('I2', 'HPP');
			$sheet->setCellValue('J2', 'NILAI');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$no = 2;
			foreach ($detail['data'] as $key => $value) {

				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['barang_kode']);
				$sheet->setCellValue('C' . $no, $value['barang_nama']);
				$sheet->setCellValue('D' . $no, $value['saldo_masuk']);
				$sheet->setCellValue('F' . $no, $value['saldo_keluar']);
				$sheet->setCellValue('G' . $no, $value['posting_detail_opname_qty']);
				$sheet->setCellValue('H' . $no, $value['posting_detail_akhir_stok']);
				$sheet->setCellValue('I' . $no, number_format($value['posting_detail_hpp'], 0, ',', '.'));
				$sheet->setCellValue('J' . $no, number_format($value['posting_detail_akhir_nilai'], 0, ',', '.'));
				$awal += $value['posting_detail_awal_stok'];
				$masuk += $value['saldo_masuk'];
				$keluar += $value['saldo_keluar'];
				$opname += $value['posting_detail_opname_qty'];
				$stok += $value['posting_detail_akhir_stok'];
				// += $value['posting_detail_hpp'];
				$nilai += $value['posting_detail_akhir_nilai'];
			}
			if ($detail['total'] == 0) {
				$sheet->setCellValue('A' . $no, 'Tidak terdapat detail posting!')->mergeCells('A' . $no . ':' . 'J' . $no);
			} else {
				$sheet->setCellValue('D' . $no, $awal);
				$sheet->setCellValue('E' . $no, $masuk);
				$sheet->setCellValue('F' . $no, $keluar);
				$sheet->setCellValue('G' . $no, $opname);
				$sheet->setCellValue('H' . $no, $stok);
				$sheet->setCellValue('I' . $no, '');
				$sheet->setCellValue('J' . $no, number_format($nilai, 0, ',', '.'));
			}

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanperinciansaldo-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_saldo/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_saldo/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_saldo/' . $filename;
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanvarian extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksipenjualan/TransaksipenjualanModel' => 'transaksipenjualan',
			'transaksipenjualan/TransaksipenjualandetailModel' => 'transaksipenjualandetail',
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
				<th class="t-center">No.</th>
				<th class="t-center">Invoice</th>
				<th class="t-center">Item</th>
				<th class="t-center">Jam</th>
				<th class="t-center">Opt.</th>
				<th class="t-center">TUNAI</th>
				<th class="t-center">KREDIT</th>
				<th class="t-center">PLG.</th>
				<th class="t-center">JT/Dis.</th>
			</tr>';
	}

	public function get_laporan()
	{
		$data = varPost();

		// $data = [
		// 	"periode"=>"tanggal",
		// 	"tanggal"=>"2022-12-29",
		// 	"bulan"=>"2022-12",
		// 	"nota_awal"=>"",
		// 	"nota_akhir"=>"",
		// ];

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
			.laporan tfoot td{
				font-weight:bold;
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
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['to_char(penjualan_tanggal, \'YYYY-MM\') =' => $data['bulan']];
		} else {
			$filter = ['to_date(cast(penjualan_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;

			if (!empty($data['nota_awal'])) {
				if (!empty($data['nota_akhir'])) {
					$filter['penjualan_kode BETWEEN \'' . $data['nota_awal'] . '\' AND \'' . $data['nota_akhir'] . '\''] = null;
				} else {
					$filter['penjualan_kode'] = $data['nota_awal'];
				}
			}
		}
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- --- ---- ---</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN DATA PRODUK </h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>';
		$penjualan = $this->db->select('penjualan_id,
			penjualan_total_item,
			penjualan_total_bayar_tunai,
			penjualan_total_kembalian,
			penjualan_total_potongan,
			penjualan_created,
			penjualan_kode,
			pp.barang_nama,
			ppd.penjualan_detail_qty,
			penjualan_total_harga,
			penjualan_jasa,
			penjualan_total_potongan_persen,
			penjualan_pajak_persen,
			penjualan_total_grand,
			to_jsonb(array_agg(to_jsonb(ppd.*))) as detail_penjualan,
			count(ppd.*) as detail_penjualan_count')
			->from('v_pos_penjualan pp')
			->where('penjualan_total_bayar >= (penjualan_total_grand - coalesce (penjualan_total_retur, 0))', null)
			->where($filter)
			->where('penjualan_status_aktif', NULL)
			->join('v_pos_penjualan_detail ppd', 'penjualan_id = ppd.penjualan_detail_parent', 'left')
			->order_by('penjualan_created', 'asc')
			// ->order_by('anggota_kode', 'asc')
			->group_by('penjualan_id, 
			penjualan_created, 
			penjualan_total_item,
			penjualan_total_bayar_tunai,
			penjualan_total_kembalian,
			penjualan_total_potongan,
			penjualan_created,
			penjualan_kode,
			pp.barang_nama,
			ppd.penjualan_detail_qty,
			penjualan_total_harga,
			penjualan_jasa,
			penjualan_total_potongan_persen,
			penjualan_pajak_persen,
			penjualan_total_grand
			')
			->get()->result_array();


		$varian_penjualan = [];
		foreach ($penjualan as $key => $val) {
			// print_r('<pre>');print_r(json_decode($val['detail_penjualan']));print_r('</pre>');
			// print_r('<pre>');print_r($val);print_r('</pre>');exit;
			$detpenjualan = $this->transaksipenjualandetail->select([
				'filters_static' => [
					'penjualan_detail_parent' => $val['penjualan_id'],
				]
			]);
			$detbarang = [];
			foreach ($detpenjualan['data'] as $dkey => $dval) {
				array_push($detbarang, $dval['barang_nama']);
			}
			$opr[$key]['barang_nama'] = implode(', ', $detbarang);
			// print_r('<pre>');print_r();print_r('</pre>');exit;

			$detailpenjualan = json_decode($val['detail_penjualan']);
			foreach ($detailpenjualan as $key_dp => $val_dp) {
				$datadetail_penjualan = $val_dp;
				$datadetail_penjualan->penjualan_kode = $val['penjualan_kode'];
				$datadetail_penjualan->penjualan_created = $val['penjualan_created'];
				$datadetail_penjualan->penjualan_jasa = $val['penjualan_jasa'];
				$datadetail_penjualan->penjualan_total_potongan_persen = $val['penjualan_total_potongan_persen'];
				$datadetail_penjualan->penjualan_pajak_persen = $val['penjualan_pajak_persen'];
				$varian_penjualan[$val_dp->penjualan_detail_satuan_kode][$val['penjualan_kode']][] = $datadetail_penjualan;
			}
		}

		if (count($penjualan) == 0) {
			$html .= '<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr><td colspan="8" style="text-align:center">Belum ada record transaksi!</td></tr></table>';
		}

		$abj = 'a';
		foreach ($varian_penjualan as $k_varian => $v_varian) {
			$html .= '<h2>' . $abj . '. ' . $k_varian . '</h2>';
			$html .= '<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">NO</th>
				<th class="t-center">Waktu</th>
				<th class="t-center">No Invoice</th>
				<th class="t-center">Qty</th>
				<th class="t-center">Produk</th>
				<!-- <th class="t-center">Satuan</th> -->
				<th class="t-center">Sub Total</th>
				<th class="t-center">Service Charge</th>
				<!-- <th class="t-center">Discount</th> -->
				<th class="t-center">Tax</th>
				<th class="t-center">Total</th>
			</tr>';
			$no = 1;
			$subtotal = $total_jasa = $diskon = $pajak = $grand_total = 0;
			foreach ($v_varian as $k_parent => $v_parent) {
				$rowspan = count($v_parent) + 1;
				$html .= '<tr>
					<td rowspan="' . $rowspan . '">' . $no . '</td>
					<td rowspan="' . $rowspan . '">' . date_format(new DateTime($v_parent[0]->penjualan_created), 'd-m-Y H:i:s') . '</td>
					<td rowspan="' . $rowspan . '">' . $v_parent[0]->penjualan_kode . '</td>
				</tr>';
				foreach ($v_parent as $k_detail => $v_detail) {
					$detailsubtotal = $v_detail->penjualan_detail_subtotal;
					$detailjasa = $detailsubtotal * $v_detail->penjualan_jasa / 100;
					$detaildiskon = $detailsubtotal * $v_detail->penjualan_total_potongan_persen / 100;
					$detailpajak = $detailsubtotal * $v_detail->penjualan_pajak_persen / 100;
					$detailgrand = ($detailsubtotal + $detailjasa + $detailpajak) - $detailjasa;
					$html .= '<tr>
						<td>' . $v_detail->penjualan_detail_qty . '</td>
						<td>' . $v_detail->barang_nama . '</td>
						<!-- <td>' . $v_detail->penjualan_detail_satuan_kode . '</td> -->
						<td class="right">Rp. ' . number_format($detailsubtotal) . '</td>
						<td class="right">Rp. ' . number_format($detailjasa) . '</td>
						<!-- <td class="right">Rp. ' . number_format($detaildiskon) . '</td> -->
						<td class="right">Rp. ' . number_format($detailpajak) . '</td>
						<td class="right">Rp. ' . number_format($detailgrand) . '</td>
					</tr>';
					$subtotal += $detailsubtotal;
					$total_jasa += $detailjasa;
					$diskon += $detaildiskon;
					$pajak += $detailpajak;
					$grand_total += $detailgrand;
				}
				$no++;
			}
			$html .= '<tfoot><tr>
				<td colspan="4" class="t-block">TOTAL</td>
				<td class="right t-block">Rp. ' . number_format($subtotal) . '</td>
				<td class="right t-block">Rp. ' . number_format($total_jasa) . '</td>
				<td class="right t-block">Rp. ' . number_format($diskon) . '</td>
				<td class="right t-block">Rp. ' . number_format($pajak) . '</td>
				<td class="right t-block">Rp. ' . number_format($grand_total) . '</td>
			</tr></tfoot>';
			$html .= '</table>';
			$abj++;
		}

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Data Produk',
			'title'         => 'Laporan Data Produk',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10',
		));
	}

	public function get_laporan_rekap()
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
				line-height:18px;
			}
			.laporan tfoot td{
				font-weight:bold;
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
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['to_char(penjualan_tanggal, \'YYYY-MM\') =' => $data['bulan']];
		} else {
			$filter = ['to_date(cast(penjualan_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> REKAP NOTA PENJUALAN </h4><br>
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
				<th class="t-center" rowspan="2">TGL.</th>
				<th class="t-center" colspan="2">TUNAI</th>
				<th class="t-center" colspan="2">KREDIT</th>
				<th class="t-center" rowspan="2">TOTAL</th>
				<!-- <th class="t-center" rowspan="2">HPP</th> -->
			</tr>
			<tr>
				<th>Jm. Invoice</th>
				<th>JUMLAH</th>
				<th>Jm. Invoice</th>
				<th>JUMLAH</th>
			</tr>';
		// $penjualan = $this->db->query('select penjualan_tanggal, COUNT(IF(penjualan_total_bayar>0,penjualan_total_bayar, null)) total_tunai, sum(penjualan_total_bayar) tunai, COUNT(IF(penjualan_total_kredit>0, penjualan_total_kredit, null)) total_kredit, sum(penjualan_total_kredit) kredit FROM pos_penjualan WHERE DATE_FORMAT(penjualan_tanggal, "%Y-%m") = "'.$data['bulan'].'" GROUP BY penjualan_tanggal')->result_array();
		$penjualan = $this->db->query('SELECT 
				penjualan_tanggal, 
				COUNT(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) total_tunai, 
				sum(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) tunai, 
				sum(penjualan_total_potongan) potongan, 
				sum(penjualan_total_kembalian) kembalian, 
				COUNT(case when(penjualan_metode = \'K\') then penjualan_total_bayar else null end) total_kredit, 
				sum(penjualan_total_kredit) kredit, 
				hpp 
			FROM pos_penjualan 
			LEFT JOIN (
					SELECT 
						SUM(penjualan_detail_hpp) hpp, 
						penjualan_detail_tanggal 
					FROM pos_penjualan_detail 
					GROUP BY penjualan_detail_tanggal
				) as hpp 
				on penjualan_detail_tanggal=penjualan_tanggal 
			WHERE penjualan_status_aktif IS NULL 
			AND to_char(penjualan_tanggal, \'YYYY-MM\') = \'' . $data['bulan'] . '\' 
			GROUP BY penjualan_tanggal, hpp')->result_array();

		$total = $nota_tunai = $nota_kredit = $total_tunai = $total_kredit = $hpp = 0;
		if (count($penjualan) == 0) {
			$html .= '<tr><td colspan="6" style="text-align:center">Belum ada record transaksi!</td></tr>';
		}
		foreach ($penjualan as $key => $value) {
			$tunai = $value['tunai'] - $value['kembalian']  + $value['potongan'];
			$kredit = ($value['kredit'] + $value['titipan_belanja']);
			$item += intval($value['penjualan_total_item']);
			$rtotal = $tunai + $kredit;
			$total += $rtotal;
			$nota_tunai += $value['total_tunai'];
			$total_tunai += $tunai;
			$nota_kredit += $value['total_kredit'];
			$total_kredit += $kredit;
			$hpp += $value['hpp'];
			$html .= '<tr>
					<td>' . date_format(new DateTime($value['penjualan_tanggal']), 'd-m-Y') . '</td>
					<td style="text-align:right;padding-right:15px">' . $value['total_tunai'] . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($tunai) . '</td>
					<td style="text-align:right;padding-right:15px">' . $value['total_kredit'] . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($kredit) . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($rtotal) . '</td>
					<!-- <td style="text-align:right;padding-right:15px">' . number_format($value['hpp']) . '</td> -->
				</tr>';
		}
		$html .= '<tfoot><tr>
				<td>TOTAL</td>
				<td style="text-align:right;padding-right:15px">' . number_format($nota_tunai) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total_tunai) . '</td>
				<td style="text-align:right;padding-right:15px">' . number_format($nota_kredit) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total_kredit) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total) . '</td>
				<!-- <td style="text-align:right;padding-right:15px">' . number_format($hpp) . '</td> -->
			</tr></tfoot>';
		$html .= '</table>';
		// $html .= $this->get_daftar_kredit($penjualan, $dtCaption);
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Rekap Nota Penjualan',
			'title'         => 'Rekap Nota Penjualan',
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

	public function header_voucherBHR($txt, $hal)
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
				<th class="t-center">BHR</th>
			</tr>';
	}

	public function header_voucherLain($txt, $hal)
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
				<th class="t-center">GIVEAWAY</th>
			</tr>';
	}

	public function get_daftar_kredit($rec, $dtCaption)
	{
		$hal = 1;
		$html = '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
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
		usort($rec, function ($a, $b) {
			return $a['anggota_kode'] <=> $b['anggota_kode'];
		});
		foreach ($rec as $key => $value) {
			if ($value['penjualan_total_bayar_voucher'] || $value['penjualan_total_kredit']) {
				$item++;
				$voucher += intval($value['penjualan_total_bayar_voucher']);
				$kredit += intval($value['penjualan_total_kredit']);
				$html .= '<tr>
						<td>' . ($no) . '</td>
						<td>' . $value['anggota_kode'] . '</td>
						<td>' . $value['grup_gaji_kode'] . '</td>
						<td>' . $value['anggota_nama'] . '</td>
						<td>' . $value['penjualan_kode'] . '</td>
						<td>' . number_format($value['penjualan_total_bayar_voucher']) . '</td>
						<td>' . number_format($value['penjualan_total_kredit']) . '</td>
						<td>' . $value['penjualan_total_cicilan_qty'] . '</td>
						<td>' . ($value['penjualan_jatuh_tempo'] != "" ? date('d/m/Y', strtotime($value['penjualan_jatuh_tempo'])) : '') . '</td>
						<td>' . number_format($value['penjualan_total_cicilan']) . '</td>
						<td>' . number_format($value['penjualan_total_kredit'] * ($value['penjualan_total_jasa'] / 100)) . '</td>
					</tr>';
				$no++;
				if ($hal == 1) $total = 48;
				else $total = 80;
				if ($no > $total) {
					// $no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header_kredit($dtCaption, $hal);
				}
			}
		}
		if ($no == 1) $html .= '<tr><td colspan="11" style="text-align:center">Belum ada transaksi kredit!</td></tr>';
		$html .= '<tr>
				<td colspan="5">TOTAL</td>
				<td>' . number_format($voucher) . '</td>
				<td>' . number_format($kredit) . '</td>
				<td>=</td>
				<td>' . number_format($voucher + $kredit) . '</td>
				<td colspan="2"></td>
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
					<td class="bottom">' . $data['pegawai_nama'] . '</td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table>';
		return $html;
	}

	public function get_daftar_voucher($rec, $dtCaption)
	{
		$hal = 1;
		$html = '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> DAFTAR PENGGUNAAN VOUCHER BHR (USP) </h4><br>
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
				<th class="t-center">BHR</th>
			</tr>';

		$item = $k = $kredit = $voucher = 0;
		$no = $no_hal = $total = 1;
		/*print_r($rec);
		exit();*/
		foreach ($rec as $key => $value) {
			if ($value['penjualan_total_bayar_voucher_khusus']) {
				$item++;
				$voucher += intval($value['penjualan_total_bayar_voucher_khusus']);
				$html .= '<tr>
						<td>' . ($no_hal) . '</td>
						<td>' . $value['anggota_kode'] . '</td>
						<td>' . $value['grup_gaji_kode'] . '</td>
						<td>' . $value['anggota_nama'] . '</td>
						<td>' . $value['penjualan_kode'] . '</td>
						<td>' . number_format($value['penjualan_total_bayar_voucher_khusus']) . '</td>
					</tr>';
				$no++;
				$no_hal++;
				if ($hal == 1) $total = 48;
				else $total = 80;
				if ($no > $total) {
					$no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header_voucherBHR($dtCaption, $hal);
				}
			}
		}
		if ($no == 1) $html .= '<tr><td colspan="6" style="text-align:center">Belum ada transaksi voucher BHR!</td></tr>';
		$html .= '<tr>
				<td colspan="5">TOTAL</td>
				<td>' . number_format($voucher) . '</td>
			</tr>';
		$html .= '</table>';
		return $html;
	}

	public function get_daftar_voucher_lain($rec, $dtCaption)
	{
		$hal = 1;
		$html = '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> DAFTAR PENGGUNAAN VOUCHER GIVEAWAY (USP) </h4><br>
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
				<th class="t-center">VOUHER</th>
			</tr>';

		$item = $k = $kredit = $voucher = 0;
		$no = $no_hal = $total = 1;
		/*print_r($rec);
		exit();*/
		foreach ($rec as $key => $value) {
			if ($value['penjualan_total_bayar_voucher_lain']) {
				$item++;
				$voucher += intval($value['penjualan_total_bayar_voucher_lain']);
				$html .= '<tr>
						<td>' . ($no_hal) . '</td>
						<td>' . $value['anggota_kode'] . '</td>
						<td>' . $value['grup_gaji_kode'] . '</td>
						<td>' . $value['anggota_nama'] . '</td>
						<td>' . $value['penjualan_kode'] . '</td>
						<td>' . number_format($value['penjualan_total_bayar_voucher_lain']) . '</td>
					</tr>';
				$no++;
				$no_hal++;
				if ($hal == 1) $total = 48;
				else $total = 80;
				if ($no > $total) {
					$no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header_voucherLain($dtCaption, $hal);
				}
			}
		}
		if ($no == 1) $html .= '<tr><td colspan="6" style="text-align:center">Belum ada transaksi voucher Giveaway!</td></tr>';
		$html .= '<tr>
				<td colspan="5">TOTAL</td>
				<td>' . number_format($voucher) . '</td>
			</tr>';
		$html .= '</table>';
		return $html;
	}

	public function get_laporan_detail($data)
	{
		// $data = varPost();
		$bulan = explode('-', $data['bulan']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$tanggal_sampai = date('d/m/Y', strtotime(varPost('tanggal_sampai')));
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
				
			}

			.laporan td {
				padding:0px 10px;
			}

			.ttd{
				
				padding : 0px 3px;
				text-align:center;
				vertical-align:top;
			}

			.ttd td {
				
				padding:0px 3px;
				height:40px;
			}

			.ttd .top{
				text-align:center;
				vertical-align:top;
				
			}

			.ttd .bottom{
				text-align:center;
				vertical-align:bottom;
				
			}

			.laporan .total {
				
				padding: 0px 10px;
			}	

			table{
				
				width:100%;
			}
			.laporan th {
				padding-top:10px;
				padding-bottom:10px;
			}
		</style>';
		$dtCaption = '';
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['DATE_FORMAT(penjualan_tanggal, "%Y-%m") =' => $data['bulan']];
		} else {
			$filter = ['DATE_FORMAT(penjualan_tanggal, "%Y-%m-%d") = ' => $data['tanggal']];
			$dtCaption = 'Periode : ' . $tanggal;
			if (isset($data['tanggal_sampai']) && $data['tanggal_sampai'] >= $data['tanggal']) {
				$dtCaption .= ' Sampai ' . $tanggal_sampai;
				$filter = [
					'DATE_FORMAT(penjualan_tanggal, "%Y-%m-%d") >=' => $data['tanggal'],
					'DATE_FORMAT(penjualan_tanggal, "%Y-%m-%d") <=' => $data['tanggal_sampai']
				];
			}
		}
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN PENJUALAN BARANG </h4><br>
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
				<th class="t-center">NO. BUKTI</th>
				<th class="t-center">KODE</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">QTY</th>
				<th class="t-center">ST/ISI</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">Disc.</th>
				<th class="t-center">JML. HARGA</th>
				<th class="t-center">JML. HPP</th>
				<th class="t-center">PENDAPATAN</th>
			</tr>';
		// <th class="t-center">TGL.</th>
		$jual = $this->db->select('penjualan_id,penjualan_tanggal, penjualan_kode, penjualan_total_harga, anggota_kode, anggota_nama')
			->from('v_pos_penjualan')
			->where($filter)
			->order_by('penjualan_kode', 'asc')
			->get()->result_array();
		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		$color = '';
		foreach ($jual as $key => $value) {
			// if($tgl == $value['retur_pembelian_tanggal']) $tanggal = '';
			// else $tgl = $tanggal = $value['retur_pembelian_tanggal'];
			if ($key % 2 == 0) {
				$color = "";
			} else {
				$color = "background-color:#c6ccc8";
			}
			$detail =  $this->db->select('barang_kode, barang_nama, penjualan_detail_qty, penjualan_detail_satuan_kode, penjualan_detail_harga, penjualan_detail_qty, penjualan_detail_potongan,penjualan_detail_subtotal,penjualan_detail_hpp')
				->from('v_pos_penjualan_detail')
				->where('penjualan_detail_parent', $value['penjualan_id'])
				->order_by('penjualan_detail_order', 'asc')
				->get()->result_array();
			// <td>'.(($tanggal)?date('d/m/Y', strtotime($tanggal)):'').'</td>
			$html .= '<tr style="' . $color . '">
					<td>' . $value['penjualan_kode'] . '</td>';
			$tlaba = $hpp = 0;

			foreach ($detail as $k => $v) {
				if ($k > 0) {
					$html .= '<tr style="' . $color . '">
						<td></td>';
				}
				$laba = $v['penjualan_detail_subtotal'] - $v['penjualan_detail_hpp'];
				$hpp += $v['penjualan_detail_hpp'];
				$tlaba += $laba;
				// $satuan = $v['retur_pembelian_detail_satuan_kode']."(".$v['retur_pembelian_detail_satuan_kode'].")";
				$html .= '
							<td>' . $v['barang_kode'] . '</td>
							<td>' . $v['barang_nama'] . '</td>
							<td>' . $v['penjualan_detail_qty'] . '</td>
							<td>' . ($v['penjualan_detail_satuan_kode'] ? $v['penjualan_detail_satuan_kode'] : "") . '</td>
							<td>' . number_format($v['penjualan_detail_harga']) . '</td>
							<td>' . number_format($v['penjualan_detail_potongan']) . '</td>
							<td>' . number_format($v['penjualan_detail_subtotal']) . '</td>
							<td>' . number_format($v['penjualan_detail_hpp']) . '</td>
							<td>' . number_format($laba) . '</td>
						</tr>';
			}

			if (count($detail) > 1) {
				$html .= '<tr style="' . $color . '">
					<td></td>
					<td></td>
					<td colspan="5" style="border-top: 1px solid black;border-bottom: 1px solid black;">SubTotal</td>
					<td>' . number_format($value['penjualan_total_harga']) . '</td>
					<td>' . number_format($hpp) . '</td>
					<td>' . number_format($tlaba) . '</td>
					</tr>';
			}
			if ($value['anggota_kode']) {
				$html .= '<tr style="' . $color . '">
					<td></td>
					<td colspan="9" style="border-top: 1px solid black;border-bottom: 1px solid black;">' . $value['anggota_kode'] . ' - ' . $value['anggota_nama'] . '</td>
					</tr>';
			}
		}
		$html .= '</table>';
		return $html;
	}

	public function get_rekap_harian($data)
	{
		$hal = 1;
		$html = '
		<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . date("d/m/Y") . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> REKAP KASIR </h4><br>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p> Periode: ' . date("d/m/Y", strtotime($data['tanggal'])) . '</p>
				</td>
			</tr>
		</table>
		<hr style="border-top:1px solid black">
		<br>
		<table class="laporan" cellspacing=0 style="width:100%;">
			<thead>
				<tr>
					<th class="t-center">KETERANGAN</td>
					<th class="t-center">OPT.</td>
					<th class="t-center">NOTA</td>
					<th class="t-center">JAM</td>
					<th class="t-center">JUMLAH</td>
				</tr>
			</thead>
		';
		$penjualan = $this->db->query('select penjualan_tanggal, COUNT(IF(penjualan_total_bayar_tunai>0,penjualan_total_bayar_tunai, null)) total_tunai, sum(penjualan_total_bayar_tunai) tunai, sum(penjualan_total_potongan) potongan, sum(penjualan_total_kembalian) kembalian, COUNT(IF((penjualan_total_bayar_tunai<penjualan_total_grand),penjualan_total_bayar_tunai, null)) total_kredit, sum(penjualan_total_kredit) kredit, penjualan_user_nama, FROM v_pos_penjualan WHERE penjualan_tanggal= "' . $data['tanggal'] . '" GROUP BY penjualan_tanggal, penjualan_user_id')->result_array();

		$retur = $this->db->query('select retur_penjualan_tanggal, sum(retur_penjualan_titipan_belanja_nilai) titipan_belanja, sum(retur_penjualan_tunai) tunai, sum(retur_penjualan_kredit) kredit FROM pos_retur_penjualan WHERE retur_penjualan_tanggal= "' . $data['tanggal'] . '" GROUP BY retur_penjualan_tanggal')->row_array();
		$no = 1;
		// kredit
		$total_kredit = $total_tunai = 0;
		foreach ($penjualan as $key => $value) {
			if ($value['total_kredit'] > 0) {
				$kredit = ($value['kredit'] + $value['titipan_belanja']);
				$total_kredit += $kredit;
				if ($no == 1) {
					$html .= '<tr><td class="noborder">Penjualan Kredit</td>';
				} else {
					$html .= '<tr><td class="noborder"></td>';
				}
				$html .= '<td class="noborder">' . $value['penjualan_user_nama'] . '</td>
						<td class="noborder">' . (number_format($value['total_kredit'])) . '</td>
						<td class="noborder"></td>
						<td class="noborder">' . (number_format($kredit)) . '</td>						
					</tr>';
			}
		}
		$retur_kredit = $retur['titipan_belanja'] + $retur['kredit'];
		$html .= '<tr><td class="noborder">Retur Penjualan Kredit</td>';
		$html .= '<td class="noborder"></td>
				<td class="noborder"></td>
				<td class="noborder"></td>
				<td class="noborder">' . (number_format($retur_kredit)) . '</td>						
			</tr>';
		$total_kredit -= $retur_kredit;
		$html .= '<tr><td class="noborder t-block" colspan="4">Total Penjualan Kredit</td><td class="noborder t-block">' . number_format($total_kredit) . '</td>';
		// tunai
		foreach ($penjualan as $key => $value) {
			if ($value['total_tunai'] > 0) {
				$tunai = $value['tunai'] - $value['kembalian'] + $value['voucher'] + $value['potongan'] + $value['lain'];
				$total_tunai += $tunai;
				if ($no == 1) {
					$html .= '<tr><td class="noborder">Penjualan Tunai</td>';
				} else {
					$html .= '<tr><td class="noborder"></td>';
				}
				$html .= '<td class="noborder">' . $value['penjualan_user_nama'] . '</td>
						<td class="noborder">' . (number_format($value['total_tunai'])) . '</td>
						<td class="noborder"></td>
						<td class="noborder">' . (number_format($tunai)) . '</td>						
					</tr>';
			}
		}
		$html .= '<tr><td class="noborder">Retur Penjualan Tunai</td>';
		$html .= '<td class="noborder"></td>
				<td class="noborder"></td>
				<td class="noborder"></td>
				<td class="noborder">' . (number_format($retur['tunai'])) . '</td>						
			</tr>';
		$total_tunai -= $retur['tunai'];
		$html .= '<tr><td class="noborder t-block" colspan="4">Total Penjualan Tunai</td><td class="noborder t-block">' . number_format($total_tunai) . '</td></tr>';
		$html .= '<tr><td class="noborder t-block" colspan="4">TOTAL PENJUALAN</td><td class="noborder t-block">' . number_format(($total_tunai + $total_kredit)) . '</td></tr>';

		$html .= '</table>
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
			</table>';
		return $html;
	}

	public function select()
	{
		$data = varPost();
		$nota = [];
		$nota['success'] = true;
		$nota['data'] = $this->db
			->select('penjualan_kode')
			->order_by('penjualan_kode', 'ASC')
			->get_where('pos_penjualan', ['penjualan_tanggal' => $data['tanggal']])
			->result_array();
		$this->response($nota);
	}

	public function spreadsheet_laporan()
	{
		$data = varPost();

		$bulan = explode('-', $data['bulan']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['to_char(penjualan_tanggal, \'YYYY-MM\') =' => $data['bulan']];
		} else {
			$filter = ['to_date(cast(penjualan_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;

			if (!empty($data['nota_awal'])) {
				if (!empty($data['nota_akhir'])) {
					$filter['penjualan_kode BETWEEN \'' . $data['nota_awal'] . '\' AND \'' . $data['nota_akhir'] . '\''] = null;
				} else {
					$filter['penjualan_kode'] = $data['nota_awal'];
				}
			}
		}
		$ops = $this->db->select('*')
			->from('v_pos_penjualan')
			->where($filter)
			->where('penjualan_status_aktif', NULL)
			->order_by('penjualan_created', 'asc')
			// ->order_by('anggota_kode', 'asc')
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
			$sheet->mergeCells('A1:I1');
			$sheet->setCellValue('A1', 'LAPORAN PENJUALAAN');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'I') as $columnID) {
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
			$sheet->getStyle('A2:I2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'NO');
			$sheet->setCellValue('B2', 'TIME');
			$sheet->setCellValue('C2', 'PRODUK');
			$sheet->setCellValue('D2', 'INVOICE');
			$sheet->setCellValue('E2', 'TOTAL');
			$sheet->setCellValue('F2', 'CHARGE');
			$sheet->setCellValue('G2', 'DISCOUNT');
			$sheet->setCellValue('H2', 'TAX');
			$sheet->setCellValue('I2', 'TOTAL');


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
				$sheet->setCellValue('B' . $no, date_format(new DateTime($value['penjualan_created']), 'd-m-Y H:i:s'));
				$sheet->setCellValue('C' . $no, $value['barang_nama']);
				$sheet->setCellValue('D' . $no, $value['penjualan_kode']);
				$sheet->setCellValue('E' . $no, 'Rp. ' . number_format($value['penjualan_total_harga']) . '');
				$sheet->setCellValue('F' . $no, 'Rp. ' . number_format($value['penjualan_total_harga'] * $value['penjualan_jasa'] / 100) . ' (' . number_format($value['penjualan_jasa']) . '%)');
				$sheet->setCellValue('G' . $no, 'Rp. ' . number_format($value['penjualan_total_harga'] * $value['penjualan_total_potongan_persen'] / 100) . ' (' . number_format($value['penjualan_total_potongan_persen']) . '%)');
				$sheet->setCellValue('H' . $no, 'Rp. ' . number_format($value['penjualan_total_harga'] * $value['penjualan_pajak_persen'] / 100) . ' (' . number_format($value['penjualan_pajak_persen']) . '%)');
				$sheet->setCellValue('I' . $no, 'Rp. ' . number_format($value['penjualan_total_grand']) . '');
			}
			$sheet->getStyle('A3:I' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanpenjualan-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_penjualan/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_penjualan/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_penjualan/' . $filename;
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

	public function spreadsheet_laporan_rekap()
	{
		$data = varPost();

		$bulan = explode('-', $data['bulan']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$dtCaption = '';
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['to_char(penjualan_tanggal, \'YYYY-MM\') =' => $data['bulan']];
		} else {
			$filter = ['to_date(cast(penjualan_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}
		$ops = $this->db->query('SELECT 
				penjualan_tanggal, 
				COUNT(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) total_tunai, 
				sum(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) tunai, 
				sum(penjualan_total_potongan) potongan, 
				sum(penjualan_total_kembalian) kembalian, 
				COUNT(case when(penjualan_metode = \'K\') then penjualan_total_bayar else null end) total_kredit, 
				sum(penjualan_total_kredit) kredit, 
				hpp 
			FROM pos_penjualan 
			LEFT JOIN (
					SELECT 
						SUM(penjualan_detail_hpp) hpp, 
						penjualan_detail_tanggal 
					FROM pos_penjualan_detail 
					GROUP BY penjualan_detail_tanggal
				) as hpp 
				on penjualan_detail_tanggal=penjualan_tanggal 
			WHERE penjualan_status_aktif IS NULL 
			AND to_char(penjualan_tanggal, \'YYYY-MM\') = \'' . $data['bulan'] . '\' 
			GROUP BY penjualan_tanggal, hpp')->result_array();

		$total = $nota_tunai = $nota_kredit = $total_tunai = $total_kredit = $hpp = 0;


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
			$sheet->mergeCells('A1:F1');
			$sheet->setCellValue('A1', 'REKAP NOTA PENJUALAN');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'F') as $columnID) {
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
					'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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

			$sheet->getStyle('A2:F2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'TGL')->mergeCells('A2:A3');
			$sheet->setCellValue('B2', 'TUNAI')->mergeCells('B2:C2');
			$sheet->setCellValue('B3', 'JM. INVOICE');
			$sheet->setCellValue('C3', 'JUMLAH');
			$sheet->setCellValue('D2', 'KREDIT')->mergeCells('D2:E2');
			$sheet->setCellValue('D3', 'JM. INVOICE');
			$sheet->setCellValue('E3', 'JUMLAH');
			$sheet->setCellValue('F2', 'TOTAL')->mergeCells('F2:F3');


			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$no = 3;
			foreach ($ops as $key => $value) {

				$no += 1;
				$tunai = $value['tunai'] - $value['kembalian']  + $value['potongan'];
				$kredit = ($value['kredit'] + $value['titipan_belanja']);
				$item += intval($value['penjualan_total_item']);
				$rtotal = $tunai + $kredit;
				$total += $rtotal;
				$nota_tunai += $value['total_tunai'];
				$total_tunai += $tunai;
				$nota_kredit += $value['total_kredit'];
				$total_kredit += $kredit;
				$hpp += $value['hpp'];

				$sheet->setCellValue('A' . $no, date_format(new DateTime($value['penjualan_tanggal']), 'd-m-Y'));
				$sheet->setCellValue('B' . $no, $value['total_tunai']);
				$sheet->setCellValue('C' . $no, number_format($tunai));
				$sheet->setCellValue('D' . $no, $value['total_kredit']);
				$sheet->setCellValue('E' . $no, number_format($kredit));
				$sheet->setCellValue('F' . $no, number_format($rtotal));
			}

			$no += 1;
			$sheet->setCellValue('A' . $no, 'TOTAL');
			$sheet->setCellValue('B' . $no, number_format($nota_tunai));
			$sheet->setCellValue('C' . $no, number_format($total_tunai));
			$sheet->setCellValue('D' . $no, number_format($nota_kredit));
			$sheet->setCellValue('E' . $no, number_format($total_kredit));
			$sheet->setCellValue('F' . $no, number_format($total));

			$sheet->getStyle('A3:F' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanpenjualan-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_penjualan/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_penjualan/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_penjualan/' . $filename;
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

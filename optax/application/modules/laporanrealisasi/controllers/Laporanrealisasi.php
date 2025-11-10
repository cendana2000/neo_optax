<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanrealisasi extends Base_Controller
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
		$filter = '';
		if (varPost('periode') == 'bulan') {
			$filter_bulan_awal = $data['bulan'];
			$filter_bulan_akhir = $data['bulan_akhir'];
			// $dtCaption = 'Bulan : ' . $data['bulan']  . ' - ' . $data['bulan_akhir'];
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan_akhir[1])) . ' ' . $bulan_akhir[0] : '');
			$filter = "WHERE to_char(realisasi_tanggal, 'YYYY-MM') BETWEEN '$filter_bulan_awal' AND '$filter_bulan_akhir'";
		} else {
			$tanggal = $data['tanggal'];
			// $filter = "WHERE to_date(cast(realisasi_tanggal as TEXT), 'YYYY-MM-DD') = '" + $data['tanggal'] + "'";
			$filter = "WHERE to_date(cast(realisasi_tanggal as TEXT), 'YYYY-MM-DD') = '$tanggal'";
			$dtCaption = 'Tanggal : ' . $data['tanggal'];
		}

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>BAPENDA KOTA MALANG</p>
					<p><u>---- ------- ----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN REALISASI PAJAK</h4><br>
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
				<th class="t-center">NPWP</th>
				<th class="t-center">Omzet</th>
				<th class="t-center">Jasa</th>
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
		$filter_jenis_pajak = ($data['jenis_pajak'] !== '') ? 'AND jenis_id = \'' . $data['jenis_pajak'] . '\'' : '';
		$realisasipajak = $this->db->query("SELECT pajak_realisasi.* 
		FROM pajak_realisasi 
		INNER JOIN pajak_wajibpajak ON pajak_realisasi.realisasi_wajibpajak_npwpd = pajak_wajibpajak.wajibpajak_npwpd 
		INNER JOIN pajak_jenis ON pajak_wajibpajak.wajibpajak_sektor_nama = pajak_jenis.jenis_id 
		$filter $filter_jenis_pajak")->result_array();


		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		$totalOmzet = 0;
		$totalJasa = 0;
		$totalPajak = 0;
		$totalAll = 0;

		foreach ($realisasipajak as $key => $value) {
			$totalOmzet += $value['realisasi_sub_total'];
			$totalJasa += $value['realisasi_jasa'];
			$totalPajak += $value['realisasi_pajak'];
			$cTotal = $value['realisasi_sub_total'] + $value['realisasi_jasa'] + $value['realisasi_pajak'];
			$totalAll += $cTotal;

			if ($tgl == $value['realisasi_tanggal']) $tanggal = '';
			else $tgl = $tanggal = $value['realisasi_tanggal'];
			$html .= '<tr>
					<td>' . $this->content_date($tanggal) . '</td>
					<td>' . $value['realisasi_wajibpajak_npwpd'] . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_sub_total']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_jasa']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_pajak']) . '</td>
					<td style="text-align: right;">' . number_format($cTotal) . '</td>
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
				<td style="text-align: right;">' . $totalOmzet . '</td>
				<td style="text-align: right;">' . $totalJasa . ' </td>
				<td style="text-align: right;">' . $totalPajak . ' </td>
				<td style="text-align: right;">' . $totalAll . '</td>
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
			'file_name'     => 'Laporan Realisasi Pajak',
			'title'         => 'Laporan Realisasi Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function get_laporan_rekap()
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
		$filter = '';
		if (varPost('periode') == 'bulan') {
			$filter_bulan_awal = $data['bulan'];
			$filter_bulan_akhir = $data['bulan_akhir'];
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan_akhir[1])) . ' ' . $bulan_akhir[0] : '');
			$filter = "AND to_char(pr.realisasi_tanggal, 'YYYY-MM') BETWEEN '$filter_bulan_awal' AND '$filter_bulan_akhir'";
		} else {
			$tanggal = $data['tanggal'];
			$filter = "AND to_date(cast(pr.realisasi_tanggal as TEXT), 'YYYY-MM-DD') = '$tanggal'";
			$dtCaption = 'Tanggal : ' . $data['tanggal'];
		}

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>BAPENDA KOTA MALANG</p>
					<p><u>---- ------- ----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN MONITORING PAJAK</h4><br>
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
				<th class="t-center">Jenis Pajak</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
		if (!empty($data['jenis_pajak'])) {
			$existrealisasi = $this->db->query(
				'SELECT * 
				from pajak_jenis 
				where jenis_id = \'' . $data['jenis_pajak'] . '\''
			)->row_array();
			if (!empty($existrealisasi)) {
				if ($existrealisasi['jenis_tipe'] == 'parent') {
					$filterparent = 'AND pj.jenis_id = \'' . $data['jenis_pajak'] . '\'';
					$filterdetail = '';
				} else if ($existrealisasi['jenis_tipe'] == 'detail') {
					$filterparent = 'AND pj.jenis_id = \'' . $existrealisasi['jenis_parent'] . '\'';
					$filterdetail = 'AND pj.jenis_id = \'' . $data['jenis_pajak'] . '\'';
				}
			}
		}
		$ops = $this->db->query(
			'SELECT * 
			from pajak_jenis pj 
			where jenis_tipe = \'parent\' 
			' . $filterparent . ''
		)->result_array();
		$no = 1;
		$totalrealisasi = [
			'realisasi_sub_total' => 0,
			'realisasi_pajak' => 0,
			'realisasi_total' => 0
		];
		foreach ($ops as $key => $val) {
			$html .= '<tr>
				<td>' . $no . '</td>
				<td>' . $val['jenis_nama'] . '</td>
				<td style="text-align: right;"></td>
				<td style="text-align: right;"></td>
				<td style="text-align: right;"></td>
			</tr>';
			$opschild = $this->db->query('SELECT 
				pj.jenis_id,
				pj.jenis_kode, 
				pj.jenis_nama,
				sum(pr.realisasi_sub_total) as realisasi_sub_total,
				sum(pr.realisasi_pajak) as realisasi_pajak,
				sum(pr.realisasi_total) as realisasi_total
			from pajak_jenis pj 
			left join pajak_wajibpajak pw on pj.jenis_id = pw.wajibpajak_sektor_nama 
			left join pajak_realisasi pr on pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd ' . $filter . '
			where jenis_parent = \'' . $val['jenis_id'] . '\'
			' . $filterdetail . '
			group by pj.jenis_id')->result_array();
			foreach ($opschild as $ckey => $cval) {
				$html .= '<tr>
					<td></td>
					<td>- ' . $cval['jenis_nama'] . '</td>
					<td style="text-align: right;">' . number_format($cval['realisasi_sub_total']) . '</td>
					<td style="text-align: right;">' . number_format($cval['realisasi_pajak']) . '</td>
					<td style="text-align: right;">' . number_format($cval['realisasi_total']) . '</td>
				</td>';
				$totalrealisasi['realisasi_sub_total'] += $cval['realisasi_sub_total'];
				$totalrealisasi['realisasi_pajak'] += $cval['realisasi_pajak'];
				$totalrealisasi['realisasi_total'] += $cval['realisasi_total'];
			}
			$no += 1;
		}
		$html .= '<tr>
			<td colspan="2" style="text-align: center;"><b>JUMLAH</b></td>
			<td style="text-align: right;"><b>' . number_format($totalrealisasi['realisasi_sub_total']) . '</b></td>
			<td style="text-align: right;"><b>' . number_format($totalrealisasi['realisasi_pajak']) . '</b></td>
			<td style="text-align: right;"><b>' . number_format($totalrealisasi['realisasi_total']) . '</b></td>
		</tr>';

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Realisasi Pajak',
			'title'         => 'Laporan Realisasi Pajak',
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
	// rincian
	public function spreadsheet_laporan()
	{
		$data = varPost();

		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$filter = '';
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] : '');
			$filter = ["to_char(realisasi_tanggal, 'YYYY-MM') BETWEEN '" . $data['bulan'] . "' AND '" . $data['bulan_akhir'] . "'" => null];
		} else {
			//$filter = "WHERE to_date(cast(realisasi_tanggal as TEXT), 'YYYY-MM-DD') = '" + $data['tanggal'] + "'";
			$filter = ['to_date(cast(realisasi_tanggal as TEXT), \'YYYY-MM-DD\') =' => $data['tanggal']];
			$dtCaption = 'Tanggal : ' . $tanggal;
		}

		$filter_jenis_pajak = ($data['jenis_pajak'] !== '') ? 'AND jenis_id = \'' . $data['jenis_pajak'] . '\'' : '';
		$realisasipajak = $this->db->select('pajak_realisasi.*')
			->from('pajak_realisasi')
			->join('pajak_wajibpajak', 'pajak_realisasi.realisasi_wajibpajak_npwpd = pajak_wajibpajak.wajibpajak_npwpd')
			->join('pajak_jenis', 'pajak_wajibpajak.wajibpajak_sektor_nama = pajak_jenis.jenis_id')
			->where($filter, $filter_jenis_pajak)
			->order_by('realisasi_tanggal', 'asc')
			->get()
			->result_array();



		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		$totalOmzet = 0;
		$totalJasa = 0;
		$totalPajak = 0;
		$totalAll = 0;


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
			$sheet->mergeCells('A1:G1');
			$sheet->setCellValue('A1', 'LAPORAN REALISASI RINCIAN');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'G') as $columnID) {
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
			$sheet->getStyle('A2:G2')->applyFromArray($styleArray);
			$sheet->setCellValue('A2', 'NO');
			$sheet->setCellValue('B2', 'TANGGAL');
			$sheet->setCellValue('C2', 'NWPW');
			$sheet->setCellValue('D2', 'Omzet');
			$sheet->setCellValue('E2', 'Jasa');
			$sheet->setCellValue('F2', 'Pajak');
			$sheet->setCellValue('G2', 'Total');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];


			$no = 2;
			foreach ($realisasipajak as $key => $value) {

				$totalOmzet += $value['realisasi_sub_total'];
				$totalJasa += $value['realisasi_jasa'];
				$totalPajak += $value['realisasi_pajak'];
				$cTotal = $value['realisasi_sub_total'] + $value['realisasi_jasa'] + $value['realisasi_pajak'];
				$totalAll += $cTotal;

				if ($tgl == $value['realisasi_tanggal']) $tanggal = '';
				else $tgl = $tanggal = $value['realisasi_tanggal'];

				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $this->content_date($tanggal));
				$sheet->setCellValue('C' . $no, $value['realisasi_wajibpajak_npwpd']);
				$sheet->setCellValue('D' . $no, $value['realisasi_sub_total']);
				$sheet->setCellValue('E' . $no, $value['realisasi_jasa']);
				$sheet->setCellValue('F' . $no, $value['realisasi_pajak']);
				$sheet->setCellValue('G' . $no, $cTotal);
				// $sheet->setCellValue('G' . $no,  number_format(($value['realisasi_bayar_opsi'] == 'T' ? $value['realisasi_bayar_grand_total'] : 0)));
				// $sheet->setCellValue('H' . $no, number_format(($value['realisasi_bayar_opsi'] == 'K' ? $value['realisasi_bayar_grand_total'] : 0)));


			}
			$no = $no + 1;
			$sheet->setCellValue('A' . $no, 'TOTAL')->mergeCells('A' . $no . ':' . 'C' . $no);
			$sheet->setCellValue('D' . $no, $totalOmzet);
			$sheet->setCellValue('E' . $no, $totalJasa);
			$sheet->setCellValue('F' . $no, $totalPajak);
			$sheet->setCellValue('G' . $no, $totalAll);

			$sheet->getStyle('A2:G' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanrealisasirincian-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_realisasi/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_realisasi/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_realisasi/' . $filename;
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
	//rekap
	public function spreadsheet_rekap()
	{
		$data = varPost();
		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$filter = '';
		if (varPost('periode') == 'bulan') {
			$filter_bulan_awal = $data['bulan'];
			$filter_bulan_akhir = $data['bulan_akhir'];
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0] . ($data['bulan_akhir'] !== $data['bulan'] ? ' - ' . phpChgMonth(intval($bulan_akhir[1])) . ' ' . $bulan_akhir[0] : '');
			$filter = "AND to_char(pr.realisasi_tanggal, 'YYYY-MM') BETWEEN '$filter_bulan_awal' AND '$filter_bulan_akhir'";
		} else {
			$tanggal = $data['tanggal'];
			$filter = "AND to_date(cast(pr.realisasi_tanggal as TEXT), 'YYYY-MM-DD') = '$tanggal'";
			$dtCaption = 'Tanggal : ' . $data['tanggal'];
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
			$sheet->setCellValue('A1', 'LAPORAN REALISASI REKAP');
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
			$sheet->setCellValue('B2', 'JENIS PAJAK');
			$sheet->setCellValue('C2', 'SUB TOTAL');
			$sheet->setCellValue('D2', 'PAJAK');
			$sheet->setCellValue('E2', 'TOTAL');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];


			if (!empty($data['jenis_pajak'])) {
				$existrealisasi = $this->db->query(
					'SELECT * 
					from pajak_jenis 
					where jenis_id = \'' . $data['jenis_pajak'] . '\''
				)->row_array();
				if (!empty($existrealisasi)) {
					if ($existrealisasi['jenis_tipe'] == 'parent') {
						$filterparent = 'AND pj.jenis_id = \'' . $data['jenis_pajak'] . '\'';
						$filterdetail = '';
					} else if ($existrealisasi['jenis_tipe'] == 'detail') {
						$filterparent = 'AND pj.jenis_id = \'' . $existrealisasi['jenis_parent'] . '\'';
						$filterdetail = 'AND pj.jenis_id = \'' . $data['jenis_pajak'] . '\'';
					}
				}
			}
			$ops = $this->db->query(
				'SELECT * 
				from pajak_jenis pj 
				where jenis_tipe = \'parent\' 
				' . $filterparent . ''
			)->result_array();
			$no = 2;
			$totalrealisasi = [
				'realisasi_sub_total' => 0,
				'realisasi_pajak' => 0,
				'realisasi_total' => 0
			];
			foreach ($ops as $key => $val) {
				// $html .= '<tr>
				// 	<td>' . $no . '</td>
				// 	<td>' . $val['jenis_nama'] . '</td>
				// 	<td style="text-align: right;"></td>
				// 	<td style="text-align: right;"></td>
				// 	<td style="text-align: right;"></td>
				// </tr>';
				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $val['jenis_nama']);
				$sheet->setCellValue('C' . $no, $val['']);
				$sheet->setCellValue('D' . $no, $val['']);
				$sheet->setCellValue('E' . $no, $val['']);
				$opschild = $this->db->query('SELECT 
					pj.jenis_id,
					pj.jenis_kode, 
					pj.jenis_nama,
					sum(pr.realisasi_sub_total) as realisasi_sub_total,
					sum(pr.realisasi_pajak) as realisasi_pajak,
					sum(pr.realisasi_total) as realisasi_total
				from pajak_jenis pj 
				left join pajak_wajibpajak pw on pj.jenis_id = pw.wajibpajak_sektor_nama 
				left join pajak_realisasi pr on pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd ' . $filter . '
				where jenis_parent = \'' . $val['jenis_id'] . '\'
				' . $filterdetail . '
				group by pj.jenis_id')->result_array();
				foreach ($opschild as $ckey => $cval) {
					// $html .= '<tr>
					// 	<td></td>
					// 	<td>- ' . $cval['jenis_nama'] . '</td>
					// 	<td style="text-align: right;">' . number_format($cval['realisasi_sub_total']) . '</td>
					// 	<td style="text-align: right;">' . number_format($cval['realisasi_pajak']) . '</td>
					// 	<td style="text-align: right;">' . number_format($cval['realisasi_total']) . '</td>
					// </td>';
					$no += 1;
					$sheet->setCellValue('A' . $no, $key + 1);
					$sheet->setCellValue('B' . $no, $cval['jenis_nama']);
					$sheet->setCellValue('C' . $no, $cval['realisasi_sub_total']);
					$sheet->setCellValue('D' . $no, $cval['realisasi_pajak']);
					$sheet->setCellValue('E' . $no, $cval['realisasi_total']);

					$totalrealisasi['realisasi_sub_total'] += $cval['realisasi_sub_total'];
					$totalrealisasi['realisasi_pajak'] += $cval['realisasi_pajak'];
					$totalrealisasi['realisasi_total'] += $cval['realisasi_total'];
				}
				$no += 1;
			}
			$no = $no + 1;
			$sheet->setCellValue('A' . $no, 'JUMLAH')->mergeCells('A' . $no . ':' . 'B' . $no);
			$sheet->setCellValue('C' . $no, $totalrealisasi['realisasi_sub_total']);
			$sheet->setCellValue('D' . $no, $totalrealisasi['realisasi_pajak']);
			$sheet->setCellValue('E' . $no, $totalrealisasi['realisasi_total']);



			$no = 2;
			// foreach ($realisasipajak as $key => $value) {

			// 	$totalOmzet += $value['realisasi_sub_total'];
			// 	$totalJasa += $value['realisasi_jasa'];
			// 	$totalPajak += $value['realisasi_pajak'];
			// 	$cTotal = $value['realisasi_sub_total'] + $value['realisasi_jasa'] + $value['realisasi_pajak'];
			// 	$totalAll += $cTotal;

			// 	if ($tgl == $value['realisasi_tanggal']) $tanggal = '';
			// 	else $tgl = $tanggal = $value['realisasi_tanggal'];

			// 	$no += 1;
			// 	$sheet->setCellValue('A' . $no, $key + 1);
			// 	$sheet->setCellValue('B' . $no, $this->content_date($tanggal));
			// 	$sheet->setCellValue('C' . $no, $value['realisasi_wajibpajak_npwpd']);
			// 	$sheet->setCellValue('D' . $no, $value['realisasi_sub_total']);
			// 	$sheet->setCellValue('E' . $no, $value['realisasi_jasa']);
			// 	$sheet->setCellValue('F' . $no, $value['realisasi_pajak']);
			// 	$sheet->setCellValue('G' . $no, $cTotal);
			// 	// $sheet->setCellValue('G' . $no,  number_format(($value['realisasi_bayar_opsi'] == 'T' ? $value['realisasi_bayar_grand_total'] : 0)));
			// 	// $sheet->setCellValue('H' . $no, number_format(($value['realisasi_bayar_opsi'] == 'K' ? $value['realisasi_bayar_grand_total'] : 0)));


			// }
			// $no = $no+1;
			// $sheet->setCellValue('A' . $no, 'TOTAL')->mergeCells('A' .$no. ':'.'C'.$no);
			// $sheet->setCellValue('D' . $no, $totalOmzet);
			// $sheet->setCellValue('E' . $no, $totalJasa);
			// $sheet->setCellValue('F' . $no, $totalPajak);
			// $sheet->setCellValue('G' . $no, $totalAll);

			$sheet->getStyle('A2:E' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanrealisasirekap-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_realisasi/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_realisasi/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_realisasi/' . $filename;
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

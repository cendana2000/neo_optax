<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanwp extends Base_Controller
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
				<th class="t-center">NPWPD</th>
				<th class="t-center">WAJIB PAJAK</th>
				<th class="t-center">PENANGGUNG JAWAB</th>
				<th class="t-center">EMAIL</th>
				<th class="t-center">HP/TELP</th>
				<th class="t-center">SEKTOR USAHA</th>
			</tr>';
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
					<p>OPTAX</p>
					<p><u>---- ------- ----</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> DAFTAR WAJIB PAJAK</h4><br>
				</td>
			</tr>
			<tr>
				<td></td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">NPWPD</th>
				<th class="t-center">WAJIB PAJAK</th>
				<th class="t-center">PENANGGUNG JAWAB</th>
				<th class="t-center">EMAIL</th>
				<th class="t-center">HP/TELP</th>
				<th class="t-center">SEKTOR USAHA</th>
			</tr>';
		$wajibpajak = $this->db->query("SELECT * FROM v_pajak_wajib_pajak 
		WHERE wajibpajak_status = '2'
		ORDER BY jenis_nama")->result_array();

		foreach ($wajibpajak as $key => $value) {
			$html .= '<tr>
					<td>' . $value['wajibpajak_npwpd'] . '</td>
					<td>' . $value['wajibpajak_nama'] . '</td>
					<td>' . $value['wajibpajak_nama_penanggungjawab'] . '</td>
					<td>' . $value['wajibpajak_email'] . '</td>
					<td>' . $value['wajibpajak_telp'] . '</td>
					<td>' . $value['jenis_nama'] . '</td>
				</tr>';
			if ($hal == 1) $total = 60;
		}

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

	public function get_laporan_single()
	{
		$data = varPost();

		$data_wp = $this->db->get_where('v_pajak_wajib_pajak', $data)->row_array();

		if (empty($data_wp['wajibpajak_berkas'])) {
			$data_wp['wajibpajak_berkas'] = base_url() . "/assets/berkasnpwp/images/no_image.png";
		} else {
			$data_wp['wajibpajak_berkas'] = base_url() . $data_wp['wajibpajak_berkas'];
		}

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

		$html .= '
		<h3>Data Detail Wajib Pajak</h3>
		<hr	>
		<table  cellpadding="10">
			<tr>
				<td style="width: 25%!important;">NPWPD</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_npwpd'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Sektor Usaha</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['jenis_nama'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Nama Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_nama'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Alamat</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_alamat'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Nama Penanggung Jawab</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_nama_penanggungjawab'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">No Telp Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_telp'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Email Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_wp['wajibpajak_telp'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Berkas NPWPD	</td>
				<td style="width:5%!importnat">:</td>
				<td>
					<img style="width:250px" src="' . $data_wp['wajibpajak_berkas'] . '" alt="' . base_url() . "/assets/berkasnpwp/images/no_image.png" . '">
				</td>
			</tr>
		</table>
		';

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

	public function spreadsheet_laporan()
	{
		$data = varPost();
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
			$sheet->setCellValue('A1', 'DAFTAR WAJIB PAJAK');
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
			$sheet->setCellValue('B2', 'NPWPD');
			$sheet->setCellValue('C2', 'WAJIB PAJAK');
			$sheet->setCellValue('D2', 'PENANGGUNG JAWAB');
			$sheet->setCellValue('E2', 'EMAIL');
			$sheet->setCellValue('F2', 'HP/TELP');
			$sheet->setCellValue('G2', 'SEKTOR USAHA');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$ops = $wajibpajak = $this->db->query("SELECT * FROM v_pajak_wajib_pajak 
			WHERE wajibpajak_status = '2'
			ORDER BY jenis_nama")->result_array();
			$no = 2;
			foreach ($ops as $key => $value) {
				foreach ($value as $vkey => $vvalue) {
					if (is_null($vvalue)) {
						$value[$vkey] = "-";
					}
				}
				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['wajibpajak_npwpd']);
				$sheet->setCellValue('C' . $no, $value['wajibpajak_nama']);
				$sheet->setCellValue('D' . $no, $value['wajibpajak_nama_penanggungjawab']);
				$sheet->setCellValue('E' . $no, $value['wajibpajak_email']);
				$sheet->setCellValue('F' . $no, $value['wajibpajak_telp']);
				$sheet->setCellValue('G' . $no, $value['jenis_nama']);
			}
			$sheet->getStyle('A7:G' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanwajibpajak-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/wajibpajak/')) {
				mkdir(FCPATH . 'assets/laporan/wajibpajak/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/wajibpajak/' . $filename;
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

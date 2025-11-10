<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanpendapatan extends Base_Controller
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
				<th class="t-center">Tanggal</th>
				<th class="t-center">Total Penjualan</th>
				<th class="t-center">Total Pembelian</th>
				<th class="t-center">Pendapatan</th>
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
		$filter_penjualan = '';
		$filter_pembelian = '';
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
						<h4> LAPORAN PENDAPATAN</h4><br>
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
				<th class="t-center">Total Penjualan</th>
				<th class="t-center">Total Pembelian</th>
				<th class="t-center">Pendapatan</th>
			</tr>';


		if (varPost('periode') == 'bulan') {
			$bulan_awal = $data['bulan'];
			$bulan_akhir = $data['bulan_akhir'];
			$dtCaption = 'Bulan : ' . $data['bulan']  . ' - ' . $data['bulan_akhir'];

			$begin = new DateTime($bulan_awal . '-1');
			$end   = (new DateTime($bulan_akhir . '-1'))->modify('+1 month');

			$no = $total = 1;
			$tgl = $tanggal = '';
			$total_penjualan = 0;
			$total_pembelian = 0;
			$total_all = 0;

			for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
				$cDate = $i->format("Y-m-d");



				$filter_penjualan = "WHERE penjualan_tanggal  = '$cDate'";
				$filter_pembelian = "WHERE pembelian_tanggal  = '$cDate'";
				$dtCaption = 'Tanggal : ' . $data['tanggal'];

				$pendapatan = $this->db->query("SELECT (SELECT SUM(pembelian_bayar_grand_total) FROM pos_pembelian_barang $filter_pembelian) as total_pembelian,
			(SELECT SUM(penjualan_total_grand) as total FROM pos_penjualan $filter_penjualan) as total_penjualan")->row_array();
				// print_r('<pre>');print_r($cDate);print_r('</pre>');

				$total_penjualan += $pendapatan['total_penjualan'];
				$total_pembelian += $pendapatan['total_pembelian'];
				$total_all += $pendapatan['total_penjualan'] - $pendapatan['total_pembelian'];

				$cPenjualan = ($pendapatan['total_penjualan'] != null) ? $pendapatan['total_penjualan'] : 0;
				$cPembelian = ($pendapatan['total_pembelian'] != null) ? $pendapatan['total_pembelian'] : 0;

				$ctotal = $pendapatan['total_penjualan'] - $pendapatan['total_pembelian'];
				$html .= '<tr>
							<td>' . date_format(new DateTime($cDate), 'd-m-Y') . '</td>
							<td style="text-align: right;">' . number_format($cPenjualan) . '</td>
							<td style="text-align: right;">' . number_format($cPembelian) . '</td>
							<td style="text-align: right;">' . $ctotal . '</td>
						</tr>';
				$no++;
				if ($hal == 1) $total = 60;
				else $total = 80;
				if ($no > $total) {
					$no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
				}

				// echo $cDate . ':' . $pembelian['total_penjualan'];
				// echo '<br>';
			}
			// exit;
		} else {
			$tanggal = $data['tanggal'];
			$filter_penjualan = "WHERE penjualan_tanggal = '$tanggal'";
			$filter_pembelian = "WHERE pembelian_tanggal = '$tanggal'";
			$dtCaption = 'Tanggal : ' . $data['tanggal'];

			$pembelian = $this->db->query("SELECT (SELECT SUM(pembelian_bayar_grand_total) FROM pos_pembelian_barang $filter_pembelian) as total_pembelian,
			(SELECT SUM(penjualan_total_grand) as total FROM pos_penjualan $filter_penjualan) as total_penjualan")->result_array();

			$item = $tunai = $kredit = 0;
			$no = $total = 1;
			$tgl = $tanggal = '';
			$total_penjualan = 0;
			$total_pembelian = 0;
			$total_all = 0;
			foreach ($pembelian as $key => $value) {
				$total_penjualan += $value['total_penjualan'];
				$total_pembelian += $value['total_pembelian'];
				$total_all += $value['total_penjualan'] - $value['total_pembelian'];

				$ctotal = $value['total_penjualan'] - $value['total_pembelian'];
				if ($tgl == $value['pembelian_tanggal']) $tanggal = '';
				else $tgl = $tanggal = $value['pembelian_tanggal'];
				$html .= '<tr>
						<td>' . date('d-m-Y') . '</td>
						<td style="text-align: right;">' . number_format($value['total_penjualan']) . '</td>
						<td style="text-align: right;">' . number_format($value['total_pembelian']) . '</td>
						<td style="text-align: right;">' . number_format($ctotal) . '</td>
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
		}

		$html .= '<tr>
						<td >TOTAL</td>
						<td style="text-align: right;">' . number_format($total_penjualan) . '</td>
						<td style="text-align: right;">' . number_format($total_pembelian) . '</td>
						<td style="text-align: right;">' . number_format($total_all) . '</td>
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
			'file_name'     => 'Laporan Pendapatan',
			'title'         => 'Laporan Pendapatan',
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

		$bulan = explode('-', $data['bulan']);
		$bulan_akhir = explode('-', $data['bulan_akhir']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));

		if (varPost('periode') == 'bulan') {
			$bulan_awal = $data['bulan'];
			$bulan_akhir = $data['bulan_akhir'];
			$dtCaption = 'Bulan : ' . $data['bulan']  . ' - ' . $data['bulan_akhir'];

			$begin = new DateTime($bulan_awal . '-1');
			$end   = (new DateTime($bulan_akhir . '-1'))->modify('+1 month');

			$tgl = $tanggal = '';
			$total_penjualan = 0;
			$total_pembelian = 0;
			$total_all = 0;
			$no = 2;

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
				$sheet->mergeCells('A1:D1');
				$sheet->setCellValue('A1', 'LAPORAN PENDAPATAN');
				$sheet->getStyle('A1')->applyFromArray($styleArray);

				foreach (range('A', 'D') as $columnID) {
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
				$sheet->getStyle('A2:D2')->applyFromArray($styleArray);
				$sheet->setCellValue('A2', 'TANGGAL');
				$sheet->setCellValue('B2', 'TOTAL PENJUALAN');
				$sheet->setCellValue('C2', 'TOTAL PEMBELIAN');
				$sheet->setCellValue('D2', 'PENDAPATAN');

				// Set Borders
				$styleArray = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						],
					],
				];

				$rightArray = [
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
					],
				];

				for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
					$cDate = $i->format("Y-m-d");

					$no += 1;

					$filter_penjualan = "WHERE penjualan_tanggal  = '$cDate'";
					$filter_pembelian = "WHERE pembelian_tanggal  = '$cDate'";

					$pendapatan = $this->db->query("SELECT (SELECT SUM(pembelian_bayar_grand_total) FROM pos_pembelian_barang $filter_pembelian) as total_pembelian,
					(SELECT SUM(penjualan_total_grand) as total FROM pos_penjualan $filter_penjualan) as total_penjualan")->row_array();
					// print_r('<pre>');print_r($cDate);print_r('</pre>');

					$total_penjualan += $pendapatan['total_penjualan'];
					$total_pembelian += $pendapatan['total_pembelian'];
					$total_all += $pendapatan['total_penjualan'] - $pendapatan['total_pembelian'];

					$cPenjualan = ($pendapatan['total_penjualan'] != null) ? $pendapatan['total_penjualan'] : 0;
					$cPembelian = ($pendapatan['total_pembelian'] != null) ? $pendapatan['total_pembelian'] : 0;
					$ctotal = $pendapatan['total_penjualan'] - $pendapatan['total_pembelian'];
					$sheet->setCellValue('A' . $no, date_format(new DateTime($cDate), 'd-m-Y'));
					$sheet->setCellValue('B' . $no, number_format($cPenjualan));
					$sheet->setCellValue('C' . $no, number_format($cPembelian));
					$sheet->setCellValue('D' . $no, $ctotal);
				}

				$sheet->setCellValue('A' . $no, 'TOTAL');
				$sheet->setCellValue('B' . $no, number_format($total_penjualan));
				$sheet->setCellValue('C' . $no, number_format($total_pembelian));
				$sheet->setCellValue('D' . $no, number_format($total_all));
				$sheet->getStyle('A3:D' . $no)->applyFromArray($styleArray);
				$sheet->getStyle('B3:D' . $no)->applyFromArray($rightArray);

				// Write a new .xlsx file
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

				// Save the new .xlsx file
				$filename = 'laporanpendapatan-' . date('d-m-y_H:i:s') . '.xlsx';
				if (!file_exists(FCPATH . 'assets/laporan/laporan_pendapatan/')) {
					mkdir(FCPATH . 'assets/laporan/laporan_pendapatan/', 0777, true);
				}
				$file = FCPATH . 'assets/laporan/laporan_pendapatan/' . $filename;
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
		} else {
			$tanggal = $data['tanggal'];
			$filter_penjualan = "WHERE penjualan_tanggal = '$tanggal'";
			$filter_pembelian = "WHERE pembelian_tanggal = '$tanggal'";
			$dtCaption = 'Tanggal : ' . $data['tanggal'];

			$ops = $this->db->query("SELECT (SELECT SUM(pembelian_bayar_grand_total) FROM pos_pembelian_barang $filter_pembelian) as total_pembelian,
			(SELECT SUM(penjualan_total_grand) as total FROM pos_penjualan $filter_penjualan) as total_penjualan")->result_array();

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
				$sheet->mergeCells('A1:D1');
				$sheet->setCellValue('A1', 'LAPORAN PENDAPATAN');
				$sheet->getStyle('A1')->applyFromArray($styleArray);

				foreach (range('A', 'D') as $columnID) {
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
				$sheet->getStyle('A2:D2')->applyFromArray($styleArray);
				$sheet->setCellValue('A2', 'TANGGAL');
				$sheet->setCellValue('B2', 'TOTAL PENJUALAN');
				$sheet->setCellValue('C2', 'TOTAL PEMBELIAN');
				$sheet->setCellValue('D2', 'PENDAPATAN');

				// Set Borders
				$styleArray = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						],
					],
				];


				$tgl = $tanggal = '';
				$total_penjualan = 0;
				$total_pembelian = 0;
				$total_all = 0;

				foreach ($ops as $key => $value) {
					$no = 2;
					$total_penjualan += $value['total_penjualan'];
					$total_pembelian += $value['total_pembelian'];
					$total_all += $value['total_penjualan'] - $value['total_pembelian'];

					$ctotal = $value['total_penjualan'] - $value['total_pembelian'];
					if ($tgl == $value['pembelian_tanggal']) $tanggal = '';
					else $tgl = $tanggal = $value['pembelian_tanggal'];

					$no += 1;
					$sheet->setCellValue('A' . $no, date('d-m-Y'));
					$sheet->setCellValue('B' . $no, number_format($value['total_penjualan']));
					$sheet->setCellValue('C' . $no, number_format($value['total_pembelian']));
					$sheet->setCellValue('D' . $no, number_format($ctotal));
				}
				$no = $no + 1;

				$sheet->setCellValue('A' . $no, 'TOTAL');
				$sheet->setCellValue('B' . $no, number_format($total_penjualan));
				$sheet->setCellValue('C' . $no, number_format($total_pembelian));
				$sheet->setCellValue('D' . $no, number_format($total_all));
				$sheet->getStyle('A3:D' . $no)->applyFromArray($styleArray);

				// Write a new .xlsx file
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

				// Save the new .xlsx file
				$filename = 'laporanpendapatan-' . date('d-m-y_H:i:s') . '.xlsx';
				if (!file_exists(FCPATH . 'assets/laporan/laporan_pendapatan/')) {
					mkdir(FCPATH . 'assets/laporan/laporan_pendapatan/', 0777, true);
				}
				$file = FCPATH . 'assets/laporan/laporan_pendapatan/' . $filename;
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
}

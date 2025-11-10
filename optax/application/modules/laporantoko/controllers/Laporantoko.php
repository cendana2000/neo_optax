<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporantoko extends Base_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'wajibpajak/WajibpajakModel'  => 'wajibpajak',
      'toko/TokoModel'              => 'toko',
      'transaksipembelian/TransaksipembelianModel' => 'transaksipembelian',
			'transaksipembelian/TransaksipembeliandetailModel' => 'transaksipembeliandetail',
			'supplier/SupplierModel' => 'supplier'
    ));
  }

  public function index()
  {
    $where['toko_status = \'2\''] = null;
		$this->select_dt(varPost(), 'toko', 'table', true, $where);

		$res = $this->toko->select([
			'filters_static' => [
				'toko_status' => '2',
			],
		]);
    $this->response(
			$res
    );
  }

	public function gettoko_ajax(){
    $data = varPost();
    $return = $this->toko->select(array(
      'custom_fields' => 'toko_id as id, toko_nama as text'
    ))['data'];
    $this->response(array('items' => $return, 'total_count' => count($return)));
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
				<th class="t-center">NO</th>
				<th class="t-center">NAMA PERUSAHAAN</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">SEKTOR USAHA</th>
				<th class="t-center">ALAMAT</th>
				<th class="t-center">NAMA PENANGGUNGJAWAB</th>
				<th class="t-center">NO TELEPON</th>
				<th class="t-center">EMAIL</th>
				<th class="t-center">KODE USAHA</th>
				<th class="t-center">TANGGAL PERMOHONAN</th>
			</tr>';
	}

	public function get_laporan_rekap()
	{
		$data = varPost();
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
						<h4> DAFTAR TEMPAT USAHA</h4><br>
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
				<th class="t-center">NO</th>
				<th class="t-center">NAMA PERUSAHAAN</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">SEKTOR USAHA</th>
				<th class="t-center">ALAMAT</th>
				<th class="t-center">NAMA PENANGGUNGJAWAB</th>
				<th class="t-center">NO TELEPON</th>
				<th class="t-center">EMAIL</th>
				<th class="t-center">KODE USAHA</th>
				<th class="t-center">TANGGAL PERMOHONAN</th>
			</tr>';
		$wajibpajak = $this->db->query("SELECT * FROM v_pajak_wajib_pajak ORDER BY jenis_nama")->result_array();

    $opr = $this->toko->select([
      'filters_static' => [
        'toko_status' => '2',
      ],
    ])['data'];

		$no = 1;
		foreach ($opr as $key => $value) {
			$html .= '<tr>
					<td>' . $no . '</td>
					<td>' . $value['toko_nama'] . '</td>
					<td>' . $value['toko_wajibpajak_npwpd'] . '</td>
					<td>' . $value['jenis_nama'] . '</td>
					<td>' . $value['wajibpajak_alamat'] . '</td>
					<td>' . $value['wajibpajak_nama_penanggungjawab'] . '</td>
					<td>' . $value['wajibpajak_telp'] . '</td>
					<td>' . $value['wajibpajak_email'] . '</td>
					<td>' . $value['toko_kode'] . '</td>
					<td>' . $value['toko_registered_at'] . '</td>
				</tr>';
			if ($hal == 1) $total = 60;
			$no += 1;
		}

		$html .= '</table>';

		$result = createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'LaporanRekapTempatUsaha',
			'title'         => 'Laporan Rekap Tempat Usaha',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
		$this->response($result);
	}

	public function get_laporan_single()
	{
		$data = varPost();

		$data_tk = $this->toko->read([
			'toko_id' => $data['toko_id'],
		]);

		if (empty($data_tk['toko_logo'])) {
			$data_tk['toko_logo'] = base_url() . "/assets/berkasnpwp/images/no_image.png";
		} else {
			$data_tk['toko_logo'] = base_url() . $data_tk['toko_logo'];
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
		<h3>Data Detail Tempat Usaha</h3>
		<hr	>
		<table  cellpadding="10">
			<tr>
				<td style="width: 25%!important;">Nama Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['toko_nama'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">NPWPD</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['toko_wajibpajak_npwpd'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Sektor Usaha</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['jenis_nama'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Alamat</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['wajibpajak_alamat'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Nama Penanggung Jawab</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['wajibpajak_nama_penanggungjawab'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">No Telp Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['wajibpajak_telp'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Email Perusahaan</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['wajibpajak_email'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Kode Usaha</td>
				<td style="width:5%!importnat">:</td>
				<td>' . $data_tk['toko_kode'] . '</td>
			</tr>
			<tr>
				<td style="width: 25%!important;">Logo Toko</td>
				<td style="width:5%!importnat">:</td>
				<td>
					<img style="width:250px" src="' . $data_tk['toko_logo'] . '" alt="' . base_url() . "/assets/berkasnpwp/images/no_image.png" . '">
				</td>
			</tr>
		</table>
		';

		$result = createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'LaporanTempatUsaha-'.$data_tk['toko_wajibpajak_npwpd'].'-' . $data_tk['toko_nama'],
			'title'         => 'Laporan Tempat Usaha - '.$data_tk['toko_wajibpajak_npwpd'].' - '.$data_tk['toko_nama'],
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
		$this->response($result);
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
			$sheet->mergeCells('A1:J1');
			$sheet->setCellValue('A1', 'DAFTAR TEMPAT USAHA');
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
			$sheet->setCellValue('B2', 'NPWPD');
			$sheet->setCellValue('C2', 'Nama WP');
			$sheet->setCellValue('D2', 'SEKTOR USAHA');
			$sheet->setCellValue('E2', 'ALAMAT');
			$sheet->setCellValue('F2', 'NAMA PENANGGUNGJAWAB');
			$sheet->setCellValue('G2', 'NO TELEPON');
			$sheet->setCellValue('H2', 'EMAIL');
			$sheet->setCellValue('I2', 'KODE USAHA');
			$sheet->setCellValue('J2', 'TANGGAL PERMOHONAN');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$ops = $this->toko->select([
				'filters_static' => [
					'toko_status' => '2',
				],
			])['data'];
			$no = 2;
			foreach ($ops as $key => $value) {
				foreach ($value as $vkey => $vvalue) {
						if (is_null($vvalue)) {
								$value[$vkey] = "-";
						}
				}
				$no += 1;
				$sheet->setCellValue('A'.$no, $key+1);
				$sheet->setCellValue('B'.$no, $value['wajibpajak_npwpd']);
				$sheet->setCellValue('C'.$no, $value['realisasitoko_nama_parent_nama']);
				$sheet->setCellValue('D'.$no, $value['jenis_nama']);
				$sheet->setCellValue('E'.$no, $value['wajibpajak_alamat']);
				$sheet->setCellValue('F'.$no, $value['wajibpajak_nama_penanggungjawab']);
				$sheet->setCellValue('G'.$no, $value['wajibpajak_telp']);
				$sheet->setCellValue('H'.$no, $value['wajibpajak_email']);
				$sheet->setCellValue('I'.$no, $value['toko_kode']);
				$sheet->setCellValue('J'.$no, $value['toko_registered_at']);
			}
			$sheet->getStyle('A7:J'.$no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporantempatusaha-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/tempat_usaha/')) {
				mkdir(FCPATH . 'assets/laporan/tempat_usaha/', 0777, true);
			}		
			$file = FCPATH . 'assets/laporan/tempat_usaha/' . $filename;
			$writer->save($file);

			$this->response([
				'success' => true,
				'file' => $filename
			]);
		} catch (\Throwable $th) {
			print_r('<pre>');print_r($th);print_r('</pre>');exit;
			$this->response([
				'success' => false,
				'message' => $th
			]);
		}
	}
}
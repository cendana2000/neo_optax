<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekappajak extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'rekappajak/rekappajakparentModel' 			=> 'rekapparent',
			'rekappajak/rekappajakparentfilterModelV8' 	=> 'rekapparentfilter',
			'rekappajak/rekappajakModel' 				=> 'rekappajak',
			'rekappajak/rekappajakdetailModel' 			=> 'rekappajakdetail',
			'wajibpajak/WajibpajakModel' 				=> 'wajibpajak',
			'transaksiwp/TransaksiwpPosModel' 			=> 'transaksiwppos',
			'transaksiwp/TransaksiwpModel' 				=> 'transaksiwp',
		));
	}

	public function index()
	{
		$post  = varPost();
		$where = [];

		$pemdaId = (int) $this->session->userdata('pemda_id');

		if ($pemdaId > 0) {
			$where['pemda_id'] = $pemdaId;
		}
		if (!empty($post['kecamatan'])) {
			$where['kecamatan_id'] = $post['kecamatan'];
		}

		if (!empty($post['jenis_pajak'])) {
			$where['jenis_nama'] = $post['jenis_pajak'];
		}

		if (!empty($post['jenis_device'])) {
			$where['jenis_device'] = $post['jenis_device'];
		}

		$result = $this->select_dt(
			$post,
			'rekapparent',
			'table',
			true,
			$where
		);

		return $this->response($result);
	}

	function getSubRekap()
	{
		if (!empty($post['filterBulan'])) {
			$periode = explode(' - ', $post['filterBulan']);
			if (count($periode) === 2) {
				$start = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $periode[0])));
				$end   = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $periode[1])));

				$where[] = ['field' => 'tanggal_last_transaksi', 'op' => '>=', 'value' => $start];
				$where[] = ['field' => 'tanggal_last_transaksi', 'op' => '<=', 'value' => $end];
			}
		}
	}

	public function readWp()
	{
		$data = varPost();
		$ops = $this->wajibpajak->read(['wajibpajak_id' => $data['wajibpajak_id']]);
		$this->response($ops);
	}

	public function loadDataPos()
	{
		$wajibpajak_id = varPost('wajibpajak_id');
		$sumber_data   = varPost('sumber_data');
		$periode       = varPost('periode');

		$startdate = date('Y-m-d 00:00:00');
		$enddate   = date('Y-m-d 23:59:59');

		if ($periode) {
			$periodearr = explode(' - ', $periode);
			if (count($periodearr) === 2) {
				$startdate = date('Y-m-d 00:00:00', strtotime($periodearr[0]));
				$enddate   = date('Y-m-d 23:59:59', strtotime($periodearr[1]));
			}
		}

		if ($sumber_data === 'POS') {
			$where = [];
			$where["penjualan_tanggal >= '{$startdate}' AND penjualan_tanggal <= '{$enddate}'"] = null;
			$where['penjualan_deleted_at IS NULL'] = null;
			$where['pos_penjualan.wajibpajak_id'] = $wajibpajak_id;

			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where["EXISTS(
					SELECT 1 
					FROM pajak_wajibpajak 
					WHERE pajak_wajibpajak.wajibpajak_id = pos_penjualan.wajibpajak_id 
					AND pemda_id = {$pemda_id}
				)"] = null;
			}
			$opr = $this->select_dt(
				varPost(),
				'transaksiwppos',
				'table',
				false,
				$where
			);
			foreach ($opr['aaData'] as &$row) {
				$row['trx_id']    = $row['penjualan_id'];
				$row['trx_kode']  = $row['penjualan_kode'];
				$row['trx_tgl']   = $row['penjualan_tanggal'];
				$row['trx_time']  = $row['penjualan_created'];
				$row['trx_total'] = $row['penjualan_total_grand'];
				$statuses = [];
				if ($row['penjualan_status_aktif']) {
					$statuses[] = 'batal';
				} else {
					$statuses[] = 'aktif';
				}

				if (!empty($row['penjualan_total_retur'])) {
					$statuses[] = 'retur';
				}

				if ($row['penjualan_lock'] == '1') {
					$statuses[] = 'posting';
				}

				$row['trx_status'] = $statuses;
			}
			$get_total = $this->db
				->select("SUM(penjualan_total_grand) AS total_nominal_penjualan")
				->where($where)
				->get('pos_penjualan')
				->row();
			$opr['sumtotal'] = $get_total;
		} elseif ($sumber_data === 'POS+UPLOAD' || $sumber_data === 'UPLOAD') {
			$where = [];
			$where["realisasi_tanggal >= '{$startdate}' AND realisasi_tanggal <= '{$enddate}'"] = null;
			$where['realisasi_deleted_at IS NULL'] = null;
			$where['realisasi_wajibpajak_id'] = $wajibpajak_id;

			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where["EXISTS(
					SELECT 1 
					FROM pajak_wajibpajak 
					WHERE pajak_wajibpajak.wajibpajak_id = pajak_wajibpajak.wajibpajak_id 
					AND pemda_id = {$pemda_id}
				)"] = null;
			}
			$opr = $this->select_dt(
				varPost(),
				'rekappajak',
				'table',
				false,
				$where
			);
			foreach ($opr['aaData'] as &$row) {
				$row['trx_id']    = $row['realisasi_id'];
				$row['trx_kode']  = $row['realisasi_no'];
				$row['trx_tgl']   = $row['realisasi_tanggal'];
				$row['trx_time']  = $row['realisasi_tanggal'];
				$row['trx_total'] = $row['realisasi_total'];
				$statuses = [];
				if (empty($row['realisasi_deleted_at'])) {
					$statuses[] = 'aktif';
				} else {
					$statuses[] = 'batal';
				}
				$row['trx_status'] = $statuses;
			}

			$get_total = $this->db
				->select("SUM(realisasi_total) AS total_nominal_penjualan")
				->where($where)
				->get('pajak_realisasi')
				->row();
			$opr['sumtotal'] = $get_total;
		}

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('pemda_id', $pemda_id);
		}
		$wp = $this->db
			->select('
				wajibpajak_nama_penanggungjawab,
				wajibpajak_npwpd,
				toko_kode,
				toko_nama
			')
			->get_where('v_pajak_toko', [
				'wajibpajak_id' => $wajibpajak_id
			])
			->row();
		$opr['wajibpajak'] = $wp;
		$this->response(
			$opr
		);
	}

	function detailTransaksi()
	{
		$data = varPost();
		if (empty($data['sumber_data'])) {
			return $this->response([
				'success' => false,
				'message' => 'Sumber data tidak ditemukan'
			]);
		}
		$ops = $this->rekappajak->detailTransaksi($data);
		$this->response($ops);
	}


	public function sub_table()
	{
		$data = varPost();
		$realisasi_npwpd = varpost('realisasi_npwpd');
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $realisasi_npwpd;

		if ($data['filterBulan'] != null) {
			$data = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $data[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $data[0] . '\''] = null;
		}
		$opr = $this->select_dt(varPost(), 'realisasi', 'datatable', true, $where, null, null, " GROUP BY 1,2,3,4,5,6,7,8 ");
		$get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		sum(realisasi_pajak) as total_pajak,
		sum(realisasi_sub_total) as total_subtotal,
		sum(realisasi_sub_total) + sum(realisasi_jasa) + sum(realisasi_pajak) as total_total,")
			->where($where)
			->get('pajak_realisasi')
			->row();
		$opr['sumtotal'] = $get_total;
		// $opr['sql2'] = $this->db->last_query();
		$this->response(
			$opr
		);
	}

	public function headerRealisasi($txt, $hal)
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
				<th class="t-center">No</th>
				<th class="t-center">Tanggal</th>
				<th class="t-center">Subtotal</th>
				<!--
				<th class="t-center">Service Charge</th>
				<th class="t-center">Lain-Lain</th>
				-->
				<th class="t-center">Diskon</th>
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
	}

	public function spreadsheet_realisasi()
	{
		$data = varPost();
		if (empty($data['filterBulan'])) {
			$masapajak = 'All';
		} else {
			$bulan = explode('-', $data['filterBulan']);
			$masapajak = phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
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
			$sheet->mergeCells('A1:H1');
			$sheet->setCellValue('A1', 'REKAP PAJAK');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'J') as $columnID) {
				$sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

			$sheet->mergeCells('A3:C3');
			$sheet->mergeCells('A4:C4');
			$sheet->mergeCells('A5:C5');
			$sheet->setCellValue('A3', 'Masa Pajak');
			$sheet->setCellValue('A4', 'Jumlah WP Terdaftar');

			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				],
			];

			$wp_terdaftar = $this->db->query("select count(*) as wp_terdaftar from pajak_wajibpajak pw  where wajibpajak_status  = '2'")->row_array()['wp_terdaftar'];
			$wp_terkoneksi = $this->db->query("select count(*) as wp_terkoneksi from pajak_toko where toko_status = '2'")->row_array()['wp_terkoneksi'];
			$sheet->mergeCells('D3:I3');
			$sheet->mergeCells('D4:I4');
			$sheet->mergeCells('D5:I5');
			$sheet->setCellValue('D3', $masapajak);
			$sheet->setCellValue('D4', $wp_terdaftar);
			$sheet->getStyle('D3:D5')->applyFromArray($styleArray);

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
			$sheet->getStyle('A7:H7')->applyFromArray($styleArray);
			$sheet->setCellValue('A7', 'No');
			$sheet->setCellValue('B7', 'NPWPD');
			$sheet->setCellValue('C7', 'Nama WP');
			$sheet->setCellValue('D7', 'Transaksi Terakhir');
			$sheet->setCellValue('E7', 'Omzet(Rp)');
			$sheet->setCellValue('F7', 'Pajak(Rp)');
			$sheet->setCellValue('G7', 'Tgl. Pemasangan');
			$sheet->setCellValue('H7', 'Jenis Pajak');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			if (empty($data['filterBulan'])) {
				$where = [
					'realisasi_parent_wajibpajak_status' => '2',
				];
				$ops = $this->realisasiparent->select([
					'filters_static' => $where
				])['data'];
			} else {
				$where = [
					'realisasi_parent_wajibpajak_status' => '2',
					'realisasi_parent_tanggal' => $data['filterBulan']
				];
				$ops = $this->realisasiparentfilter->select([
					'filters_static' => $where
				])['data'];
			}
			$no = 7;
			foreach ($ops as $key => $value) {
				foreach ($value as $vkey => $vvalue) {
					if (is_null($vvalue)) {
						$value[$vkey] = "-";
					}
				}
				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['realisasi_parent_npwpd']);
				$sheet->setCellValue('C' . $no, $value['realisasi_parent_nama']);
				$sheet->setCellValue('D' . $no, $value['realisasi_parent_transaksi_terakhir']);
				$sheet->setCellValue('E' . $no, $value['realisasi_parent_sub_total']);
				$sheet->setCellValue('F' . $no, $value['realisasi_parent_sub_total'] / 10);
				$sheet->setCellValue('G' . $no, $value['realisasi_parent_tanggal_daftar']);
				$sheet->setCellValue('H' . $no, $value['realisasi_parent_jenis_pajak']);
			}
			$sheet->getStyle('A7:H' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'rekappajak-' . date('d-m-y-H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/monitor_realisasi/')) {
				mkdir(FCPATH . 'assets/laporan/monitor_realisasi/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/monitor_realisasi/' . $filename;
			$writer->save($file);

			$this->response([
				'success' => true,
				'file' => $filename
			]);
		} catch (\Throwable $th) {
			$this->response([
				'success' => false,
			]);
		}
	}

	public function spreadsheet_subrealisasi()
	{
		$data = varPost();
		$realisasi_npwpd = varPost('realisasi_npwpd');
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $realisasi_npwpd;

		if ($data['filterBulan'] != null) {
			$bulan = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $bulan[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $bulan[0] . '\''] = null;
		}

		if (empty($data['filterBulan'])) {
			$masapajak = 'All';
		} else {
			$bulan = explode('-', $data['filterBulan']);
			$masapajak = phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
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
			$sheet->mergeCells('A1:H1');
			$sheet->setCellValue('A1', 'SUB REKAP PAJAK');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'H') as $columnID) {
				$sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

			$sheet->mergeCells('A3:C3');
			$sheet->mergeCells('A4:C4');
			$sheet->mergeCells('A5:C5');
			$sheet->mergeCells('A6:C6');
			$sheet->mergeCells('A7:C7');
			$sheet->setCellValue('A3', 'Masa Pajak');
			$sheet->setCellValue('A4', 'NPWPD');
			$sheet->setCellValue('A5', 'ALAMAT');
			$sheet->setCellValue('A6', 'Nama WP');
			$sheet->setCellValue('A7', 'Nama Penanggung Jawab');

			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				],
			];

			$wp = $this->wajibpajak->read(array('wajibpajak_npwpd' => $realisasi_npwpd));
			$sheet->mergeCells('D3:H3');
			$sheet->mergeCells('D4:H4');
			$sheet->mergeCells('D5:H5');
			$sheet->mergeCells('D6:H6');
			$sheet->mergeCells('D7:H7');
			$sheet->setCellValue('D3', $masapajak);
			$sheet->setCellValue('D4', $wp['wajibpajak_npwpd']);
			$sheet->setCellValue('D5', $wp['wajibpajak_alamat']);
			$sheet->setCellValue('D6', $wp['wajibpajak_nama']);
			$sheet->setCellValue('D7', $wp['wajibpajak_nama_penanggungjawab']);
			$sheet->getStyle('D3:D7')->applyFromArray($styleArray);

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
			$sheet->getStyle('A9:H9')->applyFromArray($styleArray);
			$sheet->setCellValue('A9', 'No');
			$sheet->setCellValue('B9', 'Tanggal');
			$sheet->setCellValue('C9', 'Subtotal');
			$sheet->setCellValue('D9', 'Service Charge');
			$sheet->setCellValue('E9', 'Lain-Lain');
			$sheet->setCellValue('F9', 'Diskon');
			$sheet->setCellValue('G9', 'Pajak');
			$sheet->setCellValue('H9', 'Total');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$ops = $this->realisasi->select([
				'filters_static' => $where
			])['data'];
			$no = 9;
			foreach ($ops as $key => $value) {
				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['realisasi_tanggal']);
				$sheet->setCellValue('C' . $no, $value['realisasi_sub_total']);
				$sheet->setCellValue('D' . $no, $value['realisasi_jasa']);
				$sheet->setCellValue('E' . $no, '0');
				$sheet->setCellValue('F' . $no, '0');
				$sheet->setCellValue('G' . $no, $value['realisasi_pajak']);
				$sheet->setCellValue('H' . $no, $value['realisasi_total']);
			}
			$sheet->getStyle('A9:H' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'subrekappajak-' . date('d-m-y-H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/monitor_realisasi/')) {
				mkdir(FCPATH . 'assets/laporan/monitor_realisasi/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/monitor_realisasi/' . $filename;
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
			]);
		}
	}

	public function pdf_realisasi()
	{
		$data = varPost();
		if (empty($data['filterBulan'])) {
			$masapajak = 'All';
		} else {
			$bulan = explode('-', $data['filterBulan']);
			$masapajak = phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
		}
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

		$wp_terdaftar = $this->db->query("select count(*) as wp_terdaftar from pajak_wajibpajak pw where wajibpajak_status  = '2' and wajibpajak_deleted_at is null")->row_array()['wp_terdaftar'];

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>OPTAX</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>REKAP PAJAK</h4><br>
				</td>
			</tr>
		</table>
		<table style="width:100%;">
			<tr>
				<td width="20%">Masa Pajak</td>
				<td>: ' . $masapajak . '</td>
			</tr>
			<tr>
				<td width="20%">Jumlah WP Terdaftar</td>
				<td>: ' . $wp_terdaftar . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Nama WP</th>
				<th class="t-center">Transaksi Terakhir</th>
				<th class="t-center">Sub Total(Rp)</th>
				<th class="t-center">Pajak(Rp)</th>
				<th class="t-center">Total(Rp)</th>
				<!-- <th class="t-center">Tgl. Pemasangan</th> -->
				<th class="t-center">Jenis Pajak</th>
			</tr>';
		if (empty($data['filterBulan'])) {
			$where = [
				'realisasi_parent_wajibpajak_status' => '2',
			];
			$ops = $this->realisasiparent->select([
				'filters_static' => $where
			])['data'];
		} else {
			$where = [
				'realisasi_parent_wajibpajak_status' => '2',
				'realisasi_parent_tanggal' => $data['filterBulan']
			];
			$ops = $this->realisasiparentfilter->select([
				'filters_static' => $where
			])['data'];
		}
		$no = $total = $tbl_no = 1;

		foreach ($ops as $key => $value) {
			$html .= '<tr>
					<td>' . $tbl_no . '</td>
					<td>' . $value['realisasi_parent_npwpd'] . '</td>
					<td>' . $value['realisasi_parent_nama'] . '</td>
					<td>' . $value['realisasi_parent_transaksi_terakhir'] . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_parent_sub_total']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_parent_pajak']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_parent_total_pajak']) . '</td>
					<td>' . $value['realisasi_parent_jenis_pajak'] . '</td>
				</tr>';
			$tbl_no++;
			$no++;
		}

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Rekap Pajak',
			'title'         => 'Rekap Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function pdf_subrealisasi()
	{
		$data = varPost();
		$realisasi_npwpd = varPost('realisasi_npwpd');
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $realisasi_npwpd;

		if ($data['filterBulan'] != null) {
			$bulan = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $bulan[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $bulan[0] . '\''] = null;
		}

		if (empty($data['filterBulan'])) {
			$masapajak = 'All';
		} else {
			$bulan = explode('-', $data['filterBulan']);
			$masapajak = phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
		}

		$get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		sum(realisasi_pajak) as total_pajak,
		sum(realisasi_sub_total) as total_subtotal,
		sum(realisasi_total) as total_total,")
			->where($where)
			->get('pajak_realisasi')
			->row();

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
				width: 1px;
    			white-space: nowrap;
			}

			.divider{
				border-right: 1px solid black;
			}

			.laporan td, .laporan th{
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

		$ops = $this->realisasi->select([
			'filters_static' => $where
		])['data'];
		$wp = $this->wajibpajak->read(array('wajibpajak_npwpd' => $realisasi_npwpd));
		$pajak_total = 0;
		$realisasi_total_total = 0;
		foreach ($ops as $key => $value) {
			$pajak 					 = ceil(($value['realisasi_sub_total'] + $value['realisasi_jasa']) * 10 / 100);
			// $pajak_total	   		+= $pajak;			 
			$pajak_total	   		+= $value['realisasi_pajak'];
			// $realisasi_total 		 = $value['realisasi_sub_total'] + $value['realisasi_jasa'] + $pajak;
			$realisasi_total 		 = $value['realisasi_total'];
			$realisasi_total_total  += $realisasi_total;
		}

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>OPTAX</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>REKAP OMZET WAJIB PAJAK</h4><br>
				</td>
			</tr>
		</table>
		<table style="width:100%;">
			<tr>
				<td width="20%">Masa Pajak</td>
				<td>: ' . $masapajak . '</td>
			</tr>
			<tr>
				<td width="20%">NPWPD</td>
				<td>: ' . $wp['wajibpajak_npwpd'] . '</td>
			</tr>
			<tr>
				<td width="20%">Alamat</td>
				<td>: ' . $wp['wajibpajak_alamat'] . '</td>
			</tr>
			<tr>
				<td width="20%">Nama WP</td>
				<td>: ' . $wp['wajibpajak_nama'] . '</td>
			</tr>
			<tr>
				<td width="20%">Nama Penanggung Jawab</td>
				<td>: ' . $wp['wajibpajak_nama_penanggungjawab'] . '</td>
			</tr>
			<tr>
				<td width="20%">Pajak Yang Dibayarkan</td>
				<td>: ' . number_format($pajak_total, 0, ",", ".") . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">Tanggal</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Service Charge</th>
				<!--
				<th class="t-center">Lain-Lain</th>
				<th class="t-center">Diskon</th>
				-->
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';

		$no = $total = $tbl_no = 1;
		$dtCaption = '';

		foreach ($ops as $key => $value) {
			$pajak 					 = ($value['realisasi_sub_total'] + $value['realisasi_jasa']) * 10 / 100;
			// $pajak_total	   		 = $value['realisasi_pajak'];
			$realisasi_total 		 = $value['realisasi_sub_total'] + $value['realisasi_jasa'] + $pajak;
			$html 			   		.= '<tr>
				<td style="text-align: center;">' . $tbl_no . '</td>
				<td style="text-align: center;">' . $value['realisasi_tanggal'] . '</td>
				<td style="text-align: right;">' . number_format($value['realisasi_sub_total']) . '</td>
				<td style="text-align: right;">' . number_format($value['realisasi_jasa']) . '</td>
				<!--
				<td>0</td>
				<td>0</td>
				-->
				<td style="text-align: right;">' . number_format($value['realisasi_pajak']) . '</td>
				<td style="text-align: right;">' . number_format($value['realisasi_total']) . '</td>
			</tr>';
			$tbl_no++;
			$no++;
			if ($hal == 1) $total = 45;
			else $total = 50;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->headerRealisasi($dtCaption, $hal);
			}
			if (count($ops) == $key + 1) {
				$html .= '<tr>
					<th colspan="2">TOTAL</th>
					<th style="text-align: right;">' . number_format(ceil($get_total->total_subtotal * pow(100, 10)) / pow(100, 10), 0, ".", ",") . '</th>
					<th style="text-align: right;">' . number_format($get_total->total_jasa, 0, ".", ",") . '</th>
					<!--
					<th>0</th>
					<th>0</th>
					-->
					<th style="text-align: right;">' . number_format($pajak_total, 0, ".", ",") . '</th>
					<th style="text-align: right;">' . number_format($realisasi_total_total, 0, ".", ",") . '</th>
				</tr>';
			}
		}

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Sub Rekap Pajak',
			'title'         => 'Sub Rekap Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function get_kecamatan()
	{
		$loginAccess = $this->session->userdata('login_access');
		$roleAccess  = $this->session->userdata('pegawai_role_access_id');
		$pemdaId     = $this->session->userdata('pemda_id');

		$this->db->select('
			ck.kecamatan_id,
			ck.kecamatan_nama
		');
		$this->db->from('conf_kecamatan ck');
		$this->db->join('conf_pemda cp', 'cp.kabkota_id = ck.kabkota_id', 'left');

		if ($roleAccess === '123') {

			if (empty($pemdaId)) {
				return $this->response([]);
			}

			$this->db->where('cp.pemda_id', (int) $pemdaId);
		} else {
			$this->db->where('cp.pemda_id', (int) $pemdaId);
		}

		$this->db->group_by(['ck.kecamatan_id', 'ck.kecamatan_nama']);
		$this->db->order_by('ck.kecamatan_nama', 'ASC');

		$result = $this->db->get()->result_array();
		return $this->response($result);
	}

	public function get_pemda()
	{
		if ($this->session->userdata('login_access') != 'pemda') {
			$where = [
				'kabkota_id' => $this->session->userdata('kabkota_id')
			];
			return $this->response(
				$this->select_dt(varPost(), 'pemda', 'table', true, $where)
			);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Mohon login menggunakan role selain pemda.'
			]);
		}
	}
}

/* End of file realisasi.php */
/* Location: ./application/modules/rekappajak/controllers/realisasi.php */
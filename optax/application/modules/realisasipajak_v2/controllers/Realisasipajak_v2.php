<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Realisasipajak_v2 extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'realisasipajak/RealisasipajakparentModel' => 'realisasiparent',
			'realisasipajak/RealisasipajakparentfilterModel' => 'realisasiparentfilter',
			'realisasipajak/RealisasipajakModel' => 'realisasi',
			'realisasipajak/RealisasipajakdetailModel' => 'realisasidetail',
			'wajibpajak/WajibpajakModel' => 'wajibpajak',
		));
	}

	public function index()
	{
		$data = varPost();
		if (empty($data['filterBulan'])) {
			$where = [
				'realisasi_parent_wajibpajak_status' => '2',
			];
			return $this->response(
				$this->select_dt(varPost(), 'realisasiparent', 'table', true, $where)
			);
		} else {
			$where = [
				'realisasi_parent_wajibpajak_status' => '2',
				'realisasi_parent_tanggal' => $data['filterBulan']
			];

			return $this->response(
				$this->select_dt(varPost(), 'realisasiparentfilter', 'table', true, $where)
			);
		}
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
		$opr = $this->select_dt(varPost(), 'realisasi', 'datatable', true, $where);
		$get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		sum(realisasi_pajak) as total_pajak,
		sum(realisasi_sub_total) as total_subtotal,
		sum(realisasi_total) as total_total,")
			->where($where)
			->get('pajak_realisasi')
			->row();
		$opr['sql'] = $this->db->last_query();
		$opr['sumtotal'] = $get_total;
		$this->response(
			$opr
		);
	}

	public function read()
	{
		$realisasi_id = varPost('realisasi_id');

		// Get Realisasi Pajak 
		$this->db->select('realisasi_wajibpajak_npwpd');
		$this->db->select('wajibpajak_nama');
		$this->db->select('wajibpajak_alamat');
		$this->db->select('wajibpajak_nama_penanggungjawab');
		$this->db->select('realisasi_tanggal');
		$this->db->join('pajak_wajibpajak', 'pajak_wajibpajak.wajibpajak_npwpd = pajak_realisasi.realisasi_wajibpajak_npwpd', 'left');
		$realisasi = $this->db->get_where('pajak_realisasi', ['realisasi_id' => $realisasi_id])->row_array();

		$realisasi['detail'] = $this->db->get_where('pajak_realisasi_detail', ['realisasi_detail_parent' => $realisasi_id])->result_array();

		$this->response($realisasi);
	}

	public function readWp()
	{
		$data = varPost();
		$ops = $this->wajibpajak->read(['wajibpajak_npwpd' => $data['wp_npwpd']]);
		$this->response($ops);
	}

	public function realisasi_detail()
	{
		$where['realisasi_detail_parent'] = varPost('realisasi_id');
		$opr = $this->select_dt(varPost(), 'realisasidetail', 'datatable', true, $where);
		$get_total = $this->db
			->select("sum(realisasi_detail_jasa) as total_jasa,
		sum(realisasi_detail_pajak) as total_pajak,
		sum(realisasi_detail_sub_total) as total_subtotal,
		sum(realisasi_detail_total) as total_total,")
			->where($where)
			->get('pajak_realisasi_detail')
			->row();
		$opr['sumtotal'] = $get_total;
		$this->response(
			$opr
		);
	}

	public function wp_header()
	{
		$this->response([
			'wp_terkoneksi' => $this->db->query("select count(*) as wp_terkoneksi from pajak_toko where toko_status = '2'")->row_array()['wp_terkoneksi'],
			'wp_terdaftar' => $this->db->query("select count(*) as wp_terdaftar from pajak_wajibpajak pw  where wajibpajak_status  = '2'")->row_array()['wp_terdaftar'],
		]);
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
			$sheet->mergeCells('A1:I1');
			$sheet->setCellValue('A1', 'REALISASI PAJAK');
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
			$sheet->setCellValue('A5', 'Jumlah WP Terkoneksi');

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
			$sheet->setCellValue('D5', $wp_terkoneksi);
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
			$sheet->getStyle('A7:I7')->applyFromArray($styleArray);
			$sheet->setCellValue('A7', 'No');
			$sheet->setCellValue('B7', 'NPWPD');
			$sheet->setCellValue('C7', 'Nama WP');
			$sheet->setCellValue('D7', 'Transaksi Terakhir');
			$sheet->setCellValue('E7', 'Jumlah Transaksi');
			$sheet->setCellValue('F7', 'Omzet(Rp)');
			$sheet->setCellValue('G7', 'Pajak(Rp)');
			$sheet->setCellValue('H7', 'Tgl. Pemasangan');
			$sheet->setCellValue('I7', 'Jenis Pajak');

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
				$sheet->setCellValue('E' . $no, $value['realisasi_parent_jml_transaksi']);
				$sheet->setCellValue('F' . $no, $value['realisasi_parent_omzet']);
				$sheet->setCellValue('G' . $no, $value['realisasi_parent_total_pajak']);
				$sheet->setCellValue('H' . $no, $value['realisasi_parent_tanggal_daftar']);
				$sheet->setCellValue('I' . $no, $value['realisasi_parent_jenis_pajak']);
			}
			$sheet->getStyle('A7:I' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'realisasipajak-' . date('d-m-y-H:i:s') . '.xlsx';
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
			$sheet->setCellValue('A1', 'SUB REALISASI PAJAK');
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
			$filename = 'subrealisasipajak-' . date('d-m-y-H:i:s') . '.xlsx';
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

	public function spreadsheet_rincirealisasi()
	{
		$data = varPost();
		$realisasi_npwpd = $data['wp_npwpd'];
		$where['realisasi_detail_parent'] = $data['realisasi_id'];
		if (!empty($data['realisasi_tanggal'])) {
			$masapajak = date_format(new DateTime($data['realisasi_tanggal']), 'd-m-Y');
		} else {
			$masapajak = '';
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
			$sheet->setCellValue('A1', 'RINCIAN REALISASI PAJAK');
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
			$sheet->setCellValue('A3', 'Tanggal Transaksi');
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
			$sheet->getStyle('A9:I9')->applyFromArray($styleArray);
			$sheet->setCellValue('A9', 'No');
			$sheet->setCellValue('B9', 'Waktu');
			$sheet->setCellValue('C9', 'No Transaksi');
			$sheet->setCellValue('D9', 'Subtotal');
			$sheet->setCellValue('E9', 'Service Charge');
			$sheet->setCellValue('F9', 'Diskon');
			$sheet->setCellValue('G9', 'Lain-Lain');
			$sheet->setCellValue('H9', 'Pajak(Rp)');
			$sheet->setCellValue('I9', 'Total');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];

			$ops = $this->realisasidetail->select(['filters_static' => $where])['data'];
			$no = 9;
			$total = [
				'realisasi_detail_sub_total' => 0,
				'realisasi_detail_jasa' => 0,
				'realisasi_detail_pajak' => 0,
				'realisasi_detail_total' => 0,
			];
			foreach ($ops as $key => $value) {
				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $value['realisasi_detail_time']);
				$sheet->setCellValue('C' . $no, $value['realisasi_detail_penjualan_kode']);
				$sheet->setCellValue('D' . $no, $value['realisasi_detail_sub_total']);
				$sheet->setCellValue('E' . $no, $value['realisasi_detail_jasa']);
				$sheet->setCellValue('F' . $no, '0');
				$sheet->setCellValue('G' . $no, '0');
				$sheet->setCellValue('H' . $no, $value['realisasi_detail_pajak']);
				$sheet->setCellValue('I' . $no, $value['realisasi_detail_total']);

				$total['realisasi_detail_sub_total'] += $value['realisasi_detail_sub_total'];
				$total['realisasi_detail_jasa'] += $value['realisasi_detail_jasa'];
				$total['realisasi_detail_pajak'] += $value['realisasi_detail_pajak'];
				$total['realisasi_detail_total'] += $value['realisasi_detail_total'];
			}
			$no += 1;
			$sheet->mergeCells('A' . $no . ':C' . $no);
			$sheet->setCellValue('A' . $no, '');
			$sheet->setCellValue('D' . $no, $total['realisasi_detail_sub_total']);
			$sheet->setCellValue('E' . $no, $total['realisasi_detail_jasa']);
			$sheet->setCellValue('F' . $no, '0');
			$sheet->setCellValue('G' . $no, '0');
			$sheet->setCellValue('H' . $no, $total['realisasi_detail_pajak']);
			$sheet->setCellValue('I' . $no, $total['realisasi_detail_total']);

			$sheet->getStyle('A9:I' . $no)->applyFromArray($styleArray);

			$styleArray = [
				'font' => [
					'bold' => true,
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
			$sheet->getStyle('A' . $no . ':I' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'rincianrealisasipajak-' . date('d-m-y-H:i:s') . '.xlsx';
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

		$wp_terdaftar = $this->db->query("select count(*) as wp_terdaftar from pajak_wajibpajak pw  where wajibpajak_status  = '2'")->row_array()['wp_terdaftar'];
		$wp_terkoneksi = $this->db->query("select count(*) as wp_terkoneksi from pajak_toko where toko_status = '2'")->row_array()['wp_terkoneksi'];

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>BAPENDA KOTA MALANG</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>REALISASI PAJAK</h4><br>
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
			<tr>
				<td width="20%">Jumlah WP Terkoneksi</td>
				<td>: ' . $wp_terkoneksi . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Nama WP</th>
				<th class="t-center">Transaksi Terakhir</th>
				<th class="t-center">Jumlah Transaksi</th>
				<th class="t-center">Omzet(Rp)</th>
				<th class="t-center">Pajak(Rp)</th>
				<th class="t-center">Tgl. Pemasangan</th>
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
					<td style="text-align: right;">' . number_format($value['realisasi_parent_jml_transaksi']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_parent_omzet']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_parent_total_pajak']) . '</td>
					<td>' . $value['realisasi_parent_tanggal_daftar'] . '</td>
					<td>' . $value['realisasi_parent_jenis_pajak'] . '</td>
				</tr>';
			$tbl_no++;
			$no++;
			if ($hal == 1) $total = 60;
			else $total = 80;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
			}
		}

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Realisasi Pajak',
			'title'         => 'Realisasi Pajak',
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

		$wp = $this->wajibpajak->read(array('wajibpajak_npwpd' => $realisasi_npwpd));

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>BAPENDA KOTA MALANG</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>SUB REALISASI PAJAK</h4><br>
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
				<td width="20%">ALAMAT</td>
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
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">Tanggal</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Service Charge</th>
				<th class="t-center">Lain-Lain</th>
				<th class="t-center">Diskon</th>
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
		$ops = $this->realisasi->select([
			'filters_static' => $where
		])['data'];
		$no = $total = $tbl_no = 1;

		foreach ($ops as $key => $value) {
			$html .= '<tr>
					<td>' . $tbl_no . '</td>
					<td>' . $value['realisasi_tanggal'] . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_sub_total']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_jasa']) . '</td>
					<td>0</td>
					<td>0</td>
					<td style="text-align: right;">' . number_format($value['realisasi_pajak']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_total']) . '</td>
				</tr>';
			$tbl_no++;
			$no++;
			if ($hal == 1) $total = 46;
			else $total = 50;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->headerRealisasi($dtCaption, $hal);
			}
		}

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Realisasi Pajak',
			'title'         => 'Realisasi Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
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
				<th class="t-center">Service Charge</th>
				<th class="t-center">Lain-Lain</th>
				<th class="t-center">Diskon</th>
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
	}

	public function pdf_rincirealisasi()
	{
		$data = varPost();
		$realisasi_npwpd = $data['wp_npwpd'];
		$where['realisasi_detail_parent'] = $data['realisasi_id'];
		if (!empty($data['realisasi_tanggal'])) {
			$masapajak = date_format(new DateTime($data['realisasi_tanggal']), 'd-m-Y');
		} else {
			$masapajak = '';
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

		$wp = $this->wajibpajak->read(array('wajibpajak_npwpd' => $realisasi_npwpd));

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>BAPENDA KOTA MALANG</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>RINCIAN REALISASI PAJAK</h4><br>
				</td>
			</tr>
		</table>
		<table style="width:100%;">
			<tr>
				<td width="20%">Tanggal Transaksi</td>
				<td>: ' . $masapajak . '</td>
			</tr>
			<tr>
				<td width="20%">Tanggal Transaksi</td>
				<td>: ' . $wp['wajibpajak_npwpd'] . '</td>
			</tr>
			<tr>
				<td width="20%">ALAMAT</td>
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
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">Waktu</th>
				<th class="t-center">No Transaksi</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Service Charge</th>
				<th class="t-center">Diskon</th>
				<th class="t-center">Lain-Lain</th>
				<th class="t-center">Pajak(Rp)</th>
				<th class="t-center">Total</th>
			</tr>';
		$ops = $this->realisasidetail->select(['filters_static' => $where])['data'];
		$no = $total = $tbl_no = 1;
		$opstotal = [
			'realisasi_detail_sub_total' => 0,
			'realisasi_detail_jasa' => 0,
			'realisasi_detail_pajak' => 0,
			'realisasi_detail_total' => 0,
		];
		foreach ($ops as $key => $value) {
			$html .= '<tr>
					<td>' . $tbl_no . '</td>
					<td>' . $value['realisasi_detail_time'] . '</td>
					<td>' . $value['realisasi_detail_penjualan_kode'] . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_detail_sub_total']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_detail_jasa']) . '</td>
					<td style="text-align: right;">0</td>
					<td style="text-align: right;">0</td>
					<td style="text-align: right;">' . number_format($value['realisasi_detail_pajak']) . '</td>
					<td style="text-align: right;">' . number_format($value['realisasi_detail_total']) . '</td>
				</tr>';
			$tbl_no++;
			$no++;
			if ($hal == 1) $total = 60;
			else $total = 80;
			if ($no > $total) {
				$no = 1;
				$hal++;
				$html .= '</table><div style="page-break-after: always"></div>' . $this->headerRincian($dtCaption, $hal);
			}
			$opstotal['realisasi_detail_sub_total'] += $value['realisasi_detail_sub_total'];
			$opstotal['realisasi_detail_jasa'] += $value['realisasi_detail_jasa'];
			$opstotal['realisasi_detail_pajak'] += $value['realisasi_detail_pajak'];
			$opstotal['realisasi_detail_total'] += $value['realisasi_detail_total'];
		}

		$html .= '<tr>
			<td colspan="3"></td>
			<td style="text-align: right;"><b>' . number_format($opstotal['realisasi_detail_sub_total']) . '</b></td>
			<td style="text-align: right;"><b>' . number_format($opstotal['realisasi_detail_jasa']) . '</b></td>
			<td style="text-align: right;"><b>0</b></td>
			<td style="text-align: right;"><b>0</b></td>
			<td style="text-align: right;"><b>' . number_format($opstotal['realisasi_detail_pajak']) . '</b></td>
			<td style="text-align: right;"><b>' . number_format($opstotal['realisasi_detail_total']) . '</b></td>
		</tr></table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Realisasi Pajak',
			'title'         => 'Realisasi Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function headerRincian($txt, $hal)
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
				<th class="t-center">No Transaksi</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Service Charge</th>
				<th class="t-center">Diskon</th>
				<th class="t-center">Lain-Lain</th>
				<th class="t-center">Pajak(Rp)</th>
				<th class="t-center">Total</th>
			</tr>';
	}

	function edit_sub_periode()
	{
		$data = varPost();
		$id = $data['modal-realisasi_id'];
		$wajibpajak_npwpd = $data['modal-wajibpajak_npwpd'];
		$pegawai_id = $this->session->userdata('pegawai_id');

		$user = [
			'wajibpajak_npwpd' => $wajibpajak_npwpd,
		];
		$npwpd = $wajibpajak_npwpd;
		$sum_subtotal = $data['sum_subtotal'];
		$sum_service = $data['sum_service'];
		$sum_tax = $data['sum_tax'];
		$sum_total = $data['sum_total'];

		$sum_subtotal = preg_replace('/\D/', '', $sum_subtotal);
		$sum_service = preg_replace('/\D/', '', $sum_service);
		$sum_tax = preg_replace('/\D/', '', $sum_tax);
		$sum_total = preg_replace('/\D/', '', $sum_total);

		$realisasi_id = $id;

		$del_realisasi_detail = $this->db->where('realisasi_detail_parent = \'' . $realisasi_id . '\'')->delete('pajak_realisasi_detail');

		foreach ($data['time'] as $key => $value) {
			$kode = $data['receiptno'][$key];
			$subtotal = $data['subtotal'][$key];
			$tax = $data['tax'][$key];
			$total = $data['total'][$key];
			$service = $data['service'][$key];
			$subtotal = preg_replace('/\D/', '', $subtotal);
			$tax = preg_replace('/\D/', '', $tax);
			$total = preg_replace('/\D/', '', $total);
			$service = preg_replace('/\D/', '', $service);
			$batch[] = [
				'realisasi_detail_id' => gen_uuid($this->realisasidetail->get_table()),
				'realisasi_detail_npwpd' => $user['wajibpajak_npwpd'],
				'realisasi_detail_parent' => $realisasi_id,
				'realisasi_detail_time' => $value,
				'realisasi_detail_penjualan_kode' => $kode,
				'realisasi_detail_sub_total' => $subtotal,
				'realisasi_detail_jasa' => $service,
				'realisasi_detail_pajak' => $tax,
				'realisasi_detail_total' => $total,
			];
		}
		// die(json_encode($batch));
		$ops = $this->db->insert_batch('pajak_realisasi_detail', $batch);

		$ops = $this->db->where('realisasi_id = \'' . $realisasi_id . '\'')
			->update('pajak_realisasi', [
				'realisasi_sub_total' => $sum_subtotal,
				'realisasi_jasa' => $sum_service,
				'realisasi_pajak' => $sum_tax,
				'realisasi_total' => $sum_total,
			]);

		if ($ops === FALSE) {
			return $this->response([
				'success' => false,
				'message' => 'Gagal menyimpan periode realisasi'
			]);
		}

		return $this->response([
			"success" => true,
			"message" => "Berhasil edit periode realisasi"
		]);
	}

	function delete_sub_periode()
	{
		$data = varPost();
		$id = $data['realisasi_id'];

		$ops = $this->db->query("UPDATE pajak_realisasi
		SET realisasi_deleted_at='" . date('Y-m-d H:i:s') . "'
		WHERE realisasi_id='" . $id . "';");

		if ($ops === FALSE) {
			return $this->response([
				'success' => false,
				'message' => 'Gagal menghapus periode realisasi'
			]);
		}

		return $this->response([
			"success" => true,
			"message" => "Berhasil menghapus periode realisasi"
		]);
	}
}

/* End of file realisasi.php */
/* Location: ./application/modules/realisasipajak/controllers/realisasi.php */
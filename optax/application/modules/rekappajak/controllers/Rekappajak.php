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

	public function readWp()
	{
		$data = varPost();
		$ops = $this->wajibpajak->read(['wajibpajak_id' => $data['wajibpajak_id']]);
		$this->response($ops);
	}

	public function loadData()
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
			$opr['sumber_data'] = $sumber_data;
			foreach ($opr['aaData'] as &$row) {
				$row['trx_id']    = $row['penjualan_id'];
				$row['trx_kode']  = $row['penjualan_kode'];
				$row['trx_tgl']   = $row['penjualan_tanggal'];
				$row['trx_time']  = $row['penjualan_created'];
				$row['trx_total'] = $row['penjualan_total_grand'];
				$row['trx_jasa'] = $row['penjualan_jasa'] * $row['penjualan_total_harga'];
				$row['trx_diskon'] = $row['penjualan_total_potongan_persen'] * $row['penjualan_total_harga'];
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
			$opr['sumber_data'] = $sumber_data;
			foreach ($opr['aaData'] as &$row) {
				$row['trx_id']    = $row['realisasi_id'];
				$row['trx_kode']  = $row['realisasi_no'];
				$row['trx_tgl']   = $row['realisasi_tanggal'];
				$row['trx_time']  = $row['realisasi_tanggal'];
				$row['trx_total'] = $row['realisasi_total'];
				$row['trx_jasa'] = $row['realisasi_jasa'] * $row['realisasi_sub_total'];
				$row['trx_diskon'] = $row['realisasi_diskon'] * $row['realisasi_sub_total'];
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

	public function spreadsheet_rekap()
	{
		$post  = varPost();
		$where = [];
		try {
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
			$data = $this->db
				->where($where)
				->order_by('tanggal_last_transaksi', 'DESC')
				->get('v_rekap_pajak')
				->result_array();

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$sheet->mergeCells('A1:G1');
			$sheet->setCellValue('A1', 'REKAP OBJEK PAJAK');

			$sheet->getStyle('A1')->applyFromArray([
				'font' => ['bold' => true],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => ['argb' => 'EAEAEA']
				]
			]);

			foreach (range('A', 'G') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$headerRow = 3;
			$sheet->setCellValue("A{$headerRow}", 'No');
			$sheet->setCellValue("B{$headerRow}", 'NPWPD');
			$sheet->setCellValue("C{$headerRow}", 'Nama WP');
			$sheet->setCellValue("D{$headerRow}", 'Jenis Pajak');
			$sheet->setCellValue("E{$headerRow}", 'Kecamatan');
			$sheet->setCellValue("F{$headerRow}", 'Transaksi Terakhir');
			$sheet->setCellValue("G{$headerRow}", 'Jenis Device');

			$sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
				'font' => ['bold' => true],
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
					]
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => ['argb' => 'EAEAEA']
				]
			]);

			$row = $headerRow;
			foreach ($data as $i => $val) {
				$row++;

				$sheet->setCellValue("A{$row}", $i + 1);
				$sheet->setCellValue("B{$row}", $val['npwpd'] ?? '-');
				$sheet->setCellValue("C{$row}", $val['nama_wp'] ?? '-');
				$sheet->setCellValue("D{$row}", $val['jenis_nama'] ?? '-');
				$sheet->setCellValue("E{$row}", $val['kecamatan_nama'] ?? '-');
				$sheet->setCellValue("F{$row}", $val['tanggal_last_transaksi'] ?? '-');
				$sheet->setCellValue("G{$row}", $val['jenis_device'] ?? '-');
			}

			$sheet->getStyle("A{$headerRow}:G{$row}")->applyFromArray([
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
					]
				]
			]);
			$filename = 'Rekap Pajak - ' . $this->session->userdata("pegawai_nama") . ' - ' . date('Ymd-His') . '.xlsx';

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Cache-Control: max-age=0');
			header('Pragma: public');

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			$writer->save('php://output');
			exit;
		} catch (\Throwable $th) {
			return $this->response([
				'success' => false,
				'message' => $th->getMessage()
			]);
		}
	}


	public function spreadsheet_rincirekap()
	{
		$wajibpajak_id = varPost('wajibpajak_id');
		$sumber_data   = varPost('sumber_data');
		$periode       = varPost('periode');

		$startdate = date('Y-m-d 00:00:00');
		$enddate   = date('Y-m-d 23:59:59');

		if (!$wajibpajak_id || !$sumber_data) {
			show_error('Parameter tidak lengkap (wajibpajak_id / sumber_data)', 400);
		}

		if ($periode) {
			$arr = explode(' - ', $periode);
			if (count($arr) === 2) {
				$startdate = date('Y-m-d 00:00:00', strtotime($arr[0]));
				$enddate   = date('Y-m-d 23:59:59', strtotime($arr[1]));
			}
		}
		if ($sumber_data === 'POS') {

			$this->db->select('
				pp.penjualan_id AS trx_id,
				pp.penjualan_kode AS trx_kode,
				pp.penjualan_tanggal AS trx_tgl,
				pp.penjualan_created AS trx_time,
				pp.penjualan_total_harga AS trx_subtotal,
				(pp.penjualan_jasa * pp.penjualan_total_harga) AS trx_jasa,
				(pp.penjualan_total_potongan_persen * pp.penjualan_total_harga) AS trx_diskon,
				pp.penjualan_total_grand AS trx_total
			');
			$this->db->from('pos_penjualan pp');
			$this->db->where('pp.wajibpajak_id', $wajibpajak_id);
			$this->db->where('pp.penjualan_deleted_at IS NULL', null, false);
			$this->db->where('pp.penjualan_tanggal >=', $startdate);
			$this->db->where('pp.penjualan_tanggal <=', $enddate);

			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$this->db->where("
					EXISTS (
						SELECT 1 FROM pajak_wajibpajak pw
						WHERE pw.wajibpajak_id = pp.wajibpajak_id
						AND pw.pemda_id = {$pemda_id}
					)
				", null, false);
			}

			$this->db->order_by('pp.penjualan_tanggal', 'DESC');
			$this->db->order_by('pp.penjualan_created', 'DESC');

			$rows = $this->db->get()->result_array();
		} else {

			$this->db->select('
				pr.realisasi_id AS trx_id,
				pr.realisasi_no AS trx_kode,
				pr.realisasi_tanggal AS trx_tgl,
				pr.realisasi_tanggal AS trx_time,
				pr.realisasi_sub_total AS trx_subtotal,
				(pr.realisasi_jasa * pr.realisasi_sub_total) AS trx_jasa,
				(pr.realisasi_diskon * pr.realisasi_sub_total) AS trx_diskon,
				pr.realisasi_total AS trx_total
			');
			$this->db->from('pajak_realisasi pr');
			$this->db->where('pr.realisasi_wajibpajak_id', $wajibpajak_id);
			$this->db->where('pr.realisasi_tanggal >=', $startdate);
			$this->db->where('pr.realisasi_tanggal <=', $enddate);

			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$this->db->where("
					EXISTS (
						SELECT 1 FROM pajak_wajibpajak pw
						WHERE pw.wajibpajak_id = pr.realisasi_wajibpajak_id
						AND pw.pemda_id = {$pemda_id}
					)
				", null, false);
			}

			$this->db->order_by('pr.realisasi_tanggal', 'DESC');
			$this->db->order_by('pr.realisasi_id', 'DESC');

			$rows = $this->db->get()->result_array();
		}

		$wp = $this->db
			->get_where('v_pajak_toko', ['wajibpajak_id' => $wajibpajak_id])
			->row();

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		foreach (range('A', 'J') as $c) {
			$sheet->getColumnDimension($c)->setAutoSize(true);
		}

		$sheet->mergeCells('A1:J1');
		$sheet->setCellValue('A1', 'RINCIAN REKAP PAJAK');
		$sheet->getStyle('A1')->applyFromArray([
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => ['argb' => 'EAEAEA']
			]
		]);

		$sheet->setCellValue('A3', 'NPWPD');
		$sheet->setCellValue('C3', $wp->wajibpajak_npwpd);

		$sheet->setCellValue('A4', 'Nama Objek Pajak');
		$sheet->setCellValue('C4', $wp->toko_nama);

		$sheet->setCellValue('A5', 'PIC');
		$sheet->setCellValue('C5', $wp->wajibpajak_nama_penanggungjawab);

		$sheet->setCellValue('A6', 'Periode');
		$sheet->setCellValue('C6', $periode);

		$header = 7;
		$cols = [
			'A' => 'No',
			'B' => 'Nama Toko',
			'C' => 'Tanggal',
			'D' => 'Waktu',
			'E' => 'Kode',
			'F' => 'Subtotal',
			'G' => 'Jasa',
			'H' => 'Diskon',
			'I' => 'Pajak',
			'J' => 'Total',
		];

		foreach ($cols as $col => $label) {
			$sheet->setCellValue($col . $header, $label);
			$sheet->getStyle($col . $header)->getFont()->setBold(true);
		}

		$row = $header;
		foreach ($rows as $i => $r) {
			$row++;

			$jasa   = (float) $r['trx_jasa'];
			$diskon = (float) $r['trx_diskon'];
			$pajak  = $r['trx_subtotal'] / 10;

			$sheet->setCellValue("A{$row}", $i + 1);
			$sheet->setCellValue("B{$row}", $wp->toko_nama);
			$sheet->setCellValue("C{$row}", date('d-m-Y', strtotime($r['trx_tgl'])));
			$sheet->setCellValue("D{$row}", date('H:i', strtotime($r['trx_time'])));
			$sheet->setCellValue("E{$row}", $r['trx_kode']);
			$sheet->setCellValue("F{$row}", $r['trx_subtotal']);
			$sheet->setCellValue("G{$row}", $jasa);
			$sheet->setCellValue("H{$row}", $diskon);
			$sheet->setCellValue("I{$row}", $pajak);
			$sheet->setCellValue("J{$row}", $r['trx_total']);
		}
		$filename = 'Rincian Rekap Pajak - ' . $wp->toko_nama  . ' - ' . date('Ymd-His') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	public function pdf_rekap()
	{
		$post = varPost();
		$where = [];

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
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Nama WP</th>
				<th class="t-center">Jenis Pajak</th>
				<th class="t-center">Kecamatan</th>
				<th class="t-center">Transaksi Terakhir</th>
				<th class="t-center">Jenis Device</th>
			</tr>';
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
		$data = $this->db
			->where($where)
			->order_by('tanggal_last_transaksi', 'DESC')
			->get('v_rekap_pajak')
			->result_array();
		$no = $total = $tbl_no = 1;

		foreach ($data as $key => $value) {
			$html .= '<tr>
					<td>' . $tbl_no . '</td>
					<td>' . $value['npwpd'] . '</td>
					<td>' . $value['nama_wp'] . '</td>
					<td>' . $value['jenis_nama'] . '</td>
					<td>' . $value['kecamatan_nama'] . '</td>
					<td>' . $value['tanggal_last_transaksi'] . '</td>
					<td>' . $value['jenis_device'] . '</td>					
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

	public function pdf_rincirekap()
	{
		$wajibpajak_id = varPost('wajibpajak_id');
		$sumber_data   = varPost('sumber_data');
		$periode       = varPost('periode');

		if (!$wajibpajak_id || !$sumber_data) {
			show_error('Parameter tidak lengkap', 400);
		}

		$startdate = date('Y-m-d 00:00:00');
		$enddate   = date('Y-m-d 23:59:59');

		if ($periode) {
			$arr = explode(' - ', $periode);
			if (count($arr) === 2) {
				$startdate = date('Y-m-d 00:00:00', strtotime($arr[0]));
				$enddate   = date('Y-m-d 23:59:59', strtotime($arr[1]));
			}
		}

		if ($sumber_data === 'POS') {

			$this->db->select('
            pp.penjualan_tanggal   AS trx_tgl,
            pp.penjualan_created   AS trx_time,
            pp.penjualan_kode      AS trx_kode,
            pp.penjualan_total_harga AS trx_subtotal,
            (pp.penjualan_jasa * pp.penjualan_total_harga) AS trx_jasa,
            (pp.penjualan_total_potongan_persen * pp.penjualan_total_harga) AS trx_diskon,
            pp.penjualan_total_grand AS trx_total
        ');
			$this->db->from('pos_penjualan pp');
			$this->db->where('pp.wajibpajak_id', $wajibpajak_id);
			$this->db->where('pp.penjualan_deleted_at IS NULL', null, false);
			$this->db->where("pp.penjualan_tanggal BETWEEN '{$startdate}' AND '{$enddate}'", null, false);
			$this->db->order_by('pp.penjualan_tanggal', 'ASC');

			$rows = $this->db->get()->result_array();
		} else {

			$this->db->select('
            pr.realisasi_tanggal AS trx_tgl,
            pr.realisasi_tanggal AS trx_time,
            pr.realisasi_no      AS trx_kode,
            pr.realisasi_sub_total AS trx_subtotal,
            (pr.realisasi_jasa * pr.realisasi_sub_total) AS trx_jasa,
            (pr.realisasi_diskon * pr.realisasi_sub_total) AS trx_diskon,
            pr.realisasi_total   AS trx_total
        ');
			$this->db->from('pajak_realisasi pr');
			$this->db->where('pr.realisasi_wajibpajak_id', $wajibpajak_id);
			$this->db->where('pr.realisasi_deleted_at IS NULL', null, false);
			$this->db->where("pr.realisasi_tanggal BETWEEN '{$startdate}' AND '{$enddate}'", null, false);
			$this->db->order_by('pr.realisasi_tanggal', 'ASC');

			$rows = $this->db->get()->result_array();
		}

		$wp = $this->db
			->get_where('v_pajak_toko', ['wajibpajak_id' => $wajibpajak_id])
			->row();

		$total_subtotal = $total_jasa = $total_diskon = $total_pajak = $total_total = 0;

		foreach ($rows as $r) {
			$pajak = $r['trx_subtotal'] / 10;

			$total_subtotal += $r['trx_subtotal'];
			$total_jasa     += $r['trx_jasa'];
			$total_diskon   += $r['trx_diskon'];
			$total_pajak    += $pajak;
			$total_total    += $r['trx_total'];
		}

		$html = '<h3 style="text-align:center">RINCIAN REKAP PAJAK</h3>
		<table width="100%" cellpadding="4">
			<tr><td width="20%">NPWPD</td><td>: ' . $wp->wajibpajak_npwpd . '</td></tr>
			<tr><td>Nama Toko</td><td>: ' . $wp->toko_nama . '</td></tr>
			<tr><td>PIC</td><td>: ' . $wp->wajibpajak_nama_penanggungjawab . '</td></tr>
			<tr><td>Periode</td><td>: ' . $periode . '</td></tr>
		</table><br>';

		$html .= '<table border="1" width="100%" cellpadding="4" cellspacing="0">
        <tr style="background:#eee">
            <th>No</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Kode</th>
            <th>Subtotal</th>
            <th>Jasa</th>
            <th>Diskon</th>
            <th>Pajak</th>
            <th>Total</th>
        </tr>';

		foreach ($rows as $i => $r) {
			$pajak = $r['trx_subtotal'] / 10;
			$html .= '<tr>
				<td align="center">' . ($i + 1) . '</td>
				<td>' . date('d-m-Y', strtotime($r['trx_tgl'])) . '</td>
				<td>' . date('H:i', strtotime($r['trx_time'])) . '</td>
				<td>' . $r['trx_kode'] . '</td>
				<td align="right">' . number_format($r['trx_subtotal']) . '</td>
				<td align="right">' . number_format($r['trx_jasa']) . '</td>
				<td align="right">' . number_format($r['trx_diskon']) . '</td>
				<td align="right">' . number_format($pajak) . '</td>
				<td align="right">' . number_format($r['trx_total']) . '</td>
			</tr>';
		}

		$html .= '<tr style="font-weight:bold">
			<td colspan="4" align="center">TOTAL</td>
			<td align="right">' . number_format($total_subtotal) . '</td>
			<td align="right">' . number_format($total_jasa) . '</td>
			<td align="right">' . number_format($total_diskon) . '</td>
			<td align="right">' . number_format($total_pajak) . '</td>
			<td align="right">' . number_format($total_total) . '</td>
		</tr>
		</table>';

		createPdf([
			'data'       => $html,
			'json'       => true,
			'paper_size' => 'A4',
			'file_name'  => 'Rincian Rekap Pajak',
			'title'      => 'Rincian Rekap Pajak',
			'margin'     => '10 10 10 10',
			'font_size'  => '10'
		]);
	}
}

/* End of file realisasi.php */
/* Location: ./application/modules/rekappajak/controllers/realisasi.php */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanretur extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'returpembelian/ReturpembelianModel' 		=> 'returpembelian',
			'returpembelian/ReturpembeliandetailModel' 	=> 'returpembeliandetail',
			'returpenjualan/ReturpenjualanModel' 		=> 'returpenjualan',
			'returpenjualan/ReturpenjualandetailModel' 	=> 'returpenjualandetail',
		));
	}

	public function header_retur_beli($txt, $hal)
	{
		return '<table>
			<tr>
				<td>' . $txt . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">TGL.</th>
				<th class="t-center">NO. BUKTI</th>
				<th class="t-center">KODE</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">QTY</th>
				<th class="t-center">ST/ISI</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">JML. HARGA</th>
				<th class="t-center">TOTAL</th>
			</tr>';
	}

	public function laporan_retur_beli()
	{
		$data = varPost();
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
			$filter = ['retur_pembelian_tanggal = to_date(\'' . $data['bulan'] . '\', \'YYYY-MM\') ' => NULL];
		} else {
			$filter = ['retur_pembelian_tanggal = ' => $data['tanggal']];
			$dtCaption = 'Periode : ' . $tanggal;
			if (isset($data['tanggal_sampai']) && $data['tanggal_sampai'] >= $data['tanggal']) {
				$dtCaption .= ' Sampai ' . $tanggal_sampai;
				$filter = [
					'retur_pembelian_tanggal >=' => $data['tanggal'],
					'retur_pembelian_tanggal <=' => $data['tanggal_sampai']
				];
				// $filter[] = ['DATE_FORMAT(retur_pembelian_tanggal, "%Y-%m-%d") <=' => $data['tanggal_sampai']];
			}
		}
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ------ ---</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN RETUR PEMBELIAN BARANG </h4><br>
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
				<th class="t-center">NO. BUKTI</th>
				<th class="t-center">KODE</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">QTY</th>
				<th class="t-center">ST/ISI</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">JML. HARGA</th>
				<th class="t-center">TOTAL</th>
			</tr>';
		$retur = $this->db->select('retur_pembelian_id,retur_pembelian_tanggal, retur_pembelian_kode, retur_pembelian_total')
			->from('pos_retur_pembelian_barang')
			->where($filter)
			->order_by('retur_pembelian_kode', 'asc')
			->get()->result_array();
		// print_r($retur);exit;
		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		$color = '';
		foreach ($retur as $key => $value) {
			if ($tgl == $value['retur_pembelian_tanggal']) $tanggal = '';
			else $tgl = $tanggal = $value['retur_pembelian_tanggal'];
			if ($key % 2 == 0) {
				$color = "";
			} else {
				$color = "background-color:#c6ccc8";
			}
			$detail =  $this->db->select('barang_kode, barang_nama, retur_pembelian_detail_retur_qty, retur_pembelian_detail_qty_barang, retur_pembelian_detail_harga, retur_pembelian_detail_jumlah,barang_satuan_konversi, retur_pembelian_detail_satuan_kode')
				->from('v_pos_retur_pembelian_barang_detail')
				->where('retur_pembelian_detail_parent', $value['retur_pembelian_id'])
				->order_by('retur_pembelian_detail_order', 'asc')
				->get()->result_array();
			// echo $this->db->last_query();
			$html .= '<tr style="' . $color . '">
					<td>' . (($tanggal) ? date('d/m/Y', strtotime($tanggal)) : '') . '</td>
					<td>' . $value['retur_pembelian_kode'] . '</td>';
			foreach ($detail as $k => $v) {
				if ($k > 0) {
					$html .= '<tr style="' . $color . '">
						<td></td>
						<td></td>';
				}
				$satuan = $v['retur_pembelian_detail_satuan_kode'] . "(" . $v['retur_pembelian_detail_satuan_kode'] . ")";
				$html .= '
							<td>' . $v['barang_kode'] . '</td>
							<td>' . $v['barang_nama'] . '</td>
							<td class="right">' . $v['retur_pembelian_detail_retur_qty'] . '</td>
							<td>' . ($satuan != "()" ? $satuan : "") . '</td>
							<td class="right">' . number_format($v['retur_pembelian_detail_harga']) . '</td>
							<td class="right">' . number_format($v['retur_pembelian_detail_jumlah']) . '</td>
							<td></td>
						</tr>';
			}
			if (count($detail) > 1) {
				$html .= '<tr style="' . $color . '">
					<td></td>
					<td></td>
					<td></td>
					<td colspan="5" style="border-top: 1px solid black;border-bottom: 1px solid black;">SubTotal</td>
					<td class="right">' . number_format($value['retur_pembelian_total']) . '</td>
					</tr>';
			}
		}
		$html .= '</table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Retur Produk',
			'title'         => 'Laporan Retur Produk',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}
	public function header_retur_jual()
	{
		return '<table>
			<tr>
				<td>' . $txt . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">TGL.</th>
				<th class="t-center">NO. BUKTI</th>
				<th class="t-center">KODE</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">QTY</th>
				<th class="t-center">ST/ISI</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">JML. HARGA</th>
				<th class="t-center">TOTAL</th>
			</tr>';
	}
	public function laporan_retur_jual()
	{
		$data = varPost();
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
				// border:1px solid black;
				padding-top:10px;
				padding-bottom:10px;
			}
		</style>';
		$dtCaption = '';
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['retur_penjualan_tanggal = to_date(\'' . $data['bulan'] . '\', \'YYYY-MM\') ' => NULL];
		} else {
			$filter = ['retur_penjualan_tanggal =' => $data['tanggal']];
			$dtCaption = 'Periode : ' . $tanggal;
			if (isset($data['tanggal_sampai']) && $data['tanggal_sampai'] >= $data['tanggal']) {
				$dtCaption .= ' Sampai ' . $tanggal_sampai;
				$filter = [
					'retur_penjualan_tanggal >=' => $data['tanggal'],
					'retur_penjualan_tanggal <=' => $data['tanggal_sampai']
				];
			}
		}
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ------ ---</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN RETUR PENJUALAN BARANG </h4><br>
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
				<th class="t-center">NO. BUKTI</th>
				<th class="t-center">KODE</th>
				<th class="t-center">KETERANGAN</th>
				<th class="t-center">QTY</th>
				<th class="t-center">ST/ISI</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">JML. HARGA</th>
				<th class="t-center">TOTAL</th>
			</tr>';
		$retur = $this->db->select('retur_penjualan_id,retur_penjualan_tanggal, retur_penjualan_kode, retur_penjualan_total')
			->from('pos_retur_penjualan_barang')
			->where($filter)
			->order_by('retur_penjualan_created', 'asc')
			->get()->result_array();
		$item = $tunai = $kredit = 0;
		$no = $total = 1;
		$tgl = $tanggal = '';
		$color = '';
		foreach ($retur as $key => $value) {
			if ($tgl == $value['retur_penjualan_tanggal']) $tanggal = '';
			else $tgl = $tanggal = $value['retur_penjualan_tanggal'];
			if ($key % 2 == 0) {
				$color = "";
			} else {
				$color = "background-color:#c6ccc8";
			}
			$detail =  $this->db->select('barang_kode, barang_nama, retur_penjualan_detail_qty,retur_penjualan_detail_retur_qty, retur_penjualan_detail_harga, retur_penjualan_detail_jumlah, retur_penjualan_detail_satuan_kode')
				->from('v_pos_retur_penjualan_detail')
				->where('retur_penjualan_detail_parent', $value['retur_penjualan_id'])
				->order_by('retur_penjualan_detail_order', 'asc')
				->get()->result_array();
			// echo $this->db->last_query();
			$html .= '<tr style="' . $color . '">
					<td>' . (($tanggal) ? date('d/m/Y', strtotime($tanggal)) : '') . '</td>
					<td>' . $value['retur_penjualan_kode'] . '</td>';
			foreach ($detail as $k => $v) {
				if ($k > 0) {
					$html .= '<tr style="' . $color . '">
						<td></td>
						<td></td>';
				}
				$html .= '
							<td>' . $v['barang_kode'] . '</td>
							<td>' . $v['barang_nama'] . '</td>
							<td>' . $v['retur_penjualan_detail_retur_qty'] . '</td>
							<td>' . ($v['retur_penjualan_detail_satuan_kode'] ? $v['retur_penjualan_detail_satuan_kode'] : '') . '</td>
							<td>' . number_format($v['retur_penjualan_detail_harga']) . '</td>
							<td>' . number_format($v['retur_penjualan_detail_jumlah']) . '</td>
							<td></td>
						</tr>';
			}
			if (count($detail) > 1) {
				$html .= '<tr style="' . $color . '">
					<td></td>
					<td></td>
					<td></td>
					<td colspan="5" style="border-top: 1px solid black;border-bottom: 1px solid black;">SubTotal</td>
					<td>' . number_format($value['retur_penjualan_total']) . '</td>
					</tr>';
			}
		}
		// $html .= '<tr>
		// 		<td colspan="2">TOTAL</td>
		// 		<td>'.number_format($item).'</td>
		// 		<td colspan="3"></td>
		// 		<td>'.number_format($tunai).'</td>
		// 		<td>'.number_format($kredit).'</td>
		// 	</tr>';
		$html .= '</table>';
		// echo $html;exit();
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Retur Produk',
			'title'         => 'Laporan Retur Produk',
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
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$tanggal_sampai = date('d/m/Y', strtotime(varPost('tanggal_sampai')));
		$filter = [];
		if (varPost('periode') == 'bulan') {
			$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
			$filter = ['retur_pembelian_tanggal = to_date(\'' . $data['bulan'] . '\', \'YYYY-MM\') ' => NULL];
		} else {
			$filter = ['retur_pembelian_tanggal = ' => $data['tanggal']];
			$dtCaption = 'Periode : ' . $tanggal;
			if (isset($data['tanggal_sampai']) && $data['tanggal_sampai'] >= $data['tanggal']) {
				$dtCaption .= ' Sampai ' . $tanggal_sampai;
				$filter = [
					'retur_pembelian_tanggal >=' => $data['tanggal'],
					'retur_pembelian_tanggal <=' => $data['tanggal_sampai']
				];
			}
		}
		$ops = $this->db->select('retur_pembelian_id,retur_pembelian_tanggal, retur_pembelian_kode, retur_pembelian_total')
			->from('pos_retur_pembelian_barang')
			->where($filter)
			->order_by('retur_pembelian_kode', 'asc')
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
			$sheet->setCellValue('A1', 'LAPORAN RETUR');
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
			$sheet->setCellValue('A2', 'TGL.');
			$sheet->setCellValue('B2', 'NO. BUKTI');
			$sheet->setCellValue('C2', 'KODE');
			$sheet->setCellValue('D2', 'KETERANGAN');
			$sheet->setCellValue('E2', 'QTY');
			$sheet->setCellValue('F2', 'ST/ISI');
			$sheet->setCellValue('G2', 'HARGA');
			$sheet->setCellValue('H2', 'JML. HARGA');
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
			// foreach ($ops as $key => $value) {

			// 	$no += 1;
			// 	$sheet->setCellValue('A' . $no, $key + 1);
			// 	$sheet->setCellValue('B' . $no, $value['pembelian_tanggal']);
			// 	$sheet->setCellValue('C' . $no, $value['pembelian_kode']);
			// 	$sheet->setCellValue('D' . $no, $value['pembelian_jumlah_item']);
			// 	$sheet->setCellValue('E' . $no, $value['pembelian_jatuh_tempo']);
			// 	$sheet->setCellValue('F' . $no, $value['supplier_kode']);
			// 	$sheet->setCellValue('G' . $no,  number_format(($value['pembelian_bayar_opsi'] == 'T' ? $value['pembelian_bayar_grand_total'] : 0)));
			// 	$sheet->setCellValue('H' . $no, number_format(($value['pembelian_bayar_opsi'] == 'K' ? $value['pembelian_bayar_grand_total'] : 0)));
			// }
			$sheet->getStyle('A3:I' . $no)->applyFromArray($styleArray);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'laporanretur-' . date('d-m-y_H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/laporan_retur/')) {
				mkdir(FCPATH . 'assets/laporan/laporan_retur/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/laporan_retur/' . $filename;
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

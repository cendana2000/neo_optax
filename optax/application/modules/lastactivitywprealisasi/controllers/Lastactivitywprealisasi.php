<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lastactivitywprealisasi extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'LastactivitywpRealisasiModel'                       => 'lastactivitywprealisasi',
        ));
    }

    public function index()
    {
        $data = varPost();
        $where = [];
        $var = $this->select_dt(varPost(), 'lastactivitywprealisasi', 'table', false, $where);
        $this->response(
            $var
        );
    }

    public function get($val = 'all')
    {
        $data = varPost();
        $where = [];
        if ($val == 'active') $where['status_active'] = 'Active';
        if ($val == 'inactive') $where['status_active'] = 'Inactive';
        if ($val == 'offline') $where['status_active'] = 'Offline';
        $var = $this->select_dt(varPost(), 'lastactivitywprealisasi', 'table', false, $where);
        $this->response(
            $var
        );
    }



    public function pdf($val = 'all')
    {
        $ops = $this->db->query(
            'SELECT * 
			from v_realisasi_last_activity'
        )->result_array();

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
					<p>OPTAX</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>LAST ACTIVITY WAJIB PAJAK</h4><br>
				</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>				
				<th class="t-center">Nama WP</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Tanggal Transaksi Terakhir</th>
				<th class="t-center">Status</th>
			</tr>';

        $no = $total = $tbl_no = 1;

        foreach ($ops as $key => $value) {
            $html .= '<tr>
					<td style="text-align:center">' . $tbl_no . '</td>					
					<td>' . $value["wajibpajak_nama"] . '</td>
					<td>' . $value["wajibpajak_npwpd"] . '</td>
					<td>' . $value["realisasi_tanggal"] . '</td>
					<td>' . $value["status_active"] . '</td>
				</tr>';
            $tbl_no++;
            $no++;
            if ($hal == 1) $total = 46;
            else $total = 50;
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
				<th class="t-center">No</th>				
				<th class="t-center">Nama WP</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Tanggal Realisasi Terakhir</th>
				<th class="t-center">Status</th>
			</tr>';
    }

    public function spreadsheet()
    {
        $ops = $this->db->query(
            'SELECT * 
			from v_realisasi_last_activity'
        )->result_array();

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
            $sheet->setCellValue('A1', 'LAST ACTIVITY WAJIB PAJAK');
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
            $sheet->setCellValue('A2', 'No');
            $sheet->setCellValue('B2', 'Nama WP');
            $sheet->setCellValue('C2', 'NPWPD');
            $sheet->setCellValue('D2', 'Tanggal Transaksi Terakhir');
            $sheet->setCellValue('E2', 'Status');

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
                $sheet->setCellValue('B' . $no, $value["wajibpajak_nama"]);
                $sheet->setCellValue('C' . $no, $value["wajibpajak_npwpd"]);
                $sheet->setCellValue('D' . $no, $value["tanggal_last_transaksi"]);
                $sheet->setCellValue('E' . $no, $value["status_active"]);
            }

            $sheet->getStyle('A2:F' . $no)->applyFromArray($styleArray);

            // Write a new .xlsx file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            // Save the new .xlsx file
            $filename = 'lastactivitywp_realisasi-' . date('d-m-y_H:i:s') . '.xlsx';
            if (!file_exists(FCPATH . 'assets/laporan/lastactivitywp_realisasi/')) {
                mkdir(FCPATH . 'assets/laporan/lastactivitywp_realisasi/', 0777, true);
            }
            $file = FCPATH . 'assets/laporan/lastactivitywp_realisasi/' . $filename;
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

/* End of file Pricelist.php */
/* Location: ./application/modules/pricelist/controllers/Pricelist.php */
<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rekonsiliasi extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'RekonsiliasiModel' => 'rekonsiliasi'
        ));
    }

    public function index()
    {
        $post       = varPost();
        $tahun      = varPost('filter_tahun', date('Y'));
        $limit      = varPost('length', 50);
        $offset     = varPost('start', 0);
        $search     = $post['search']['value'];

        $columns    = [
            1    => 'wp.wajibpajak_npwpd',
            2    => 'wp.wajibpajak_nama',
            3    => 'januari',
            6    => 'februari',
            9    => 'maret',
            12   => 'april',
            15   => 'mei',
            18   => 'juni',
            21   => 'juli',
            24   => 'agustus',
            27   => 'september',
            30   => 'oktober',
            33   => 'november',
            36   => 'desember',
        ];

        try {
            $sort_col   = $columns[$post['order'][0]['column']];
            $sort_dir   = $post['order'][0]['dir'] ?? 'asc';
        } catch (Exception $_) {
        }

        $sort_col   ??= 'wp.wajibpajak_npwpd';
        $sort_dir   ??= 'asc';

        $datarow    = $this->rekonsiliasi->paginate($tahun, $limit, $offset, $search, $sort_col, $sort_dir);
        $this->response($datarow);
    }

    public function pdf()
    {
        $dtCaption  = '';

        $html       = '<style>
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
			th.t-center{
				vertical-align:middle!important;
				text-align:center;
				background-color : #5a8ed1;
			}
			.t-right{
				vertical-align:middle!important;
				text-align:right;
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
                    <h4>LAPORAN REKONSILIASI</h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right"></td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="t-center" rowspan="2">NPWPD</th>
                    <th class="t-center" rowspan="2">Objek Pajak</th>
                    <th class="t-center" colspan="3">Januari</th>
                    <th class="t-center" colspan="3">Februari</th>
                    <th class="t-center" colspan="3">Maret</th>
                    <th class="t-center" colspan="3">April</th>
                    <th class="t-center" colspan="3">Mei</th>
                    <th class="t-center" colspan="3">Juni</th>
                    <th class="t-center" colspan="3">Juli</th>
                    <th class="t-center" colspan="3">Agustus</th>
                    <th class="t-center" colspan="3">September</th>
                    <th class="t-center" colspan="3">Oktober</th>
                    <th class="t-center" colspan="3">November</th>
                    <th class="t-center" colspan="3">Desember</th>
                </tr>
                <tr>';

        for ($i = 1; $i <= 12; $i++) {
            $html .= '<th class="t-center">Optax</th>
                        <th class="t-center">Pembayaran</th>
                        <th class="t-center">Selisih</th>';
        }

        $html .= '</tr>
            </thead>
            <tbody>';

        $tahun      = varPost('tahun', date('Y'));
        $records    = $this->rekonsiliasi->get($tahun);

        if (empty($records)) {
            $html .= '<tr><td colspan="38" class="t-center">Tidak Ada Data</td></tr>';
        } else {
            foreach ($records as $value) {
                $jan_total      = $value->jan_penjualan + $value->jan_oapi;
                $jan_bayar      = 0;
                $jan_selisih    = $jan_total;
                $feb_total      = $value->feb_penjualan + $value->feb_oapi;
                $feb_bayar      = 0;
                $feb_selisih    = $feb_total;
                $mar_total      = $value->mar_penjualan + $value->mar_oapi;
                $mar_bayar      = 0;
                $mar_selisih    = $mar_total;
                $apr_total      = $value->apr_penjualan + $value->apr_oapi;
                $apr_bayar      = 0;
                $apr_selisih    = $apr_total;
                $mei_total      = $value->mei_penjualan + $value->mei_oapi;
                $mei_bayar      = 0;
                $mei_selisih    = $mei_total;
                $jun_total      = $value->jun_penjualan + $value->jun_oapi;
                $jun_bayar      = 0;
                $jun_selisih    = $jun_total;
                $jul_total      = $value->jul_penjualan + $value->jul_oapi;
                $jul_bayar      = 0;
                $jul_selisih    = $jul_total;
                $agu_total      = $value->agu_penjualan + $value->agu_oapi;
                $agu_bayar      = 0;
                $agu_selisih    = $agu_total;
                $sep_total      = $value->sep_penjualan + $value->sep_oapi;
                $sep_bayar      = 0;
                $sep_selisih    = $sep_total;
                $okt_total      = $value->okt_penjualan + $value->okt_oapi;
                $okt_bayar      = 0;
                $okt_selisih    = $okt_total;
                $nov_total      = $value->nov_penjualan + $value->nov_oapi;
                $nov_bayar      = 0;
                $nov_selisih    = $nov_total;
                $des_total      = $value->des_penjualan + $value->des_oapi;
                $des_bayar      = 0;
                $des_selisih    = $des_total;

                $html .= '<tr>
                            <td>' . $value->wajibpajak_npwpd . '</td>
                            <td>' . $value->wajibpajak_nama . '</td>
                            <td class="t-right">' . number_Format($jan_total) . '</td>
                            <td class="t-right">' . number_Format($jan_bayar) . '</td>
                            <td class="t-right">' . number_Format($jan_selisih) . '</td>
                            <td class="t-right">' . number_Format($feb_total) . '</td>
                            <td class="t-right">' . number_Format($feb_bayar) . '</td>
                            <td class="t-right">' . number_Format($feb_selisih) . '</td>
                            <td class="t-right">' . number_Format($mar_total) . '</td>
                            <td class="t-right">' . number_Format($mar_bayar) . '</td>
                            <td class="t-right">' . number_Format($mar_selisih) . '</td>
                            <td class="t-right">' . number_Format($apr_total) . '</td>
                            <td class="t-right">' . number_Format($apr_bayar) . '</td>
                            <td class="t-right">' . number_Format($apr_selisih) . '</td>
                            <td class="t-right">' . number_Format($mei_total) . '</td>
                            <td class="t-right">' . number_Format($mei_bayar) . '</td>
                            <td class="t-right">' . number_Format($mei_selisih) . '</td>
                            <td class="t-right">' . number_Format($jun_total) . '</td>
                            <td class="t-right">' . number_Format($jun_bayar) . '</td>
                            <td class="t-right">' . number_Format($jun_selisih) . '</td>
                            <td class="t-right">' . number_Format($jul_total) . '</td>
                            <td class="t-right">' . number_Format($jul_bayar) . '</td>
                            <td class="t-right">' . number_Format($jul_selisih) . '</td>
                            <td class="t-right">' . number_Format($agu_total) . '</td>
                            <td class="t-right">' . number_Format($agu_bayar) . '</td>
                            <td class="t-right">' . number_Format($agu_selisih) . '</td>
                            <td class="t-right">' . number_Format($sep_total) . '</td>
                            <td class="t-right">' . number_Format($sep_bayar) . '</td>
                            <td class="t-right">' . number_Format($sep_selisih) . '</td>
                            <td class="t-right">' . number_Format($okt_total) . '</td>
                            <td class="t-right">' . number_Format($okt_bayar) . '</td>
                            <td class="t-right">' . number_Format($okt_selisih) . '</td>
                            <td class="t-right">' . number_Format($nov_total) . '</td>
                            <td class="t-right">' . number_Format($nov_bayar) . '</td>
                            <td class="t-right">' . number_Format($nov_selisih) . '</td>
                            <td class="t-right">' . number_Format($des_total) . '</td>
                            <td class="t-right">' . number_Format($des_bayar) . '</td>
                            <td class="t-right">' . number_Format($des_selisih) . '</td>
                        </tr>';
            }
        }

        $html .= '
            </tbody>
        </table>
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
            'file_name'     => 'Laporan Rekonsiliasi',
            'title'         => 'Laporan Rekonsiliasi',
            'stylesheet'    => 'laporan/print.css',
            'margin'        => '10 5 10 5',
            // 'font_face'     => 'cour',
            'font_size'     => '10',
        ));
    }

    public function spreadsheet()
    {
        try {
            $spreadsheet    = new Spreadsheet();
            $sheet          = $spreadsheet->getActiveSheet();
            $sheet->setShowGridlines(false);

            // Set Header
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::VERTICAL_CENTER,
                ]
            ];
            $sheet->mergeCells('A1:AM1');
            $sheet->setCellValue('A1', 'Laporan Rekonsiliasi');
            $sheet->getStyle('A1')->applyFromArray($styleArray);

            foreach (range('A', 'AM') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }

            $tahun      = varPost('tahun', date('Y'));
            $records    = $this->rekonsiliasi->get($tahun);

            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'eaeaea',
                    ],
                    'endColor' => [
                        'argb' => 'eaeaea',
                    ],
                ],
            ];

            $sheet->mergeCells('A2:A3');
            $sheet->setCellValue('A2', 'No');

            $sheet->mergeCells('B2:B3');
            $sheet->setCellValue('B2', 'NPWPD');

            $sheet->mergeCells('C2:C3');
            $sheet->setCellValue('C2', 'Objek Pajak');

            $sheet->mergeCells('D2:F2');
            $sheet->setCellValue('D2', 'Januari');
            $sheet->setCellValue('D3', 'Optax');
            $sheet->setCellValue('E3', 'Pembayaran');
            $sheet->setCellValue('F3', 'Selisih');

            $sheet->mergeCells('G2:I2');
            $sheet->setCellValue('G2', 'Februari');
            $sheet->setCellValue('G3', 'Optax');
            $sheet->setCellValue('H3', 'Pembayaran');
            $sheet->setCellValue('I3', 'Selisih');

            $sheet->mergeCells('J2:L2');
            $sheet->setCellValue('J2', 'Maret');
            $sheet->setCellValue('J3', 'Optax');
            $sheet->setCellValue('K3', 'Pembayaran');
            $sheet->setCellValue('L3', 'Selisih');

            $sheet->mergeCells('M2:O2');
            $sheet->setCellValue('M2', 'April');
            $sheet->setCellValue('M3', 'Optax');
            $sheet->setCellValue('N3', 'Pembayaran');
            $sheet->setCellValue('O3', 'Selisih');

            $sheet->mergeCells('P2:R2');
            $sheet->setCellValue('P2', 'Mei');
            $sheet->setCellValue('P3', 'Optax');
            $sheet->setCellValue('Q3', 'Pembayaran');
            $sheet->setCellValue('R3', 'Selisih');

            $sheet->mergeCells('S2:U2');
            $sheet->setCellValue('S2', 'Juni');
            $sheet->setCellValue('S3', 'Optax');
            $sheet->setCellValue('T3', 'Pembayaran');
            $sheet->setCellValue('U3', 'Selisih');

            $sheet->mergeCells('V2:X2');
            $sheet->setCellValue('V2', 'Juli');
            $sheet->setCellValue('V3', 'Optax');
            $sheet->setCellValue('W3', 'Pembayaran');
            $sheet->setCellValue('X3', 'Selisih');

            $sheet->mergeCells('Y2:AA2');
            $sheet->setCellValue('Y2', 'Agustus');
            $sheet->setCellValue('Y3', 'Optax');
            $sheet->setCellValue('Z3', 'Pembayaran');
            $sheet->setCellValue('AA3', 'Selisih');

            $sheet->mergeCells('AB2:AD2');
            $sheet->setCellValue('AB2', 'September');
            $sheet->setCellValue('AB3', 'Optax');
            $sheet->setCellValue('AC3', 'Pembayaran');
            $sheet->setCellValue('AD3', 'Selisih');

            $sheet->mergeCells('AE2:AG2');
            $sheet->setCellValue('AE2', 'Oktober');
            $sheet->setCellValue('AE3', 'Optax');
            $sheet->setCellValue('AF3', 'Pembayaran');
            $sheet->setCellValue('AG3', 'Selisih');

            $sheet->mergeCells('AH2:AJ2');
            $sheet->setCellValue('AH2', 'November');
            $sheet->setCellValue('AH3', 'Optax');
            $sheet->setCellValue('AI3', 'Pembayaran');
            $sheet->setCellValue('AJ3', 'Selisih');

            $sheet->mergeCells('AK2:AM2');
            $sheet->setCellValue('AK2', 'Desember');
            $sheet->setCellValue('AK3', 'Optax');
            $sheet->setCellValue('AL3', 'Pembayaran');
            $sheet->setCellValue('AM3', 'Selisih');

            $sheet->getStyle('A2:AM3')->applyFromArray($styleArray);

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $no = 3;
            foreach ($records as $key => $value) {
                $jan_total      = $value->jan_penjualan + $value->jan_oapi;
                $feb_total      = $value->feb_penjualan + $value->feb_oapi;
                $mar_total      = $value->mar_penjualan + $value->mar_oapi;
                $apr_total      = $value->apr_penjualan + $value->apr_oapi;
                $mei_total      = $value->mei_penjualan + $value->mei_oapi;
                $jun_total      = $value->jun_penjualan + $value->jun_oapi;
                $jul_total      = $value->jul_penjualan + $value->jul_oapi;
                $agu_total      = $value->agu_penjualan + $value->agu_oapi;
                $sep_total      = $value->sep_penjualan + $value->sep_oapi;
                $okt_total      = $value->okt_penjualan + $value->okt_oapi;
                $nov_total      = $value->nov_penjualan + $value->nov_oapi;
                $des_total      = $value->des_penjualan + $value->des_oapi;

                $no            += 1;

                $sheet->setCellValue('A' . $no, $key + 1);
                $sheet->setCellValue('B' . $no, $value->wajibpajak_npwpd);
                $sheet->setCellValue('C' . $no, $value->wajibpajak_nama);

                $sheet->setCellValue('D' . $no, $jan_total);
                $sheet->setCellValue('E' . $no, 0);
                $sheet->setCellValueExplicit('F' . $no, "=D$no-E$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('G' . $no, $feb_total);
                $sheet->setCellValue('H' . $no, 0);
                $sheet->setCellValueExplicit('I' . $no, "=G$no-H$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('J' . $no, $mar_total);
                $sheet->setCellValue('K' . $no, 0);
                $sheet->setCellValueExplicit('L' . $no, "=J$no-K$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('M' . $no, $apr_total);
                $sheet->setCellValue('N' . $no, 0);
                $sheet->setCellValueExplicit('O' . $no, "=M$no-N$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('P' . $no, $mei_total);
                $sheet->setCellValue('Q' . $no, 0);
                $sheet->setCellValueExplicit('R' . $no, "=P$no-Q$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('S' . $no, $jun_total);
                $sheet->setCellValue('T' . $no, 0);
                $sheet->setCellValueExplicit('U' . $no, "=S$no-T$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('V' . $no, $jul_total);
                $sheet->setCellValue('W' . $no, 0);
                $sheet->setCellValueExplicit('X' . $no, "=V$no-W$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('Y' . $no, $agu_total);
                $sheet->setCellValue('Z' . $no, 0);
                $sheet->setCellValueExplicit('AA' . $no, "=Y$no-Z$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('AB' . $no, $sep_total);
                $sheet->setCellValue('AC' . $no, 0);
                $sheet->setCellValueExplicit('AD' . $no, "=AB$no-AC$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('AE' . $no, $okt_total);
                $sheet->setCellValue('AF' . $no, 0);
                $sheet->setCellValueExplicit('AG' . $no, "=AE$no-AF$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('AH' . $no, $nov_total);
                $sheet->setCellValue('AI' . $no, 0);
                $sheet->setCellValueExplicit('AJ' . $no, "=AH$no-AI$no", DataType::TYPE_FORMULA);

                $sheet->setCellValue('AK' . $no, $des_total);
                $sheet->setCellValue('AL' . $no, 0);
                $sheet->setCellValueExplicit('AM' . $no, "=AK$no-AL$no", DataType::TYPE_FORMULA);
            }

            $sheet->getStyle('A2:AM' . $no)->applyFromArray($styleArray);

            $writer         = new Xlsx($spreadsheet);
            $filename       = 'laporannotrx-' . date('d-m-y-H-i-s') . '.xlsx';
            $folder         = FCPATH . 'assets/laporan/rekonsiliasi/';
            $file           = $folder . $filename;

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $writer->save($file);

            $this->response([
                'success' => true,
                'file' => $filename
            ]);
        } catch (Throwable $th) {
            $this->response([
                'success'   => false,
                'msg'       => $th->getMessage()
            ]);
        }
    }
}

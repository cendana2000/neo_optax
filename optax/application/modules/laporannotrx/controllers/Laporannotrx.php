<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporannotrx extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'laporannotrx/LaporanNoTrxModel' => 'laporannotrx'
        ));
    }

    public function index()
    {
        $post       = varPost();
        $periode    = varPost('periode');
        $periodearr = explode(' - ', $periode);
        $enddate    = date('Y-m-d');
        $startdate  = date('Y-m-d');

        if (is_array($periodearr) && count($periodearr) > 1) {
            $startdate  = date('Y-m-d', strtotime($periodearr[0]));
            $enddate    = date('Y-m-d', strtotime($periodearr[1]));
        }

        $where      = [];

        $where["pos_no_trx_tanggal >= '$startdate'"]   = null;
        $where["pos_no_trx_tanggal <= '$enddate'"]     = null;

        if (!empty($post['kecamatan_id'])) {
            $where['kecamatan_id']  = $post['kecamatan_id'];
        }

        if (!empty($post['kelurahan_id'])) {
            $where['kelurahan_id'] = $post['kelurahan_id'];
        }

        if (!empty($post['wajibpajak_id'])) {
            $where['wajibpajak_id'] = $post['wajibpajak_id'];
        }

        $this->response(
            $this->select_dt($post, 'laporannotrx', 'table', true, $where)
        );
    }

    public function select_wp()
    {
        $page = varPost('page');
        $page = $page ? (intval($page) - 1) : '0';
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pajak_wajibpajak.pemda_id', $pemda_id);
        }

        if ($q = varPost('q')) {
            $this->db->where("wajibpajak_nama ILIKE '%$q%'");
        }

        $items  = $this->db
            ->select("wajibpajak_id AS id, CONCAT(toko_kode, ' - ', toko_nama) AS text")
            ->join('pajak_toko', 'pajak_toko.toko_wajibpajak_id = pajak_wajibpajak.wajibpajak_id', 'left')
            ->where('wajibpajak_status', '2')
            ->limit(varPost('limit'), $page)
            ->get('pajak_wajibpajak')
            ->result();

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pajak_wajibpajak.pemda_id', $pemda_id);
        }

        if ($q = varPost('q')) {
            $this->db->where("wajibpajak_nama ILIKE '%$q%'");
        }

        $total_count    = $this->db->count_all_results('pajak_wajibpajak');
        $this->response([
            'items'         => $items,
            'total_count'   => $total_count
        ]);
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
                    <h4> LAPORAN TIDAK ADA TRANSAKSI</h4><br>
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
                    <th class="t-center">Tanggal</th>
                    <th class="t-center">NPWPD</th>
                    <th class="t-center">Nama</th>
                    <th class="t-center">Alamat</th>
                    <th class="t-center">Kecamatan</th>
                    <th class="t-center">Kelurahan</th>
                </tr>
            </thead>
            <tbody>';

        $where      = [];

        $post       = varPost();
        $periode    = varPost('periode');
        $periodearr = explode(' - ', $periode);
        $enddate    = date('Y-m-d');
        $startdate  = date('Y-m-d');

        if (is_array($periodearr) && count($periodearr) > 1) {
            $startdate  = date('Y-m-d', strtotime($periodearr[0]));
            $enddate    = date('Y-m-d', strtotime($periodearr[1]));
        }

        $where["pos_no_trx_tanggal >= '$startdate'"]   = null;
        $where["pos_no_trx_tanggal <= '$enddate'"]     = null;

        if (!empty($post['kecamatan_id'])) {
            $where['kecamatan_id']  = $post['kecamatan_id'];
        }

        if (!empty($post['kelurahan_id'])) {
            $where['kelurahan_id'] = $post['kelurahan_id'];
        }

        if (!empty($post['wajibpajak_id'])) {
            $where['wajibpajak_id'] = $post['wajibpajak_id'];
        }

        $fields     = [
            'pos_no_trx_id',
            'wajibpajak_id',
            'pos_no_trx_tanggal',
            'pos_no_trx_created_at',
            'pos_no_trx_updated_at',
            'wajibpajak_npwpd',
            'wajibpajak_nama',
            'wajibpajak_alamat',
            'kecamatan_id',
            'kelurahan_id',
            'kecamatan_nama',
            'kelurahan_nama',
        ];
        $records    = $this->laporannotrx->select([
            'table'             => $this->laporannotrx->get_view(),
            'view_mode'         => 'table',
            'filters_static'    => $where,
            'sort_static'       => 'pos_no_trx_tanggal desc, wajibpajak_npwpd asc, wajibpajak_nama asc',
            'custom_fields'     => implode(',', $fields)
        ])['data'];

        if (empty($records)) {
            $html .= '<tr><td colspan="6" class="t-center">Tidak Ada Data</td></tr>';
        } else {
            foreach ($records as $value) {
                $html .= '<tr>
                            <td class="t-center">' . $value['pos_no_trx_tanggal'] . '</td>
                            <td class="t-center">' . $value['wajibpajak_npwpd'] . '</td>
                            <td class="t-center">' . $value['wajibpajak_nama'] . '</td>
                            <td class="t-center">' . $value['wajibpajak_alamat'] . '</td>
                            <td class="t-center">' . $value['kecamatan_nama'] . '</td>
                            <td class="t-center">' . $value['kelurahan_nama'] . '</td>
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
            'file_name'     => 'Laporan Tidak Ada Transaksi',
            'title'         => 'Laporan Tidak Ada Transaksi',
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
            $sheet->mergeCells('A1:G1');
            $sheet->setCellValue('A1', 'Laporan Tidak ada Transaksi');
            $sheet->getStyle('A1')->applyFromArray($styleArray);

            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }

            $where      = [];

            $post       = varPost();
            $periode    = varPost('periode');
            $periodearr = explode(' - ', $periode);
            $enddate    = date('Y-m-d');
            $startdate  = date('Y-m-d');

            if (is_array($periodearr) && count($periodearr) > 1) {
                $startdate  = date('Y-m-d', strtotime($periodearr[0]));
                $enddate    = date('Y-m-d', strtotime($periodearr[1]));
            }

            $where["pos_no_trx_tanggal >= '$startdate'"]   = null;
            $where["pos_no_trx_tanggal <= '$enddate'"]     = null;

            if (!empty($post['kecamatan_id'])) {
                $where['kecamatan_id']  = $post['kecamatan_id'];
            }

            if (!empty($post['kelurahan_id'])) {
                $where['kelurahan_id'] = $post['kelurahan_id'];
            }

            if (!empty($post['wajibpajak_id'])) {
                $where['wajibpajak_id'] = $post['wajibpajak_id'];
            }

            $fields     = [
                'pos_no_trx_id',
                'wajibpajak_id',
                'pos_no_trx_tanggal',
                'pos_no_trx_created_at',
                'pos_no_trx_updated_at',
                'wajibpajak_npwpd',
                'wajibpajak_nama',
                'wajibpajak_alamat',
                'kecamatan_id',
                'kelurahan_id',
                'kecamatan_nama',
                'kelurahan_nama',
            ];
            $records    = $this->laporannotrx->select([
                'table'             => $this->laporannotrx->get_view(),
                'view_mode'         => 'table',
                'filters_static'    => $where,
                'sort_static'       => 'pos_no_trx_tanggal desc, wajibpajak_npwpd asc, wajibpajak_nama asc',
                'custom_fields'     => implode(',', $fields)
            ])['data'];

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

            $sheet->getStyle('A2:G2')->applyFromArray($styleArray);
            $sheet->setCellValue('A2', 'No');
            $sheet->setCellValue('B2', 'Tanggal');
            $sheet->setCellValue('C2', 'NPWPD');
            $sheet->setCellValue('D2', 'Nama');
            $sheet->setCellValue('E2', 'Alamat');
            $sheet->setCellValue('F2', 'Kecamatan');
            $sheet->setCellValue('G2', 'Kelurahan');

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $no = 2;
            foreach ($records as $key => $value) {
                $no += 1;
                $sheet->setCellValue('A' . $no, $key + 1);
                $sheet->setCellValue('B' . $no, $value['pos_no_trx_tanggal']);
                $sheet->setCellValue('C' . $no, $value['wajibpajak_npwpd']);
                $sheet->setCellValue('D' . $no, $value['wajibpajak_nama']);
                $sheet->setCellValue('E' . $no, $value['wajibpajak_alamat']);
                $sheet->setCellValue('F' . $no, $value['kecamatan_nama']);
                $sheet->setCellValue('G' . $no, $value['kelurahan_nama']);
            }

            $sheet->getStyle('A2:G' . $no)->applyFromArray($styleArray);

            $writer         = new Xlsx($spreadsheet);
            $filename       = 'laporannotrx-' . date('d-m-y-H-i-s') . '.xlsx';
            $folder         = FCPATH . 'assets/laporan/no_trx/';
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

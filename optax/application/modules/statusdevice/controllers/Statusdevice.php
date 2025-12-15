<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Statusdevice extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'statusdevice/LastactivitywpmobileModel'      => 'lastactivity',
            'statusdevice/StatusDeviceModel'              => 'statusdevice',
        ));
    }

    public function index()
    {
        $data = varPost();
        $conf = $this->db
            ->where('conf_code', 'mobile_interval_ping')
            ->get('pajak_config')
            ->row();

        $tolerance = 3;
        $interval  = $conf->conf_value ?? 10;
        $duration  = $interval * $tolerance;

        $max_timestamp = date('Y-m-d H:i:s', strtotime("- $duration minutes"));
        $now           = date('Y-m-d H:i:s');
        $statusDevice = $data['status_device'] ?? null;
        $statusData   = $data['status_data'] ?? null;
        $jenisDevice  = $data['jenis_device'] ?? null;

        $filterQuery = [];

        if (!empty($statusData)) {
            $map = [
                'active'   => 'Active',
                'inactive' => 'Inactive',
                'offline'  => 'Offline',
            ];

            if (isset($map[$statusData])) {
                $filterQuery['status_active'] = $map[$statusData];
            }
        }

        $operation = $this->select_dt($data, 'statusdevice', 'datatable', true, $filterQuery);

        $allRows = $this->db
            ->get($this->statusdevice->get_tablename())
            ->result_array();
        $totalRows = count($allRows);


        $summaryDevice = ['online' => 0, 'offline' => 0, 'warning' => 0];
        $summaryData   = ['active' => 0, 'inactive' => 0, 'offline' => 0];

        foreach ($allRows as $row) {
            $timestamps = array_filter([
                $row['mobile_last_active'],
                $row['web_last_active'],
                $row['toko_desktop_ping_timestamp']
            ]);

            $latest = !empty($timestamps) ? max($timestamps) : null;

            // SUMMARY DEVICE
            if (
                $row['mobile_last_active'] === null &&
                $row['web_last_active'] === null &&
                $row['toko_desktop_ping_timestamp'] === null &&
                $row['toko_is_oapi'] === null &&
                $row['tanggal_last_transaksi'] === null
            ) {
                $summaryDevice['offline']++;
            } elseif ($row['web_last_active'] === null && $row['tanggal_last_transaksi'] !== null && $row['toko_is_oapi'] === null) {
                $summaryDevice['warning']++;
            } else {
                if ($latest !== null && $latest >= $max_timestamp) {
                    $summaryDevice['online']++;
                } else {
                    $summaryDevice['offline']++;
                }
            }

            // SUMMARY DATA
            $tglTrx = $row['tanggal_last_transaksi'];
            $stat   = strtolower($row['status_active']);

            if ($tglTrx === null || $stat === 'offline') {
                $summaryData['offline']++;
            } elseif ($stat === 'inactive') {
                $summaryData['inactive']++;
            } else {
                $summaryData['active']++;
            }
        }

        foreach ($allRows as &$row) {

            // HITUNG JENIS DEVICE
            $mobile = $row['mobile_last_active'];
            $web    = $row['web_last_active'];
            $desk   = $row['toko_desktop_ping_timestamp'];
            $oapi   = $row['toko_is_oapi'];
            $lastTrx = $row['tanggal_last_transaksi'];

            if ($mobile === null && $web === null && $desk === null && $oapi === null && $lastTrx === null) {
                $row['jenis_device'] = 'Website POS';
            } else {
                $candidates = [];

                if ($mobile !== null) $candidates['Mobile POS'] = $mobile;
                if ($web !== null)    $candidates['Website POS'] = $web;
                if ($desk !== null)   $candidates['Desktop Pooling'] = $desk;
                if ($oapi !== null)   $candidates['API Reader'] = $desk;

                if ($web === null && $oapi === null && $lastTrx !== null) {
                    $candidates['Website POS'] = $lastTrx;
                }

                if (!empty($candidates)) {
                    arsort($candidates);
                    $row['jenis_device'] = array_key_first($candidates);
                } else {
                    $row['jenis_device'] = '-';
                }
            }

            // STATUS DEVICE
            $timestamps = array_filter([
                $row['mobile_last_active'],
                $row['web_last_active'],
                $row['toko_desktop_ping_timestamp'],
                $row['tanggal_last_transaksi'],
            ], fn($v) => !empty($v));

            $latest = !empty($timestamps) ? max($timestamps) : null;

            if (
                $row['mobile_last_active'] === null &&
                $row['web_last_active'] === null &&
                $row['toko_desktop_ping_timestamp'] === null &&
                $row['toko_is_oapi'] === null &&
                $row['tanggal_last_transaksi'] === null
            ) {
                $row['status_device'] = '<div class="status-box status-offline">Device Nonaktif</div>';
            } elseif ($row['web_last_active'] === null && $row['tanggal_last_transaksi'] !== null && $row['toko_is_oapi'] === null) {
                $row['status_device'] = '<div class="status-box status-idle">Device Disconnected</div>';
            } elseif ($row['toko_is_oapi'] === 'ACTIVE') {
                if ($latest !== null && $latest >= $max_timestamp && $latest <= $now) {
                    $row['status_device'] = '<div class="status-box status-online">' . $latest . '</div>';
                } else {
                    $row['status_device'] = '<div class="status-box status-offline">' . ($latest ?? '-') . '</div>';
                }
            } else {
                if ($latest !== null && $latest >= $max_timestamp && $latest <= $now) {
                    $row['status_device'] = '<div class="status-box status-online">' . $latest . '</div>';
                } else {
                    $row['status_device'] = '<div class="status-box status-offline">' . ($latest ?? '-') . '</div>';
                }
            }

            // STATUS DATA
            $tglTransaksi = $row['tanggal_last_transaksi'];
            if (!$tglTransaksi) {
                $row['status_data'] = '<div class="status-box status-offline">Tidak Ada Transaksi</div>';
            } else {
                $stat = strtolower($row['status_active']);
                if ($stat === 'active') {
                    $row['status_data'] = '<div class="status-box status-online">' . $tglTransaksi . '</div>';
                } elseif ($stat === 'inactive') {
                    $row['status_data'] = '<div class="status-box status-idle">' . $tglTransaksi . '</div>';
                } else {
                    $row['status_data'] = '<div class="status-box status-offline">' . $tglTransaksi . '</div>';
                }
            }
        }

        $sourceData = $allRows;

        $filtered = [];
        foreach ($sourceData as &$row) {

            if ($jenisDevice && $jenisDevice !== $row['jenis_device']) continue;

            // status_device
            preg_match('/status-(online|offline|idle)/', $row['status_device'], $m);
            $row['status_device_class'] = $m[1] ?? null;

            // status_data
            preg_match('/status-(online|offline|idle)/', $row['status_data'], $m2);
            $row['status_data_class'] = $m2[1] ?? null;

            if ($statusDevice) {
                $mapStatusDevice = [
                    'online'  => 'online',
                    'offline' => 'offline',
                    'warning' => 'idle'
                ];
                if ($row['status_device_class'] !== $mapStatusDevice[$statusDevice]) continue;
            }

            if ($statusData) {
                $mapStatusData = [
                    'active'   => 'online',
                    'inactive' => 'idle',
                    'offline'  => 'offline'
                ];
                if ($row['status_data_class'] !== $mapStatusData[$statusData]) continue;
            }

            $filtered[] = $row;
        }

        $filteredData = $filtered;
        $totalFiltered = count($filteredData);
        $start = intval($data['start'] ?? 0);
        $length = intval($data['length'] ?? 10);
        $pagedData = array_slice($filteredData, $start, $length);

        $operation['summary'] = [
            'device' => $summaryDevice,
            'data'   => $summaryData
        ];

        echo json_encode([
            "draw" => intval($data['draw'] ?? 1),
            "recordsTotal" => $totalRows,
            "recordsFiltered" => $totalFiltered,
            "data" => $pagedData,
            "summary" => [
                'device' => $summaryDevice,
                'data'   => $summaryData
            ]
        ]);
        exit;
    }

    public function select_wp()
    {
        $data           = varPost();
        $where          = ' AND toko_status = \'2\'';
        $data['page']   = isset($data['page']) ? (intval($data['page']) - 1) : '0';
        $total          = $this->db->query('SELECT count(toko_id) total FROM pajak_toko 
		WHERE LOWER(concat(toko_kode, toko_nama))::text like \'%' . strtolower(varPost('q')) . '%\' ' . $where)->getResultArray();

        $return         = $this->db->query('SELECT toko_kode as id, concat(toko_kode, \' - \', toko_nama) as text FROM v_pajak_toko 
		WHERE LOWER(concat(toko_kode, toko_nama))::text like \'%' . strtolower(varPost('q')) . '%\' ' . $where . ' LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->getResultArray();
        $this->response(array('items' => $return, 'total_count' => $total[0]['total']));
    }

    public function detail($toko_id)
    {
        try {
            $record      = $this->db
                ->table($this->statusdevice->get_tablename())
                ->where('v_status_device.toko_id', $toko_id)
                ->get()
                ->getRow();

            if (!$record) {
                throw new Exception('Data Tidak Ditemukan');
            }

            $datarow['is_success']  = true;
            $datarow['msg']         = 'sukses';
            $datarow['data']        = $record;
        } catch (Throwable $th) {
            $datarow['is_success']  = false;
            $datarow['msg']         = $th->getMessage();
        } finally {
            $this->response($datarow);
        }
    }

    public function pdf()
    {
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

        $dtCaption  = '';

        $records    = $this->db->table($this->lastactivity->get_table_name())
            ->join('pajak_toko', 'pajak_toko.toko_kode = log_mobile.log_user_code_store', 'left')
            ->join('pajak_wajibpajak', 'pajak_wajibpajak.wajibpajak_npwpd = log_mobile.log_wajibpajak_npwpd', 'left');

        if ($v = varPost('tanggal')) {
            $records->where('log_tanggal', $v);
        }

        if ($v = varPost('code_store')) {
            $records->where('log_user_code_store', $v);
        }

        $records    = $records->get()
            ->getResult();

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
                    <h4> LAPORAN LAST ACTIVITY (POS MOBILE)</h4><br>
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
                    <th class="t-center">Device ID</th>
                    <th class="t-center">Device Model</th>
                    <th class="t-center">Wajib Pajak</th>
                    <th class="t-center">Terakhir Dilihat</th>
                    <th class="t-center">Rerata Ping Perjam</th>
                </tr>
            </thead>
            <tbody>';

        if (empty($records)) {
            $html .= '<tr><td colspan="5" class="t-center">Tidak Ada Data</td></tr>';
        } else {
            foreach ($records as $value) {
                $total  = 0;
                $total += $value->log_jam_0 ?? 0;
                $total += $value->log_jam_1 ?? 0;
                $total += $value->log_jam_2 ?? 0;
                $total += $value->log_jam_3 ?? 0;
                $total += $value->log_jam_4 ?? 0;
                $total += $value->log_jam_5 ?? 0;
                $total += $value->log_jam_6 ?? 0;
                $total += $value->log_jam_7 ?? 0;
                $total += $value->log_jam_8 ?? 0;
                $total += $value->log_jam_9 ?? 0;
                $total += $value->log_jam_10 ?? 0;
                $total += $value->log_jam_11 ?? 0;
                $total += $value->log_jam_12 ?? 0;
                $total += $value->log_jam_13 ?? 0;
                $total += $value->log_jam_14 ?? 0;
                $total += $value->log_jam_15 ?? 0;
                $total += $value->log_jam_16 ?? 0;
                $total += $value->log_jam_17 ?? 0;
                $total += $value->log_jam_18 ?? 0;
                $total += $value->log_jam_19 ?? 0;
                $total += $value->log_jam_20 ?? 0;
                $total += $value->log_jam_21 ?? 0;
                $total += $value->log_jam_22 ?? 0;
                $total += $value->log_jam_23 ?? 0;

                $rerata = 0;
                if ($total > 0) {
                    $rerata = $total / 23;
                    $rerata = round($rerata);
                }

                $html .= '<tr>
                    <td class="t-center">' . $value->log_device_id . '</td>
                    <td class="t-center">' . $value->log_device_model . '</td>
                    <td class="t-center">' . "$value->log_wajibpajak_npwpd - $value->log_wajibpajak_nama" . '</td>
                    <td class="t-center">' . $value->log_last_active . '</td>
                    <td class="t-center">' . $rerata . '</td>
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
            'file_name'     => 'Realisasi Pajak',
            'title'         => 'Realisasi Pajak',
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
            $sheet->mergeCells('A1:F1');
            $sheet->setCellValue('A1', 'Last Activity WP Mobile');
            $sheet->getStyle('A1')->applyFromArray($styleArray);

            foreach (range('A', 'F') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }

            $records    = $this->db->table($this->lastactivity->get_table_name())
                ->join('pajak_toko', 'pajak_toko.toko_kode = log_mobile.log_user_code_store', 'left')
                ->join('pajak_wajibpajak', 'pajak_wajibpajak.wajibpajak_npwpd = log_mobile.log_wajibpajak_npwpd', 'left');

            if ($v = varPost('tanggal')) {
                $records->where('log_tanggal', $v);
            }

            if ($v = varPost('code_store')) {
                $records->where('log_user_code_store', $v);
            }

            $records    = $records->get()
                ->getResult();

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
            $sheet->getStyle('A2:F2')->applyFromArray($styleArray);
            $sheet->setCellValue('A2', 'No');
            $sheet->setCellValue('B2', 'Device ID');
            $sheet->setCellValue('C2', 'Device Model');
            $sheet->setCellValue('D2', 'Wajib Pajak');
            $sheet->setCellValue('E2', 'Terakhir Dilihat');
            $sheet->setCellValue('F2', 'Rerata Ping Perjam');

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $no = 2;
            foreach ($records as $key => $value) {
                $total  = 0;
                $total += $value->log_jam_0 ?? 0;
                $total += $value->log_jam_1 ?? 0;
                $total += $value->log_jam_2 ?? 0;
                $total += $value->log_jam_3 ?? 0;
                $total += $value->log_jam_4 ?? 0;
                $total += $value->log_jam_5 ?? 0;
                $total += $value->log_jam_6 ?? 0;
                $total += $value->log_jam_7 ?? 0;
                $total += $value->log_jam_8 ?? 0;
                $total += $value->log_jam_9 ?? 0;
                $total += $value->log_jam_10 ?? 0;
                $total += $value->log_jam_11 ?? 0;
                $total += $value->log_jam_12 ?? 0;
                $total += $value->log_jam_13 ?? 0;
                $total += $value->log_jam_14 ?? 0;
                $total += $value->log_jam_15 ?? 0;
                $total += $value->log_jam_16 ?? 0;
                $total += $value->log_jam_17 ?? 0;
                $total += $value->log_jam_18 ?? 0;
                $total += $value->log_jam_19 ?? 0;
                $total += $value->log_jam_20 ?? 0;
                $total += $value->log_jam_21 ?? 0;
                $total += $value->log_jam_22 ?? 0;
                $total += $value->log_jam_23 ?? 0;

                $rerata = 0;
                if ($total > 0) {
                    $rerata = $total / 23;
                    $rerata = round($rerata);
                }

                $no += 1;
                $sheet->setCellValue('A' . $no, $key + 1);
                $sheet->setCellValue('B' . $no, $value->log_device_id);
                $sheet->setCellValue('C' . $no, $value->log_device_model);
                $sheet->setCellValue('D' . $no, "$value->log_wajibpajak_npwpd - $value->log_wajibpajak_nama");
                $sheet->setCellValue('E' . $no, $value->log_last_active);
                $sheet->setCellValue('F' . $no, $rerata);
            }

            $sheet->getStyle('A2:F' . $no)->applyFromArray($styleArray);

            $writer         = new Xlsx($spreadsheet);
            $filename       = 'subrealisasipajak-' . date('d-m-y-H-i-s') . '.xlsx';
            $folder         = FCPATH . 'assets/laporan/monitor_realisasi/';
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

    public function get_interval()
    {
        try {
            $conf           = $this->dbmp->table('pajak_config')->where('conf_code', 'mobile_interval_ping')->get()->getRow();

            if (!$conf) {
                throw new Exception('Pengaturan Tidak Ditemukan');
            }

            $datarow['success'] = true;
            $datarow['msg']     = 'sukses';
            $datarow['data']    = $conf->conf_value ?? 10;
        } catch (Throwable $th) {
            $datarow['success'] = false;
            $datarow['msg']     = $th->getMessage();
        } finally {
            $this->response($datarow);
        }
    }
}

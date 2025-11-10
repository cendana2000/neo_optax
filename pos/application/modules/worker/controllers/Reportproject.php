
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Amp\Beanstalk\BeanstalkClient;
use \Amp\Loop;
use Carbon\Carbon;
use Khill\Duration\Duration;

class Reportproject extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Excel');
    }

    public function runGen()
    {
        $baseQueue = $_SERVER['BASE_QUEUE'];
        Loop::run(function () use ($start, $beanstalk, $baseQueue) {
            $beanstalk = new BeanstalkClient("tcp://".$baseQueue);
            yield $beanstalk->watch('report_project_list');

            while (list($jobId, $payload) = yield $beanstalk->reserve()) {

                $dataPayload = json_decode($payload, true);
                
                $dataExport = $this->db->where(['report_export_id' => $dataPayload['id']])->get('report_export')->row_array();
                if ($dataExport) {
                    $this->db->where(['report_export_id' => $dataPayload['id']])->update('report_export', ['report_export_status' => 1]);
                    $dataProject = $this->db->where(['project_id' => $dataExport['report_export_project_id']])->get('project')->row_array();
                    if ($dataProject) {
                        $whereBorehole = [
                            'borehole_project_id' => $dataProject['project_id'],
                            'borehole_deleted_at is null' => null,
                            'borehole_borehole_status_id != "af6bb5c370b3cf516b95361cde62ce8c"' => null,
                        ];
                        if ($dataExport['report_export_date']) {
                            $start_date = explode("-", $dataExport['report_export_date'])[0];
                            $end_date   = explode("-", $dataExport['report_export_date'])[1];
                            if ($start_date && $end_date) {
                                $whereBorehole['borehole_date_start >= "'.$start_date.'"'] = null;
                                $whereBorehole['borehole_date_start <= "'.$start_date.'"'] = null;
                            }
                        }
                        $dataBorehole = $this->db->where($whereBorehole)
                                    ->order_by('borehole_date_start')
                                    ->get('v_borehole_project')
                                    ->result_array();
                        $config = [
                            'dataExport' => $dataExport,
                            'dataProject'=> $dataProject,
                            'dataBorehole'=> $dataBorehole
                        ];
                        $this->export($config);
                    }
                }

                $beanstalk->delete($jobId);
            }
        });
    }

    public function export($config)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];
        $project_id   = $dataProject['project_id'];


        $excel = new Excel([2, NULL, NULL, 2]);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');
        $excel->getActiveSheet()->getPageMargins()->setTop(0.4);
        $excel->getActiveSheet()->getPageMargins()->setRight(0.4);
        $excel->getActiveSheet()->getPageMargins()->setLeft(0.4);
        $excel->getActiveSheet()->getPageMargins()->setBottom(0.4);

        $start_col  = $excel->getVar() ['left'];
        $baris = 2;
        $pageno= 1;
        $firstCol = null;
        $lastCol  = null;

        $styleRow = '{nowrap:true;vertical-align:middle;border:default;text-align:center;indent:1;}';

        if ($baris == 2 || true) {

            $excel->setDataCells([
                [
                    'cell' => $excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+1).($baris),
                    'value'=> "Project",
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col).($baris+1).":".$excel->siConvert($start_col+1).($baris+1),
                    'value'=> $dataProject['project_code'],
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col+2).($baris).":".$excel->siConvert($start_col+15).($baris+1),
                    'value'=> "Borehole List",
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris).":".$excel->siConvert($start_col+17).($baris),
                    'value'=> "Page No",
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris+1).":".$excel->siConvert($start_col+17).($baris+1),
                    'value'=> $pageno,
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
            ]);
            $excel->setBorderOutside($excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+17).($baris+1), "2px");

            $baris += 3;

            $styleHeaderTable = '{nowrap:false;vertical-align:middle;text-align:center;border:default;}';
            $excel->setDataCells([
                // No
                [
                    'cell' => $excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col).($baris+2),
                    'value'=> "No",
                    'style'=> $styleHeaderTable
                ],

                // Borehole
                [
                    'cell' => $excel->siConvert($start_col+1).($baris).":".$excel->siConvert($start_col+1).($baris+2),
                    'value'=> "Borehole",
                    'style'=> $styleHeaderTable
                ],

                // Rig
                [
                    'cell' => $excel->siConvert($start_col+2).($baris).":".$excel->siConvert($start_col+2).($baris+2),
                    'value'=> "Unit Rig",
                    'style'=> $styleHeaderTable
                ],

                // Date
                [
                    'cell' => $excel->siConvert($start_col+3).($baris).":".$excel->siConvert($start_col+4).($baris+1),
                    'value'=> "Date",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+3).($baris+2),
                    'value'=> "From",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+4).($baris+2),
                    'value'=> "To",
                    'style'=> $styleHeaderTable
                ],

                // Open Hole
                [
                    'cell' => $excel->siConvert($start_col+5).($baris).":".$excel->siConvert($start_col+5).($baris+1),
                    'value'=> "Open Hole",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+5).($baris+2),
                    'value'=> "Meter",
                    'style'=> $styleHeaderTable
                ],

                // Coring
                [
                    'cell' => $excel->siConvert($start_col+6).($baris).":".$excel->siConvert($start_col+8).($baris),
                    'value'=> "Coring",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+6).($baris+1),
                    'value'=> "< 90%",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+7).($baris+1),
                    'value'=> "> 90%",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+8).($baris+1),
                    'value'=> "Total",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+6).($baris+2),
                    'value'=> "(meter)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+7).($baris+2),
                    'value'=> "(meter)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+8).($baris+2),
                    'value'=> "(meter)",
                    'style'=> $styleHeaderTable
                ],

                // Rig Move
                [
                    'cell' => $excel->siConvert($start_col+9).($baris).":".$excel->siConvert($start_col+11).($baris),
                    'value'=> "Rig Move",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+9).($baris+1).":".$excel->siConvert($start_col+9).($baris+2),
                    'value'=> "From",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+10).($baris+1).":".$excel->siConvert($start_col+10).($baris+2),
                    'value'=> "To",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+11).($baris+1),
                    'value'=> "Distance",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+11).($baris+2),
                    'value'=> "(meter)",
                    'style'=> $styleHeaderTable
                ],

                // Consumeable
                [
                    'cell' => $excel->siConvert($start_col+12).($baris).":".$excel->siConvert($start_col+16).($baris),
                    'value'=> "Consumeable",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+12).($baris+1),
                    'value'=> "Corebox",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+12).($baris+2),
                    'value'=> "(Pcs)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+13).($baris+1),
                    'value'=> "Fuel",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+13).($baris+2),
                    'value'=> "(Pcs)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+14).($baris+1),
                    'value'=> "Casing",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+14).($baris+2),
                    'value'=> "(Pcs)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+15).($baris+1),
                    'value'=> "Polymer",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+15).($baris+2),
                    'value'=> "(Liter)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris+1),
                    'value'=> "Bentonite",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris+2),
                    'value'=> "(Kg)",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+17).($baris).":".$excel->siConvert($start_col+17).($baris+2),
                    'value'=> "Stand by",
                    'style'=> $styleHeaderTable
                ],
            ]);
            $excel->setBorderOutside($excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+17).($baris), "2px");

            $baris += 4;
        }

        foreach ($dataBorehole as $key => $value) {

            $borehole_id = $value['borehole_id'];

            $totalCore = $this->db->select_sum('drilling_record_core_length')->where(['drilling_record_borehole_id' => $borehole_id, 'drilling_record_deleted_at' => null])->get('drilling_record')->row_array()['drilling_record_core_length'];
            $totalMeterage = $this->db->select_sum('drilling_record_thickness')->where(['drilling_record_borehole_id' => $borehole_id, 'drilling_record_deleted_at' => null])->get('drilling_record')->row_array()['drilling_record_thickness'];
            $totalOpenHole = $totalMeterage - $totalCore;
            $totalOpenHoleAll += $totalOpenHole;

            $totalCoreUnder90 = $this->db->select_sum('drilling_record_core_length')->where([
                                'drilling_record_borehole_id' => $borehole_id,
                                'drilling_record_core_recovery < 90' => null,
                                'drilling_record_deleted_at' => null
                            ])->get('drilling_record')->row_array()['drilling_record_core_length'];
            $totalCoreUp90 = $this->db->select_sum('drilling_record_core_length')->where([
                                'drilling_record_borehole_id' => $borehole_id,
                                'drilling_record_core_recovery >= 90' => null,
                                'drilling_record_deleted_at' => null
                            ])->get('drilling_record')->row_array()['drilling_record_core_length'];
            $totalCoring += round(($totalCoreUnder90 + $totalCoreUp90), 2);

            $rigMoveFrom = $this->db->select('borehole_name')->where([
                                'borehole_drilling_rig_id'  => $value['borehole_drilling_rig_id'],
                                'borehole_created_at <'     => $value['borehole_created_at'],
                                'borehole_deleted_at'       => null
                            ])->order_by('borehole_date_start', 'DESC')->limit(1)
                            ->get('v_borehole_project')->row_array()['borehole_name'];
            $rigDistance = $this->db->select_sum('drill_activity_moving')->where(['drill_activity_borehole_id' => $borehole_id, 'drill_activity_deleted_at' => null])->get('drill_activity')->row_array()['drill_activity_moving'];
            $totalDistance += $rigDistance;

            $consumeable_core_box = $this->db->select_sum('drilling_summary_core_box')->where(['drilling_summary_borehole_id' => $borehole_id, 'drilling_summary_deleted_at' => null])->get('drilling_summary')->row_array()['drilling_summary_core_box'];
            $consumeable_bentonite = $this->db->select_sum('drilling_summary_bentonite')->where(['drilling_summary_borehole_id' => $borehole_id, 'drilling_summary_deleted_at' => null])->get('drilling_summary')->row_array()['drilling_summary_bentonite'];
            $consumeable_casing = $this->db->select_sum('drilling_summary_casing')->where(['drilling_summary_borehole_id' => $borehole_id, 'drilling_summary_deleted_at' => null])->get('drilling_summary')->row_array()['drilling_summary_casing'];
            $consumeable_polimer = $this->db->select_sum('drilling_summary_polimer')->where(['drilling_summary_borehole_id' => $borehole_id, 'drilling_summary_deleted_at' => null])->get('drilling_summary')->row_array()['drilling_summary_polimer'];
            $consumeable_fuel = $this->db->select_sum('drilling_summary_fuel')->where(['drilling_summary_borehole_id' => $borehole_id, 'drilling_summary_deleted_at' => null])->get('drilling_summary')->row_array()['drilling_summary_fuel'];

            $totalCoreBox += $consumeable_core_box;
            $totalBentonite += $consumeable_bentonite;
            $totalCasing += $consumeable_casing;
            $totalPolymer += $consumeable_polimer;
            $totalFuel += $consumeable_fuel;

            $standby    = $this->db->query('SELECT (SUM(TIME_TO_SEC(drill_activity_to) - TIME_TO_SEC(drill_activity_from))) AS standby from drill_activity where drill_activity_borehole_id = "'.$borehole_id.'" and drill_activity_deleted_at is null ORDER BY drill_activity_created_at ASC')->row_array()['standby'];
            $totalStandby += $standby;
            $standby_dur = new Duration($standby);
            $standby    = $standby_dur->formatted();

            if ($key == 0) {$firstCol = $excel->siConvert($start_col).($baris);}
            if ($key == count($dataBorehole)-1) {$lastCol = $excel->siConvert($start_col+17).($baris);}
            $excel->setDataCells([
                [
                    'cell' => $excel->siConvert($start_col).($baris),
                    'value'=> ($key+1),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+1).($baris),
                    'value'=> $value['borehole_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+2).($baris),
                    'value'=> $value['drilling_rig_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+3).($baris),
                    'value'=> $value['borehole_date_start'] ? date('d-M-Y', strtotime($value['borehole_date_start'])) : "",
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+4).($baris),
                    'value'=> $value['borehole_date_end'] ? date('d-M-Y', strtotime($value['borehole_date_end'])) : "",
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+5).($baris),
                    'value'=> round($totalOpenHole, 2),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+6).($baris),
                    'value'=> round($totalCoreUnder90, 2),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+7).($baris),
                    'value'=> round($totalCoreUp90, 2),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+8).($baris),
                    'value'=> round(($totalCoreUnder90 + $totalCoreUp90), 2),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+9).($baris),
                    'value'=> $rigMoveFrom ? $rigMoveFrom : "",
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+10).($baris),
                    'value'=> $value['borehole_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+11).($baris),
                    'value'=> $rigDistance,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+12).($baris),
                    'value'=> $consumeable_core_box,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+13).($baris),
                    'value'=> $consumeable_fuel,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+14).($baris),
                    'value'=> $consumeable_casing,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+15).($baris),
                    'value'=> $consumeable_polimer,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris),
                    'value'=> $consumeable_bentonite,
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+17).($baris),
                    'value'=> $standby,
                    'style'=> $styleRow
                ],
            ]);

            $baris++;
        }
        $excel->setAutoSize('C');
        $excel->setAutoSize('D');
        $excel->setAutoSize('E');
        $excel->setAutoSize('F');
        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol.":".$lastCol, "2px");
        }

        $totalStandby_dur = new Duration($totalStandby);
        $totalStandby     = $totalStandby_dur->formatted();

        $baris++;
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+4).($baris),
                'value'=> "Total",
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+5).($baris),
                'value'=> $totalOpenHoleAll,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+6).($baris),
                'value'=> "",
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+7).($baris),
                'value'=> "",
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+8).($baris),
                'value'=> $totalCoring,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+9).($baris),
                'value'=> "",
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+10).($baris),
                'value'=> "",
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+11).($baris),
                'value'=> $totalDistance,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+12).($baris),
                'value'=> $totalCoreBox,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+13).($baris),
                'value'=> $totalFuel,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+14).($baris),
                'value'=> $totalCasing,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+15).($baris),
                'value'=> $totalPolymer,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+16).($baris),
                'value'=> $totalBentonite,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col+17).($baris),
                'value'=> $totalStandby,
                'style'=> '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+17).($baris), "2px");

        $excel->setShowGridlines(FALSE);

        if (!file_exists("./dokumen/export/excel")) {
            mkdir("./dokumen/export/excel", 0777, true);
        }
        if (!file_exists("./dokumen/export/pdf")) {
            mkdir("./dokumen/export/pdf", 0777, true);
        }

        $nameFile = gen_uuid();
        $excel->savePath('./dokumen/export/excel/');
        $excel->export($nameFile);
        $excel->exportPdf('./dokumen/export/pdf/'.$nameFile.".pdf");

        $dataUpdateReport = [
            'report_export_status' => 2,
            'report_export_file_excel' => $nameFile.".xlsx",
            'report_export_file_pdf' => $nameFile.".pdf",
        ];
        $this->db->where(['report_export_id' => $dataExport['report_export_id']])->update('report_export', $dataUpdateReport);
        return true;
    }

}


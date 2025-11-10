
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Amp\Beanstalk\BeanstalkClient;
use \Amp\Loop;
use Carbon\Carbon;

class Reportdaily extends Base_Controller
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
            $beanstalk = new BeanstalkClient("tcp://" . $baseQueue);
            yield $beanstalk->watch('report_daily_record');

            while (list($jobId, $payload) = yield $beanstalk->reserve()) {

                $dataPayload = json_decode($payload, true);
                $dataExport = $this->db->where(['report_export_id' => $dataPayload['id']])->get('report_export')->row_array();
                if ($dataExport) {
                    $this->db->where(['report_export_id' => $dataPayload['id']])->update('report_export', ['report_export_status' => 1]);
                    $dataProject = $this->db->where(['project_id' => $dataExport['report_export_project_id']])->get('project')->row_array();
                    if ($dataProject) {
                        $whereBorehole = [
                            'borehole_id' => $dataExport['report_export_filter'],
                        ];
                        $dataBorehole = $this->db->where($whereBorehole)
                            ->get('v_borehole_full_detail_short')
                            ->row_array();
                        $config = [
                            'dataExport' => $dataExport,
                            'dataProject' => $dataProject,
                            'dataBorehole' => $dataBorehole
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


        $excel = new Excel([2, NULL, NULL, 2]);

        $excel = $this->sheetBorehole(0, $config, $excel);
        $excel = $this->sheetDrillActivity(1, $config, $excel);
        $excel = $this->sheetDrillRecordOpenHole(2, $config, $excel);
        $excel = $this->sheetDrillRecordCoring(3, $config, $excel);
        $excel = $this->sheetDrillConsumable(4, $config, $excel);
        $excel = $this->sheetRockLogging(5, $config, $excel);
        $excel = $this->sheetRockLoggingDesc(6, $config, $excel);
        $excel = $this->sheetSampleDispatch(7, $config, $excel);

        if (!file_exists("./dokumen/export/excel")) {
            mkdir("./dokumen/export/excel", 0775, true);
        }
        if (!file_exists("./dokumen/export/pdf")) {
            mkdir("./dokumen/export/pdf", 0775, true);
        }

        $nameFile = gen_uuid();
        $excel->savePath('./dokumen/export/excel/');
        $excel->export($nameFile);
        $excel->exportPdf('./dokumen/export/pdf/' . $nameFile . ".pdf");

        $dataUpdateReport = [
            'report_export_status' => 2,
            'report_export_file_excel' => $nameFile . ".xlsx",
            'report_export_file_pdf' => $nameFile . ".pdf",
        ];
        $this->db->where(['report_export_id' => $dataExport['report_export_id']])->update('report_export', $dataUpdateReport);
        return true;
    }

    public function sheetBorehole($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $excel->createSheet($numSheet)->setSheetTitle('Borehole');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1) . ":" . $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 12) . ($baris + 1),
                'value' => "Borehole Status Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 13) . ($baris) . ":" . $excel->siConvert($start_col + 14) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 13) . ($baris + 1) . ":" . $excel->siConvert($start_col + 14) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 14) . ($baris + 1), "2px");

        $baris += 3;

        $firstCol = $excel->siConvert($start_col) . ($baris);
        $lastCol = $excel->siConvert($start_col + 14) . ($baris + 9);

        $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        $excel->setBackGroundColor($firstCol . ":" . $lastCol, ['color' => 'D9D9D9']);

        // IDENTIFICATION
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris),
                'value' => "IDENTIFICATION",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;font-italic:true;font-underline:true;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => "Block",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['project_block_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 3),
                'value' => "Rig ID",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 3),
                'value' => $dataBorehole['drilling_rig_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 5),
                'value' => "Hole Size",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 5),
                'value' => $dataBorehole['hole_size_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 7),
                'value' => "Borehole Status",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 7),
                'value' => $dataBorehole['borehole_status_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 9),
                'value' => "Data Status",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 9),
                'value' => $dataBorehole['data_status_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
        ]);

        // COLLAR SURVEY
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris) . ":" . $excel->siConvert($start_col + 7) . ($baris),
                'value' => "COLLAR SURVEY",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;font-italic:true;font-underline:true;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 1),
                'value' => "Coord Sytem",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 1),
                'value' => $dataBorehole['coordinate_system_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 3),
                'value' => "UTM Zone",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 3),
                'value' => $dataBorehole['utm_zone_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 5),
                'value' => "Loc Accuracy",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 5),
                'value' => $dataBorehole['loc_accuracy_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 7),
                'value' => "Easting",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 7),
                'value' => $dataBorehole['borehole_easting'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],


            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 1),
                'value' => "Northing",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => $dataBorehole['borehole_northing'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 3),
                'value' => "Elevation",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 3),
                'value' => $dataBorehole['borehole_elevation'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 5),
                'value' => "Azimuth",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 5),
                'value' => $dataBorehole['borehole_azimuth'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 7),
                'value' => "Inclination",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 7),
                'value' => $dataBorehole['borehole_inclination'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
        ]);

        // DRILLING
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris),
                'value' => "DRILLING",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;font-italic:true;font-underline:true;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => "Date Started",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 1),
                'value' => $dataBorehole['borehole_date_start'] ? date('d-M-Y', strtotime($dataBorehole['borehole_date_start'])) : "",
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 3),
                'value' => "Date End",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 3),
                'value' => $dataBorehole['borehole_date_end'] ? date('d-M-Y', strtotime($dataBorehole['borehole_date_end'])) : "",
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 5),
                'value' => "Plan Depth",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 5),
                'value' => $dataBorehole['borehole_plan_depth'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 7),
                'value' => "Actual Depth",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 7),
                'value' => $dataBorehole['borehole_actual_depth'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
        ]);

        // GEOLOGICAL LOG
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris) . ":" . $excel->siConvert($start_col + 13) . ($baris),
                'value' => "GEOLOGICAL LOG",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;font-italic:true;font-underline:true;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris + 1),
                'value' => "Geologist",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 13) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris + 3),
                'value' => "Geophysical Logged",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 13) . ($baris + 3),
                'value' => $dataBorehole['borehole_geophy_logged'] == 0 ? "No" : "Yes",
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris + 5),
                'value' => "Geophysical Depth",
                'style' => '{vertical-align:middle;text-align:left;font-size:9;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 13) . ($baris + 5),
                'value' => $dataBorehole['borehole_geophy_depth'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;background-color:#FFFFFF;}'
            ],
        ]);

        $baris += 10;

        $colAutoSize = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetDrillActivity($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataActivity = $this->db->where([
            'drill_activity_borehole_id' => $dataBorehole['borehole_id'],
            'drill_activity_deleted_at'  => null,
        ])
            ->order_by('drill_activity_date ASC, drill_activity_from ASC')
            ->get('v_drilling_activity_report')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Drilling Activity');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => "Drilling Activity Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris),
                'value' => "Drilling Rig",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 1),
                'value' => $dataBorehole['drilling_rig_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 11) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 11) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 11) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col) . ($baris + 1),
                'value' => "No",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => "Date",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 2) . ($baris + 1),
                'value' => "Shift",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris) . ":" . $excel->siConvert($start_col + 4) . ($baris),
                'value' => "Time",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 1),
                'value' => "From",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 1),
                'value' => "To",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 11) . ($baris),
                'value' => "Borehole Activity",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris + 1),
                'value' => "Category",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 1) . ":" . $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => "Activity",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => "Mov. Distance",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 1) . ":" . $excel->siConvert($start_col + 11) . ($baris + 1),
                'value' => "Remarks",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 11) . ($baris + 1), "2px");

        $baris += 3;

        if ($dataActivity) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataActivity as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataActivity) - 1) {
                    $lastCol = $excel->siConvert($start_col + 11) . ($baris);
                }

                if ($val['drill_activity_category'] == 0) {
                    $category_name = 'Rig Operation';
                } elseif ($val['drill_activity_category'] == 1) {
                    $category_name = 'Rig Standby';
                } elseif ($val['drill_activity_category'] == 2) {
                    $category_name = 'Rig Breakdown';
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => ($key + 1),
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => date('d-M-Y', strtotime($val['drill_activity_date'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['shift_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => date('H:i', strtotime($val['drill_activity_from'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => date('H:i', strtotime($val['drill_activity_to'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris),
                        'value' => $category_name,
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 6) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris),
                        'value' => $val['activity_name'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 9) . ($baris),
                        'value' => $val['drill_activity_moving'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 10) . ($baris) . ":" . $excel->siConvert($start_col + 11) . ($baris),
                        'value' => $val['drill_activity_remark'],
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $colAutoSize = ['C', 'G'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetDrillRecordOpenHole($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'drilling_record_borehole_id'   => $dataBorehole['borehole_id'],
            'drilling_record_deleted_at'    => null,
            'drilling_record_drill_type_id' => '64bf562a3d6579c0dde805bc048b0839',
        ])
            ->order_by('drilling_record_date ASC, drilling_record_from ASC')
            ->get('drilling_record')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Drill Record - Open Hole');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 6) . ($baris + 1),
                'value' => "Open Hole Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris),
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "No",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Date",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris),
                'value' => "From",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris),
                'value' => "To",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris),
                'value' => "Thick",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris),
                'value' => "Remarks",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris), "2px");

        $baris += 2;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 8) . ($baris);
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => ($key + 1),
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => date('d-M-Y', strtotime($val['drilling_record_date'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['drilling_record_from'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['drilling_record_to'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['drilling_record_thickness'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris),
                        'value' => "",
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $baris++;

        $totalThickness = round(array_sum(array_column($dataRecord, 'drilling_record_thickness')), 2);
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 3) . ($baris),
                'value' => "Total",
                'style' => '{nowrap:true;vertical-align:middle;text-align:right;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris),
                'value' => $totalThickness,
                'style' => '{nowrap:true;vertical-align:middle;text-align:left;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris), "2px");

        $colAutoSize = ['C'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetDrillRecordCoring($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'drilling_record_borehole_id'   => $dataBorehole['borehole_id'],
            'drilling_record_deleted_at'    => null,
            'drilling_record_drill_type_id' => 'b16aceee627a6e454302f1ee9ff2f1e4',
        ])
            ->order_by('drilling_record_date ASC, drilling_record_from ASC')
            ->get('drilling_record')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Drill Record - Coring');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => "Core Run Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris),
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 10) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "No",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Date",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris),
                'value' => "Run No.",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris),
                'value' => "From",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris),
                'value' => "To",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris),
                'value' => "Thick",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris),
                'value' => "Core Length",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris),
                'value' => "Recovery",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => "RQD",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris),
                'value' => "Remarks",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris), "2px");

        $baris += 2;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 10) . ($baris);
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => ($key + 1),
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => date('d-M-Y', strtotime($val['drilling_record_date'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['drilling_record_run'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['drilling_record_from'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['drilling_record_to'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris),
                        'value' => $val['drilling_record_thickness'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 6) . ($baris),
                        'value' => $val['drilling_record_core_length'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 7) . ($baris),
                        'value' => $val['drilling_record_core_recovery'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 8) . ($baris),
                        'value' => $val['drilling_record_rqd'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 9) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris),
                        'value' => "",
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $baris++;

        $totalThickness     = round(array_sum(array_column($dataRecord, 'drilling_record_thickness')), 2);
        $totalCoreLength    = round(array_sum(array_column($dataRecord, 'drilling_record_core_length')), 2);
        $totalRecovery      = round(array_sum(array_column($dataRecord, 'drilling_record_core_recovery')), 2);
        $totalRqd           = round(array_sum(array_column($dataRecord, 'drilling_record_rqd')), 2);
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 4) . ($baris),
                'value' => "Total",
                'style' => '{nowrap:true;vertical-align:middle;text-align:right;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris),
                'value' => $totalThickness,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris),
                'value' => $totalCoreLength,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris),
                'value' => $totalRecovery,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => $totalRqd,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris),
                'value' => "",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris), "2px");

        $colAutoSize = ['C'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetDrillConsumable($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'drilling_summary_borehole_id'   => $dataBorehole['borehole_id'],
            'drilling_summary_deleted_at'    => null,
        ])
            ->order_by('drilling_summary_date ASC')
            ->get('drilling_summary')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Drilling - Consumable');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => "Drilling Consumables Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col) . ($baris + 1),
                'value' => "No",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => "Date",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 2) . ($baris + 1),
                'value' => "Status",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris),
                'value' => "Consumable",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 1),
                'value' => "Core Box",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 1),
                'value' => "Bentonite",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris + 1),
                'value' => "Casing",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris + 1),
                'value' => "Polimer",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => "Fuel",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => "Peg Number",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => "Stand By",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1), "2px");

        $baris += 3;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 10) . ($baris);
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => ($key + 1),
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => date('d-M-Y', strtotime($val['drilling_summary_date'])),
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => "",
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['drilling_summary_core_box'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['drilling_summary_bentonite'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris),
                        'value' => $val['drilling_summary_casing'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 6) . ($baris),
                        'value' => $val['drilling_summary_polimer'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 7) . ($baris),
                        'value' => $val['drilling_summary_fuel'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 8) . ($baris),
                        'value' => $val['drilling_summary_peg_number'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 9) . ($baris),
                        'value' => "",
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $baris++;

        $totalCoreBox   = round(array_sum(array_column($dataRecord, 'drilling_summary_core_box')));
        $totalBentonite = round(array_sum(array_column($dataRecord, 'drilling_summary_bentonite')));
        $totalCasing    = round(array_sum(array_column($dataRecord, 'drilling_summary_casing')));
        $totalPolimer   = round(array_sum(array_column($dataRecord, 'drilling_summary_polimer')));
        $totalFuel      = round(array_sum(array_column($dataRecord, 'drilling_summary_fuel')));
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 2) . ($baris),
                'value' => "Total",
                'style' => '{nowrap:true;vertical-align:middle;text-align:right;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris),
                'value' => $totalCoreBox,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris),
                'value' => $totalBentonite,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris),
                'value' => $totalCasing,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris),
                'value' => $totalPolimer,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris),
                'value' => $totalFuel,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => "",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris),
                'value' => "",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border: default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris), "2px");

        $colAutoSize = ['C'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetRockLogging($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'rock_logging_borehole_id'   => $dataBorehole['borehole_id'],
            'rock_logging_deleted_at'    => null,
        ])
            ->order_by('rock_logging_from ASC')
            ->get('v_rock_logging_export')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Rock Logging');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => "B2:C2",
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "B3:C3",
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "D2:F2",
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "D3:F3",
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "G2:AG3",
                'value' => "Lithology Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => "AH2:AK2",
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "AH3:AK3",
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "AL2:AP2",
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => "AL3:AP3",
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);

        $baris += 2;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;font-size:9;border:default;}';
        $styleHeaderTableNorm   = '{nowrap:true;vertical-align:middle;text-align:center;font-size:9;border:default;}';
        $styleHeaderTableRotate = '{nowrap:true;vertical-align:bottom;text-align:center;text-rotate:90;height:40;font-size:9;border:default;}';
        $excel->setDataCells([
            [
                'cell' => "B4:C7",
                'value' => "Lithology Depth",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "B8",
                'value' => "From",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "C8",
                'value' => "To",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "D4:D8",
                'value' => "Seam",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "E4:E8",
                'value' => "Ply",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "F4:F8",
                'value' => "Horizon",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "G4:G8",
                'value' => "Sample Purpose",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "H4:H8",
                'value' => "Lithology Sample Number",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "I4:T4",
                'value' => "Lithology Descriptor",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => "I5:I8",
                'value' => "Interval Status",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "J5:J8",
                'value' => "Lithology %",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "K5:K8",
                'value' => "Lithology",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "L5:L8",
                'value' => "Lithology Qualifier",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "M5:M8",
                'value' => "Shade",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "N5:N8",
                'value' => "Hue",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "O5:O8",
                'value' => "Colour",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "P5:P8",
                'value' => "Adjective 1",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "Q5:Q8",
                'value' => "Adjective 2",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "R5:R8",
                'value' => "Adjective 3",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "S5:S8",
                'value' => "Adjective 4",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "T5:T8",
                'value' => "Inter-Relationship",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "U4:U8",
                'value' => "",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "V4:AB4",
                'value' => "Geotechnical",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => "V5:V8",
                'value' => "Weathering",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "W5:W8",
                'value' => "Estimated Strength",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "X5:X8",
                'value' => "Bed Spacing",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "Y5:Y8",
                'value' => "Detect Type",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "Z5:Z8",
                'value' => "Intact",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AA5:AA8",
                'value' => "Detect Spacing",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AB5:AB8",
                'value' => "Detect Dip",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AC4:AC8",
                'value' => "",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "AD4:AF4",
                'value' => "Mechanical",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => "AD5:AD8",
                'value' => "Core State",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AE5:AE8",
                'value' => "Mechanical State",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AF5:AF8",
                'value' => "Texture",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AG4:AG8",
                'value' => "",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "AH4:AK4",
                'value' => "Sendimentology",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => "AH5:AH8",
                'value' => "Basalt Contact",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AI5:AI8",
                'value' => "Sed. Future 1",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AJ5:AJ8",
                'value' => "Sed. Future 2",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AK5:AK8",
                'value' => "Bedding Dip",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AL4:AL8",
                'value' => "",
                'style' => $styleHeaderTableNorm
            ],
            [
                'cell' => "AM4:AP4",
                'value' => "Mineral",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => "AM5:AM8",
                'value' => "Abudance",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AN5:AN8",
                'value' => "Mineral / Fosil Type",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AO5:AO8",
                'value' => "Mineral / Fosil Assoc.",
                'style' => $styleHeaderTableRotate
            ],
            [
                'cell' => "AP5:AP8",
                'value' => "Gas",
                'style' => $styleHeaderTableRotate
            ],
        ]);
        $excel->setBorderOutside("B2:AP8", "2px");

        $baris += 6;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 40) . ($baris);
                }
                for ($i = 1; $i < 5; $i++) {
                    $readAdj = $this->db->where([
                        'adjective_id'   => $val['rock_logging_adjective_' . $i],
                        'adjective_deleted_at'    => null,
                    ])
                        ->get('adjective')->row();
                    $ajd['adjective_' . $i] = $readAdj->adjective_code;
                }

                for ($y = 1; $y < 3; $y++) {
                    $readAdj = $this->db->where([
                        'sediment_feature_id'   => $val['rock_logging_sed_feature_' . $y],
                        'sediment_feature_deleted_at'    => null,
                    ])
                        ->get('sediment_feature')->row();
                    $sedFeat['sed_feat_' . $y] = $readAdj->sediment_feature_code;
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => $val['rock_logging_from'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => $val['rock_logging_to'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['rock_logging_seam'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['rock_logging_ply'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['rock_logging_horizon'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris),
                        'value' => $val['sample_purpose_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 6) . ($baris),
                        'value' => $val['rock_logging_sample_number'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 7) . ($baris),
                        'value' => $val['interval_status_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 8) . ($baris),
                        'value' => $val['rock_logging_lithology'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 9) . ($baris),
                        'value' => $val['lithology_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 10) . ($baris),
                        'value' => $val['lithology_qualifier_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 11) . ($baris),
                        'value' => $val['shade_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 12) . ($baris),
                        'value' => $val['hue_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 13) . ($baris),
                        'value' => $val['color_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 14) . ($baris),
                        'value' => $ajd['adjective_1'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 15) . ($baris),
                        'value' => $ajd['adjective_2'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 16) . ($baris),
                        'value' => $ajd['adjective_3'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 17) . ($baris),
                        'value' => $ajd['adjective_4'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 18) . ($baris),
                        'value' => $val['inter_relation_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 19) . ($baris),
                        'value' => '',
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 20) . ($baris),
                        'value' => $val['weathering_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 21) . ($baris),
                        'value' => $val['estimated_strange_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 22) . ($baris),
                        'value' => $val['bed_spacing_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 23) . ($baris),
                        'value' => $val['defect_type_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 24) . ($baris),
                        'value' => $val['intact_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 25) . ($baris),
                        'value' => $val['defect_spacing_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 26) . ($baris),
                        'value' => $val['rock_logging_defect_dip'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 27) . ($baris),
                        'value' => "",
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 28) . ($baris),
                        'value' => $val['core_state_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 29) . ($baris),
                        'value' => $val['mechanical_state_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 30) . ($baris),
                        'value' => $val['texture_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 31) . ($baris),
                        'value' => "",
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 32) . ($baris),
                        'value' => $val['basal_contact_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 33) . ($baris),
                        'value' => $sedFeat['sed_feat_1'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 34) . ($baris),
                        'value' => $sedFeat['sed_feat_2'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 35) . ($baris),
                        'value' => $val['rock_logging_bedding_dip'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 37) . ($baris),
                        'value' => $val['fossil_abundance_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 38) . ($baris),
                        'value' => $val['fossil_type_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 39) . ($baris),
                        'value' => $val['fossil_assoc_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 40) . ($baris),
                        'value' => $val['gas_code'],
                        'style' => $styleCellNum
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $colAutoSize = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'J', 'M', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AO', 'AP'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetRockLoggingDesc($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'rock_logging_borehole_id'   => $dataBorehole['borehole_id'],
            'rock_logging_deleted_at'    => null,
        ])
            ->order_by('rock_logging_borehole_id ASC')
            ->get('v_rock_logging_export')->result_array();
        foreach ($dataRecord as $key => $val) {
            $dataDescription = [
                'interval_status_description' => $val['interval_status_description'],
                'sample_purpose_description' => $val['sample_purpose_description'],
                'shade_description' => $val['shade_description'],
                'color_description' => $val['color_description'],
                'inter_relation_description' => $val['inter_relation_description'],
                'lithology_qualifier_description' => $val['lithology_qualifier_description'],
                'weathering_description' => $val['weathering_description'],
                'estimated_strange_description' => $val['estimated_strange_description'],
                'bed_spacing_description' => $val['bed_spacing_description'],
                'defect_type_description' => $val['defect_type_description'],
                'intact_description' => $val['intact_description'],
                'defect_spacing_description' => $val['defect_spacing_description'],
                'core_state_description' => $val['core_state_description'],
                'mechanical_state_description' => $val['mechanical_state_description'],
                'texture_description' => $val['texture_description'],
                'basal_contact_description' => $val['basal_contact_description'],
                'fossil_abundance_description' => $val['fossil_abundance_description'],
                'fossil_type_description' => $val['fossil_type_description'],
                'hue_description' => $val['hue_description'],
                'fossil_assoc_description' => $val['fossil_assoc_description'],
                'gas_description' => $val['gas_description'],
                'lithology_description' => $val['lithology_description']
            ];
            $dataRecord[$key]['description'] = implode(', ', array_values(array_unique(preg_grep("/^\s*$/", $dataDescription, PREG_GREP_INVERT))));
        }

        $excel->createSheet($numSheet)->setSheetTitle('Rock Logging-Descrip');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris + 1),
                'value' => "Lithology Description Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 11) . ($baris),
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 11) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 12) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 12) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Lithology Depth",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => "From",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => "To",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 2) . ($baris + 1),
                'value' => "Lithology Code",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris) . ":" . $excel->siConvert($start_col + 4) . ($baris),
                'value' => "Coal",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 1),
                'value' => "Seam",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris + 1),
                'value' => "Ply",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 12) . ($baris + 1),
                'value' => "Description",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 12) . ($baris + 1), "2px");

        $baris += 3;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 12) . ($baris);
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => $val['rock_logging_from'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => $val['rock_logging_to'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['lithology_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['rock_logging_seam'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['rock_logging_ply'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 12) . ($baris),
                        'value' => $val['description'],
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $baris++;

        // $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris), "2px");

        $colAutoSize = ['C'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }

    public function sheetSampleDispatch($numSheet, $config, $excel)
    {
        $dataExport   = $config['dataExport'];
        $dataProject  = $config['dataProject'];
        $dataBorehole = $config['dataBorehole'];

        $dataRecord = $this->db->where([
            'sample_borehole_id'   => $dataBorehole['borehole_id'],
            'sample_deleted_at'    => null,
        ])
            ->order_by('sample_date ASC')
            ->get('v_sample_export')->result_array();

        $excel->createSheet($numSheet)->setSheetTitle('Sample Dispatch');
        $excel->setActiveSheetIndex($numSheet);

        $excel->getPageMargins()->setTop(0.4);
        $excel->getPageMargins()->setRight(0.4);
        $excel->getPageMargins()->setLeft(0.4);
        $excel->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

        $start_col  = $excel->getVar()['left'];
        $baris = 2;
        $pageno = 1;
        $firstCol = null;
        $lastCol  = null;

        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris),
                'value' => "Project",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col) . ($baris + 1),
                'value' => $dataProject['project_code'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris),
                'value' => "Borehole",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => $dataBorehole['borehole_name'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => "Sample Dispatch Sheet",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris),
                'value' => "Geologist",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => $dataBorehole['user_nama'],
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris),
                'value' => "Page No",
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => $pageno,
                'style' => '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1), "2px");

        $baris += 3;

        $styleHeaderTable = '{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;}';
        $excel->setDataCells([
            [
                'cell' => $excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col) . ($baris + 1),
                'value' => "Purpose",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 1) . ($baris) . ":" . $excel->siConvert($start_col + 1) . ($baris + 1),
                'value' => "Sample ID",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris) . ":" . $excel->siConvert($start_col + 3) . ($baris),
                'value' => "Sample Depth",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 2) . ($baris + 1),
                'value' => "From",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 3) . ($baris + 1),
                'value' => "To",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 4) . ($baris) . ":" . $excel->siConvert($start_col + 4) . ($baris + 1),
                'value' => "Field Sample Mass (Kg)",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 5) . ($baris) . ":" . $excel->siConvert($start_col + 5) . ($baris + 1),
                'value' => "Seam",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 6) . ($baris) . ":" . $excel->siConvert($start_col + 6) . ($baris + 1),
                'value' => "Ply",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 7) . ($baris) . ":" . $excel->siConvert($start_col + 7) . ($baris + 1),
                'value' => "Coal Recovery",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 8) . ($baris) . ":" . $excel->siConvert($start_col + 8) . ($baris + 1),
                'value' => "Laboratory",
                'style' => $styleHeaderTable
            ],
            [
                'cell' => $excel->siConvert($start_col + 9) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1),
                'value' => "Dispatch date",
                'style' => $styleHeaderTable
            ],
        ]);
        $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 9) . ($baris + 1), "2px");

        $baris += 3;

        if ($dataRecord) {
            $styleCellText = '{nowrap:true;vertical-align:middle;text-align:left;}';
            $styleCellNum = '{nowrap:true;vertical-align:middle;text-align:center;}';
            foreach ($dataRecord as $key => $val) {

                if ($key == 0) {
                    $firstCol = $excel->siConvert($start_col) . ($baris);
                }
                if ($key == count($dataRecord) - 1) {
                    $lastCol = $excel->siConvert($start_col + 9) . ($baris);
                }

                $excel->setDataCells([
                    [
                        'cell' => $excel->siConvert($start_col) . ($baris),
                        'value' => $val['sample_purpose_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 1) . ($baris),
                        'value' => $val['sample_name'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 2) . ($baris),
                        'value' => $val['sample_from'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 3) . ($baris),
                        'value' => $val['sample_to'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 4) . ($baris),
                        'value' => $val['sample_field_mass'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 5) . ($baris),
                        'value' => $val['sample_seam'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 6) . ($baris),
                        'value' => $val['sample_ply'],
                        'style' => $styleCellText
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 7) . ($baris),
                        'value' => $val['sample_coal_recovery'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 8) . ($baris),
                        'value' => $val['laboratory_code'],
                        'style' => $styleCellNum
                    ],
                    [
                        'cell' => $excel->siConvert($start_col + 9) . ($baris),
                        'value' => date('d-M-Y', strtotime($val['sample_date'])),
                        'style' => $styleCellText
                    ],
                ]);

                $baris++;
            }
        }

        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol . ":" . $lastCol, "2px");
        }

        $baris++;

        // $excel->setBorderOutside($excel->siConvert($start_col) . ($baris) . ":" . $excel->siConvert($start_col + 10) . ($baris), "2px");

        $colAutoSize = ['C'];
        foreach ($colAutoSize as $v) {
            $excel->setAutoSize($v);
        }

        $excel->setShowGridlines(FALSE);

        return $excel;
    }
}

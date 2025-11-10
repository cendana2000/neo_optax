
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Amp\Beanstalk\BeanstalkClient;
use \Amp\Loop;
use Carbon\Carbon;

class Reportborehole extends Base_Controller
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
            yield $beanstalk->watch('report_borehole_list');

            while (list($jobId, $payload) = yield $beanstalk->reserve()) {

                $dataPayload = json_decode($payload, true);
                // $dataPayload = [
                //     'id' => '8913ba8be78fba09e78f60ad5185e19c'
                // ];
                $dataExport = $this->db->where(['report_export_id' => $dataPayload['id']])->get('report_export')->row_array();
                if ($dataExport) {
                    $this->db->where(['report_export_id' => $dataPayload['id']])->update('report_export', ['report_export_status' => 1]);
                    $dataProject = $this->db->where(['project_id' => $dataExport['report_export_project_id']])->get('project')->row_array();
                    if ($dataProject) {
                        $whereBorehole = [
                            'borehole_project_id' => $dataProject['project_id'],
                            'borehole_deleted_at is null' => null,
                        ];
                        if ($dataExport['report_export_filter']) {
                            $whereBorehole['borehole_borehole_status_id'] = $dataExport['report_export_filter'];
                        }
                        $dataBorehole = $this->db->where($whereBorehole)
                                    ->order_by('borehole_date_start')
                                    ->get('v_borehole_full_detail_short')
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


        $excel = new Excel([2, NULL, NULL, 2]);
        $excel->getActiveSheet()->getPageMargins()->setTop(0.4);
        $excel->getActiveSheet()->getPageMargins()->setRight(0.4);
        $excel->getActiveSheet()->getPageMargins()->setLeft(0.4);
        $excel->getActiveSheet()->getPageMargins()->setBottom(0.4);
        $excel->setPaperSize('A4');
        $excel->setOrientation('L');

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
                    'cell' => $excel->siConvert($start_col+2).($baris).":".$excel->siConvert($start_col+19).($baris+1),
                    'value'=> "Borehole List",
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;background-color:#FFF2CC;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col+20).($baris).":".$excel->siConvert($start_col+21).($baris),
                    'value'=> "Page No",
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
                [
                    'cell' => $excel->siConvert($start_col+20).($baris+1).":".$excel->siConvert($start_col+21).($baris+1),
                    'value'=> $pageno,
                    'style'=>'{nowrap:true;vertical-align:middle;text-align:center;font-bold:true;border:default;}'
                ],
            ]);
            $excel->setBorderOutside($excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+21).($baris+1), "2px");

            $baris += 3;

            $styleHeaderTable = '{nowrap:false;vertical-align:middle;text-align:center;border:default;}';
            $excel->setDataCells([
                [
                    'cell' => $excel->siConvert($start_col).($baris),
                    'value'=> "No",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+1).($baris),
                    'value'=> "Block",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+2).($baris),
                    'value'=> "Borehole Name",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+3).($baris),
                    'value'=> "Borehole Method",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+4).($baris),
                    'value'=> "Borehole Size",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+5).($baris),
                    'value'=> "Rig Name",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+6).($baris),
                    'value'=> "Date Start",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+7).($baris),
                    'value'=> "Date End",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+8).($baris),
                    'value'=> "Borehole Status",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+9).($baris),
                    'value'=> "Coordinate System",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+10).($baris),
                    'value'=> "UTM Zone",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+11).($baris),
                    'value'=> "Location Accuracy",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+12).($baris),
                    'value'=> "Easting",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+13).($baris),
                    'value'=> "Northing",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+14).($baris),
                    'value'=> "Elevation",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+15).($baris),
                    'value'=> "Borehole Plan Depth",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris),
                    'value'=> "Borehole Actual Depth",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+17).($baris),
                    'value'=> "Azimuth",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+18).($baris),
                    'value'=> "Inclination",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+19).($baris),
                    'value'=> "Data Status",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+20).($baris),
                    'value'=> "Geophysical Logged",
                    'style'=> $styleHeaderTable
                ],
                [
                    'cell' => $excel->siConvert($start_col+21).($baris),
                    'value'=> "Geophysical Depth",
                    'style'=> $styleHeaderTable
                ],
            ]);
            $excel->setBorderOutside($excel->siConvert($start_col).($baris).":".$excel->siConvert($start_col+21).($baris), "2px");

            $baris += 2;
        }
        foreach ($dataBorehole as $key => $value) {

            if ($key == 0) {$firstCol = $excel->siConvert($start_col).($baris);}
            if ($key == count($dataBorehole)-1) {$lastCol = $excel->siConvert($start_col+21).($baris);}
            $excel->setDataCells([
                [
                    'cell' => $excel->siConvert($start_col).($baris),
                    'value'=> ($key+1),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+1).($baris),
                    'value'=> $value['project_block_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+2).($baris),
                    'value'=> $value['borehole_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+3).($baris),
                    'value'=> $value['borehole_method_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+4).($baris),
                    'value'=> $value['hole_size_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+5).($baris),
                    'value'=> $value['drilling_rig_name'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+6).($baris),
                    'value'=> date('d-M-Y', strtotime($value['borehole_date_start'])),
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+7).($baris),
                    'value'=> $value['borehole_date_end'] ? date('d-M-Y', strtotime($value['borehole_date_end'])) : "",
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+8).($baris),
                    'value'=> $value['borehole_status_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+9).($baris),
                    'value'=> $value['coordinate_system_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+10).($baris),
                    'value'=> $value['utm_zone_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+11).($baris),
                    'value'=> $value['loc_accuracy_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+12).($baris),
                    'value'=> $value['borehole_easting'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+13).($baris),
                    'value'=> $value['borehole_northing'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+14).($baris),
                    'value'=> $value['borehole_elevation'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+15).($baris),
                    'value'=> $value['borehole_plan_depth'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+16).($baris),
                    'value'=> $value['borehole_actual_depth'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+17).($baris),
                    'value'=> $value['borehole_azimuth'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+18).($baris),
                    'value'=> $value['borehole_inclination'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+19).($baris),
                    'value'=> $value['data_status_code'],
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+20).($baris),
                    'value'=> $value['borehole_geophy_logged'] == 0 ? "No" : "Yes",
                    'style'=> $styleRow
                ],
                [
                    'cell' => $excel->siConvert($start_col+21).($baris),
                    'value'=> $value['borehole_geophy_depth'],
                    'style'=> $styleRow
                ],
            ]);

            $baris++;
        }
        $excel->setAutoSize('B');
        $excel->setAutoSize('C');
        $excel->setAutoSize('D');
        $excel->setAutoSize('H');
        $excel->setAutoSize('I');
        if ($firstCol && $lastCol) {
            $excel->setBorderOutside($firstCol.":".$lastCol, "2px");
        }
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


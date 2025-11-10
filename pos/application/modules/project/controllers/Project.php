<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Brick\Geo\Point;
use Brick\Geo\IO\EWKBReader;
use Brick\Geo\IO\EWKBWriter;

class Project extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'ProjectModel' => 'Project',
            'projectrequest/ProjectRequestModel' => 'ProjectRequest',
        ));
    }

    public function loadtable()
    {
        $data = varPost();

        $where['project_deleted_at'] = null;
        $operation = $this->select_dt($data, 'Project', 'datatable', true, $where);

        $this->response($operation);
    }

    public function store()
    {
        $data = varPost();
        $data['project_start_date'] = date('Y-m-d', strtotime($data['project_start_date']));
        $data['project_end_date'] = date('Y-m-d', strtotime($data['project_end_date']));
        $data['project_created_at'] = date("Y-m-d H:i:s");
        $data['project_updated_at'] = date("Y-m-d H:i:s");
        $operation = $this->Project->insert(gen_uuid($this->Project->get_table()), $data);
        $request = [
            'project_request_id' => $data['project_request_id'],
            'project_request_project_id' => $operation['id']
        ];
        $this->ProjectRequest->update($request['project_request_id'], $request);
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        $data['project_start_date'] = date('Y-m-d', strtotime($data['project_start_date']));
        $data['project_end_date'] = date('Y-m-d', strtotime($data['project_end_date']));
        $data['project_updated_at'] = date("Y-m-d H:i:s");
        $operation = $this->Project->update($data['project_id'], $data);
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->Project->read($data['id']);
        $this->response($operation);
    }

    public function readProjectReq()
    {
        $data = varPost();
        $operation = $this->ProjectRequest->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $data['project_deleted_at'] = date("Y-m-d H:i:s");
        $operation = $this->Project->update($data['id'], $data);
        $this->response($operation);
    }

    public function cekCode()
    {
        $data = varPost();

        $get = $this->Project->select([
            'fields' => ['project_code', 'project_id'],
            'filters_static' => [
                'project_code' => $data['code'],
                'project_deleted_at' => null
            ],
            'limit' => 1
        ]);
        $operation = [
            'success' => true
        ];
        if ($get['total'] != 0) {
            $operation['success'] = false;
            $operation['id'] = $get['data'][0]['project_id'];
        } elseif ($data['code'] == "") {
            $operation['success'] = false;
            $operation['id'] = null;
        }
        $this->response($operation);
    }

    public function combobox_projectRequest()
    {
        $operation = $this->ProjectRequest->select(array(
            'filters_static' => array(
                'project_request_status' => 1,
                'project_request_deleted_at' => null,
                'project_request_project_id' => null,
            ),
            'sort_static' => 'project_request_pic_name ASC'
        ));

        $this->response($operation);
    }

    public function test()
    {
        // $point = Point::fromText('POINT (353813.729 9571482.292)', 32748);
        // $point = Point::fromBinary(hex2bin('0101000020EC7F0000DBF97EEA569815416210584993416241'), 32748);
        // echo '<pre>';
        // var_dump($point->toArray());

        $reader = new EWKBReader();
        $point = $reader->read(hex2bin('0101000020EC7F0000DBF97EEA569815416210584993416241'));

        echo $point->asText(); // POINT (1.5 2.5)
        echo $point->SRID(); // 4326
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */
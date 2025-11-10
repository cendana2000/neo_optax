<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datastatus extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'DataStatusModel' => 'DataStatus',
            'borehole/BoreholeModel' => 'Borehole'
        ));
    }

    public function loadtable()
    {
        $data = varPost();

        if (!check_superadmin()) {
            $project_id = $this->session->userdata('user_project_id');
            $where['(data_status_created_by is null or data_status_created_by = "' . $project_id . '")'] = null;
        }

        $where['data_status_deleted_at'] = null;
        $operation = $this->select_dt($data, 'DataStatus', 'datatable', true, $where);

        $this->response($operation);
    }

    public function store()
    {
        $data = varPost();
        if (!check_superadmin()) {
            $project_id = $this->session->userdata('user_project_id');
            $data['data_status_created_by'] = $project_id;
        }
        $data['data_status_created_at'] = date("Y-m-d H:i:s");
        $data['data_status_updated_at'] = date("Y-m-d H:i:s");
        $operation = $this->DataStatus->insert(gen_uuid($this->DataStatus->get_table()), $data);
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        $data['data_status_updated_at'] = date("Y-m-d H:i:s");
        $operation = $this->DataStatus->update($data['data_status_id'], $data);
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->DataStatus->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $checkBorehole = $this->Borehole->count_exist([
            'borehole_data_status_id' => $data['id'],
            'borehole_deleted_at' => null
        ]);

        if ($checkBorehole > 0) {
            $operation = [
                'success' => false,
                'message' => 'cant delete data because it has been used in Borehole'
            ];
        } else {
            $data['data_status_deleted_at'] = date("Y-m-d H:i:s");
            $operation = $this->DataStatus->update($data['id'], $data);
        }
        $this->response($operation);
    }

    public function cekCode()
    {
        $data = varPost();

        $get = $this->DataStatus->select([
            'fields' => ['data_status_code', 'data_status_id'],
            'filters_static' => [
                'data_status_code' => $data['code'],
                'data_status_deleted_at' => null,
            ],
            'limit' => 1
        ]);
        $operation = [
            'success' => true
        ];
        if ($get['total'] != 0) {
            $operation['success'] = false;
            $operation['id'] = $get['data'][0]['data_status_id'];
        }
        $this->response($operation);
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */
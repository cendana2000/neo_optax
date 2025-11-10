<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jabatan extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'JabatanModel' => 'Jabatan',
        ));
    }

    public function loadtable()
    {
        $data = varPost();
        $where["jabatan_status = '1' "] = null;
        $operation = $this->select_dt($data, 'Jabatan', 'datatable', true, $where);

        $this->response($operation);
    }

    public function store()
    {
        $data = varPost();
        $data['jabatan_created_at'] = date("Y-m-d H:i:s");
        $data['jabatan_updated_at'] = date("Y-m-d H:i:s");
        $data['jabatan_status'] = 1;
        $operation = $this->Jabatan->insert(gen_uuid(), $data);
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        $data['jabatan_updated_at'] = date("Y-m-d H:i:s");
        $operation = $this->Jabatan->update($data['jabatan_id'], $data);
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->Jabatan->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $data['jabatan_status'] = 0;
        $operation = $this->Jabatan->update($data['id'], $data);
        $this->response($operation);
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */
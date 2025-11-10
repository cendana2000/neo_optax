<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HakAkses extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'HakAksesModel' => 'HakAkses',
        ));
    }

    public function index()
    {
        $this->load->view('HakAkses/Table');
    }

    public function loadTable()
    {
        $data = varPost();
        $where = [];
        $where['hak_akses_status'] = 1;

        $operation = $this->select_dt($data, 'HakAkses', 'datatable', true, $where);
        $this->response($operation);
    }

    public function combobox()
    {
        $where = [];
        $data['hak_akses_status'] = 1;
        $operation = $this->HakAkses->select(array(
            'filters_static' => array(
                'hak_akses_status' => 1
            )
        ));

        $this->response($operation);
    }

    public function store()
    {
        $data = varPost();
        $data['hak_akses_status'] = 1;
        $data['hak_akses_is_super'] = 0;
        $operation = $this->HakAkses->insert(gen_uuid($this->HakAkses->get_table()), $data);
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        $operation = $this->HakAkses->update($data['hak_akses_id'], $data);
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->HakAkses->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $data['hak_akses_status'] = 0;
        $operation = $this->HakAkses->update($data['id']);
        $this->response($operation);
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */
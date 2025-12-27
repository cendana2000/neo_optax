<?php defined('BASEPATH') or exit('No direct script access allowed');

class Kecamatan extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'kecamatan/KecamatanModel' => 'kecamatan'
        ));
    }

    public function select()
    {
        $this->response($this->kecamatan->select());
    }
}

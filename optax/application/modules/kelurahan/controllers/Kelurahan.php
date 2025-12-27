<?php defined('BASEPATH') or exit('No direct script access allowed');

class Kelurahan extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'kelurahan/KelurahanModel' => 'kelurahan'
        ));
    }

    public function select()
    {
        $this->response($this->kelurahan->select());
    }
}

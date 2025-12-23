<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pemda extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array(
            'pemda/pemdaModel' => 'pemda'
        ));
    }

    public function select()
    {
        $this->response($this->pemda->select(
            [
                'filters_static' => varPost(),
                'without_global_scope'  => true
            ]
        ));
    }

    public function set_pemda($id = null)
    {
        $this->session->set_userdata('pemda_id', $id);
        $operation = array('success' => false);

        $this->response($operation);
    }
}

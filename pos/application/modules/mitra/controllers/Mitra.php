<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mitra extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"   => 'User',
            "project/ProjectModel"   => 'Project',
            "user/UserProjectModel"   => 'UserProject',
        ));
    }

    public function index()
    {
        $id = $this->session->userdata('wajibpajak_id');
        if ($id == "") {
            $this->load->view('mitra/login');
        } else {
            $this->load->view('mitra/panel');
            // $this->main($id);
        }
    }
    public function check_wp(Type $var = null)
    {
        $data = varPost();
        $url = 'http://pajak.malangkota.go.id/Monitoring_API/check_wp';
        $postdata = json_encode($data);

        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        // $this->response($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output($result);
    }
}
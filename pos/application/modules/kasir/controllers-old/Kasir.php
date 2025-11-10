<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'konfigurasi/KasirModel' => 'kasir'
		));
	}

	public function index()
	{
		$ip = $this->input->ip_address();
		// echo $ip;exit;
		$kasir = $this->kasir->read(array('kasir_ip' => $ip));
		if($kasir['kasir_id']){
			if (!$this->session->userdata('is_login')) {
				$this->load->view('login/login_form');
			}else{
				$data = array(
					'user' 	=> $this->session->userdata('user_id'),
					'kasir' => $kasir
				);
				$this->load->view('kasir', $data);
			}
		}else{
			$this->session->sess_destroy();
			$this->load->view('login/login_form', ['message' => 'Alamat IP tidak terdaftar !']);			
		}
		// }
	}
}
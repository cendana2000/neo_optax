<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Block extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'konfigurasi/KasirModel' => 'kasir',
			// 'api/KeranjangModel' 	 => 'keranjang'
		));
	}

	public function index()
	{
		$this->load->view('block');
	}
}

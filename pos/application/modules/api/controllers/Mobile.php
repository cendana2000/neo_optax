<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobile extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array());
		$this->auth = AUTHORIZATION::Auth();
	}

	function Auth()
	{
		$headers = $this->input->request_headers();
		if (!array_key_exists('Authorization', $headers) && empty($headers['Authorization'])) {
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'success' => false,
				'message' => 'not allowed',
			), JSON_UNESCAPED_UNICODE));
			$this->output->_display();
			exit;
		} else {
			$decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);
			if ($decodedToken == false) {
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode(array(
					'success' => false,
					'message' => 'token invalid',
				), JSON_UNESCAPED_UNICODE));
				$this->output->_display();
				exit;
			} else {
				if (empty($decodedToken->session_db)) {
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode(array(
						'success' => false,
						'message' => 'parameter session_db not found',
					), JSON_UNESCAPED_UNICODE));
					$this->output->_display();
					exit;
				}
			}
		}
	}

	public function index()
	{
		// Modules::run('transaksipenjualan/Transaksipenjualan/load_menu', $this->dbname);
		$this->response(array('message' => 'Welcome to API Mobile'));
	}

	public function load_data_mobile()
	{
		Modules::run('transaksipenjualan/Transaksipenjualan/load_data_mobile', $this->dbname);
	}

	public function mobile_detail()
	{
		Modules::run('transaksipenjualan/Transaksipenjualan/mobile_detail');
	}

	public function load_menu()
	{
		Modules::run('transaksipenjualan/Transaksipenjualan/load_menu', $this->dbname);
	}

	public function store_mobile()
	{
		Modules::run('transaksipenjualan/Transaksipenjualan/store_mobile');
	}
}
/* End of file Mobile.php */
/* Location: ./application/modules/api/controllers/Mobile.php */
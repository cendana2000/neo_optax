<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthJWT extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array());
	}

	public function index()
	{
	}

	public function generate($id = '')
	{
		$tokenData['id'] = $id;
		$tokenData['session_db'] = 'pos_sena';
		$tokenData['token'] = AUTHORIZATION::generateToken($tokenData);
		$this->response(array('success' => false, 'data' => $tokenData));
	}

	public function validate()
	{
		$headers = $this->input->request_headers();

		if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
			// print_r('<pre>');print_r("here");print_r('</pre>');exit;
			$decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);
			if ($decodedToken != false) {
				$this->response($decodedToken);
				return;
			}
		}
		$this->response(array('success' => false));
	}
}

/* End of file AuthJWT.php */
/* Location: ./application/modules/api/controllers/AuthJWT.php */
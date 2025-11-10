<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardWp extends Base_Controller
{

	public function index()
	{
		die('132');
	}

	public function chart()
	{
		$this->response([
			'message' => 'test'
		]);
	}
}

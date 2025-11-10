<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conf extends Base_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
        	'ConfigurationModel' => 'Configuration'
        ));
    }


	public function index()
	{	
		
		$this->load->view('Table');
	}

	public function get()
	{
		$get = $this->Configuration->select([
			'filters_static' => [
				'conf_group <> ' => 'fcm'
			],
			'sort_static' => 'conf_id asc'
		]);
		$data = group_by_array($get['data'], 'conf_group');
		$operation = [
			'success' => true,
			'data' => $data
		];
		$this->response($operation);
	}

	public function store()
	{
		$data = varPost();

		if (isset($data['cek_jadwal'])) {
			$removeJadwal = $this->Configuration->update(['conf_group' => 'jadwal'], ['conf_value' => null]);
		}
		foreach ($data as $k => $v) {
			
			$value = $v;
			if($v != "undefined"){
				if ($k == 'jam_mulai_kerja' || $k == 'jam_selesai_kerja') {
					$v = date('H:i:s', strtotime($v));
				}
				$update = $this->Configuration->update(
					[
						'conf_code' => $k
					],
					[
						'conf_value' => $v
					]
				);
			}

			// print_r('<pre>');print_r($k);print_r('</pre>');

		}
		// exit;
		$this->response($update);
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conf extends Base_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
        	'ConfigurationModel' => 'Configuration',
					'barang/BarangModel' => 'barang',
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

		$file = $_FILES['struk_logo']['name'];
		$config['upload_path']  = './assets/master/kasir/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = 2048;
		$config['file_name'] = uniqid('kasir_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

		if(!empty($file)){
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('struk_logo')) {
				return $this->response([
					'success' => false,
					'message' => $this->upload->display_errors('', ''),
				]);
			}else{
				$uploadedImage = $this->upload->data();
				$opr = $this->Configuration->update([
					'conf_code' => 'struk_logo',
				], [
					'conf_value' => $uploadedImage['file_name'],
				]);
			}
		}

		if(isset($data['struk_header'])){
			if(isset($data['struk_is_antrian'])) $data['struk_is_antrian'] = 'true';
			if(isset($data['struk_is_title_show'])) $data['struk_is_title_show'] = 'true';
			if(isset($data['struk_is_logo'])) $data['struk_is_logo'] = 'true';
			if(!isset($data['struk_is_antrian'])) $data['struk_is_antrian'] = 'false';
			if(!isset($data['struk_is_title_show'])) $data['struk_is_title_show'] = 'false';
			if(!isset($data['struk_is_logo'])) $data['struk_is_logo'] = 'false';
		}

		foreach ($data as $k => $v) {
			$value = $v;
			if($v != "undefined" || $k != 'csrf_tecton'){
				$update = $this->Configuration->update(
					[
						'conf_code' => $k
					],
					[
						'conf_value' => $v
					]
				);
			}
		}
		$this->response($update);
	}

}

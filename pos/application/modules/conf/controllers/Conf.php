<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Conf extends Base_Controller
{
	protected $db;

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

		if (!empty($file)) {
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('struk_logo')) {
				return $this->response([
					'success' => false,
					'message' => $this->upload->display_errors('', ''),
				]);
			} else {
				$uploadedImage = $this->upload->data();
				$opr = $this->Configuration->update([
					'conf_code' => 'struk_logo',
				], [
					'conf_value' => $uploadedImage['file_name'],
				]);
			}
		}

		if (isset($data['struk_header'])) {
			if (isset($data['struk_is_antrian'])) $data['struk_is_antrian'] = 'true';
			if (isset($data['struk_is_title_show'])) $data['struk_is_title_show'] = 'true';
			if (isset($data['struk_is_logo'])) $data['struk_is_logo'] = 'true';
			if (!isset($data['struk_is_antrian'])) $data['struk_is_antrian'] = 'false';
			if (!isset($data['struk_is_title_show'])) $data['struk_is_title_show'] = 'false';
			if (!isset($data['struk_is_logo'])) $data['struk_is_logo'] = 'false';
		}

		foreach ($data as $k => $v) {
			$value = $v;
			if ($v != "undefined" || $k != 'csrf_tecton') {
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

	public function get_config_mobile($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		$isMobile = false;
		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$isMobile = true;
		}

		$query = "
			SELECT conf_id, conf_code, conf_title, conf_value, conf_group, conf_type
			FROM pos_config
			WHERE conf_id = 'conf_7'
			LIMIT 1
		";
		$row = $this->db->query($query)->row_array();

		if (!$row) {
			$this->response([
				"conf_id" => "conf_7",
				"conf_code" => "struk_logo",
				"conf_title" => "Logo",
				"struk_logo" => null,
				"conf_group" => "struk_header",
				"conf_type" => "text",
			]);
			return;
		}

		$logo = null;
		if ($isMobile && !empty($row['conf_value'])) {
			$logo = $_ENV['BASE_URL'] . "assets/master/kasir/" . $row['conf_value'];
		} else {
			$logo = $row['conf_value'];
		}

		$response = [
			"conf_id"    => $row['conf_id'],
			"conf_code"  => $row['conf_code'],
			"conf_title" => $row['conf_title'],
			"struk_logo" => $logo,
			"conf_group" => $row['conf_group'],
			"conf_type"  => $row['conf_type'],
		];

		$this->response($response);
	}
}

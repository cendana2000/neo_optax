<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History_login extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'satuan/satuanModel' => 'satuan'
		));
	}


	public function online()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
		$data = $_POST;

		$this->response($data);
		$dataSend = [
			'history_wp_id' => $data['toko_id'],
			'history_nama_wp' => $data['toko_nama'],
			'history_user_id' => $data['user_id'],
			'history_user_nama' => $data['user_nama'],
			'history_is_online' => 1,
			'history_socket_id' => $data['socket_id'],
			'history_last_login' => date('Y-m-d H:i:s'),
		];

		$this->db->db_debug = false;
		if ($this->db->insert('history_login', $dataSend)) {
			$this->response([
				'success' => true,
				'socket_id' => $data['socket_id']
			]);
		} else {
			$_POST = json_decode(file_get_contents("php://input"), true);
			$data = $_POST;

			$this->db->db_debug = false;
			$this->db->where([
				'history_wp_id' => $data['toko_id'],
				'history_user_id' => $data['user_id']
			]);
			$this->db->set($dataSend);
			if ($this->db->update('history_login')) {
				$this->response([
					'success' => true,
					'socket_id' => $data['socket_id']
				]);
			} else {
				$this->response([
					'success' => false,
				]);
			}
		}
	}

	public function offline()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
		$data = $_POST;

		$this->db->db_debug = false;

		if ($this->db->query("UPDATE history_login SET history_is_online = 0, history_last_login = '" . date('Y-m-d H:i:s') . "' WHERE history_socket_id ='" . $data['socket_id'] . "'")) {
			$this->response([
				'success' => true,
				'socket_id' => $data['socket_id']
			]);
		} else {
			$this->response([
				'success' => false,
			]);
		}
	}
}

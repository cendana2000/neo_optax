<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginV2 extends BASE_Controller
{
	private $dbses;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			"hakakses/RoleAccessModel"  => 'RoleAccess',
			"user/UserModel"  => 'User',
			'user/UserProjectModel' => 'UserProject',
		));
	}

	public function index()
	{
		$data = varPost();
		$posAppType = ['PAJAK RESTORAN', 'PAJAK HOTEL', 'PAJAK HIBURAN'];

		$posUser = $this->dbmp->get_where('pos_user', [
			'pos_user_email' => strtolower($data['email'])
		])->row_array();

		if (empty($posUser)) {
			return $this->response(array(
				'success' => false,
				'message' => 'User not found. Please check your email or Password.',
			));
		}

		$toko = $this->dbmp->get_where('v_pajak_pos', ['toko_kode' => $posUser['pos_user_code_store']])->row_array();

		if (empty($toko)) {
			return $this->response(array(
				'success' => false,
				'message' => 'User not found, code store not registered'
			));
		}

		$getJenis = $this->dbmp->get_where('pajak_jenis', [
			'jenis_nama' => $toko['jenis_nama']
		])->row_array();
		$getJenisParent = $this->dbmp->get_where('pajak_jenis', [
			'jenis_id' => $getJenis['jenis_parent']
		])->row_array();

		$sessionDb = $_ENV['PREFIX_DBPOS'] . $posUser['pos_user_code_store'];
		$this->dbses = $this->load->database(multidb_connect($sessionDb), true);

		if ($posUser['pos_user_jenis_parent_name'] == 'PAJAK PARKIR') {
			if (!empty($posUser['pos_user_password_argon2id'])) {
				$sandi = $posUser['pos_user_password_argon2id'];
				$sandi = str_replace('$t=3', '$m=4096', $sandi);
				$sandi = str_replace(",m=4096", ",t=3", $sandi);

				if (password_verify($data['password'], $sandi)) {
					$this->updateWebLastActive($data['email']);
					$userParkir = $this->dbses->get_where('pengguna', [
						'email' => strtolower($data['email'])
					])->row_array();
					$userParkir['login_status'] = true;
					$userParkir['jenis_wp'] = 'PARKIR';
					$userParkir['session_db'] = $sessionDb;
					$userParkir['global_pajak'] = $toko['jenis_tarif'];
					$userParkir['toko_nama'] = $toko['toko_nama'];
					$userParkir['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
					$userParkir['toko'] = $toko;
					$userParkir['password_raw'] = $data['password'];
					unset($userParkir['sandi']);
					$this->session->set_userdata($userParkir);
					unset($userParkir['password_raw']);
					return $this->response([
						'success' => true,
						'message' => 'Berhasil login',
						'user' => $userParkir,
					]);
				}
			}
		}

		if (in_array($posUser['pos_user_jenis_parent_name'], $posAppType) && $this->password($data['password']) == $posUser['pos_user_password']) {
			$this->updateWebLastActive($data['email']);
			$user = $this->dbses->get_where('pos_user', [
				'user_email' => $data['email']
			])->row_array();

			$this->logWebActivity(
				$posUser['pos_user_id'],
				$posUser['pos_user_code_store'],
				$user['user_nama'] ?? $posUser['pos_user_name'],
				$wajibpajakInfo['wajibpajak_nama'] ?? '',
				$wajibpajakInfo['wajibpajak_npwpd'] ?? ''
			);

			$user['login_status'] = true;
			$user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
			$user['global_pajak'] = $toko['jenis_tarif'];
			$user['toko_nama'] = $toko['toko_nama'];
			$user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
			$user['toko'] = $toko;
			$user['toko_kode'] = $toko['toko_kode'];
			$user['pos_user_name'] = $posUser['pos_user_name'];
			if ($getJenisParent['jenis_nama'] == 'PAJAK RESTORAN') {
				$user['jenis_wp'] = 'RESTO';
			} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HOTEL') {
				$user['jenis_wp'] = 'HOTEL';
			} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HIBURAN') {
				$user['jenis_wp'] = 'HIBURAN';
			} else {
				$user['jenis_wp'] = 'DEFAULT';
			}
			$this->session->set_userdata($user);
			$operation = array(
				'success' => true,
				'data' => $user,
			);
			if ($user['hak_akses_is_super']) {
				$operation['is_super'] = 1;
			}
			return $this->response($operation);
		}

		return $this->response(array(
			'success' => false,
			'message' => 'User not found. Please check your email or Password.',
		));
	}

	public function updateWebLastActive($email)
	{
		$wajibpajak = $this->dbmp
			->where('wajibpajak_email', strtolower($email))
			->get('pajak_wajibpajak')
			->row_array();

		if ($wajibpajak) {
			$result = $this->dbmp
				->where('wajibpajak_email', strtolower($email))
				->update('pajak_wajibpajak', ['web_last_active' => date('Y-m-d H:i:s')]);
		} else {
			$this->response([
				'success' => false,
				'message' => 'Email Tidak Ada'
			]);
		}
	}

	public function logWebActivity($userId, $userCodeStore, $userName, $wajibpajakNama = '', $wajibpajakNpwpd = '')
	{

		$today   = date('Y-m-d');
		$hour    = date('G');

		$this->load->library('user_agent');
		if ($this->agent->is_browser()) {
			$browser = $this->agent->browser() . ' ' . $this->agent->version();
		} elseif ($this->agent->is_mobile()) {
			$browser = $this->agent->mobile();
		} else {
			$browser = 'Unknown';
		}

		$deviceId = 'web_' . md5($userId . session_id());

		$record = $this->dbmp
			->where('log_user_id', $userId)
			->get('log_mobile')
			->row();

		if ($record) {
			$isNewDay = ($record->log_tanggal != $today);
			$updateData = [
				'log_tanggal'      => $today,
				'log_device_id'    => $deviceId,
				'log_device_model' => $browser,
				'log_last_active'  => date('Y-m-d H:i:s'),
			];

			if ($isNewDay) {
				for ($i = 0; $i < 24; $i++) {
					$updateData["log_jam_$i"] = 0;
				}
				$updateData["log_jam_$hour"] = 1;
			} else {
				$currentHourValue = $record->{"log_jam_$hour"} ?? 0;
				$updateData["log_jam_$hour"] = $currentHourValue + 1;
			}
			$this->dbmp
				->where('log_id', $record->log_id)
				->update('log_mobile', $updateData);
		} else {
			$data = [
				'log_id'               => gen_uuid('log_mobile'),
				'log_tanggal'          => $today,
				'log_user_id'          => $userId,
				'log_user_code_store'  => $userCodeStore,
				'log_user_name'        => $userName,
				'log_device_id'        => $deviceId,
				'log_device_model'     => $browser,
				'log_last_active'      => date('Y-m-d H:i:s'),
				'log_created_at'       => date('Y-m-d H:i:s'),
				"log_jam_$hour"        => 1,
				'log_wajibpajak_nama'  => $wajibpajakNama,
				'log_wajibpajak_npwpd' => $wajibpajakNpwpd
			];
			for ($i = 0; $i < 24; $i++) {
				if (!isset($data["log_jam_$i"])) {
					$data["log_jam_$i"] = 0;
				}
			}
			$this->dbmp->insert('log_mobile', $data);
		}
	}
}

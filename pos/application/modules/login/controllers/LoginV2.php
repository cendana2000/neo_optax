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

	public function index() {
		$data = varPost();
		$posAppType = ['PAJAK RESTORAN', 'PAJAK HOTEL', 'PAJAK HIBURAN'];

		$posUser = $this->dbmp->get_where('pos_user', [
			'pos_user_email' => strtolower($data['email'])
		])->row_array();

		if(empty($posUser)){
			return $this->response(array(
				'success' => false,
				'message' => 'User not found. Please check your email or Password.',
			));
		}

		$toko = $this->dbmp->get_where('v_pajak_pos', ['toko_kode' => $posUser['pos_user_code_store']])->row_array();

		if(empty($toko)){
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

		if($posUser['pos_user_jenis_parent_name'] == 'PAJAK PARKIR'){
			if(!empty($posUser['pos_user_password_argon2id'])){
				$sandi = $posUser['pos_user_password_argon2id'];
				$sandi = str_replace('$t=3','$m=4096',$sandi);
				$sandi = str_replace(",m=4096",",t=3",$sandi);
	
				if(password_verify($data['password'], $sandi)){
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

		if(in_array($posUser['pos_user_jenis_parent_name'], $posAppType) && $this->password($data['password']) == $posUser['pos_user_password']){
            $user = $this->dbses->get_where('pos_user', [
				'user_email' => $data['email']
			])->row_array();
			$user['login_status'] = true;
			$user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
			$user['global_pajak'] = $toko['jenis_tarif'];
			$user['toko_nama'] = $toko['toko_nama'];
			$user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
			$user['toko'] = $toko;
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
}
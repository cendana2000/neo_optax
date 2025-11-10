<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permohonantoko extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'toko/tokoModel' => 'toko'
		));
	}


	public function argon2()
	{

		$password = 'dummyparkir';
		$hash = '$argon2id$v=19$t=3,m=4096,p=1$9+o1EUzrR/ta/2WHPBhFjg$2u1JVIx+qymZPe/HRH8Nr9KPPU04kMvHCkZSBpN2uAY';
		$splithash = str_replace(['t=3,m=4096'], ['m=4096,t=3'], $hash);

		print_r('<pre>');
		print_r($splithash);
		print_r('</pre>');
		print_r('<pre>');
		print_r(password_get_info($splithash));
		print_r('</pre>');
		if (password_verify($password, $splithash)) {
			print_r('<pre>');
			print_r('valid');
			print_r('</pre>');
		} else {
			print_r('<pre>');
			print_r('invalid');
			print_r('</pre>');
		}

		$newhash = genPasswordArgon2id($password);
		print_r('<pre>');
		print_r($newhash);
		print_r('</pre>');
		print_r('<pre>');
		print_r(password_get_info($newhash));
		print_r('</pre>');

		if (password_verify($password, $newhash)) {
			print_r('<pre>');
			print_r('valid');
			print_r('</pre>');
		} else {
			print_r('<pre>');
			print_r('invalid');
			print_r('</pre>');
		}
	}

	public function index()
	{
		// $where['wajibpajak_deleted_at'] = null;
		$where['toko_status = \'1\' or toko_status = \'\' or toko_status = \'3\''] = null;
		$this->response(
			$this->select_dt(varPost(), 'toko', 'table', true, $where)
		);
	}

	public function getpermohonan()
	{
		// $where['wajibpajak_deleted_at'] = null;
		$where['toko_status = 0 or toko_status = \'\''] = null;
		$this->response(
			$this->select_dt(varPost(), 'toko', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->toko->read(varPost()));
	}

	public function update()
	{
		$data = varPost();

		$checkToko = $this->db->get_where('v_pajak_pos_v2', ['toko_id' => $data['toko_id']])->row_array();
		$dataWP = $this->db->get_where('pajak_wajibpajak', ['wajibpajak_id' => $checkToko['toko_wajibpajak_id']])->row_array();
		$getJenis = $this->db->get_where('pajak_jenis', [
			'jenis_kode' => $dataWP['wajibpajak_sektor_nama']
		])->row_array();
		$getJenisParent = $this->db->get_where('pajak_jenis', [
			'jenis_id' => $getJenis['jenis_parent']
		])->row_array();
		if ($getJenisParent['jenis_nama'] == 'PAJAK RESTORAN') {
			$dbReference = $_ENV['DBPOS_REFERENCE'];
		} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HOTEL') {
			$dbReference = $_ENV['DBPOS_REFERENCE_HOTEL'];
		} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HIBURAN') {
			$dbReference = $_ENV['DBPOS_REFERENCE_HIBURAN'];
		} else {
			$dbReference = $_ENV['DBPOS_REFERENCE'];
		}
		$isParkir = false;
		if ($getJenis['jenis_nama'] == 'PAJAK PARKIR' && $dbReference == $_ENV['DBPOS_REFERENCE']) {
			$dbReference = $_ENV['DBPOS_REFERENCE_PARKIR'];
			$isParkir = true;
		}

		if (count($checkToko) > 0) {
			if ($checkToko['toko_is_pos'] == 'ACTIVE') {
				return $this->response([
					'success' => false,
					'message' => 'Tidak dapat mengkatifasi, Wajib Pajak ' . $checkToko['toko_nama'] . ' sudah melakukan Aktifasi POS',
				]);
			}

			if ($data['toko_status'] == '2') {
				$ispos = 'ACTIVE';
			} else {
				$ispos = 'INACTIVE';
			}
			if ($checkToko['toko_kode']) {
				$this->db->query("UPDATE pajak_toko set toko_is_pos = '{$ispos}', toko_status = '{$data['toko_status']}' WHERE toko_id = '{$checkToko['toko_id']}'");

				return $this->response([
					'success' => true,
					'message' => 'Status Point Of Sales (POS): ' . $ispos,
				]);
			} else {
				$data['toko_verified_at'] = date('Y-m-d H:i:s');
				$data['toko_verified_by'] = $this->session->userdata('pegawai_id');
				$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
				if ($data['toko_status'] == '2') {
					$data['toko_kode'] = substr(str_shuffle($permitted_chars), 0, 5);
					$toko = $this->toko->read(['toko_kode' => $data['toko_kode']]);
					if (!$toko) {
						$data['toko_kode'] = substr(str_shuffle($permitted_chars), 0, 5);
					}
				}
				$this->db->query("UPDATE pajak_toko set toko_is_pos = '{$ispos}' WHERE toko_id = '{$checkToko['toko_id']}'");
				$update_toko = $this->toko->update($data['toko_id'], $data);

				if ($update_toko['success'] && $data['toko_status'] == '2') {
					$dbname = $_ENV['PREFIX_DBPOS'] . $update_toko['record']['toko_kode'];
					// Terminate session db pos_reference
					$this->db->query("SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity
					WHERE pg_stat_activity.datname = '" . $dbReference . "' AND pid <> pg_backend_pid();");

					$checkDbExists = $this->db->query("SELECT 1 FROM pg_database WHERE datname = '$dbname'")->row_array();
					if ($checkDbExists) {
						return $this->response([
							'success' => false,
							'message' => 'Database POS sudah ada, silakan hapus dulu atau gunakan nama lain.',
						]);
					}

					// Create database from template pos_reference
					$this->db->query("CREATE DATABASE $dbname TEMPLATE " . $dbReference . "");
					sleep(2);
					$this->db->query("ALTER DATABASE $dbname OWNER TO " . $_ENV['DB_USER'] . ";");

					// ========================== DML ==============================

					$account_wp = $this->db->get_where('pajak_wajibpajak', [
						'wajibpajak_email' => strtolower($data['wajibpajak_email'])
					])->row_array();

					// // insert account
					$config['hostname'] = $_ENV['DB_HOST'];
					$config['port'] 	= $_ENV['DB_PORT'];
					$config['username'] = $_ENV['DB_USER'];
					$config['password'] = $_ENV['DB_PASS'];
					$config['database'] = $dbname;
					$config['dbdriver'] = 'postgre';
					$config['dbprefix'] = '';
					$config['pconnect'] = FALSE;
					$config['db_debug'] = TRUE;
					$config['cache_on'] = FALSE;
					$config['cachedir'] = '';
					$config['char_set'] = 'utf8';
					$config['dbcollat'] = 'utf8_general_ci';
					$this->dbsc = $this->load->database($config, true);

					if ($isParkir) {
						$this->dbsc->query("INSERT INTO pengguna (peran_pengguna_id,nama_pengguna,sandi,email,telepon,alamat,status,created_at,updated_at) VALUES ('bf178127-b351-493a-8600-d9ce1afd08c3', '{$account_wp['wajibpajak_nama_penanggungjawab']}', '{$account_wp['wajibpajak_password_argon2id']}', '{$account_wp['wajibpajak_email']}', '{$account_wp['wajibpajak_telp']}', '{$account_wp['wajibpajak_alamat']}', true, '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')");
						$this->dbsc->query("INSERT INTO toko (npwpd, pemilik, store_code) VALUES ('{$account_wp['wajibpajak_npwpd']}', '{$account_wp['wajibpajak_nama_penanggungjawab']}','{$update_toko['record']['toko_kode']}')");
						$tokoparkir = $this->dbsc->get_where('toko', [
							'npwpd' => $account_wp['wajibpajak_npwpd']
						])->row_array();
						$this->dbsc->query("INSERT INTO pengaturan_app_toko (public_toko_id, header_store_name, address_store) VALUES ('{$tokoparkir['id']}', '{$account_wp['wajibpajak_nama']}', '{$account_wp['wajibpajak_alamat']}')");
					} else {
						$this->dbsc->query("INSERT INTO pos_user (user_id, user_role_access_id, user_project_id, user_nama, user_alamat, user_telepon, user_email, user_password, user_status, user_foto, user_last_change_password, user_is_registered, user_token_registrasi, user_created_at, user_updated_at, user_deleted_at) VALUES('5f2c8335208a3d4f79ec05cc3a898880', '123', NULL, '" . $account_wp['wajibpajak_nama_penanggungjawab'] . "', '" . $account_wp['wajibpajak_alamat'] . "', '" . $account_wp['wajibpajak_telp'] . "', '" . $account_wp['wajibpajak_email'] . "', '" . $account_wp['wajibpajak_password'] . "', 1, '1d09cd5f9b1c8795a5443ab262334e06.png', NULL, 1, NULL, '" . date('Y-m-d H:i:s') . "', NULL, NULL);
						");
					}

					// Insert ke database pajak_app_prod setelah selesai create di database toko
					$pos_user_id = md5(uniqid(rand(), true));
					$this->db->query("INSERT INTO pos_user (pos_user_id, pos_user_name, pos_user_email, pos_user_password, pos_user_password_argon2id, pos_user_code_store, pos_user_status, pos_user_photo, pos_user_last_change_password, pos_user_role_access_id, pos_user_jenis_parent_name, pos_user_created_at, pos_user_address, pos_user_phone) VALUES (
						'{$pos_user_id}',
						'{$account_wp['wajibpajak_nama_penanggungjawab']}', 
						'{$account_wp['wajibpajak_email']}', 
						'{$account_wp['wajibpajak_password']}', 
						'{$account_wp['wajibpajak_password_argon2id']}', 
						'{$update_toko['record']['toko_kode']}', 
						TRUE, 
						'1d09cd5f9b1c8795a5443ab262334e06.png', 
						NULL, 
						'123', 
						'{$getJenisParent['jenis_nama']}',
						'" . date('Y-m-d H:i:s') . "', 
						'{$account_wp['wajibpajak_alamat']}', 
						'{$account_wp['wajibpajak_telp']}'
					)");
				}

				$operation = $update_toko;
				if ($operation['success'] == true) {
					$dataSendEmail = [
						'to_email'      => strtolower($data['wajibpajak_email']),
						'subject'       => 'Verifikasi Permohonan Toko',
						'template'      => 'ConfirmToko',
						'data'          => [
							'to_email'          => strtolower($data['wajibpajak_email']),
							'link'              => base_url() . 'index.php/mitralogin/EmailVerification?id=' . $operation['record']['wajibpajak_id'],
							'wajibpajak'        => $data['wajibpajak_nama'],
							'penanggungjawab'   => $data['wajibpajak_penanggungjawab'],
							'toko_kode'	 				=> $data['toko_kode'],
							'base_url' 					=> base_url(),
							'emailfrom'					=> $this->config->item('app_email'),
						]
					];
					$to         = $dataSendEmail['to_email'];
					$subject    = $dataSendEmail['subject'];
					$message    = $this->load->view($dataSendEmail['template'], ['data' => $dataSendEmail['data']], TRUE);
					$dataEmail  = [
						'message' => $message
					];
					$this->sendEmail($to, $subject, $dataEmail);
				}

				return $this->response($update_toko);
			}
		}
		return $this->response([
			'success' => false,
			'message' => 'Toko tidak ditemukan',
		]);
	}

	public function genOapiPos()
	{
		$data = varPost();
		// $data['wajibpajak_id'] = '62ef60ab19c326255dff94234ddd144d';
		// $data['wajibpajak_endpoint'] = 'asdasdsd';
		// $data['wajibpajak_preset'] = '18d8cb3d82d9dae6b04fd7bf78bbd829';

		$dataWP = $this->db->get_where('pajak_wajibpajak', ['wajibpajak_id' => $data['wajibpajak_id']])->row_array();
		$checkToko = $this->db->get_where('v_pajak_pos_v2', ['toko_wajibpajak_id' => $data['wajibpajak_id']])->row_array();

		if (count($checkToko) > 0) {

			$this->db->query("UPDATE pajak_toko set toko_api_penjualan = '{$data['wajibpajak_endpoint']}', toko_tipe_pos = '2', toko_preset_id = '{$data['wajibpajak_preset']}', toko_jadwal_before = '{$data['wajibpajak_schedule_before']}', toko_is_oapi = 'ACTIVE' WHERE toko_id = '{$checkToko['toko_id']}'");

			if ($checkToko['toko_is_oapi'] == 'ACTIVE') {
				return $this->response([
					'success' => true,
					'message' => 'Berhasil mengubah Outer API Wajib Pajak ' . $checkToko['toko_nama'],
				]);
			}

			return $this->response([
				'success' => true,
				'message' => 'Berhasil mengaktifkan Outer API Wajib Pajak ' . $checkToko['toko_nama'],
			]);
		} else {
			$getJenis = $this->db->get_where('pajak_jenis', [
				'jenis_kode' => $dataWP['wajibpajak_sektor_nama']
			])->row_array();
			$getJenisParent = $this->db->get_where('pajak_jenis', [
				'jenis_id' => $getJenis['jenis_parent']
			])->row_array();

			if ($getJenisParent['jenis_nama'] == 'PAJAK RESTORAN') {
				$dbReference = $_ENV['DBPOS_REFERENCE'];
			} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HOTEL') {
				$dbReference = $_ENV['DBPOS_REFERENCE_HOTEL'];
			} elseif ($getJenisParent['jenis_nama'] == 'PAJAK HIBURAN') {
				$dbReference = $_ENV['DBPOS_REFERENCE_HIBURAN'];
			} elseif ($getJenisParent['jenis_nama'] == 'PAJAK PARKIR') {
				$dbReference = $_ENV['DBPOS_REFERENCE_PARKIR'];
			} else {
				$dbReference = $_ENV['DBPOS_REFERENCE'];
			}

			$codeStore = substr(bin2hex(random_bytes(5)), 0, 5);
			$dbname = 'pos_' . $codeStore;
			$timestamp = date('Y-m-d H:i:s');

			$this->db->query("INSERT INTO pajak_toko (toko_id, toko_kode, toko_nama, toko_wajibpajak_id, toko_wajibpajak_npwpd, toko_logo, toko_register_id, toko_registered_at, toko_verified_at, toko_verified_by, toko_status, toko_tipe_pos, toko_api_penjualan, toko_preset_id, toko_is_oapi, toko_jadwal_before) VALUES('" . md5(time()) . "', '{$codeStore}', '{$dataWP['wajibpajak_nama']}', '{$dataWP['wajibpajak_id']}', '{$dataWP['wajibpajak_npwpd']}', NULL, NULL, '{$timestamp}', '{$timestamp}', '{$this->session->userdata('user_pegawai_id')}', '2', 2, '{$data['wajibpajak_endpoint']}', '{$data['wajibpajak_preset']}', 'ACTIVE', '{$data['wajibpajak_schedule_before']}');
			");

			$this->db->query("SELECT pg_terminate_backend(pg_stat_activity.pid) FROM
			pg_stat_activity
			WHERE pg_stat_activity.datname = '" . $dbReference . "' AND pid <> pg_backend_pid();");

			// Create database from template pos_reference
			$this->db->query("CREATE DATABASE $dbname TEMPLATE " . $dbReference . "");

			return $this->response([
				'success' => true,
				'message' => 'POS OAPI wajib pajak berhasil diproses'
			]);
		}
	}
}

/* End of file wajibpajak.php */
/* Location: ./application/modules/wajibpajak/controllers/wajibpajak.php */
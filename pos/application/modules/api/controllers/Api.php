<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends Base_Controller
{
	private $headers = null;
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: *");

		$this->load->model(array(
			'api/MobileActivationModel' 				=> 'activation',
			'api/MobileDeviceModel' 					=> 'device',
			'anggota/AnggotaModel' 						=> 'nasabah',
			'transaksisimpanan/TransaksiSimpananModel' 	=> 'simpanan',
			'transaksisimpanan/TransaksiSimpananDetailModel' => 'detailsimpanan',
			'pengajuanpinjaman/PengajuanPinjamanModel' 	=> 'pinjaman',
			'kartusimpanan/KartusimpananModel' 			=> 'kartusimpanan',
			'tariksimpanan/TarikSimpananModel' 			=> 'tariksimpanan',
			'kartupinjaman/KartupinjamanModel' 			=> 'kartupinjaman',
			'MobileHistoryModel'			 			=> 'history',
			'MobileNotificationModel'			 		=> 'notifikasi',
			'akun/AkunModel'							=> 'akun',
			'chat/MobileMessageModel'					=> 'message',
			'api/MobileMessageInfoModel'				=> 'messageinfo',
			'user/UserTokenModel' 						=> 'usertoken',
			'api/JurubayarModel'						=> 'jurubayar',
			'pengajuantalangan/Pengajuantalanganmodel'	=> 'pengajuantalangan',
			'kategoribarang/KategoribarangModel'		=> 'kategori',
			'kartutalangan/KartutalanganModel'			=> 'kartutalangan',
			'pembayaranpinjaman/PembayaranpinjamandetailModel'	=> 'pembayaranpinjamandetail',
			'pembayarantalangan/Pembayarantalangandetailmodel'	=> 'pembayarantalangandetail',
			'grupgaji/GrupgajiModel' 					=> 'grupgaji',
			'barang/barangModel' 				 => 'barang',
			'api/KeranjangModel'				=> 'keranjang',
			'transaksisimpanankhusus/Transaksisimpanankhususmodel'	=> 'transaksisimpanankhusus'
		));

		$this->headers = getallheaders();
	}

	public function index()
	{
		// phpinfo();
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		$bcd = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 5, 250);
		$this->output->set_content_type('png')->set_output($bcd);
	}

	public function ip($value = '')
	{
		$ip = $this->input->ip_address();
		echo $ip;
	}

	public function penjualan_eror($value = '')
	{
		$penjualan_eror = $this->db->query('SELECT penjualan_tanggal, penjualan_kode, penjualan_detail_qty, penjualan_detail_harga, penjualan_detail_subtotal, barang_kode, barang_nama FROM pos_penjualan_detail 
				LEFT JOIN pos_penjualan on penjualan_detail_parent = penjualan_id
				LEFT JOIN ms_barang on barang_id = penjualan_detail_barang_id
			WHERE (penjualan_detail_qty * penjualan_detail_harga) <> penjualan_detail_subtotal AND penjualan_detail_potongan = 0')->result_array();
		echo "<pre>";
		print_r($penjualan_eror);
		echo "</pre>";
	}

	public function config_eror($value = '')
	{

		echo "<pre>";
		echo $this->config->item('base_jasa_pinjaman');
		echo "</pre>";
	}

	public function config($value = '')
	{
		echo $this->config->item('base_gudang');
		exit;
	}

	public function barcode_anggota($kodeanggota = 'DEFAULT_CODE')
	{
		// error_reporting(-1);
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		$bcd = $generator->getBarcode($kodeanggota, $generator::TYPE_CODE_128, 5, 250);
		$filename = 'assets/barcode/' . $kodeanggota . '.png';
		$data = file_put_contents($filename, $bcd);
		$data = file_get_contents($filename);
		$base64 = 'data:image/png' . ';base64,' . base64_encode($data);
		echo $base64;

		/* if(file_exists($filename)){ 
			$mime = mime_content_type($filename); //<-- detect file type
			header('Content-Length: '.filesize($filename)); //<-- sends filesize header
			header("Content-Type: $mime"); //<-- send mime-type header
			header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
			readfile($filename); //<--reads and outputs the file onto the output buffer
			die(); //<--cleanup
			exit; //and exit
		} */

		/* $bcd = $generator->getBarcode($kodeanggota, $generator::TYPE_CODE_128,5,250);
		$this->output->set_content_type('png')->set_output($bcd); */
	}

	/*aktifasi*/

	public function activate($value = '')
	{
		$data = varPost();
		$opr = array(
			'success' => false,
			'message' => 'Kode Anggota tidak terdaftar pada sistem'
		);
		$cek_anggota = $this->nasabah->read(['anggota_kode' => $data['activate_kodeanggota'], 'anggota_nama' => $data['activate_namaanggota']]);
		if ($cek_anggota) {
			$cek_aktivasi = $this->activation->read($data);
			if (isset($cek_aktivasi['activate_pin'])) {
				$opr = array(
					'success' => false,
					'message' => "Kode Anggota {$data['activate_kodeanggota']} masih dalam proses aktifasi"
				);
			} else {
				$opr = $this->activation->insert(gen_uuid(), $data);
				$opr['message'] = 'Proses pendaftaran berhasil, silakan menunggu sampai akun berhasil di aktifasi';
			}
		}
		$this->response($opr);
	}

	public function cek_login($value = '')
	{
		$data = varPost();
		$cek = $this->activation->read(
			array('activate_kodeanggota' => $data['activate_kodeanggota'])
		);
		if (!$cek || $cek['activate_status'] == 0) {
			$opr = array(
				'success' => false,
				'message' => 'Proses aktifasi pengguna belum dilakukan, hubungi Administrator'
			);
		} else {
			if ($cek['activate_pin'] == $data['activate_pin']) {
				if ($cek['activate_fcmtoken'] == $data['fcmtoken'] || is_null($cek['activate_fcmtoken'])) {
					$opr = [
						'success' => true,
						'data' => $data,
						'mobile_device' => true
					];
				} else {
					$opr = [
						'success' => false,
						// 'data' => $data,
						// 'mobile_device' => true,
						'message' => "Login gagal, keluar dari perangkat sebelumnya untuk masuk ke perangkat baru"
					];
				}
			} else {
				$opr = array(
					'success' => false,
					'message' => 'Pin yang anda masukkan salah!'
				);
			}
		}

		$this->response($opr);
	}
	public function getTest()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://pajak.malangkota.go.id/Monitoring_API/");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt(
			$ch,
			CURLOPT_POSTFIELDS,
			"postvar1=value1&postvar2=value2&postvar3=value3"
		);

		// In real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		curl_close($ch);

		// Further processing ...
		if ($server_output == "OK") {
		} else {
		}
	}
	public function login($value = '')
	{

		$data = varPost('data');
		$cek = $this->activation->read(
			array('activate_kodeanggota' => $data['activate_kodeanggota'], 'activate_pin' => $data['activate_pin'])
		);
		$data_nasabah = $this->nasabah->select([
			'filters_static' => ['anggota_kode' => $data['activate_kodeanggota']],
			'fields' => ['anggota_id', 'anggota_kode', 'anggota_nama', 'anggota_nomor_ktp', 'anggota_nip', 'anggota_kota', 'anggota_kecamatan', 'anggota_kelurahan', 'anggota_alamat', 'anggota_jk', 'anggota_agama', 'anggota_pekerjaan', 'anggota_tgl_gabung', 'anggota_tgl_keluar', 'anggota_foto', 'grup_gaji_nama', 'kelompok_anggota_nama']
		])['data'][0];
		$kodeanggota = $data['activate_kodeanggota'];
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		$bcd = $generator->getBarcode($kodeanggota . '͔', $generator::TYPE_CODE_128, 5, 250);
		$filename = 'assets/barcode/' . $kodeanggota . '.png';
		file_put_contents($filename, $bcd);
		$barkode = file_get_contents($filename);
		$base64 = 'data:image/png' . ';base64,' . base64_encode($barkode);

		$data_nasabah['user_barcode'] = $base64;
		$opr = array(
			'success' => true,
			'message' => 'Login berhasil, mengarahkan ke halaman utama ...',
			'data' => [
				'login_data' => $cek,
				'user_data' => $data_nasabah,
			],
			'token' => base64_encode($data['activate_kodeanggota']),
		);

		$dat['message'] = "";
		$dat['title'] = "";
		$dat['notif_type'] = 'logout';
		$dat['token'] = [$cek['activate_fcmtoken']];
		$this->sendFcm($dat);

		$this->activation->update($cek['activate_id'], array(
			'activate_device' => htmlspecialchars_decode($data['device']),
			'activate_fcmtoken' => $data['fcmtoken'],
		));

		$this->device->insert_update(array(
			// 'device_user_id' => $data_nasabah['anggota_id']
			'device_token' => $data['fcmtoken'],
		), array(
			'device_metadata' => htmlspecialchars_decode($data['device']),
			'device_token' => $data['fcmtoken'],
			'device_last_activity' => date('Y-m-d H:i:s'),
			'device_user_id' => $data_nasabah['anggota_id'],
			'device_user_kode' => $data_nasabah['anggota_kode'],
		));
		$this->response($opr);
	}

	public function kirim_pesan()
	{
		$data = varPost();

		$cek = $this->messageinfo->read(['message_info_id_anggota' => $data['anggota_id']]);

		if ($cek) {

			$info_id = $cek['message_info_id'];
		} else {
			$info_id = gen_uuid($this->messageinfo->get_table());

			$insert = [
				'message_info_id_anggota' => $data['anggota_id'],
				'message_info_datetime' => date('Y-m-d h:i:s')
			];

			$this->messageinfo->insert($info_id, $insert);
		}

		$insert = [
			'message_info_id' => $info_id,
			'message_sento' => $data['anggota_id'],
			'message_content' => base64_encode($data['pesan']),
			'message_datetime' => date('Y-m-d H:i:s'),
			'message_metadata' => htmlspecialchars_decode($data['device']),
			'message_is_sent' => 1,
			'message_is_read' => 0,
			'message_is_admin' => 0
		];

		$this->message->insert(gen_uuid($this->message->get_table()), $insert);
		$this->messageinfo->update($info_id, ['message_info_lastupdate' => date('Y-m-d H:i:s')]);
		$opr = [
			'success' => true,
		];

		$anggota = $this->nasabah->read(array('anggota_id' => $data['anggota_id']));
		$dat = [];
		$token = $this->usertoken->select(array('filters_static' => array('user_token_role_name IN("Administrator","KSP") ' => null)));
		$dat['message'] = $data['pesan'];
		$dat['title'] = 'Pesan Dari ' . $anggota['anggota_nama'];
		$dat['type'] = 'success';
		$dat['notif_type'] = 'message';
		$dat['token'] = array_column($token['data'], 'user_token');
		$dat['anggota_kode'] = $anggota['anggota_kode'];
		$dat['aksi'] = "https://apps.ekokapti.id/ekokapti/";
		$this->sendFcm($dat, 1);

		$this->response($opr);
	}

	public function tampil_pesan($id = null)
	{
		$cek = $this->messageinfo->read(['message_info_id_anggota' => $id]);

		if ($cek) {
			$pesan = $this->message->select([
				'filters_static' => ['message_info_id' => $cek['message_info_id']],
				'sort_static' => ['message_datetime asc']
			]);

			$this->db->where(['message_info_id' => $cek['message_info_id'], 'message_is_admin' => 1])->update('mobile_message', ['message_is_read' => 1]);

			$tanggal = $this->db->select('DATE(message_datetime) as tanggal')->where(['message_info_id' => $cek['message_info_id']])->group_by('DATE(message_datetime)')->get('mobile_message')->result();

			if ($pesan['total'] > 0) {

				foreach ($pesan['data'] as $key => $value) {
					$pesan['data'][$key]['message_content'] = base64_decode($value['message_content']);
				}

				$opr = [
					'success' => true,
					'data' => $pesan['data'],
					'tanggal' => $tanggal
				];
			} else {
				$opr = [
					'success' => false
				];
			}
		} else {
			$opr = [
				'success' => false
			];
		}

		$this->response($opr);
	}

	// public function login($value='')
	// {
	// 	$data = varPost();
	// 	$cek = $this->activation->read(
	// 		array('activate_kodeanggota' => $data['activate_kodeanggota'], 'activate_pin' => $data['activate_pin'])
	// 	);
	// 	$opr = array(
	// 		'success' => false,
	// 		'message' => 'Gagal memasuki sistem, pengguna tidak terdaftar, atau belum melakukan aktifasi pengguna'
	// 	);

	// 	if (!$cek || $cek['activate_status'] == 0) {
	// 		$opr = array(
	// 			'success' => false,
	// 			'message' => 'Proses aktifasi pengguna belum dilakukan, hubungi Administrator'
	// 		);
	// 	} else {
	// 		$data_nasabah = $this->nasabah->select([
	// 					'filters_static' => ['anggota_kode' => $data['activate_kodeanggota']],
	// 					'fields' => ['anggota_id','anggota_kode','anggota_nama','anggota_nomor_ktp','anggota_nip','anggota_kota','anggota_kecamatan','anggota_kelurahan','anggota_alamat','anggota_jk','anggota_agama','anggota_pekerjaan','anggota_tgl_gabung','anggota_tgl_keluar','anggota_foto','grup_gaji_nama','kelompok_anggota_nama']])['data'][0];

	// 		$kodeanggota = $data['activate_kodeanggota'];
	// 		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
	// 		$bcd = $generator->getBarcode($kodeanggota, $generator::TYPE_CODE_128,5,250);
	// 		$filename='assets/barcode/'.$kodeanggota.'.png';
	// 		file_put_contents($filename, $bcd);
	// 		$barkode = file_get_contents($filename);
	// 		$base64 = 'data:image/png' . ';base64,' . base64_encode($barkode);

	// 		$data_nasabah['user_barcode'] = $base64;
	// 		$opr = array(
	// 			'success' => true,
	// 			'message' => 'Login berhasil, mengarahkan ke halaman utama ...',
	// 			'data' => [
	// 				'login_data' => $cek,
	// 				'user_data' => $data_nasabah,
	// 			],
	// 			'token' => base64_encode($data['activate_kodeanggota'])
	// 		);

	// 		$this->activation->insert_update(array(
	// 			'activate_kodeanggota' => $data['activate_kodeanggota']
	// 		),array(
	// 			'activate_device' => htmlspecialchars_decode($data['device']),
	// 			'activate_fcmtoken' => $data['fcmtoken'],
	// 		));

	// 		$this->device->insert_update(array(
	// 			// 'device_user_id' => $data_nasabah['anggota_id']
	// 			'device_token' => $data['fcmtoken'],
	// 		),array(
	// 			'device_metadata' => htmlspecialchars_decode($data['device']),
	// 			'device_token' => $data['fcmtoken'],
	// 			'device_last_activity' => date('Y-m-d H:i:s'),
	// 			'device_user_id' => $data_nasabah['anggota_id'],
	// 			'device_user_kode' => $data_nasabah['anggota_kode'],
	// 		));
	// 	}
	// 	$this->response($opr);
	// }

	public function logout($value = '')
	{
		$user = $this->db->get_where('mobile_activation', ['activate_kodeanggota' => $value])->row();
		if ($this->db->where(['activate_id' => $user->activate_id])->update('mobile_activation', ['activate_fcmtoken' => null, 'activate_device' => null])) {
			unlink('./assets/barcode/' . $value . '.png');
			$opr = array(
				'success' => true
			);
		} else {
			$opr = array(
				'success' => false
			);
		}
		$this->response($opr);
	}

	public function logout_jurubayar($value = '')
	{
		$user = $this->db->get_where('ms_juru_bayar', ['juru_bayar_username' => $value])->row();
		if ($this->db->where(['juru_bayar_id' => $user->juru_bayar_id])->update('ms_juru_bayar', ['fcmtoken' => null])) {
			$opr = array(
				'success' => true
			);
		}
		$this->response($opr);
	}

	public function cek_session()
	{
		$data = varPost();
		$anggota = $this->activation->read(array('activate_kodeanggota' => $data['anggota_kode'], 'activate_status' => 1));
		if ($anggota) {
			$opr = array(
				'success' => true,
				'activate_fcmtoken' => $anggota['activate_fcmtoken']
			);
		} else {
			$opr = array(
				'success' => false
			);
		}

		$this->response($opr);
	}

	public function cek_session_jurubayar()
	{
		$data = varPost();
		$anggota = $this->jurubayar->read(array('juru_bayar_username' => $data['username']));
		if ($anggota) {
			$opr = array(
				'success' => true,
				'activate_fcmtoken' => $anggota['fcmtoken']
			);
		} else {
			$opr = array(
				'success' => false
			);
		}

		$this->response($opr);
	}

	public function cek_login_jurubayar($value = '')
	{
		$data = varPost();
		$cek = $this->jurubayar->read(
			array('juru_bayar_username' => $data['juru_bayar_username'], 'juru_bayar_pin' => $data['juru_bayar_pin'])
		);
		if (!$cek || $cek['juru_bayar_deleted_at'] == 1) {
			$opr = array(
				'success' => false,
				'message' => 'Pengguna Tidak Ditemukan, hubungi Administrator'
			);
		} else {
			if ($cek['fcmtoken'] == $data['fcmtoken'] || empty($cek['fcmtoken'])) {
				$opr = [
					'success' => true,
					'data' => $data,
					'mobile_device' => true
				];
			} else {
				$opr = [
					'success' => false,
					// 'data' => $data,
					'mobile_device' => false,
					'message' => "Login gagal, keluar dari perangkat sebelumnya untuk masuk ke perangkat baru"
				];
			}
		}

		$this->response($opr);
	}
	public function login_jurubayar($value = '')
	{

		$data = varPost('data');
		$cek = $this->jurubayar->read(
			array('juru_bayar_username' => $data['juru_bayar_username'], 'juru_bayar_pin' => $data['juru_bayar_pin'])
		);

		$data_jurubayar = array(
			'juru_bayar_username'	=> $cek['juru_bayar_username'],
			'juru_bayar_nama'		=> $cek['juru_bayar_nama'],
			'juru_bayar_telp'		=> $cek['juru_bayar_telp'],
		);

		$grupgaji = $this->grupgaji->read(['juru_bayar_id' => $cek['juru_bayar_id']]);

		$data_jurubayar['grup_gaji_nama'] = $grupgaji['grup_gaji_nama'];
		$data_jurubayar['grup_gaji_kode'] = $grupgaji['grup_gaji_kode'];
		$opr = array(
			'success' => true,
			'message' => 'Login berhasil, mengarahkan ke halaman utama ...',
			'data' => [
				'login_data' => $cek,
				'user_data' => $data_jurubayar,
			],
			'token' => base64_encode($cek['juru_bayar_id']),
		);

		$this->jurubayar->update($cek['juru_bayar_id'], array(
			'fcmtoken' => $data['fcmtoken'],
		));

		$this->response($opr);
	}

	public function get_saldo($value = '')
	{
		$kodeanggota = base64_decode($this->headers['Token']);
		$saldo = $this->nasabah->select([
			'filters_static' => ['anggota_kode' => $kodeanggota],
			'fields' => ['anggota_saldo_simp_pokok', 'anggota_saldo_simp_manasuka', 'anggota_saldo_simp_wajib', 'anggota_saldo_simp_wajib_khusus', 'anggota_saldo_simp_tabungan_hari_tua', 'anggota_saldo_simp_titipan_belanja',]
		]);
		$this->response($saldo);
	}

	public function get_saldo_simpanan($value = '')
	{
		$kodeanggota = base64_decode($this->headers['Token']);
		$saldo = $this->nasabah->select([
			'filters_static' => ['anggota_kode' => $kodeanggota],
			'fields' => ['anggota_saldo_simp_pokok', 'anggota_saldo_simp_manasuka', 'anggota_saldo_simp_wajib', 'anggota_saldo_simp_wajib_khusus', 'anggota_saldo_simp_tabungan_hari_tua', 'anggota_saldo_simp_titipan_belanja',]
		]);
		$simp_khusus = $this->transaksisimpanankhusus->select(array('filters_static' => array('simpanan_khusus_anggota_id' => $kodeanggota, 'simpanan_khusus_status' => 1)));

		$total_simp_khusus = 0;
		foreach ($simp_khusus['data'] as $key => $value) {
			$total_simp_khusus += $value['simpanan_khusus_nilai_setor'];
		}
		$saldo['data'][0]['simpanan_khusus_nilai_setor'] = $total_simp_khusus;
		// print_r($this->headers);exit;
		$opr = $this->pinjaman->select([
			'filters_static' => [
				'pengajuan_anggota' => $this->headers['ID'],
				'pengajuan_status' => 2
			]
		]);

		$tal = $this->pengajuantalangan->select([
			'filters_static' => [
				'pengajuan_talangan_anggota' => $this->headers['ID'],
				'pengajuan_talangan_status' => 2
			]
		]);

		if ($tal['total'] > 0) {
			foreach ($tal['data'] as $row) {
				$opr['data'][] = [
					'pengajuan_sisa_angsuran' => $row['pengajuan_talangan_sisa_angsuran']
				];
			}
			$opr['total'] += $tal['total'];
			$opr['success'] = success;
		}

		$this->response(['saldo' => $saldo, 'pinjaman' => $opr]);
	}

	public function get_simpanan($value = '')
	{
		// $kodeanggota = $this->headers['ID'];
		$kodeanggota = $value;
		$saldo = $this->nasabah->select([
			'filters_static' => ['anggota_id' => $kodeanggota],
			'fields' => ['anggota_saldo_simp_pokok', 'anggota_saldo_simp_manasuka', 'anggota_saldo_simp_wajib', 'anggota_saldo_simp_wajib_khusus', 'anggota_saldo_simp_tabungan_hari_tua', 'anggota_saldo_simp_titipan_belanja', 'anggota_saldo_shu']
		]);

		$simp_khusus = $this->transaksisimpanankhusus->select(array('filters_static' => array('simpanan_khusus_anggota_id' => $kodeanggota, 'simpanan_khusus_status' => 1)));

		$total_simp_khusus = 0;
		foreach ($simp_khusus['data'] as $key => $value) {
			$total_simp_khusus += $value['simpanan_khusus_nilai_setor'];
		}
		$saldo['data'][0]['simpanan_khusus_nilai_setor'] = $total_simp_khusus;

		$tanggal = $this->nasabah->select([
			'filters_static' => ['anggota_id' => $kodeanggota],
			'fields' => ['anggota_update_at']
		]);
		$res = ['saldo' => $saldo, 'tgl' => $tanggal];
		$this->response($res);
	}

	public function get_pinjaman($value = '')
	{
		$data_get = varGet();
		$anggota_id = $data_get['ID'];
		$pinjaman = $this->pinjaman->select([
			'filters_static' => [
				'pengajuan_anggota' => $anggota_id,
				'pengajuan_status' => $data_get['status']
			],
			'fields' => array('pengajuan_id', 'pengajuan_tgl', 'pengajuan_jumlah_pinjaman', 'pengajuan_tenor', 'pengajuan_jenis', 'pengajuan_tgl_verifikasi', 'pengajuan_tgl_realisasi', 'pengajuan_status', 'pengajuan_sisa_angsuran', 'pengajuan_pokok_bulanan', 'pengajuan_jasa_bulanan', 'pengajuan_angsuran', 'pengajuan_jatuh_tempo')
		]);
		$haji = $this->pengajuantalangan->select([
			'filters_static' => [
				'pengajuan_talangan_anggota' => $anggota_id,
				'pengajuan_talangan_status' => $data_get['status']
			],
			'fields' => array('pengajuan_talangan_id', 'pengajuan_talangan_tgl', 'pengajuan_talangan_jumlah_pinjaman', 'pengajuan_talangan_tenor', 'pengajuan_talangan_jenis', 'pengajuan_talangan_tgl_verifikasi', 'pengajuan_talangan_tgl_realisasi', 'pengajuan_talangan_status', 'pengajuan_talangan_sisa_angsuran', 'pengajuan_talangan_pokok_bulanan', 'pengajuan_talangan_jasa_bulanan', 'pengajuan_talangan_angsuran', 'pengajuan_talangan_jatuh_tempo')
		]);
		if ($haji['total'] > 0) {
			foreach ($haji['data'] as $data_haji) {
				$pinjaman['data'][] = [
					'pengajuan_id' => $data_haji['pengajuan_talangan_id'],
					'pengajuan_tgl' => $data_haji['pengajuan_talangan_tgl'],
					'pengajuan_jumlah_pinjaman' => $data_haji['pengajuan_talangan_jumlah_pinjaman'],
					'pengajuan_tenor' => $data_haji['pengajuan_talangan_tenor'],
					'pengajuan_jenis' => $data_haji['pengajuan_talangan_jenis'],
					'pengajuan_tgl_verifikasi' => $data_haji['pengajuan_talangan_tgl_verifikasi'],
					'pengajuan_tgl_realisasi' => $data_haji['pengajuan_talangan_tgl_realisasi'],
					'pengajuan_status' => $data_haji['pengajuan_talangan_status'],
					'pengajuan_sisa_angsuran' => $data_haji['pengajuan_talangan_sisa_angsuran'],
					'pengajuan_pokok_bulanan' => $data_haji['pengajuan_talangan_pokok_bulanan'],
					'pengajuan_jasa_bulanan' => $data_haji['pengajuan_talangan_jasa_bulanan'],
					'pengajuan_angsuran' => $data_haji['pengajuan_talangan_angsuran'],
					'pengajuan_jatuh_tempo' => $data_haji['pengajuan_talangan_jatuh_tempo']
				];
			}
			$pinjaman['total'] += $haji['total'];
			$pinjaman['success'] = true;
		}
		$key = array_column($pinjaman['data'], 'pengajuan_tgl');
		array_multisort($key, SORT_DESC, $pinjaman['data']);
		$this->response($pinjaman);
	}

	public function get_nasabah($value = '')
	{
		$data = $this->nasabah->read(['anggota_kode' => $value]);
		$this->response($data);
	}

	public function get_pembayaran_simpanan($value = '')
	{
		$userdata = $this->get_userdata(base64_decode($this->headers['Token']));
		$his_simpanan = $this->simpanan->select([
			'filters_static' => [
				'pembayaran_simpanan_grup_gaji' => $userdata['anggota_grup_gaji']
			],
			'sort_static' => ['pembayaran_simpanan_bulan_tagihan asc']
		]);

		foreach ($his_simpanan['data'] as $key => $value) {
			$det_simpanan = $this->detailsimpanan->select([
				'filters_static' => [
					'pembayaran_simpanan_detail_anggota' => $userdata['anggota_id'],
					'pembayaran_simpanan_detail_parent' => $value['pembayaran_simpanan_id']
				]
			]);
			$his_simpanan['data'][$key]['detailsimpanan'][] = $det_simpanan;
		}
		$this->response($his_simpanan);
	}

	public function cek_pengajuan()
	{
		$id = base64_decode(varPost('data'));
		$pengajuan_masuk = $this->pinjaman->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_status' => 0)));
		$pengajuan_diterima = $this->pinjaman->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_status' => 1)));
		$pengajuan_ditolak = $this->pinjaman->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_status' => 3)));
		$haji_masuk = $this->pengajuantalangan->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_talangan_status' => 0)));
		$haji_diterima = $this->pengajuantalangan->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_talangan_status' => 1)));
		$haji_ditolak = $this->pengajuantalangan->select(array('filters_static' => array('juru_bayar_id' => $id, 'pengajuan_talangan_status' => 3)));
		$pengajuan = array(
			'pengajuanmasuk' => $pengajuan_masuk['total'] + $haji_masuk['total'],
			'pengajuanditerima' => $pengajuan_diterima['total'] + $haji_diterima['total'],
			'pengajuanditolak' => $pengajuan_ditolak['total'] + $haji_ditolak['total']
		);

		$this->response(['data' => $pengajuan]);
	}

	public function print_lembar_permohonan($id)
	{
		$data = $this->pinjaman->read(['pengajuan_id' => $id]);
		$jb = $this->jurubayar->read(['juru_bayar_id' => $data['pengajuan_juru_bayar_id']]);
		$html = '
			<table style="width: 18cm;">
				<tr>
					<td class="t-center bb-2-double" style="padding-bottom: 0.3cm;">
						<h4 class="b" style="margin-bottom:">
							<span>PERMOHONAN KREDIT UANG</span><br/>
							<span class="i">KPRI EKO KAPTI</span><br/>
							<span>KANTOR KEMENTERIAN AGAMA KABUPATEN MALANG</span>
						</h4>
						<span class="f16">
							JL. Kolonel Sugiono 39 Telp. (0341) 834894 Malang
						</span>
					</td>
				</tr>
			</table>
		';


		$gaji_bersih   = (($data['pengajuan_gaji_bersih'] == '') ? '_______________' : $data['pengajuan_gaji_bersih']);
		$gaji_lain 	   = (($data['pengajuan_gaji_lainnya'] == '') ? '_______________' : $data['pengajuan_gaji_lainnya']);
		$pinjam_total  = (($data['pengajuan_jumlah_pinjaman'] == '') ? '_______________' : number_format($data['pengajuan_jumlah_pinjaman'], 0, '', '.'));
		$pinjam_sisa   = (($data['pengajuan_sisa_pinjaman_kpri'] == '') ? '_______________' : number_format($data['pengajuan_sisa_pinjaman_kpri'], 0, '', '.'));
		$pinjam_lain   = (($data['pengajuan_sisa_pinjaman_lainnya'] == '') ? '_______________' : number_format($data['pengajuan_sisa_pinjaman_lainnya'], 0, '', '.'));
		$pinjam_angsur = (($data['pengajuan_pokok_bulanan'] == '') ? '_______________' : number_format($data['pengajuan_pokok_bulanan'], 0, '', '.'));
		$jml_keluarga  = (($data['pengajuan_jml_tanggungan'] == '') ? '0' : $data['pengajuan_jml_tanggungan']);
		$tgl_pensiun   = (($data['pengajuan_waktu_pensiun'] == '0000-00-00') ? '-' : $data['pengajuan_waktu_pensiun']);
		$html .= '
			<table style="width: 18cm;">
				<tr>
					<td class="t-left" colspan="3">Diterima Pengurus :</td>
				</tr>
				<tr>
					<td class="t-left" style="width: 1.2cm;">Tgl</td>
					<td class="t-center" style="width: 0.3cm;">:</td>
					<td class="t-left">' . date('d-m-Y', strtotime($data['pengajuan_tgl'])) . '</td>
				</tr>
				<tr>
					<td class="t-left" style="width: 1.5cm;">No</td>
					<td class="t-center" style="width: 0.3cm;">:</td>
					<td class="t-left">' . $data['pengajuan_no'] . '</td>
				</tr>
				<tr>
					<td class="t-left" colspan="3" style="padding: 0.4cm 0;">Yang bertanda tangan di bawah ini saya :</td>
				</tr>
			</table>
			<table style="width: 18cm;">
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">1.</td>
					<td class="t-left" style="width: 4.5cm;">N a m a / N I P</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">' . $data['anggota_nama'] . ' / ' . $data['anggota_nip'] . '</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">2.</td>
					<td class="t-left">Pekerjaan / Jabatan</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">' . ($data['anggota_pekerjaan'] == '' ? '-' : $data['anggota_pekerjaan']) . '</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">3.</td>
					<td class="t-left">Golongan / Ruang Gaji</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">' . $data['grup_gaji_nama'] . '</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">4.</td>
					<td class="t-left">Tanggal lahir</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">' . date('d-m-Y', strtotime($data['pengajuan_tgl_lahir'])) . '</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">5.</td>
					<td class="t-left">Gaji bersih sebulan</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left">
						Rp. ' . number_format($gaji_bersih, 0, '', '.') . '
					</td>
					<td class="t-left" style="width: 4.5cm;">Penghasilan lain-lain</td>
					<td class="t-left">
						Rp. ' . number_format($gaji_lain, 0, '', '.') . '
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">6.</td>
					<td class="t-left" colspan="4">Masih mempunyai sisa pinjaman pada KPRI Eko Kapti sebesar</td>
					<td class="t-left">
						Rp. ' . $pinjam_sisa . '
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;"></td>
					<td class="t-left" colspan="4">Pada pihak lain sebesar</td>
					<td class="t-left">
						Rp. ' . number_format($pinjam_lain, 0, '', '.') . '
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">7.</td>
					<td class="t-left" colspan="3">Jumlah keluarga yang menjadi tanggungan</td>
					<td class="t-left" style="0.3cm;" colspan="2">
						: ' . $jml_keluarga . ' orang
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">8.</td>
					<td class="t-left" colspan="3">Masa pensiun berlaku mulai bulan</td>
					<td class="t-left" style="0.3cm;" colspan="2">
						: ' . $tgl_pensiun . '
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">9.</td>
					<td class="t-left">Tempat Tinggal</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3" style="padding-bottom: 0.1cm;">' . $data['anggota_alamat'] . '</td>
				</tr>
				<tr>
					<td class="t-left" colspan="3" style="padding-bottom: 0.1cm;"></td>
					<td class="t-left" colspan="3">TELP. ' . $data['pengajuan_telp'] . '</td>
				</tr>
			</table>
			<table style="width: 18cm;">
				<tr>
					<td class="t-justify" style="line-height: 1.6;">
						<p>&emsp;&emsp; Dengan ini mengajukan permohonan pinjam / kredit uang kepada Pengurus KPRI Eko Kapti Kankemenag Kab. Malang sebesar <b>Rp. 
							' . $pinjam_total . ' ( <i>' . ucfirst(strtolower($this->terbilang($data['pengajuan_jumlah_pinjaman']))) . ' rupiah</i> ) </b>
							untuk keperluan : ' . $data['pengajuan_keperluan'] . '</p>
						<p>&emsp;&emsp; Selanjutnya saya sanggup mengangsur tiap-tiap bulan sebesar <b>Rp. ' . $pinjam_angsur . '<i> ( ' . ucfirst(strtolower($this->terbilang($data['pengajuan_pokok_bulanan']))) . ' )</i></b> ditambah jasa sesuai dengan ketentuan yang berlaku selama : ' . $data['pengajuan_tenor'] . ' bulan.</p>
						<p>&emsp;&emsp; Untuk membayar angsuran setiap bulan, saya memberi kuasa kepada Bendaharawan Kankemenag Kab. Malang atau Petugas Pembayar Gaji untuk memotong gaji saya sebesar tagihan yang ditetapkan oleh Pengurus KPRI Eko Kapti sampai lunas.</p>
						<p>&emsp;&emsp; Demikian atas perhatiannya saya sampaikan terimakasih.</p>
					</td>
				</tr>
			</table>
		';
		$html .= '
			<table style="width: 18cm;">
				<tr>
					<td class="t-center" style="width: 30%;"></td>
					<td class="t-center" style="width: 40%;"></td>
					<td class="t-center" style="width: 30%;">Malang, tgl. ' . phpChgDate(date('Y-m-d')) . '</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="t-center">P E M O H O N</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="bb-1 t-center v-bottom" style="height: 2cm;">' . $data['anggota_nama'] . '</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="t-center">NIP. ' . $data['anggota_nip'] . '</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="t-center">Diajukan pada ' . date("d-m-Y", strtotime($data['pengajuan_create_at'])) . ' pukul ' . date("H:i:s", strtotime($data['pengajuan_create_at'])) . '</td>
				</tr>
				<tr>
					<td class="t-center" colspan="3" style="height:1cm;">
						MENGETAHUI / MENYETUJUI<br/>
						Pembayar Gaji Pegawai / Guru
					</td>
				</tr>';
		if ($data['pengajuan_verified_at'] != NULL) {
			$html .= '<tr>
						<td class="t-center" colspan="3" style="height:1cm;">
							Telah disetujui pada <br/>' . date("d-m-Y", strtotime($data['pengajuan_verified_at'])) . ' pukul ' . date("H:i:s", strtotime($data['pengajuan_verified_at'])) . '
						</td>
					</tr>';
		} else {
			$html .= '<tr>
						<td class="t-center" colspan="3" style="height:3cm;"></td>
					</tr>';
		}
		$html .= '<tr>
					<td class="t-center"></td>
					<td class="t-left bb-1">' . $jb['juru_bayar_nama'] . '</td>
					<td class="t-center"></td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-left">NIP. ' . $jb['juru_bayar_nip'] . '</td>
					<td class="t-center"></td>
				</tr>';
		$html .= '</table>';
		if ($data['pengajuan_verified_at'] != NULL) {
			$html .= '<table>
					<tr>
						<td class="bb-1 bt-1 br-1 bl-1 t-center">Dokumen ini ditandatangani secara elektronik dengan menggunakan aplikasi mobile Anggota KPRI Ekokapti dan aplikasi mobile Juru Bayar KPRI Ekokapti yang diterbitkan oleh KPRI Ekokapti</td>
					</tr>
				</table>
			';
		}
		/*<table style="width: 18cm;">
				<tr>
					<td class="t-left" style="width: 58%;"></td>
					<td class="t-left t-justify" style="width: 42%;">
						Telah dibahas Tim Simpan Pinjam pada<br/>
						Tanggal _______________ dan setuju di beri<br/>
						pinjam sebesar Rp. '.$pinjam_total.'
					</td>
				</tr>
				<tr>
					<td class="t-right" colspan="3">A.n Tim Simpan Pinjam</td>
				</tr>
				<tr>
					<td class="t-left b u" colspan="3">Catatan lain-lain</td>
				</tr>
			</table>*/
		$this->response(createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'LEGAL',
			'file_name'     => 'Pengajuan Kredit',
			'title'         => 'Pengajuan Kredit',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '16 18 10 16',
			'font_face'     => 'Tahoma',
			'font_size'     => '11pt',
			'footer'        => '{PAGENO}/{nb}',
		)));
	}

	public function get_pengajuan()
	{
		$data = varPost();

		$pinjaman = $this->pinjaman->select(
			array(
				'filters_static' => array(
					'pengajuan_status' => $data['status'],
					'juru_bayar_id' => base64_decode($data['id'])
				),
				'fields' => array('pengajuan_id', 'pengajuan_tgl', 'anggota_nama', 'pengajuan_jumlah_pinjaman', 'grup_gaji_nama', 'pengajuan_tenor', 'pengajuan_jenis', ''),
				'sort_static' => array('pengajuan_tgl desc')
			)
		);

		$haji = $this->pengajuantalangan->select(array(
			'filters_static' => array(
				'pengajuan_talangan_status' => $data['status'],
				'juru_bayar_id' => base64_decode($data['id'])
			),
			'fields' => array('pengajuan_talangan_id', 'pengajuan_talangan_tgl', 'anggota_nama', 'pengajuan_talangan_jumlah_pinjaman', 'grup_gaji_nama', 'pengajuan_talangan_tenor', 'pengajuan_talangan_jenis', 'pengajuan_talangan_tgl_verifikasi'),
			'sort_static' => array('pengajuan_talangan_tgl desc')
		));
		if ($haji['total'] > 0) {
			foreach ($haji['data'] as $data_haji) {
				$pinjaman['data'][] = [
					'pengajuan_id' => $data_haji['pengajuan_talangan_id'],
					'pengajuan_tgl' => $data_haji['pengajuan_talangan_tgl'],
					'anggota_nama' => $data_haji['anggota_nama'],
					'pengajuan_jumlah_pinjaman' => $data_haji['pengajuan_talangan_jumlah_pinjaman'],
					'grup_gaji_nama' => $data_haji['grup_gaji_nama'],
					'pengajuan_tenor' => $data_haji['pengajuan_talangan_tenor'],
					'pengajuan_jenis' => $data_haji['pengajuan_talangan_jenis'],
					'pengajuan_tgl_verifikasi' => $data_haji['pengajuan_talangan_tgl_verifikasi'],
				];

				$pinjaman['success'] = true;
			}
			$pinjaman['total'] = $pinjaman['total'] + $haji['total'];
		}
		$key = array_column($pinjaman['data'], 'pengajuan_tgl');
		array_multisort($key, SORT_DESC, $pinjaman['data']);
		$this->response($pinjaman);
	}

	public function select_kategori()
	{
		$kategori = $this->kategori->select(array(
			'filters_static' => array('kategori_barang_parent' => '#', 'kategori_barang_aktif' => '1'),
			'fields' => ['kategori_barang_id', 'kategori_barang_nama']
		));
		$this->response($kategori);
	}

	/*begin toolkit*/
	private function get_userdata($value = '')
	{
		$data = $this->nasabah->read(['anggota_kode' => $value]);

		return $data;
	}
	/*end toolkit*/


	/*begin kartu simpanan*/
	public function get_simpanan_manasuka($value = '')
	{
		$res = $this->kartusimpanan->select(array(
			'filters_static' => array(
				'kartu_simpanan_anggota' => $value,
				'kartu_simpanan_kode like "%MSK%"' => null,
			),
			'sort_static' => array('kartu_simpanan_tanggal desc')
		));

		$this->response($res);
	}
	/*end kartu simpanan*/


	/*begin kartu penarikan simpanan*/
	public function get_penarikan_manasuka($value = '')
	{
		$saldo = $this->nasabah->read($value);
		$res = $this->kartusimpanan->select(array(
			'filters_static' => array(
				'kartu_simpanan_anggota' => $value,
				'kartu_simpanan_kode like "%MSK%"' => null,
				'kartu_simpanan_saldo_keluar > 0' => null
			),
			'sort_static' => array('kartu_simpanan_tanggal desc')
		));
		$this->response(['saldo' => $saldo['anggota_saldo_simp_manasuka'], 'kartu' => $res]);
	}
	/*end kartu simpanan*/

	/*begin kartu */
	public function save_penarikan($id = '')
	{
		$data = varPost();
		$anggota = $this->nasabah->read($id);
		if ($anggota['anggota_id']) {
			$insert = [
				'tarik_simpanan_anggota' 		=> $id,
				'tarik_simpanan_tgl_request'	=> date('Y-m-d'),
				'tarik_simpanan_bulan' 			=> date('Y-m'),
				'tarik_simpanan_manasuka_nominal' => $data['nominal'],
				'tarik_simpanan_atas_nama' 		=> $anggota['anggota_nama'],
				'tarik_simpanan_metode_bayar' 	=> $data['ambil'],
				'tarik_simpanan_create_by'		=> $this->session->userdata('user_pegawai_id'),
				'tarik_simpanan_create'			=> date('Y-m-d H:i:s'),
				'tarik_simpanan_bank'			=> ($data['ambil'] == 'B' ? 'a15208b8a30f348ea01851a59d509254' : null),
				'tarik_simpanan_no_referensi'	=> ($data['banktujuan'] == 'B' ? $data['rekening'] : null),
				'tarik_simpanan_total'			=> ($data['ambil'] == 'T' ? $data['nominal'] : 0),
				'tarik_simpanan_keterangan'		=> $data['catatan'],
				'tarik_simpanan_status'			=> 1,
				'tarik_simpanan_total_bank'		=> ($data['ambil'] == 'B' ? $data['nominal'] : 0)
			];
			$insert = $this->tariksimpanan->insert(gen_uuid($this->tariksimpanan->get_table()), $insert);
		}
		$this->response($insert);
	}

	public function save_tabungan($id = '')
	{
		$data = varPost();
		$data['nominal'] = str_replace(',', '', $data['nominal']);
		$anggota = $this->nasabah->read($id);
		if ($anggota['anggota_id']) {
			$parent = gen_uuid($this->simpanan->get_table());
			$files = $_FILES['buktitransfer']['name'];
			$config['upload_path'] = './assets/buktitf/';
			$config['allowed_types'] = 'gif|jpg|png|doc|txt';
			$config['max_size'] = 1024;
			$config['file_name'] = $parent . '-' . $_FILES['buktitransfer']['name'];
			if ($files) {
				$_FILES['upload_field_name']['name']        = $_FILES['buktitransfer']['name'];
				$_FILES['upload_field_name']['type']		= $_FILES['buktitransfer']['type'];
				$_FILES['upload_field_name']['tmp_name']    = $_FILES['buktitransfer']['tmp_name'];
				$_FILES['upload_field_name']['error']       = $_FILES['buktitransfer']['error'];
				$_FILES['upload_field_name']['size']        = $_FILES['buktitransfer']['size'];
				if (($_FILES['buktitransfer']['size'] / 1000) > $config['max_size']) {
					$insert = ['success' => false, 'msg' => 'Ukuran File Harus Kurang Dari 1 Mb!'];
				} else {
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('upload_field_name')) {
						$status = 'error';
						$msg = $this->upload->display_errors('', '');
						$insert = ['success' => false, 'msg' => $msg];
					} else {
						$img = $this->upload->data();
						$data['buktitransfer'] = $img['file_name'];

						$dt_insert = [
							'pembayaran_simpanan_tgl_tagihan'			=> date('Y-m-d'),
							'pembayaran_simpanan_bulan'					=> date('Y-m'),
							'pembayaran_simpanan_total'					=> $data['nominal'],
							'pembayaran_simpanan_atas_nama'				=> $anggota['anggota_nama'],
							'pembayaran_simpanan_status'				=> '2',
							'pembayaran_simpanan_grup_gaji'				=> $anggota['anggota_grup_gaji'],
							'pembayaran_simpanan_no_referensi'			=> $data['referensi'],
							'pembayaran_simpanan_metode_bayar'			=> 'B',
							'pembayaran_simpanan_bank'					=> $data['banktujuan'],
							'pembayaran_simpanan_nominal_bayar_tunai'	=> $data['nominal'],
							'pembayaran_simpanan_create_at'				=> date('Y-m-d H:i:s'),
							'pembayaran_simpanan_bukti'					=> $data['buktitransfer']
						];
						$dt_detail = [
							'pembayaran_simpanan_detail_parent' 		=> $parent,
							'pembayaran_simpanan_detail_msk'			=> $data['nominal'],
							'pembayaran_simpanan_detail_anggota'		=> $id,
							'pembayaran_simpanan_detail_bulan_tagihan' 	=> date('Y-m'),
							'pembayaran_simpanan_detail_is_lunas'		=> '1',
						];
						$insert = $this->simpanan->insert($parent, $dt_insert);
						if ($insert['success'] == true) {
							$detail = $this->detailsimpanan->insert(gen_uuid($this->detailsimpanan->get_table()), $dt_detail);
						}
					}
				}
			}
		}
		$this->response($insert);
	}
	/*begin kartu penarikan simpanan*/
	public function get_data_pinjaman($value = '')
	{
		$saldo = $this->nasabah->read($value);
		$res = $this->kartupinjaman->select(array(
			'filters_static' => array(
				'kartu_pinjaman_anggota' => $value,
				'kartu_pinjaman_kode not like "%KP-H%"' => null,
			),
			'sort_static' => array('kartu_pinjaman_create_at desc')
		));
		$haji = $this->kartutalangan->select(array(
			'filters_static' => array(
				'kartu_talangan_anggota' => $value,
				'kartu_talangan_kode not like "%KP-H%"' => null,
			),
			'sort_static' => array('kartu_talangan_create_at desc')
		));

		if ($haji['total'] > 0) {
			foreach ($haji['data'] as $data_haji) {
				$res['data'][] = [
					'kartu_pinjaman_create_at' => $data_haji['kartu_talangan_create_at'],
					'kartu_pinjaman_saldo_pinjam' => $data_haji['kartu_talangan_saldo_pinjam'],
					'kartu_pinjaman_saldo_bayar' => $data_haji['kartu_talangan_saldo_bayar'],
					'kartu_pinjaman_saldo_bunga' => $data_haji['kartu_talangan_saldo_bunga'],
					'kartu_pinjaman_saldo_akhir' => $data_haji['kartu_talangan_saldo_akhir'],
					'kartu_pinjaman_kode' => $data_haji['kartu_talangan_kode'],
				];
			}
		}
		$this->response(['saldo' => $saldo['anggota_saldo_simp_manasuka'], 'kartu' => $res, 'jasa' => $this->config->item('base_jasa_pinjaman')]);
	}
	/*end kartu simpanan*/

	/*begin kartu penarikan simpanan*/
	public function get_data_talangan($value = '')
	{
		$saldo = $this->nasabah->read($value);
		$res = $this->kartutalangan->select(array(
			'filters_static' => array(
				'kartu_talangan_anggota' => $value,
			),
			'sort_static' => array('kartu_talangan_create_at desc')
		));
		$this->response(['saldo' => $saldo['anggota_saldo_simp_manasuka'], 'kartu' => $res]);
	}
	/*end kartu simpanan*/

	/*end kartu simpanan*/
	public function select_kredit($value = '')
	{
		$this->load->model(array('pengaturankredit/PengaturanKreditModel' => 'pengaturankredit'));
		$table = $this->pengaturankredit->select(['sort_static' => 'pengaturan_kredit_batas_min asc']);
		$jasa = $this->config->item('base_jasa_pinjaman');
		$settingan = [
			'nominal_talangan' => $this->config->item('base_nominal_talangan'),
			'tenor_talangan' => $this->config->item('base_tenor_talangan')
		];
		$this->response(['kredit' => $table, 'jasa' => $jasa, 'setting' => $settingan]);
	}

	public function save_pengajuan($value = '')
	{
		$data = varPost();
		$dt['pengajuan_anggota'] = $value;
		$dt['pengajuan_tgl'] = date('Y-m-d');
		$dt['pengajuan_tenor'] = $data['periode'];
		$dt['pengajuan_jumlah_pinjaman'] = $data['nominal'];
		$dt['pengajuan_pokok_bulanan'] = $data['pengajuan_pokok_bulanan'];
		$dt['pengajuan_jasa_bulanan'] = $data['pengajuan_jasa_bulanan'];
		// $dt['pengajuan_keterangan'] = $data['keperluan'];
		$dt['pengajuan_keperluan_tunai'] = $data['keperluan'];
		$dt['pengajuan_jenis'] = 'U';
		// $dt['pengajuan_tag_jenis'] = $data['bayar'];
		$dt['pengajuan_aktif'] = 1;
		$dt['pengajuan_create_by'] = $this->session->userdata('pegawai_id');
		$dt['pengajuan_create_at'] = date('Y-m-d H:m:s');
		$dt['pengajuan_no'] = $this->pinjaman->gen_kode();
		$dt['pengajuan_proteksi'] = $data['pengajuan_proteksi'];
		$dt['pengajuan_sisa_pinjaman_kpri'] = $data['pengajuan_sisa_pinjaman_kpri'];
		// $dt['pengajuan_gaji_bersih'] = $data['gaji_bersih'];
		// $dt['pengajuan_tgl_realisai'] = $data['pengajuan_tgl_realisai'];
		$dt['pengajuan_status'] = 0;
		// $data['pengajuan_alamat'] = $data['anggota_alamat'];
		$id = gen_uuid($this->pinjaman->get_table());
		$operation = $this->pinjaman->insert($id, $dt);
		$dat = [];

		// send fcm
		$nasabah = $this->nasabah->read(['anggota_id' => $value]);
		$grupgaji = $this->db->get_where('ms_grup_gaji', ['grup_gaji_kode' => $nasabah['grup_gaji_kode']])->row_array();
		$token = $this->db->get_where('ms_juru_bayar', ['juru_bayar_id' => $grupgaji['juru_bayar_id']])->row_array();
		$dat['message'] = $nasabah['anggota_nama'] . ' Mengajukan Pinjaman';
		$dat['title'] = 'Pengajuan Pinjaman';
		$dat['type'] = 'success';
		$dat['notif_type'] = 'notif';
		$dat['token'] = [$token['fcmtoken']];
		$dat['aksi'] = "FCM_PLUGIN_ACTIVITY";

		$this->sendFcm($dat);
		$this->response($operation);
	}

	public function check_pin($value = '')
	{
		$data = varPost();
		$cek = $this->activation->read(['activate_kodeanggota' => $value]);
		if ($cek) {
			$pin = $this->activation->read(['activate_kodeanggota' => $value, 'activate_pin' => $data['pin_lama']]);
			if ($pin) {
				$opr = array(
					'success' => true
				);
			} else {
				$opr = array(
					'success' => false,
					'message' => 'Pin Lama Tidak Sama'
				);
			}
		} else {
			$opr = array(
				'success' => false,
				'message' => 'Akun Anda Tidak Sesuai'
			);
		}

		$this->response($opr);
	}

	public function check_pin_jurubayar($value = '')
	{
		$data = varPost();
		$cek = $this->jurubayar->read(['juru_bayar_username' => $value]);
		if ($cek) {
			$pin = $this->jurubayar->read(['juru_bayar_username' => $value, 'juru_bayar_pin' => $data['pin_lama']]);
			if ($pin) {
				$opr = array(
					'success' => true
				);
			} else {
				$opr = array(
					'success' => false,
					'message' => 'Pin Lama Tidak Sama'
				);
			}
		} else {
			$opr = array(
				'success' => false,
				'message' => 'Akun Anda Tidak Sesuai'
			);
		}

		$this->response($opr);
	}

	public function sisa_hutang()
	{
		$data = varPost();
		$pengajuan = $this->pinjaman->select(array('filters_static' => array(
			'pengajuan_anggota' => $data['pengajuan_anggota'],
			'pengajuan_status' => $data['pengajuan_status'],
		)));

		$talangan = $this->pengajuantalangan->select(array('filters_static' => array(
			'pengajuan_talangan_anggota' => $data['pengajuan_anggota'],
			'pengajuan_talangan_status' => $data['pengajuan_status'],
		)));

		if ($talangan['total'] > 0) {
			foreach ($talangan['data'] as $row) {
				$pengajuan['data'][] = [
					'pengajuan_jumlah_pinjaman' => $row['pengajuan_talangan_jumlah_pinjaman'],
					'pengajuan_angsur_jumlah' => $row['pengajuan_talangan_angsur_jumlah'],
					'pengajuan_jasa_jumlah' => $row['pengajuan_talangan_jasa_jumlah'],
					'pengajuan_sisa_angsuran' => $row['pengajuan_talangan_sisa_angsuran']
				];
			}
			$pengajuan['total'] += $talangan['total'];
			$pengajuan['success'] = true;
		}
		$this->response($pengajuan);
	}

	public function change_pin($value = '')
	{
		$data = varPost();
		if (strlen($data['pin_baru']) == 6) {
			if ($data['pin_baru'] == $data['pin_baru_conf']) {
				$cek = $this->activation->read(['activate_kodeanggota' => $value]);
				if ($cek) {
					$pin = $this->db
						->where('activate_kodeanggota', $value)
						->update(
							'mobile_activation',
							[
								'activate_pin' => $data['pin_baru']
							]
						);
					if ($pin) {
						$opr = array(
							'success' => true,
							'message' => 'Pin Berhasil Diganti'
						);
					} else {
						$opr = array(
							'success' => false,
							'message' => 'Ganti Pin Gagal, Silahkan Coba Lagi'
						);
					}
				} else {
					$opr = array(
						'success' => false,
						'message' => 'Akun Anda Tidak Sesuai'
					);
				}
			} else {
				$opr = array(
					'success' => false,
					'message' => 'Password Konfirmasi Tidak Sama'
				);
			}
		} else {
			$opr = array(
				'success' => false,
				'message' => 'Panjang Pin  Harus 6 Digit'
			);
		}

		$this->response($opr);
	}

	public function change_pin_jurubayar($value = '')
	{
		$data = varPost();
		if (strlen($data['pin_baru']) == 6) {
			if ($data['pin_baru'] == $data['pin_baru_conf']) {
				$cek = $this->jurubayar->read(['juru_bayar_username' => $value]);
				if ($cek) {
					$pin = $this->db
						->where('juru_bayar_username', $value)
						->update(
							'ms_juru_bayar',
							[
								'juru_bayar_pin' => $data['pin_baru']
							]
						);
					if ($pin) {
						$opr = array(
							'success' => true,
							'message' => 'Pin Berhasil Diganti'
						);
					} else {
						$opr = array(
							'success' => false,
							'message' => 'Ganti Pin Gagal, Silahkan Coba Lagi'
						);
					}
				} else {
					$opr = array(
						'success' => false,
						'message' => 'Akun Anda Tidak Sesuai'
					);
				}
			} else {
				$opr = array(
					'success' => false,
					'message' => 'Password Konfirmasi Tidak Sama'
				);
			}
		} else {
			$opr = array(
				'success' => false,
				'message' => 'Panjang Pin  Harus 6 Digit'
			);
		}

		$this->response($opr);
	}

	public function verifikasi_data($username)
	{
		$get = varPost();
		switch ($get['pengajuan_status']) {
			case '0':
				$status = 'Ditolak';
				break;

			case '1':
				$status = 'Diterima';
				break;
		}

		$jurubayar = $this->jurubayar->read(['juru_bayar_username' => $username]);
		if ($get['type'] == 'H') {
			$user = $this->pengajuantalangan->read(['pengajuan_talangan_id' => $get['pengajuan_id']]);
			$kode = $user['anggota_kode'];
			$data = array(
				'pengajuan_talangan_tgl_verifikasi' => date('Y-m-d', strtotime($get['pengajuan_tgl_verifikasi'])),
				"pengajuan_talangan_status" => $get['pengajuan_status'],
				"pengajuan_talangan_keterangan" => $get['pengajuan_keterangan'],
				"pengajuan_talangan_juru_bayar_id" => $jurubayar['juru_bayar_id'],
				"pengajuan_talangan_verified_at" => date('Y-m-d H:i:s'),
				"pengajuan_talangan_verified_by" => $username,

			);
			$this->db
				->where(['pengajuan_talangan_id' => $get['pengajuan_id']])
				->update('ksp_pengajuan_talangan', $data);
			$this->pengajuantalangan->update($get['pengajuan_id'], $data);
		} else {
			$user = $this->pinjaman->read(['pengajuan_id' => $get['pengajuan_id']]);
			$kode = $user['anggota_kode'];
			$data = array(
				'pengajuan_tgl_verifikasi' => date('Y-m-d', strtotime($get['pengajuan_tgl_verifikasi'])),
				"pengajuan_status" => $get['pengajuan_status'],
				"pengajuan_keterangan" => $get['pengajuan_keterangan'],
				"pengajuan_juru_bayar_id" => $jurubayar['juru_bayar_id'],
				"pengajuan_verified_at" => date('Y-m-d H:i:s'),
				"pengajuan_verified_by" => $username,

			);
			$this->db
				->where(['pengajuan_id' => $get['pengajuan_id']])
				->update('ksp_pengajuan_pinjaman', $data);
			$this->pinjaman->update($get['pengajuan_id'], $data);
		}

		$token = $this->activation->read(['activate_kodeanggota' => $kode]);

		$dat['message'] = 'Pinjaman Anda ' . $status;
		$dat['title'] = 'Pengajuan Pinjaman';
		$dat['type'] = 'success';
		$dat['notif_type'] = 'pengajuan';
		$dat['token'] = [$token['activate_fcmtoken']];
		$dat['aksi'] = "FCM_PLUGIN_ACTIVITY";
		$this->sendFcm($dat);

		$this->response(['success' => true]);
	}

	public function get_detail_pengajuan()
	{
		$data = varPost();
		$pinjaman = $this->pinjaman->select(
			array(
				'filters_static' => array(
					'pengajuan_id' => $data['pengajuan_id']
				),
				'fields' => array('pengajuan_id', 'pengajuan_tgl', 'anggota_nama', 'pengajuan_jumlah_pinjaman', 'pengajuan_tenor', 'anggota_kode', 'grup_gaji_nama', 'pengajuan_no', 'pengajuan_gaji_bersih', 'anggota_alamat', 'pengajuan_keperluan_tunai', 'pengajuan_sisa_pinjaman_kpri', 'anggota_nip', 'pengajuan_jenis', 'pengajuan_keterangan', 'pengajuan_pokok_bulanan', 'pengajuan_jasa_bulanan', 'pengajuan_tgl_verifikasi'),
				'sort_static' => array('pengajuan_tgl asc')
			)
		);
		if ($pinjaman['total'] == 0) {
			$haji = $this->pengajuantalangan->select(
				array(
					'filters_static' => array(
						'pengajuan_talangan_id' => $data['pengajuan_id']
					),
					'fields' => array('pengajuan_talangan_id', 'pengajuan_talangan_tgl', 'anggota_nama', 'pengajuan_talangan_jumlah_pinjaman', 'pengajuan_talangan_tenor', 'anggota_kode', 'grup_gaji_nama', 'pengajuan_talangan_no', 'pengajuan_talangan_gaji_bersih', 'anggota_alamat', 'pengajuan_talangan_keperluan', 'pengajuan_talangan_sisa_pinjaman_kpri', 'anggota_nip', 'pengajuan_talangan_jenis', 'pengajuan_talangan_keterangan', 'pengajuan_talangan_pokok_bulanan', 'pengajuan_talangan_jasa_bulanan', 'pengajuan_talangan_tgl_verifikasi')
				)
			);
			$pinjaman['data'][] = array(
				'pengajuan_id' => $haji['data'][0]['pengajuan_talangan_id'],
				'pengajuan_tgl' => $haji['data'][0]['pengajuan_talangan_tgl'],
				'pengajuan_tenor' => $haji['data'][0]['pengajuan_talangan_tenor'],
				'pengajuan_no' => $haji['data'][0]['pengajuan_talangan_no'],
				'pengajuan_jenis' => $haji['data'][0]['pengajuan_talangan_jenis'],
				'pengajuan_gaji_bersih' => $haji['data'][0]['pengajuan_talangan_gaji_bersih'],
				'pengajuan_keperluan_tunai' => $haji['data'][0]['pengajuan_talangan_keterangan'],
				'pengajuan_sisa_pinjaman_kpri' => $haji['data'][0]['pengajuan_talangan_sisa_pinjaman_kpri'],
				'grup_gaji_nama' => $haji['data'][0]['grup_gaji_nama'],
				'anggota_nama' => $haji['data'][0]['anggota_nama'],
				'anggota_kode' => $haji['data'][0]['anggota_kode'],
				'pengajuan_jumlah_pinjaman' => $haji['data'][0]['pengajuan_talangan_jumlah_pinjaman'],
				'anggota_nip' => $haji['data'][0]['anggota_nip'],
				'anggota_alamat' => $haji['data'][0]['anggota_alamat'],
				'pengajuan_keterangan' => $haji['data'][0]['pengajuan_talangan_keterangan'],
				'pengajuan_pokok_bulanan' => $haji['data'][0]['pengajuan_talangan_pokok_bulanan'],
				'pengajuan_jasa_bulanan' => $haji['data'][0]['pengajuan_talangan_jasa_bulanan'],
				'pengajuan_tgl_verifikasi' => $haji['data'][0]['pengajuan_talangan_tgl_verifikasi'],

			);
		}
		$pinjaman['data'][0]['success'] = true;
		$this->response($pinjaman['data'][0]);
	}
	public function save_talangan($value = '')
	{
		$nasabah = $this->nasabah->read(['anggota_id' => $value]);
		$grupgaji = $this->db->get_where('ms_grup_gaji', ['grup_gaji_kode' => $nasabah['grup_gaji_kode']])->row_array();

		$data = varPost();
		$dt['pengajuan_talangan_anggota'] = $value;
		$dt['pengajuan_talangan_tgl'] = date('Y-m-d');
		$dt['pengajuan_talangan_dana'] = $data['nominal'] / $data['qty'];
		$dt['pengajuan_talangan_qty'] = $data['qty'];
		$dt['pengajuan_talangan_tenor'] = $data['periode'];
		$dt['pengajuan_talangan_jumlah_pinjaman'] = $data['nominal'];
		$dt['pengajuan_talangan_keterangan'] = $data['keperluan'];
		$dt['pengajuan_talangan_pokok_bulanan'] = $data['pengajuan_talangan_pokok_bulanan'];
		$dt['pengajuan_talangan_sisa_pinjaman_kpri'] = $data['pengajuan_talangan_sisa_pinjaman_kpri'];
		$dt['pengajuan_talangan_jasa_bulanan'] = $data['pengajuan_talangan_jasa_bulanan'];
		$dt['pengajuan_talangan_aktif'] = 1;
		$dt['pengajuan_talangan_create_by'] = $this->session->userdata('pegawai_id');
		$dt['pengajuan_talangan_tag_jenis'] = $grupgaji['grup_gaji_potong'];
		$dt['pengajuan_talangan_create_at'] = date('Y-m-d H:m:s');
		$dt['pengajuan_talangan_no'] = $this->pinjaman->gen_kode();
		$dt['pengajuan_talangan_status'] = 0;
		$dt['pengajuan_talangan_jenis'] = 'H';
		$id = gen_uuid($this->pengajuantalangan->get_table());
		$operation = $this->pengajuantalangan->insert($id, $dt);

		$dat = [];

		// send fcm
		$token = $this->db->get_where('ms_juru_bayar', ['juru_bayar_id' => $grupgaji['juru_bayar_id']])->row_array();
		$dat['message'] = $nasabah['anggota_nama'] . ' Mengajukan Pinjaman';
		$dat['title'] = 'Pengajuan Pinjaman';
		$dat['type'] = 'success';
		$dat['notif_type'] = 'notif';
		$dat['token'] = [$token['fcmtoken']];
		$dat['aksi'] = "FCM_PLUGIN_ACTIVITY";

		$this->sendFcm($dat);
		$this->response($operation);
	}
	//home history
	public function get_history($value = '')
	{
		$res = $this->history->select(array(
			'filters_static' => array(
				'history_anggota_id' => $value,
			),
			'sort_static' => array('history_datetime desc'),
			'limit' => 5
		));
		$this->response($res);
	}

	public function get_history_satker($value = '')
	{

		$date = varPost();
		$juru_bayar_id = $this->jurubayar->read(['juru_bayar_username' => $value]);
		$tam = [];

		$tam = array(
			'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
			'pengajuan_status IN("1","3")' => null
		);
		$tam_haji = array(
			'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
			'pengajuan_talangan_status IN("1","3")' => null
		);

		$haji = $this->pengajuantalangan->select(array(
			'filters_static' => $tam_haji,
			'sort_static' => array('pengajuan_talangan_tgl_verifikasi desc'),
		));

		$res = $this->pinjaman->select(array(
			'filters_static' => $tam,
			'sort_static' => array('pengajuan_tgl_verifikasi desc'),
		));

		if ($haji['total'] > 0) {
			foreach ($haji['data'] as $haji_data) {
				$res['data'][] = [
					'pengajuan_tgl_verifikasi' => $haji_data['pengajuan_talangan_tgl_verifikasi'],
					'pengajuan_status' => $haji_data['pengajuan_talangan_status'],
					'pengajuan_keterangan' => $haji_data['pengajuan_talangan_keterangan']
				];
			}
		}

		for ($i = 0; $i < 5; $i++) {
			if (!empty($res['data'][$i])) {
				$res['limit'][] = [
					'pengajuan_tgl_verifikasi' => $res['data'][$i]['pengajuan_tgl_verifikasi'],
					'pengajuan_status' => $res['data'][$i]['pengajuan_status'],
					'pengajuan_keterangan' => $res['data'][$i]['pengajuan_keterangan']
				];
			}
		}

		$this->response($res);
	}

	public function select_bank()
	{
		$res = $this->akun->select(array(
			'filters_static' => array(
				'akun_is_bank' => 1,
				'akun_key' =>	'bank_bri_usp'
			)
		));

		$this->response($res);
	}

	//history
	public function get_all_history($value = '')
	{
		$date = varPost();
		$tam = [];
		if ($date["date_from"]) {
			if (!$date["date_end"] || ($date["date_end"] < $date["date_from"])) $date["date_end"] = $date["date_from"];
			$tam = array(
				'history_anggota_id' => $value,
				'DATE(history_datetime) BETWEEN "' . $date['date_from'] . '" AND "' . $date['date_end'] . '"' => null
			);
		} else {
			$tam = array(
				'DATE(history_datetime)' => date('Y-m-d'),
				'history_anggota_id' => $value,
			);
		}
		$res = $this->history->select(array(
			'filters_static' => $tam,
			'sort_static' => array('history_datetime desc'),
		));
		$this->response($res);
	}

	public function get_all_history_satker($value)
	{
		$date = varPost();
		$juru_bayar_id = $this->jurubayar->read(['juru_bayar_username' => $value]);
		$tam = [];
		if ($date["date_from"]) {
			if (!$date["date_end"] || ($date["date_end"] < $date["date_from"])) $date["date_end"] = $date["date_from"];
			$tam = array(
				'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
				'DATE(pengajuan_tgl_verifikasi) BETWEEN "' . $date['date_from'] . '" AND "' . $date['date_end'] . '"' => null,
				'pengajuan_status IN("1","3")' => null
			);
			$tam_haji = array(
				'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
				'DATE(pengajuan_talangan_tgl_verifikasi) BETWEEN "' . $date['date_from'] . '" AND "' . $date['date_end'] . '"' => null,
				'pengajuan_talangan_status IN("1","3")' => null
			);
		} else {
			$tam = array(
				'DATE(pengajuan_tgl_verifikasi)' => date('Y-m-d'),
				'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
				'pengajuan_status IN("1","3")' => null
			);
			$tam_haji = array(
				'DATE(pengajuan_talangan_tgl_verifikasi)' => date('Y-m-d'),
				'juru_bayar_id' => $juru_bayar_id['juru_bayar_id'],
				'pengajuan_talangan_status IN("1","3")' => null
			);
		}

		$haji = $this->pengajuantalangan->select(array(
			'filters_static' => $tam_haji,
			'sort_static' => array('pengajuan_talangan_tgl_verifikasi desc'),
		));

		$res = $this->pinjaman->select(array(
			'filters_static' => $tam,
			'sort_static' => array('pengajuan_tgl_verifikasi desc'),
		));

		if ($haji['total'] > 0) {
			foreach ($haji['data'] as $haji_data) {
				$res['data'][] = [
					'pengajuan_tgl_verifikasi' => $haji_data['pengajuan_talangan_tgl_verifikasi'],
					'pengajuan_status' => $haji_data['pengajuan_talangan_status'],
					'pengajuan_keterangan' => $haji_data['pengajuan_talangan_keterangan']
				];
			}
		}

		$this->response($res);
	}

	public function get_pembayaran($value = '')
	{
		$data = varPost();
		if (strtoupper($data['pengajuan_jenis']) == 'H' or strtoupper($data['pengajuan_jenis']) == 'H+') {
			$res_haji = $this->kartutalangan->select(array(
				'filters_static' => array(
					'kartu_talangan_referensi_id' => $data['pembayaran_pinjaman_detail_pengajuan_id'],
					'kartu_talangan_order !=' => 0
				),
				'sort_static' => array('kartu_talangan_order desc')
			));

			$res['total'] = $res_haji['total'];
			$res['success'] = 1;
			$res['data'] = [];
			foreach ($res_haji['data'] as $data_haji) {
				$res['data'][] = [
					'kartu_pinjaman_saldo_bayar' => $data_haji['kartu_talangan_saldo_bayar'],
					'kartu_pinjaman_saldo_bunga' => $data_haji['kartu_talangan_saldo_bunga'],
					'kartu_pinjaman_bayar_ke' => $data_haji['kartu_talangan_order'],
					'kartu_pinjaman_tanggal' => $data_haji['kartu_talangan_tanggal'],
				];
			}

			$haji = $this->pengajuantalangan->read(['pengajuan_talangan_id' => $data['pembayaran_pinjaman_detail_pengajuan_id']]);
			$pinjaman = [
				'pengajuan_tgl' => $haji['pengajuan_talangan_tgl'],
				'pengajuan_no' => $haji['pengajuan_talangan_no'],
				'pengajuan_gaji_bersih' => $haji['pengajuan_talangan_gaji_bersih'],
				'pengajuan_sisa_pinjaman_kpri' => $haji['pengajuan_talangan_sisa_pinjaman_kpri'],
				'pengajuan_status' => $haji['pengajuan_talangan_status'],
				'pengajuan_jumlah_pinjaman' => $haji['pengajuan_talangan_jumlah_pinjaman'],
				'pengajuan_tenor' => $haji['pengajuan_talangan_tenor'],
				'pengajuan_keterangan' => $haji['pengajuan_talangan_keterangan'],
				'pengajuan_tag_bulan' => $haji['pengajuan_talangan_tag_bulan'],
				'pengajuan_keperluan_tunai' => $haji['pengajuan_talangan_keterangan'],
				'pengajuan_tunggakan_jasa' => $haji['pengajuan_talangan_tunggakan_jasa'],
				'pengajuan_tunggakan_pokok' => $haji['pengajuan_talangan_tunggakan_pokok'],
				'pengajuan_tgl_verifikasi' => $haji['pengajuan_talangan_tgl_verifikasi'],
				'pengajuan_tgl_realisasi' => $haji['pengajuan_talangan_tgl_realisasi'],
				'anggota_nama' => $haji['anggota_nama'],
				'grup_gaji_nama' => $haji['grup_gaji_nama'],
				'pengajuan_sisa_angsuran' => $haji['pengajuan_talangan_sisa_angsuran']
			];
		} else {
			$res = $this->kartupinjaman->select(array(
				'filters_static' => array(
					'kartu_pinjaman_referensi_id' => $data['pembayaran_pinjaman_detail_pengajuan_id'],
					// 'pembayaran_pinjaman_detail_is_lunas' => 1
					"kartu_pinjaman_transaksi LIKE 'Pembayaran Pinjaman%'" => null
				),
				'sort_static' => array('kartu_pinjaman_bayar_ke desc'),
			));
			// $res = $this->pembayaranpinjamandetail->select(array(
			// 	'filters_static' => array(
			// 		'pembayaran_pinjaman_detail_pengajuan_id'=>$data['pembayaran_pinjaman_detail_pengajuan_id'],
			// 		'pembayaran_pinjaman_detail_is_lunas' => 1
			// 	),
			// 	'sort_static' => array('pembayaran_pinjaman_detail_create desc'),
			// ));
			$pinjaman = $this->pinjaman->read(['pengajuan_id' => $data['pembayaran_pinjaman_detail_pengajuan_id']]);
		}
		if ($pinjaman) $res['pinjaman'] = $pinjaman;

		$this->response($res);
	}

	//history
	public function get_all_notifikasi($value = '')
	{
		$res = $this->notifikasi->select(array(
			'filters_static' => array('notif_sent_to' => $value),
			'sort_static' => array('notif_datetime desc'),
			'limit' => 20
		));
		$this->response($res);
	}

	/*end kartu simpanan*/


	public function daftar_tagihan_individu($value = '')
	{
		$html = '';
		$html .= '<style>
			.laporan td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			table{
				border-collapse: collapse;
				width:100%;
			}
		</style>';

		$data_simpanan = $this->getDataDaftarSimpanan($value);
		$data_pinjaman = $this->getDataDaftarPinjamanIndividu($value);

		$nasabah = $this->nasabah->read(['anggota_kode' => $value]);

		$html .= '<table>
				<tr>
				<td colspan="4" style="text-align: left;"><i>KPRI "EKO KAPTI"</i><br><i><u>KABUPATEN MALANG</u></i></td>
				</tr>
				<tr>
				<td colspan="4" style="text-align: center;">POTONGAN PINJAMAN & SIMPANAN<br>Bulan : ' . phpChgDate(date('Y-m')) . '</td>
				</tr>
				<tr>
				<td style="text-align: left;">Nama</td>
				<td style="text-align: center;">:</td>
				<td style="text-align: left;">' . $nasabah['anggota_nama'] . '</td>
				<td style="text-align: left; padding-left: 30%;">No.Kop: ' . $nasabah['anggota_kode'] . '</td>
				</tr>
				<tr>
				<td style="text-align: left;">Wil.Gaji</td>
				<td style="text-align: center;">:</td>
				<td style="text-align: left;">' . $nasabah['grup_gaji_nama'] . ' (' . $nasabah['grup_gaji_kode'] . ')</td>
				<td style="text-align: left; padding-left: 30%;">Nip: ' . $nasabah['anggota_nip'] . '</td>
				</tr>
		</table>';

		$v_pinj = $data_pinjaman['pinjaman']['data'];

		if (count($v_pinj) > 0) {
			$html .= '<table align="center" style=""cellspacing="0">
				<tr>
				<td style="border:1px solid black;text-align:center;">Jn</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">NOPj.</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">Tgl.</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">Pinjaman</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">Bln</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">Saldo</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">Ke</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">JASA</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">POKOK</td>
				<td style="border:1px solid black;text-align:center;border-left:0;">TAGIHAN</td>
			</tr>';
			$pinjaman = 0;
			foreach ($v_pinj as $value) {
				if ($value['pembayaran_pinjaman_detail_is_lunas'] == 0) {
					$tagihan_pinjaman = (int)$value['pengajuan_jasa_bulanan'] + $value['pengajuan_pokok_bulanan'];
					$html .= '<tr>
						<td style="text-align:center;" class="bl-1">' . $value['pengajuan_jenis'] . '</td>
						<td>' . $value['pengajuan_no_pinjam'] . '</td>
						<td>' . date("d/m/yy", strtotime($value['pengajuan_tgl_realisasi'])) . '</td>
						<td style="text-align:right;">' . number_format($value['pengajuan_jumlah_pinjaman'], 0, '', '.') . '</td>
						<td style="text-align:center;">' . $value['pengajuan_tenor'] . 'x</td>
						<td style="border-right:1px solid black;text-align:right;">' . number_format($value['pengajuan_sisa_angsuran'], 0, '', '.') . '</td>
						<td style="text-align:left;">' . $value['pengajuan_angsuran'] . '</td>
						<td style="text-align:right;">' . number_format($value['pengajuan_jasa_bulanan'], 0, '', '.') . '</td>
						<td style="border-right:1px solid black;text-align:right;">' . number_format($value['pengajuan_pokok_bulanan'], 0, '', '.') . '</td>
						<td style="border-right:1px solid black;text-align:right;width:14.8%">' . number_format($tagihan_pinjaman, 0, '', '.') . '</td>
						</tr>';
					$pinjaman += $tagihan_pinjaman;
				}
			}
			$html .= '</table>';
		}
		if ($data_simpanan['total'] > 0) {
			foreach ($data_simpanan['data'] as $key2 => $value2) {
				$tagihan_simpanan = (int)$value2['pembayaran_simpanan_detail_titipan'] + $value2['pembayaran_simpanan_detail_swk'] + $value2['pembayaran_simpanan_detail_msk'] + $value2['pembayaran_simpanan_detail_sp'] + $value2['pembayaran_simpanan_detail_sw'] + $value2['pembayaran_simpanan_detail_tht'];

				$html .= '<table align="center" cellspacing="0">
					<tr>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">T.Belanja</td>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">Sw.Khusus</td>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">Manasuka</td>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">S.Pokok</td>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">SW</td>
					<td style="text-align:center;width:14%;" class="bl-1 bt-1 bb-1">THT</td>
					<td style="text-align:center;width:14.5%;" class="bl-1 bt-1 br-1 bb-1">SIMPANAN</td>
					</tr>
					<tr>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_titipan'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_swk'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_msk'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_sp'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_sw'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1">' . number_format($value2['pembayaran_simpanan_detail_tht'], 0, '', '.') . '</td>
					<td style="text-align:right;" class="bl-1 bb-1 br-1">' . number_format($tagihan_simpanan, 0, '', '.') . '</td>
					</tr>
				</table>';

				$jumlah = $pinjaman + $tagihan_simpanan;
				$html .= '<table align="center" style="border-color: black;"cellspacing="0">
				<tr>
				<td style="border-color:#0000; width:60%"></td>
				<td colspan="2" style="border:1px solid black;border-top:0;text-align:center;">JUMLAH POTONGAN</td>
				<td style="border:1px solid black;border-top:0;border-left:0;text-align:right;width:14.8%">' . number_format($jumlah, 0, '', '.') . '</td>
				</tr>
				<tr>
				<td colspan="4" style="text-align:left">Malang, ' . date('d/m/Y') . '</td>
				</tr>
				</table>
				<br>
				<table>
				<tr>
				<td style="border-top:1px dashed black;padding-bottom:10px"></td>
				</tr>
				</table>';
			}
		}


		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Daftar Tagihan',
			'title'         => 'Daftar Tagihan',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '9',
			'footer'        => 'Hal. {PAGENO} dari {nb}'
		));
	}

	private function getDataDaftarSimpanan($data)
	{
		$child = $this->detailsimpanan->select(array('filters_static' => array(
			'pembayaran_simpanan_status'		=> "0",
			'pembayaran_simpanan_bulan_tagihan'	=> date('Y-m'),
			'anggota_kode'		=> $data
		)));

		return $child;
	}

	public function getDataDaftarPinjamanIndividu($data)
	{
		$return = [];
		$detail = $this->pembayaranpinjamandetail->select(array('filters_static' => array(
			'pembayaran_pinjaman_detail_bulan_tagihan'	=> date('Y-m'),
			'anggota_kode'		=> $data,
			'pembayaran_pinjaman_detail_is_lunas' => 0,
			'pembayaran_pinjaman_status IN("0","1")' => null
		)));

		$lunas = $this->pembayaranpinjamandetail->select(array('filters_static' => array(
			'pembayaran_pinjaman_detail_bulan_tagihan'	=> date('Y-m'),
			'anggota_kode'		=> $data,
			'pembayaran_pinjaman_detail_is_lunas' => 1,
			'pembayaran_pinjaman_status IN("0","1")' => null
		)));
		if ($lunas['total'] > 0) {
			foreach ($lunas['data'] as $value) {
				foreach ($detail['data'] as $key => $val) {
					$search = array_search($value['pembayaran_pinjaman_detail_pengajuan_id'], $detail['data'][$key]);
					if ($search) {
						unset($detail['data'][$key]);
					}
				}
				$detail['data'][] = $value;
			}
		}


		$detail = [
			'success' => $detail['success'],
			'total' => $detail['total'],
			'data' => array_values($detail['data'])
		];
		$detail_haji = $this->pembayarantalangandetail->select(array('filters_static' => array(
			'pembayaran_talangan_status'			=> "0",
			'pembayaran_talangan_bulan_tagihan'		=> date('Y-m'),
			'anggota_kode'		=> $data
		)));
		$return = [
			'pinjaman' => $detail,
			'haji' => $detail_haji
		];
		return $return;
	}

	public function getDataBarangAwal()
	{
		$data = varPost();
		$where = [
			'barang_stok>' => 0,
			'barang_harga>' => 0,
		];
		if (strlen($data['search']) > 0) {

			$where['barang_nama LIKE "%' . $data['search'] . '%"'] = null;
		}
		if (!empty($data['kategori'])) {
			$kategori = $this->db->get_where('ms_kategori_barang', ['kategori_barang_parent' => $data['kategori']])->result_array();
			$kat = array_column($kategori, 'kategori_barang_id');
			$category = implode(",", $kat);

			$where['barang_kategori_barang IN(' . $category . ')'] = null;
		}

		$opr = $this->barang->count_exist($where);



		$this->response($opr);
	}

	public function getDataBarangLanjut()
	{
		$data = varPost();
		$where = [
			'barang_stok>' => 0,
			'barang_harga>' => 0,
		];
		if (strlen($data['search']) > 0) {

			$where['barang_nama LIKE "%' . $data['search'] . '%"'] = null;
		}
		if (!empty($data['kategori'])) {

			$kategori = $this->db->get_where('ms_kategori_barang', ['kategori_barang_parent' => $data['kategori']])->result_array();
			$kat = array_column($kategori, 'kategori_barang_id');
			$category = implode(",", $kat);

			$where['barang_kategori_barang IN(' . $category . ')'] = null;
		}

		$opr = $this->barang->select([
			'filters_static' => $where,
			'limit' => $data['limit'],
			'start' => $data['start'],
			'sort_static' => 'barang_updated desc'
		]);

		$this->response($opr);
	}

	public function cek_keranjang()
	{
		$data = varPost();

		$this->response($this->keranjang->read([
			'barang_id' => $data['barang_id'],
			'anggota_id' => $data['anggota_id'],
			'keranjang_status' => '1'
		]));
	}

	public function add_cart()
	{
		$data = varPost();
		$cek = $this->keranjang->read(['barang_id' => $data['barang_id'], 'anggota_id' => $data['anggota_id'], 'keranjang_status' => '1']);
		if ($cek) {
			$keranjang = $this->keranjang->update($cek['keranjang_id'], ['barang_qty' => ($cek['barang_qty'] + 1), 'keranjang_status' => 1]);
		} else {
			$keranjang = $this->keranjang->insert(gen_uuid($this->keranjang->get_table), ['barang_id' => $data['barang_id'], 'anggota_id' => $data['anggota_id'], 'barang_qty' => 1, 'keranjang_status' => 1]);
		}
		$this->response($keranjang);
	}

	public function update_cart()
	{
		$data = varPost();
		$cek = $this->keranjang->read(['barang_id' => $data['id'], 'anggota_id' => $data['anggota_id'], 'keranjang_status' => '1']);
		if ($data['stok'] > $cek['barang_qty']) {
			$opr = $this->keranjang->update($cek['keranjang_id'], ['barang_qty' => $data['stok']]);
		} else {
			if ($data['stok'] == 0) {
				$opr = [
					'success' => false,
					'id' => $cek['keranjang_id']
				];
			} else {
				$opr = $this->keranjang->update($cek['keranjang_id'], ['barang_qty' => $data['stok']]);
			}
		}
		$this->response($opr);
	}

	public function count_cart($val)
	{
		$res = [
			'count' => $this->keranjang->count_exist([
				'anggota_id' => $val,
				'keranjang_status' => 1
			]),
			'harga' =>  $this->keranjang->select(['filters_static' => [
				'anggota_id' => $val,
				'keranjang_status' => 1
			]])
		];
		$this->response($res);
	}

	public function get_cart($id)
	{
		$keranjang = $this->keranjang->select(['filters_static' => ['anggota_id' => $id, 'keranjang_status' => 1]]);
		$this->response($keranjang);
	}

	public function delete_cart()
	{
		$data = varPost();
		$this->response($this->keranjang->delete($data['id']));
	}

	public function checkout_cart($id)
	{
		$this->db->where(['anggota_id' => $id, 'keranjang_status' => '1'])->update('pos_keranjang', ['keranjang_status' => 2, 'keranjang_tgl_pesan' => date('Y-m-d H:i:s')]);
		$user = $this->nasabah->read(['anggota_id' => $id]);
		$token = $this->usertoken->select(array('filters_static' => array('user_token_role_name IN ("Administrator","Toko")' => null)));
		$dat['message'] = 'Pesanan Masuk';
		$dat['title'] = 'Pesanan Dari ' . $user['anggota_nama'];
		$dat['type'] = 'success';
		$dat['notif_type'] = 'pesanan';
		$dat['token'] = array_column($token['data'], 'user_token');
		$dat['aksi'] = "https://apps.ekokapti.id/ekokapti/kasir";
		$this->sendFcm($dat, 1);
		$this->response(['success' => true]);
	}

	public function proses_titipan($value = '')
	{
		$data = varPost();
		$kode = $this->kartusimpanan->gen_urut_cabang();
		$titipan = $data['kredit']['penjualan_total_bayar_voucher'] ? $data['kredit']['penjualan_total_bayar_voucher'] : 0;
		$bhr = $data['kredit']['penjualan_total_bayar_voucher_khusus'] ? $data['kredit']['penjualan_total_bayar_voucher_khusus'] : 0;
		$nasabah = $this->nasabah->read(['anggota_kode' => $data['anggota_id']]);

		if ($titipan > $nasabah['anggota_saldo_simp_titipan_belanja'] || $bhr > $nasabah['anggota_saldo_bhr']) {
			$this->response(['success' => false, 'msg' => 'Saldo Nasabah Kurang, Cek Kembali Saldo Terbaru Nasabah!']);
		} else {
			$this->db->trans_start();
			if ($titipan > 0) {
				$titipan_belanja = $this->kartusimpanan->insert_kartu([
					'kartu_simpanan_anggota'		=> $data['anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $titipan,
					'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
					'kartu_simpanan_create_at' 		=> date("Y-m-d H:i:s"),
					'kartu_simpanan_keterangan'		=> 'On Insert',
					'kartu_simpanan_create_by' 		=> $data['pegawai_id'],
					'kartu_simpanan_cabang'			=> '1',
					// 'kartu_simpanan_penggunaan'		=> $data['keterangan_titipan'],
					'kartu_simpanan_transaksi_kode' => $kode,
					'kartu_simpanan_cabang_kasir'	=> $data['pegawai_nama'],
				], 'V');
			}

			if ($bhr > 0) {
				$voucher_bhr = $this->kartusimpanan->insert_kartu([
					'kartu_simpanan_anggota'		=> $data['anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $bhr,
					'kartu_simpanan_transaksi'		=> 'Voucher BHR',
					'kartu_simpanan_create_at' 		=> date("Y-m-d H:i:s"),
					'kartu_simpanan_keterangan'		=> 'On Insert',
					'kartu_simpanan_create_by' 		=> $data['pegawai_id'],
					'kartu_simpanan_cabang'			=> '1',
					// 'kartu_simpanan_penggunaan'		=> $data['keterangan_bhr'],
					'kartu_simpanan_transaksi_kode' => $kode,
					'kartu_simpanan_cabang_kasir'	=> $data['pegawai_nama'],
				], 'BHR');
			}

			if ($data['metode'] == 'K') {
				$id_pinjam = gen_uuid($this->pinjaman->get_table());
				$kode_pinjam = $this->pinjaman->gen_kode();

				$d_current = date('d', strtotime(date('Y-m-d')));
				if ($d_current >= 20) {
					$bulan_tertagih = date("Y-m", strtotime("+2 months", strtotime(date('Y-m-d'))));
				} else {
					$bulan_tertagih = date("Y-m", strtotime("+1 months", strtotime(date('Y-m-d'))));
				}

				$this->pinjaman->insert($id_pinjam, array(
					'pengajuan_tgl' 			=> date('Y-m-d'),
					'pengajuan_tgl_realisasi'  => date('Y-m-d'),
					'pengajuan_no' 				=> $kode_pinjam,
					'pengajuan_no_pinjam'		=> $kode,
					'pengajuan_anggota' 		=> $data['anggota_id'],
					'pengajuan_jumlah_pinjaman' => $data['kredit']['penjualan_total_kredit'],
					'pengajuan_tenor' 			=> $data['kredit']['penjualan_total_cicilan_qty'],
					'pengajuan_jasa' 			=> $data['kredit']['penjualan_total_jasa'], //test again
					'pengajuan_pokok' 			=> $data['kredit']['penjualan_total_kredit'],
					'pengajuan_penjualan_id' 	=> 'DRUJU',
					'pengajuan_jenis' 			=> 'B',
					'pengajuan_tag_jenis' 		=> $data['kredit']['penjualan_jenis_potongan'],
					'pengajuan_tag_bulan'		=> $bulan_tertagih,
					'pengajuan_status' 			=> 2,
					'pengajuan_proteksi' 		=> 0,
					'pengajuan_proteksi_nilai'	=> 0,
					'pengajuan_tag_awal'		=> $data['kredit']['penjualan_kredit_awal'],
					'pengajuan_jatuh_tempo'		=> $data['kredit']['penjualan_jatuh_tempo'],
					'pengajuan_create_at'		=> date('Y-m-d H:i:s'),
					'pengajuan_create_by'		=> $data['pegawai_id'],
					'pengajuan_aktif'			=> 1,
					'pengajuan_pokok_bulanan'	=> $data['kredit']['penjualan_total_cicilan'],
					'pengajuan_jasa_bulanan'	=> $data['kredit']['penjualan_total_jasa_nilai'],
					'pengajuan_sisa_angsuran'	=> $data['kredit']['penjualan_total_kredit'],
					'pengajuan_tunggakan_jasa'	=> $data['kredit']['penjualan_total_jasa'],

				));

				$this->kartupinjaman->insert_kartu([
					'kartu_pinjaman_anggota'		=> $data['anggota_id'],
					'kartu_pinjaman_tanggal'		=> date('Y-m-d'),
					'kartu_pinjaman_id' 			=> $id_pinjam,
					'kartu_pinjaman_saldo_pinjam'	=> $data['kredit']['pengajuan_jumlah_pinjaman'],
					'kartu_pinjaman_saldo_bayar'	=> 0,
					'kartu_pinjaman_transaksi'		=> 'Pencairan Pinjaman Barang',
					'kartu_pinjaman_transaksi_kode'	=> $kode,
					'kartu_pinjaman_create_by' 		=> $data['pegawai_id'],
					'kartu_pinjaman_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_pinjaman_referensi_id'	=> $id_pinjam,
					'kartu_pinjaman_cabang'			=> '1',
					'kartu_pinjaman_cabang_kasir'	=> $data['pegawai_nama'],
					'kartu_pinjaman_tenor'			=> $data['kredit']['penjualan_total_cicilan_qty']
				], 'B');
			}
			$this->db->trans_complete();
			if ($bhr > 0 || $titipan > 0 || $data['metode'] == 'K') {
				$this->response(['success' => true, 'kode' => $kode]);
			} else {
				$this->response(['success' => true, 'kode' => '']);
			}
		}
	}

	// public function proses_titipan ($value=''){
	// 	$data = varPost();
	// 	$titipan = $data['saldo_titipan'] ? $data['saldo_titipan'] : 0 ;
	// 	$bhr = $data['saldo_bhr'] ? $data['saldo_bhr'] : 0 ;

	// 	$kode = $this->kartusimpanan->gen_urut_cabang();
	// 	if($titipan > 0){
	// 		$titipan_belanja = $this->kartusimpanan->insert_kartu([
	// 			'kartu_simpanan_anggota'		=> $data['anggota_id'],
	// 			'kartu_simpanan_tanggal'		=> date("Y-m-d"),
	// 			'kartu_simpanan_saldo_masuk'	=> 0,
	// 			'kartu_simpanan_saldo_keluar'	=> $data['saldo_titipan'],
	// 			'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
	// 			'kartu_simpanan_create_at' 		=> date("Y-m-d H:i:s"),
	// 			'kartu_simpanan_keterangan'		=> 'On Insert',
	// 			'kartu_simpanan_create_by' 		=> $data['pegawai_id'],
	// 			'kartu_simpanan_cabang'			=> '1',
	// 			'kartu_simpanan_cabang_kasir'	=> 'inan',
	// 			'kartu_simpanan_transaksi_kode' => $kode
	// 		], 'V');
	// 	}

	// 	if($bhr > 0){
	// 		$voucher_bhr = $this->kartusimpanan->insert_kartu([
	// 			'kartu_simpanan_anggota'		=> $data['anggota_id'],
	// 			'kartu_simpanan_tanggal'		=> date("Y-m-d"),
	// 			'kartu_simpanan_saldo_masuk'	=> 0,
	// 			'kartu_simpanan_saldo_keluar'	=> $data['saldo_bhr'],
	// 			'kartu_simpanan_transaksi'		=> 'Voucher BHR',
	// 			'kartu_simpanan_create_at' 		=> date("Y-m-d H:i:s"),
	// 			'kartu_simpanan_keterangan'		=> 'On Insert',
	// 			'kartu_simpanan_create_by' 		=> $data['pegawai_id'],
	// 			'kartu_simpanan_cabang'			=> '1',
	// 			'kartu_simpanan_penggunaan'		=> $data['keterangan_bhr'],
	// 			'kartu_simpanan_transaksi_kode' => $kode,
	// 			'kartu_simpanan_cabang_kasir'	=> 'inan',
	// 		], 'BHR');
	// 	}

	// 	$this->response($kode);
	// }

	// public function update_titipan($value){
	// 	$data = varPost();
	// 	$this->response($this->kartusimpanan->update_kartu([
	// 		'kartu_simpanan_anggota'		=> $value,
	// 		'kartu_simpanan_tanggal'		=> date("Y-m-d"),
	// 		'kartu_simpanan_saldo_masuk'	=> 0,
	// 		'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher'],
	// 		'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
	// 		'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
	// 		'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
	// 		'kartu_simpanan_keterangan'		=> 'On Update',
	// 	], 'V'));
	// }
	// public function get_titipan($value = ''){
	// 	$data = varPost();
	// 	$this->response($this->kartusimpanan->select(['filters_static'=>['kartu_simpanan_transaksi_kode'=>$data['kode'], 'kartu_simpanan_anggota' => $data['anggota_id']]]));
	// }

	public function get_riwayat_titipan($value = '')
	{
		$data = varPost();
		$nasabah = $this->nasabah->read(['anggota_kode' => $data['anggota_kode']]);

		$riwayat = $this->kartusimpanan->select(array('filters_static' => array('kartu_simpanan_anggota' => $nasabah['anggota_id'], 'kartu_simpanan_transaksi_kode' => $data['transaksi_kode'], 'kartu_simpanan_cabang' => 1, 'kartu_simpanan_tanggal' => $data['tanggal'])));

		$grup = $this->kartusimpanan->select(array(
			'filters_static' => array('kartu_simpanan_anggota' => $nasabah['anggota_id'], '(kartu_simpanan_transaksi = "Titipan Belanja" OR kartu_simpanan_transaksi = "Voucher BHR" )' => null, 'kartu_simpanan_cabang' => 1, 'kartu_simpanan_tanggal' => $data['tanggal']),
			'group_static' => ['kartu_simpanan_transaksi_kode'],
			'fields' => ['kartu_simpanan_tanggal', 'pegawai_nama', 'kartu_simpanan_saldo_keluar', 'kartu_simpanan_transaksi_kode', 'kartu_simpanan_transaksi'],
			'sort_static' => 'kartu_simpanan_create_at desc'
		));
		if ($value == 'all') {
			$opr = array('anggota' => $nasabah, 'grup' => $grup);
		} else {
			$opr = array('riwayat' => $riwayat);
		}
		$this->response($opr);
	}

	public function get_all_nasabah()
	{
		$this->response($this->db->get('ms_anggota')->result_array());
	}

	public function testing()
	{
		$voucher = $this->db->get_where('ksp_kartu_simpanan', ['kartu_simpanan_cabang' => 1])->result_array();
		$kredit = $this->db->get_where('ksp_kartu_pinjaman', ['kartu_pinjaman_cabang' => 1])->result_array();
		$this->response(['voucher' => $voucher, 'kredit' => $kredit]);
	}

	public function read_notif()
	{
		$data = varPost();

		$this->response($this->notifikasi->update($data['id'], ['notif_is_read' => 1]));
	}

	public function sent_notif()
	{
		$data = varPost();

		$this->response($this->notifikasi->update($data['id'], ['notif_is_sent' => 1]));
	}

	public function checkAppVersion($value)
	{
		$app_version = $this->db->get_where('sys_version', ['version_status' => 1, 'version_type' => 1])->row_array();
		if ($app_version['version_app'] > $value) {
			$response = [
				'success' => true,
				'version' => $app_version['version_app']
			];
		} else {
			$response = [
				'success' => false
			];
		}
		$this->response($response);
	}

	public function checkAppVersionJb($value)
	{
		$app_version = $this->db->get_where('sys_version', ['version_status' => 1, 'version_type' => 0])->row_array();
		if ($app_version['version_app'] > $value) {
			$response = [
				'success' => true,
				'version' => $app_version['version_app']
			];
		} else {
			$response = [
				'success' => false
			];
		}
		$this->response($response);
	}

	public function get_bank_tabungan($value = null)
	{
		$this->response(
			$this->db->get('sys_conf')->row_array()
		);
	}

	public function test_fcm($value = null)
	{
		$grupgaji = $this->db->get_where('ms_grup_gaji', ['grup_gaji_kode' => $value])->row_array();
		$token = $this->db->get_where('ms_juru_bayar', ['juru_bayar_id' => $grupgaji['juru_bayar_id']])->row_array();
		// $this->response($token);
		$dat['message'] = 'Test FCM';
		$dat['title'] = 'Pengajuan Pinjaman';
		$dat['type'] = 'success';
		$dat['notif_type'] = 'notif';
		// $dat['token'] = ['ckMJ2zr5zbE:APA91bFSeRh3LsWxsBzlE1_zdlVV8jq5mVR9nceArWFREzBfTG_fV3AfI0xqukx1h8CCpZnqFZcBsk1g1D21w7HdVDeQzA-f0Jqlt6O7mKjWuEXw6Bc4f1gFKjpCIx_hFT7CSG-aZVz_'];
		$dat['token'] = [$token['fcmtoken']];
		$dat['aksi'] = "FCM_PLUGIN_ACTIVITY";

		$this->response($this->sendFcm($dat));
	}
}

/* End of file Api.php */
/* Location: ./application/modules/api/controllers/Api.php */

if (!function_exists('getallheaders')) {
	function getallheaders()
	{
		if (!is_array($_SERVER)) {
			return array();
		}

		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

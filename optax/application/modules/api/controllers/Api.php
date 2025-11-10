<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Base_Controller {
	private $headers = null;
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: *");

		$this->load->model(array(
			// 'api/MobileActivationModel' 				=> 'activation',
			// 'api/MobileDeviceModel' 					=> 'device',
			// 'anggota/AnggotaModel' 						=> 'nasabah',
			// 'transaksisimpanan/TransaksiSimpananModel' 	=> 'simpanan',
			// 'transaksisimpanan/TransaksiSimpananDetailModel' => 'detailsimpanan',
			// 'pengajuanpinjaman/PengajuanPinjamanModel' 	=> 'pinjaman',
			// 'kartusimpanan/KartusimpananModel' 			=> 'kartusimpanan',
			// 'tariksimpanan/TarikSimpananModel' 			=> 'tariksimpanan',
			// 'kartupinjaman/KartupinjamanModel' 			=> 'kartupinjaman',
			// 'MobileHistoryModel'			 			=> 'history',
			// 'MobileNotificationModel'			 		=> 'notifikasi',
			// 'akun/AkunModel'							=> 'akun',
			// 'chat/MobileMessageModel'					=> 'message',
			// 'api/MobileMessageInfoModel'				=> 'messageinfo',
			// 'user/UserTokenModel' 						=> 'usertoken',
			// 'api/JurubayarModel'						=> 'jurubayar',
			// 'pengajuantalangan/Pengajuantalanganmodel'	=> 'pengajuantalangan',
			// 'kategoribarang/KategoribarangModel'		=> 'kategori',
			// 'kartutalangan/KartutalanganModel'			=> 'kartutalangan',
			// 'pembayaranpinjaman/PembayaranpinjamandetailModel'	=> 'pembayaranpinjamandetail',
			// 'pembayarantalangan/Pembayarantalangandetailmodel'	=> 'pembayarantalangandetail',
			// 'grupgaji/GrupgajiModel' 					=> 'grupgaji',
			// 'barang/barangModel' 				 => 'barang',
			// 'api/KeranjangModel'				=> 'keranjang',
			// 'transaksisimpanankhusus/Transaksisimpanankhususmodel'	=> 'transaksisimpanankhusus',
			'conf/UserLoginModel' => 'userlogin',
			'conf/UserInboxModel' => 'userinbox',
			'conf/NotificationModel' => 'notification',
		));

		$this->headers = getallheaders();
	}
	public function testdb (Type $var = null)
	{
		$DB_USER = 'root';
		$DB_PASS = 'pos-ptpis-database';
		$NEW_DB = 'pos_abc';
		$EXISTING_DB = 'pospajak-dev';
		// exec("mysql -u".$DB_USER." --password='".$DB_PASS."' -e 'DROP DATABASE IF EXISTS `".$NEW_DB."`; CREATE DATABASE `".$NEW_DB."`;'", $test, $val);
		// exec("mysqldump -u".$DB_USER." -p'".$DB_PASS."' ".$EXISTING_DB." | mysql -u ".$DB_USER." --password='".$DB_PASS."' ".$NEW_DB);
		exec("mysqldump -q -C --databases ".$EXISTING_DB." | mysql -C -h ".$NEW_DB, $test, $val);
		print_r($test);
		print_r($val);
		/* exec("mysql -u root --password='pos-ptpis-database' -e 'DROP DATABASE IF EXISTS `pos_abc`; CREATE DATABASE `pos_abc`;'");
		exec("mysqldump -u root -p 'pos-ptpis-database' pospajak-dev | mysql -u root --password='pos-ptpis-database' pos_abc"); */
	}
	public function testview()
	{
		$view = [
			'v_pos_barang', 'v_pos_kartu_stok', 'v_pos_pembayaran',
			'v_pos_pembayaran_detail', 'v_pos_pembayaran_piutang', 'v_pos_pembayaran_piutang_detail',
			'v_pos_pembelian_barang', 'v_pos_pembelian_barang_detail', 'v_pos_penjualan',
			'v_pos_penjualan_detail', 'v_pos_retur_pembelian_barang', 'v_pos_retur_pembelian_barang_detail',
			'v_pos_retur_penjualan', 'v_pos_retur_penjualan_detail', 'v_pos_stock_opname',
			'v_pos_stock_opname_detail'

		];
		
	}

	public function testnotif(){
		$result = $this->notification->sendNotif('World', 'This is hello world');
		$this->response($result);
	}

	public function testfcm()
	{
		$users = $this->db->select('pegawai_id, pegawai_nama')->get_where('pajak_pegawai', [
			'pegawai_status' => '1'
		])->result_array();

		$title = "Test notif";
		$body = "Test notification";
		foreach($users as $key => $val){
			$pemdatoken = $this->db->select('*')->get_where('conf_user_login', [
				'user_login_app' => 'PEMDA',
				'user_login_datetime_logout is NULL' => null,
				'user_login_user_id' => $val['pegawai_id']
			])->result_array();
			$tokens = [];
			foreach($pemdatoken as $tokenkey => $tokenval){
				$tokens[] = $tokenval['user_login_fcm'];
			}
			$result = $this->googleclient->sendNotification($tokens, $title, $body);
			$this->userinbox->insert(gen_uuid(), [
				'inbox_title' => $title,
				'inbox_message' => $body,
				'inbox_sender_id' => $this->session->userdata('pegawai_id'),
				'inbox_receipt_id' => $val['pegawai_id'],
				'inbox_datetime' => date('Y-m-d H:i:s'),
				'inbox_fcm_token' => json_encode($tokens),
				'inbox_status' => json_encode($result),
				'inbox_receipt_type' => isset($data['notif_receipt_type']) ? $data['notif_receipt_type'] : null,
				'inbox_feature_id' => null,
				'inbox_opened' => null,
				'inbox_jenis' => 'PERMOHONAN',
				'inbox_feature_type' => 'SPTPD',
				'inbox_note' => null,
			]);
		}
		$this->response([
			'success' => true,
			'message' => 'notification sent successfuly'
		]);
	}

	public function index()
	{
		// phpinfo();
		// $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		// $bcd = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128,5,250);
		// $this->output->set_content_type('png')->set_output($bcd);
	}

	public function ip($value='')
	{
		$ip = $this->input->ip_address();
		echo $ip;
	}

	public function penjualan_eror($value='')
	{
		$penjualan_eror = $this->db->query('SELECT penjualan_tanggal, penjualan_kode, penjualan_detail_qty, penjualan_detail_harga, penjualan_detail_subtotal, barang_kode, barang_nama FROM pos_penjualan_detail 
				LEFT JOIN pos_penjualan on penjualan_detail_parent = penjualan_id
				LEFT JOIN ms_barang on barang_id = penjualan_detail_barang_id
			WHERE (penjualan_detail_qty * penjualan_detail_harga) <> penjualan_detail_subtotal AND penjualan_detail_potongan = 0')->result_array();
		echo "<pre>";
		print_r($penjualan_eror);
		echo "</pre>";
	}

	public function config_eror($value='')
	{
		
		echo "<pre>";
		echo $this->config->item('base_jasa_pinjaman');
		echo "</pre>";
	}

	public function config($value='')
	{
		echo $this->config->item('base_gudang');exit;
	}

	public function barcode_anggota($kodeanggota='DEFAULT_CODE')
	{
		// error_reporting(-1);
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		$bcd = $generator->getBarcode($kodeanggota, $generator::TYPE_CODE_128,5,250);
		$filename='assets/barcode/'.$kodeanggota.'.png';
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

	public function activate($value='')
	{
		$data = varPost();
		$opr = array(
			'success' => false,
			'message' => 'Kode Anggota tidak terdaftar pada sistem'
		);
		$cek_anggota = $this->nasabah->read(['anggota_kode' => $data['activate_kodeanggota'],'anggota_nama' => $data['activate_namaanggota']]);
		if ($cek_anggota) {
			$cek_aktivasi = $this->activation->read($data);
			if (isset($cek_aktivasi['activate_pin'])) {
				$opr = array(
					'success' => false,
					'message' => "Kode Anggota {$data['activate_kodeanggota']} masih dalam proses aktifasi"
				);
			} else {
				$opr = $this->activation->insert(gen_uuid(),$data);
				$opr['message'] = 'Proses pendaftaran berhasil, silakan menunggu sampai akun berhasil di aktifasi';	
			}
		}
		$this->response($opr);
	}

	public function cek_login($value = ''){
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
			if($cek['activate_pin'] == $data['activate_pin']){
				if($cek['activate_fcmtoken'] == $data['fcmtoken'] || is_null($cek['activate_fcmtoken'])){
					$opr = [
						'success' => true,
						'data' => $data,
						'mobile_device' => true
					];
				}else{
					$opr = [
						'success' => false,
						// 'data' => $data,
						// 'mobile_device' => true,
						'message' => "Login gagal, keluar dari perangkat sebelumnya untuk masuk ke perangkat baru"
					];
				}
			}else{
				$opr = array(
					'success' => false,
					'message' => 'Pin yang anda masukkan salah!'
				);
			}
		}

		$this->response($opr);
	}
}

/* End of file Api.php */
/* Location: ./application/modules/api/controllers/Api.php */

if (!function_exists('getallheaders'))  {
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





<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			"hakakses/RoleAccessModel"  => 'RoleAccess',
			'api/MobileNotificationModel' => 'notif',
			'api/MobileDeviceModel' => 'device'
		));
	}

	public function index()
	{
		$ses = $this->session->userdata();
		$role_code = $this->RoleAccess->select([
			'filters_static' => [
					'user_id'       => $ses['user_id'],
					'menu_isaktif'  => 1,
					'menu_level'    => 3,
			],
			'fields'    => 'menu_kode'
		]);
		// print_r('<pre>');print_r($role_code);print_r('</pre>');exit;



		print_r('<pre>');print_r($ses);print_r('</pre>');exit;

		$get_jenis = $this->dbmp->get_where('pajak_jenis', ['jenis_nama' => $ses['toko']['jenis_nama']])->row_array();
		$get_jenis_parent = $this->dbmp->get_where('pajak_jenis', ['jenis_id' => $get_jenis['jenis_parent']])->row_array();
		if ($get_jenis_parent['jenis_nama'] == 'PAJAK RESTORAN') {
			print_r('<pre>');print_r('RESTO');print_r('</pre>');exit;
		} else if ($get_jenis_parent['jenis_nama'] == 'PAJAK HOTEL') {
			print_r('<pre>');print_r('HOTEL');print_r('</pre>');exit;
		}
	}

	public function notif_push($value='')
	{
		pushnotif(array(
			'sentto' => '9adeb82fffb5444e81fa0ce8ad8afe7a',
			'tipe' => 'NOTIFIKASI',
			'judul' => 'Coba Notifikasi',
			'notifikasi' => 'ini adalah percobaan insert notifikasi ke tabel notif pada'.date('Y-m-d H:i:s')
		));
	}

	public function notif_send($value='')
	{
		# code...
	}

	public function senttoall($value='')
	{
		$target = $this->device->select();
		foreach ($target['data'] as $key => $value) {
			$mes = $this->notif->sent(array(
				'target' => $value['device_token'],
				'title' => 'Pesan Notifikasi '.date('Y-m-d H:i:s'),
				'message' => 'Ini adalah pesan broadcast dari server yang dikirim pada '.date('Y-m-d H:i:s'),
				'notif_id' => '32bit MD5 ID',
				'callback_action' => array(
					'route' => 'page1',
					'param' => 'param1'
				)
			));
			print_r($mes);
		}
		// $this->response($target);
	}
}

/* End of file Notification.php */
/* Location: ./application/modules/api/controllers/Notification.php */
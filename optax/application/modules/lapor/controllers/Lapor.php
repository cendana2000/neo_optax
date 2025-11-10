<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lapor extends Base_Controller
{
	var $ip_server = "192.168.0.195";
	var $app_folder = "simpatdamalang";

	public function __construct()
	{
		parent::__construct();
	}

	public function konfirmasi($npwpd_masapajak_base64 = "")
	{
		$npwpd_masapajak_base64_decode 	= base64_decode($npwpd_masapajak_base64);
		$npwpd_masapajak_array 			= explode("_", $npwpd_masapajak_base64_decode);
		$data = array(
			"npwpd" 		=> $npwpd_masapajak_array[0],
			"masa_pajak" 	=> $npwpd_masapajak_array[1],
			"nama_wp" 		=> $npwpd_masapajak_array[2],
		);

		$konfirmasi = $this->konfirmasi_to_simpatda($npwpd_masapajak_array[0], date("Y-m-d", strtotime($npwpd_masapajak_array[1])));
		file_put_contents(FCPATH . "assets/log/coba", $konfirmasi);
		$konfirmasi_result = false;
		if ($konfirmasi) {
			$konfirmasi_result = json_decode($konfirmasi);
		}

		$data["konfirmasi_result"] = $konfirmasi;

		$this->session->set_flashdata("data_wp", $data);
		redirect("https://persada.malangkota.go.id/backoffice/");
	}

	public function konfirmasi_to_simpatda($npwpd, $periode_awal)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://' . $this->ip_server . '/' . $this->app_folder . '/sptpd_persada/konfirmasi_laporan/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('periode_awal' => $periode_awal, 'npwpd' => $npwpd),
			CURLOPT_HTTPHEADER => array(
				'Cookie: simpatda=AGVROFRsCD1VJVAjUj1WOQw%2BAzlQdwFzCDVXdgwlVmpRPlVvCFJSNQJiUCEFMwF9BzYCMQBnUzcGJlttVzMHYA1sUzlYNlc1Vm9XM1RkAm0AYFFmVGgINlVoUDFSNlZpDG0DOlBtATUIYFdkDGVWYFE3VTMIO1JpAjZQIQUzAX0HNgIzAGVTNwYmWzJXIgdZDTRTZVhmV3BWM1dwVCICLgA%2FUXFUYwg2VW1QalIlVjkMNwMxUHsBMQhmVz0MeFYzUX9VMwg5UmQCJFA4BXsBNAc9AjIAb1MvBnFbKFc3B3QNClNgWGVXZ1Y4V3dUcwI3AHdROFRrCDZVZFByUldWZwx9A3dQOAFhCD5XVwwjVm1RJVVoCGBSOQIpUDQFJgE9Bz4CLABmUy8GP1soV2gHNw1mUztYIFduVjdXcFQlAlMAZVFhVC0IblUoUDlSc1ZxDCwDOFA8AToIYVczDG9WMVFhVTgIPlJtAjRQMAUzAX0HNgI7AG9TLwZxWyhXNwd0DQpTZVhjV3ZWN1chVGoCfwA%2BUTJUYwglVXxQa1J6'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
}

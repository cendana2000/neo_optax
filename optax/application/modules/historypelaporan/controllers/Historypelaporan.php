<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Historypelaporan extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			"historypelaporan/HistorypelaporanModel" => 'historypelaporan',
		));
	}

	public function index()
	{
		header("Content-type:application/json");
		$npwpd 							= $this->session->userdata('wajibpajak_npwpd');
		$data_pembayaran 				= $this->get_data_pembayaran($npwpd);
		$data 							= ($data_pembayaran) ? json_decode($data_pembayaran, true) : $data_pembayaran;
		$start							= $this->input->post('start');
		$perHalaman						= $this->input->post('length');
		$max							= ($start + $perHalaman) - 1;
		$data["rows"]					= array();
		foreach ($data["data"] as $key => $val) {
			if ($key >= $start and $key <= $max) {
				$val["JUMLAH_PAJAK"] = (int)$val["JUMLAH_PAJAK"];
				$data["rows"][] = $val;
			}
		}

		$data["iTotalDisplayRecords"] 	= count($data["data"]);
		$data["iTotalRecords"] 			= count($data["data"]);

		$data["data"]					= $data["rows"];

		print_r(json_encode($data));
	}

	private function get_data_pembayaran($npwpd)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://192.168.0.195/simpatdamalang/index.php/history_pembayaran',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('npwpd' => $npwpd),
			CURLOPT_HTTPHEADER => array(
				'Cookie: simpatda=MCSb2OmdeTzUeTpQsGKi%2BveBoNW6d1qa2aoZstRbnmabU1s5%2BSiVYJ8hIyFDIb8m2dQZqndJwsqSnU7QAoC9D%2FcAzT9xsKZVtc00VORF94nBLXQxuWyhwlgS3KElI94PBwvR4X%2Fg5mywShsu5Dxbt58gnio6uz4aEsV%2Fz304zuXvrZjWmiwRFYnCQrFGLwCcBx%2FvuIOrfz0B6SMj%2BNs98vyDeUir%2BGVYUdb0nPbd8N4N4L5SW%2FZMoTvMn1hPlV7rulxjSGYkVZApAvEI2jD1iipOXjVm63jQxeSYRel61E7DXNrIPDlW19GQ9%2BqGjgGthIGIbk8mgXK19RH2isLpYA%3D%3D'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

	public function total_tagihan()
	{
		header("Content-type:application/json");
		$npwpd 				= $this->session->userdata('wajibpajak_npwpd');
		$data_pembayaran 	= $this->get_data_pembayaran($npwpd);
		$data 				= ($data_pembayaran) ? json_decode($data_pembayaran, true) : $data_pembayaran;
		$jml_belum_lunas	= 0;
		foreach ($data["data"] as $key => $val) {
			$jml_belum_lunas += ($val["TANGGAL_LUNAS"] == null) ? 1 : 0;
		}
		$result = array(
			"status" 			=> true,
			"jml_belum_lunas" 	=> $jml_belum_lunas,
		);
		print_r(json_encode($result));
	}

	public function coba()
	{
		print_r($this->session->all_userdata());
	}

	public function cetak_sptpd($id_spt = "")
	{
		// $id_spt 		= ($this->input->post('id_spt')) ? $this->input->post('id_spt') : "";
		$ip_server 		= "192.168.0.195";
		$npwpd 			= $this->session->userdata('wajibpajak_npwpd');
		$namaJenisPajak = $this->historypelaporan->getJenisPajakName($npwpd);
		$namaJenisPajak = (count($namaJenisPajak) > 0) ? $namaJenisPajak[0]["jenis_pajak"] : "";

		if ($id_spt != "") {
			$linkPreview = array(
				"pajak restoran" => "Form%20Pajak%20Restoran",
				"pajak hotel" 	 => "Form%20Pajak%20Hotel",
			);

			$u = array(
				"pajak restoran" => "Form Pajak Restoran",
				"pajak hotel" 	 => "Form Pajak Hotel",
			);
			$curl = curl_init();
			$post_field = array('tipe' => 'form', 'id' => $id_spt, 'modul' => str_replace(" ", "_", $namaJenisPajak), 'u' => $u[$namaJenisPajak]);
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://' . $ip_server . '/simpatdamalang/preview/proses/0/0/0/' . $linkPreview[$namaJenisPajak],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $post_field,
				CURLOPT_HTTPHEADER => array(
					'Cookie: simpatda=Cm8HbgE5DTgCclEiA2wHaAc1ADpQdwd1DjNUdQ0kBjoBblJoVA4FYlMzUyJUYlklVmdfbFE2VTEBIVJkDWsMP1gwUWBaNVs3AWBQMFJnBj4KPgc2AT4NNgJsUWUDYgdtBzMAYVA2Bz8Ob1RiDTEGZAEyUmNUNwU%2BU2VTIlRiWSVWZ19uUTRVMQEhUjsNeAxSWGFRZ1pkW3wBZFB3UiQGKgo1BycBNg0zAjpRawN0B2gHPAAyUHsHNw5gVD4NeQZjAS9SNFRlBTNTdVM7VCpZbFZsX29RPlUpAXZSIQ1tDH9YX1FiWmdbawFvUHBSdQYzCn0HbgE%2BDTMCM1FzAwYHNgd2AHRQOAdnDjhUVA0iBj0BdVJvVDwFblN4UzdUd1llVmxfcVE3VSkBOFIhDTIMPFgzUTlaIltiAWBQd1IjBlcKbwc3AXgNawJ%2FUTgDIgcgBycAO1A8BzwOZ1QwDW8GYgE2UjdUZwU%2FU2VTNlRiWSVWZ19mUT5VKQF2UiENbQx%2FWF9RZ1phW3oBYFAmUmwGewo0B2QBNg0gAitRagMr'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);

			$id_pdf = json_decode($response)->id;

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://' . $ip_server . '/simpatdamalang/preview/view/' . $id_pdf . '/' . $id_spt . '-' . $linkPreview[$namaJenisPajak],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					'Cookie: simpatda=VjMFbFNrDjsGdlMgAG9UOwMxW2ENKlYkATwBIF92UW1TPFVvUAoGYQBgWyoEMlwgADFXZANkBWFWdgBkV2QDN1s0Wj1cMlczAGMBM1VmU29WZwVkU2YOOAY9U2oAYVRvAzRbaQ04VmABaAFrX2dRZ1NqVTlQMAY%2BADxbKgQyXCAAMVdmA2YFYVZ2AGlXIgNdW2JabFxiV3AAZQEmVSNTf1ZpBSVTZA4wBj5TaQB3VDsDOFtpDSZWZgFvAWtfK1E0U31VM1BhBjAAJlszBHpcaQA6V2cDbAV5ViEAc1c3A3BbXFppXGFXZwBuASFVclNmViEFbFNsDjAGN1NxAAVUZQNyWy8NZVY2ATcBAV9wUWpTJ1VoUDgGbQArWz8EJ1xgADpXeQNlBXlWbwBzV2gDM1swWjJcJFduAGEBJlUkUwJWMwU1UyoOaAZ7UzoAIVRzAyNbYA1hVm0BaAFlXz1RNVNkVTNQZQYwADBbOgQyXCAAMVduA2wFeVYhAHNXNwNwW1xabFxnV3YAYQF3VWtTLlZoBWZTZA4jBi9TaAAo'
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);

			header('Content-type: application/pdf');

			// It will be called downloaded.pdf
			header("Content-Disposition:attachment;filename=\"$id_spt-" . $u[$namaJenisPajak] . ".pdf\"");

			print_r($response);
		} else {
			$response = json_encode(array());
			print_r(json_encode(
				array(
					"status" => false,
					"response" => $response
				)
			));
		}
	}

	public function cetak_sspd($id_tbp = "")
	{
		$ip_server = "192.168.0.195";

		if ($id_tbp != "") {
			$curl = curl_init();
			$post_field = array('tipe' => 'form', 'id' => $id_tbp, 'modul' => 'tbp', 'u' => 'Form Tanda Bukti Setoran');
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://' . $ip_server . '/simpatdamalang/preview/proses/0/0/0/Form%20Tanda%20Bukti%20Setoran',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $post_field,
				CURLOPT_HTTPHEADER => array(
					'Cookie: simpatda=Cm8HbgE5DTgCclEiA2wHaAc1ADpQdwd1DjNUdQ0kBjoBblJoVA4FYlMzUyJUYlklVmdfbFE2VTEBIVJkDWsMP1gwUWBaNVs3AWBQMFJnBj4KPgc2AT4NNgJsUWUDYgdtBzMAYVA2Bz8Ob1RiDTEGZAEyUmNUNwU%2BU2VTIlRiWSVWZ19uUTRVMQEhUjsNeAxSWGFRZ1pkW3wBZFB3UiQGKgo1BycBNg0zAjpRawN0B2gHPAAyUHsHNw5gVD4NeQZjAS9SNFRlBTNTdVM7VCpZbFZsX29RPlUpAXZSIQ1tDH9YX1FiWmdbawFvUHBSdQYzCn0HbgE%2BDTMCM1FzAwYHNgd2AHRQOAdnDjhUVA0iBj0BdVJvVDwFblN4UzdUd1llVmxfcVE3VSkBOFIhDTIMPFgzUTlaIltiAWBQd1IjBlcKbwc3AXgNawJ%2FUTgDIgcgBycAO1A8BzwOZ1QwDW8GYgE2UjdUZwU%2FU2VTNlRiWSVWZ19mUT5VKQF2UiENbQx%2FWF9RZ1phW3oBYFAmUmwGewo0B2QBNg0gAitRagMr'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);

			$id_pdf = json_decode($response)->id;

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://' . $ip_server . '/simpatdamalang/preview/view/' . $id_pdf . '/' . $id_tbp . '-Form%20Tanda%20Bukti%20Setoran',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					'Cookie: simpatda=VjMFbFNrDjsGdlMgAG9UOwMxW2ENKlYkATwBIF92UW1TPFVvUAoGYQBgWyoEMlwgADFXZANkBWFWdgBkV2QDN1s0Wj1cMlczAGMBM1VmU29WZwVkU2YOOAY9U2oAYVRvAzRbaQ04VmABaAFrX2dRZ1NqVTlQMAY%2BADxbKgQyXCAAMVdmA2YFYVZ2AGlXIgNdW2JabFxiV3AAZQEmVSNTf1ZpBSVTZA4wBj5TaQB3VDsDOFtpDSZWZgFvAWtfK1E0U31VM1BhBjAAJlszBHpcaQA6V2cDbAV5ViEAc1c3A3BbXFppXGFXZwBuASFVclNmViEFbFNsDjAGN1NxAAVUZQNyWy8NZVY2ATcBAV9wUWpTJ1VoUDgGbQArWz8EJ1xgADpXeQNlBXlWbwBzV2gDM1swWjJcJFduAGEBJlUkUwJWMwU1UyoOaAZ7UzoAIVRzAyNbYA1hVm0BaAFlXz1RNVNkVTNQZQYwADBbOgQyXCAAMVduA2wFeVYhAHNXNwNwW1xabFxnV3YAYQF3VWtTLlZoBWZTZA4jBi9TaAAo'
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);

			header('Content-type: application/pdf');

			// It will be called downloaded.pdf
			header("Content-Disposition:attachment;filename=\"$id_tbp-" . "Form Tanda Bukti Setoran.pdf\"");

			print_r($response);
		} else {
			$response = json_encode(array());
			print_r(json_encode(
				array(
					"status" => false,
					"response" => $response
				)
			));
		}
	}
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Oapi extends Base_Controller
{
	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'OapiModel' 				=> 'OAPI',
			'preset/presetModel' => 'preset',
			// 'realisasipajak/TokoModel' 	=> 'pajakToko',
		));
	}

	public function setQueue()
	{
		ini_set('display_errors', 0);
		try {
			$connection = new AMQPStreamConnection($_ENV['RMQ_HOST'], $_ENV['RMQ_PORT'], $_ENV['RMQ_USERNAME'], $_ENV['RMQ_PASSWORD']);
			$channel = $connection->channel();

			$channel->queue_declare($_ENV['APP_NAME'] . "-posting_pajak_outer", false, false, false, false);

			$dataWP = $this->OAPI->getToko();

			foreach ($dataWP as $key => $value) {
				$msg = new AMQPMessage(json_encode(['toko_kode' => $value['toko_kode'], 'date' => date('Y-m-d')]));
				$channel->basic_publish($msg, '', $_ENV['APP_NAME'] . "-posting_pajak_outer");
				break;
			}
			file_put_contents(FCPATH . "assets/log/queueSuccess.log", date("Y-m-d H:i:s") . " queueSuccess" . PHP_EOL, FILE_APPEND);
			return $this->response([
				'success' => true,
				'message' => 'Berhasil memasukan sinkronisasi OAPI dalam antrian',
				// 'datawp' => $dataWP
			]);
		} catch (Exception $e) {
			file_put_contents(FCPATH . "assets/log/queueFailed.log", date("Y-m-d H:i:s") . " queueFailed" . PHP_EOL, FILE_APPEND);
			return $this->response([
				'success' => false,
				'message' => 'Gagal sinkronisasi OAPI: ' . $e->getMessage(),
			]);
		}
	}

	public function consumeData()
	{
		file_put_contents(FCPATH . "assets/log/consume.log", date("Y-m-d H:i:s") . " - consumed");
		try {
			ini_set('display_errors', 0);
			$connection = new AMQPStreamConnection($_ENV['RMQ_HOST'], $_ENV['RMQ_PORT'], $_ENV['RMQ_USERNAME'], $_ENV['RMQ_PASSWORD']);
			$channel = $connection->channel();
			$channel->queue_declare($_ENV['APP_NAME'] . "-posting_pajak_outer", false, false, false, false);
			echo " [*] V3Oapi Waiting for messages. To exit press CTRL+C" . PHP_EOL;

			$channel->basic_consume($_ENV['APP_NAME'] . "-posting_pajak_outer", '', false, true, false, false, function ($msg) {
				file_put_contents(FCPATH . "assets/log/msg_variable.log", date("Y-m-d H:i:s") . " - " . json_encode($msg));
				try {
					//code...
					$as = json_decode($msg->body);
					$query_cek_waktu = $this->db->query("SELECT * FROM pajak_realisasi where cast(realisasi_created_at as date) = '" . $as->date . "'");
					$cek_waktu 	= $query_cek_waktu->result_array();
					// if (!empty($cek_waktu)) {
					// 	echo " [x] Sent Data Failed\n";
					// } else {
					echo date('Y-m-d H:i:s') . " # Initial Sync Oapi \n";
					$dcWP = $this->OAPI->getToko();
					foreach ($dcWP as $key => $value) {
						$insert = $this->syncPosOuter($value);
						if ($insert) {
							echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Sent Data Success # " . json_encode($insert) . "\n";
						} else {
							echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Theres No Data # " . json_encode($insert) . "\n";
						}
					}
					echo date('Y-m-d H:i:s') . " # End Sync Oapi \n";
				} catch (Exception $e) {
					//throw $th;
					// Catat pengecualian apa pun yang terjadi dalam pemrosesan pesan
					error_log('Error in message processing: ' . $e->getMessage());
				}
				// }
			});

			while ($channel->is_open()) {
				$channel->wait();
			}
			$channel->close();
			$connection->close();
		} catch (Exception $e) {
			// Catat pengecualian apa pun yang terjadi dalam fungsi utama
			error_log('Error in consumeData function: ' . $e->getMessage());
		}
	}

	public function coba()
	{
		// print_r(base_url());

		// print_r($_ENV['BASE_URL']);

		$dcWP = $this->OAPI->getToko();
		foreach ($dcWP as $key => $value) {
			$insert = false;
			if ($value["toko_wajibpajak_npwpd"] == "3244.62.102") {
				$value["single_sync"] = true;
				$insert = $this->syncPosOuter($value, "2024-11-01");
			}
			if ($insert) {
				echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Sent Data Success # " . json_encode($insert) . "\n";
			} else {
				echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Theres No Data # " . json_encode($insert) . "\n";
			}
		}

		// print_r($dataWP);
		// file_put_contents(FCPATH . "assets/log/coba.log", date("Y-m-d H:i:s") . " coba" . PHP_EOL, FILE_APPEND);

		// Array
		// (
		//     [toko_id] => 3c2eb97a75c7a998d8c42443465d04c2
		//     [toko_kode] => f2vxs
		//     [toko_nama] => D. PARAGON
		//     [toko_wajibpajak_id] => ccad29cae7291e43a166e725a15d26b3
		//     [toko_wajibpajak_npwpd] => 1680.62.221
		//     [toko_logo] => 
		//     [toko_register_id] => 
		//     [toko_registered_at] => 2022-05-18 10:22:05
		//     [toko_verified_at] => 2022-05-18 10:22:18
		//     [toko_verified_by] => 5f2c8335208a3d4f79ec05cc3a898880
		//     [toko_status] => 2
		//     [toko_tipe_pos] => 2
		//     [toko_api_penjualan] => http://192.168.0.248/crawler_etax/crawl?npwpd=1680.62.221&start_date={{startdate}}&end_date={{enddate}}
		//     [toko_preset_id] => d9be32f7e5188f421066627bacc5afc8
		//     [toko_is_oapi] => ACTIVE
		//     [toko_is_pos] => 
		//     [toko_jadwal_before] => 3
		// )
	}

	//update 28-05-2024 single sinkron oapi
	public function setQueueSingle()
	{
		$npwpd 		= $this->input->post("npwpd");
		$periode 	= ($this->input->post("periode")) ? $this->input->post("periode") : date("Y-m-d");
		$dataWP 	= $this->OAPI->getToko();

		ini_set('display_errors', 0);
		try {
			$connection = new AMQPStreamConnection($_ENV['RMQ_HOST'], $_ENV['RMQ_PORT'], $_ENV['RMQ_USERNAME'], $_ENV['RMQ_PASSWORD']);
			$channel = $connection->channel();

			$channel->queue_declare($_ENV['APP_NAME'] . "-posting_pajak_outer_single", false, false, false, false);

			$dataWP = $this->OAPI->getToko();

			$npwpd = $this->input->post("npwpd");
			if ($npwpd != "") {
				foreach ($dataWP as $key => $value) {
					if ($value["toko_wajibpajak_npwpd"] == $npwpd) {
						$msg = new AMQPMessage(json_encode(
							[
								'toko_kode' => $value['toko_kode'],
								'date' 		=> date('Y-m-d'),
								'npwpd' 	=> $value['toko_wajibpajak_npwpd'],
								'periode' 	=> $periode,
							]
						));
						$channel->basic_publish($msg, '', $_ENV['APP_NAME'] . "-posting_pajak_outer_single");
					}
				}
			}
			file_put_contents(FCPATH . "assets/log/queueSuccess.log", date("Y-m-d H:i:s") . " queueSuccessSingle" . PHP_EOL, FILE_APPEND);
			return $this->response([
				'success' => true,
				'message' => 'Berhasil memasukan sinkronisasi OAPI dalam antrian',
				// 'datawp' => $dataWP
			]);
		} catch (Exception $e) {
			file_put_contents(FCPATH . "assets/log/queueFailed.log", date("Y-m-d H:i:s") . " queueFailedSingle" . PHP_EOL, FILE_APPEND);
			return $this->response([
				'success' => false,
				'message' => 'Gagal sinkronisasi OAPI: ' . $e->getMessage(),
			]);
		}
	}

	public function singleConsumeData()
	{
		file_put_contents(FCPATH . "assets/log/consume.log", date("Y-m-d H:i:s") . " - consumed");
		try {
			ini_set('display_errors', 0);
			$connection = new AMQPStreamConnection($_ENV['RMQ_HOST'], $_ENV['RMQ_PORT'], $_ENV['RMQ_USERNAME'], $_ENV['RMQ_PASSWORD']);
			$channel = $connection->channel();
			$channel->queue_declare($_ENV['APP_NAME'] . "-posting_pajak_outer_single", false, false, false, false);
			echo " [*] V3Oapi Waiting for messages. To exit press CTRL+C" . PHP_EOL;

			$channel->basic_consume($_ENV['APP_NAME'] . "-posting_pajak_outer_single", '', false, true, false, false, function ($msg) {
				file_put_contents(FCPATH . "assets/log/msg_variable.log", date("Y-m-d H:i:s") . " - " . json_encode($msg));
				try {
					//code...
					$as = json_decode($msg->body);
					$query_cek_waktu = $this->db->query("SELECT * FROM pajak_realisasi where cast(realisasi_created_at as date) = '" . $as->date . "'");
					$cek_waktu 	= $query_cek_waktu->result_array();
					// if (!empty($cek_waktu)) {
					// 	echo " [x] Sent Data Failed\n";
					// } else {
					echo date('Y-m-d H:i:s') . " # Initial Sync Oapi \n";
					$dcWP = $this->OAPI->getToko();
					foreach ($dcWP as $key => $value) {
						$insert = false;
						if ($value["toko_wajibpajak_npwpd"] == $as->npwpd) {
							$value["single_sync"] = true;
							$insert = $this->syncPosOuter($value, $as->periode);
						}
						if ($insert) {
							echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Sent Data Success # " . json_encode($insert) . "\n";
						} else {
							echo date('Y-m-d H:i:s') . " # {$value['toko_kode']} # Theres No Data # " . json_encode($insert) . "\n";
						}
					}
					echo date('Y-m-d H:i:s') . " # End Sync Oapi \n";
				} catch (Exception $e) {
					//throw $th;
					// Catat pengecualian apa pun yang terjadi dalam pemrosesan pesan
					error_log('Error in message processing: ' . $e->getMessage());
				}
				// }
			});

			while ($channel->is_open()) {
				$channel->wait();
			}
			$channel->close();
			$connection->close();
		} catch (Exception $e) {
			// Catat pengecualian apa pun yang terjadi dalam fungsi utama
			error_log('Error in consumeData function: ' . $e->getMessage());
		}
	}

	public function logsyncoapireader()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://192.168.0.248/crawler_etax/crawl/logsyncoapireader',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Cookie: bapenda_kota_malang=AWUEOQs6B25SKQsoCD0BMVZkVz5XdAIlU2QOfAciVmgHOwRuAFxQbFo7DisCPQEhAjhSMVtmAToEJABlAzoFZlA3DW0DZFJjAGFVaQIyAzMBNwQ7CzgHZlIxC24IMAEzVjZXZlc1AmJTYA47B2hWOQdnBDAAM1A3Wj4OKwI9ASECOFIzW2QBOgQkADoDcgUOUGMNaAMxUncANlUiAnIDdAE%2FBHALNQdlUmELYQglATFWbVc2V3gCZ1M3DjcHf1YxB3oEMgA3UD1afQ4yAnUBaAIzUjJbbgEiBHMAIANnBSNQXQ1tAzJSYAA9VSUCIwNtAXcEOQs9B2VSaAt5CFcBb1YnV3BXOwI3U28OXQckVm8HIARpAG5QYFpwDj4CKAFhAjVSLFtnASIEPQAgAzgFYFAxDTYDd1JpADJVIgJ1AwkBZQRgC3sHPVIkCzIIcwF5VnZXP1c%2FAmxTMA44B2BWNgdiBDUAMVAxWmwOOgI9ASECOFI7W24BIgRzACADZwUjUF0NaAM0UnEAMlVzAjoDJQE%2BBDMLNQd2UnALYAh6'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		print_r($response);
	}

	public function index()
	{
		$req = $_GET['dGtu'];

		$data = explode("|", $req);

		$token = $data[0];
		$tgl = $data[1];

		$v11 = base64_decode($token);
		$v12 = substr($v11, 0, 11);
		$v13 = substr($v11, 12);

		$na = base64_decode($v13 . "=");
		$ma = base64_decode($v12 . "=");
		$v1 = base64_decode($na . "" . $ma);


		if ($v1 == "S3n4b1m4pp0S") {
			$dcWP = $this->OAPI->getToko();

			// print_r('<pre>');print_r($dcWP);print_r('</pre>');exit;
			foreach ($dcWP as $key => $value) {
				$this->syncPosOuter($value, $tgl, false);
			}
		} else {
			redirect(base_url());
		}
	}

	private function syncPosOuter($wp, $tgl = '', $isMessageBroker = true)
	{
		$ch = curl_init();
		if (!empty($tgl)) {
			$now = $tgl;
		} else {
			$now = date('Y-m-d');
		}

		if (isset($wp['single_sync'])) {
			$now = (!empty($tgl)) ? $tgl : date('Y-m-d');
		} elseif (!empty($wp['toko_jadwal_before'])) {
			$now = date('Y-m-d', strtotime($now . "-" . $wp['toko_jadwal_before'] . " days"));
		}

		$preset = $this->preset->read($wp['toko_preset_id']);
		$presetDetail = $this->db->get_where('pajak_preset_detail_api', ['preset_detail_parent_id' => $preset['preset_id']])->result_array();

		// $data = [];
		// if (strpos($wp['toko_api_penjualan'], '{{enddate}}') == false) {
		// 	$date = $now;
		// 	if (!empty($tgl)) {
		// 		$date = $tgl;
		// 	} else if (!empty($wp['toko_jadwal_before'])) {
		// 		$date = date('Y-m-d', strtotime($now . "-" . $wp['toko_jadwal_before'] . " days"));
		// 	}

		// 	$dates = $this->getAllDatesInMonth($date);
		// 	foreach ($dates as $date_key => $date_value) {
		// 		$endpoint = str_replace("{{startdate}}", $date_value, $wp['toko_api_penjualan']);
		// 		$data_curl = $this->get_oapi_curl($ch, $endpoint, $wp);
		// 		foreach ($data_curl as $value_curl) {
		// 			$data[] = $value_curl;
		// 		}
		// 	}
		// } else {

		// 	if (!empty($tgl)) {
		// 		$startDate = $tgl['startDate'];
		// 		$endDate = $tgl['endDate'];
		// 	} else if (!empty($wp['toko_jadwal_before'])) {
		// 		$startDate = date('Y-m-d', strtotime($now . "-" . $wp['toko_jadwal_before'] . " days"));
		// 		$endDate = $now;
		// 	}

		// 	$endpoint = str_replace("{{startdate}}", $startDate, $wp['toko_api_penjualan']);
		// 	$endpoint = str_replace("{{enddate}}", $endDate, $endpoint);
		// 	$data = $this->get_oapi_curl($ch, $endpoint, $wp);
		// }

		$endpoint = str_replace("{{startdate}}", $now, $wp['toko_api_penjualan']);
		$endpoint = str_replace("{{enddate}}", $now, $endpoint);

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		file_put_contents(FCPATH . "assets/log/push_detail_cek.log", date("Y-m-d H:i:s") . " - " . $wp['toko_kode'] . " - " . $output . " - " . $endpoint . PHP_EOL, FILE_APPEND);

		$data = json_decode($output, true);

		if (str_contains($presetDetail[0]['preset_detail_right'], '->')) {
			$ex = explode('->', $presetDetail[0]['preset_detail_right']);
			$data = $data[$ex[0]];
		}

		$pos_penjualan_detail = [];
		foreach ($data as $datakey => $dataval) {
			$pos_penjualan[$datakey]['penjualan_id'] = gen_uuid('pos_penjualan');
			foreach ($presetDetail as $presetkey => $presetval) {
				$exval = explode('->', $presetval['preset_detail_right']);
				if (count($exval) > 1) {
					$val = $dataval;
					for ($i = 1; $i < count($exval); $i++) {
						if (str_contains($exval[$i], '[')) {
							// print_r('<pre>');print_r($val);print_r('</pre>');exit;
							// print_r('<pre>');print_r($i);print_r('</pre>');
							// print_r('<pre>');print_r(empty($val) ? 'true' : 'false');print_r('</pre>');
							// print_r('<pre>');print_r($val);print_r('</pre>');exit;
							// $pattern = '/\[(.*?)\]/'; // Match everything between square brackets
							// preg_match($pattern, $exval[$i], $matches);
							// if (isset($matches[1])) {
							// 	$strval = $matches[1];
							// 	$openBracketPos = strpos($exval[$i], "[");
							// 	if ($openBracketPos !== false) {
							// 		$name = substr($exval[$i], 0, $openBracketPos);
							// 		// print_r('<pre>');print_r($name);print_r('</pre>');exit;
							// 		// print_r('<pre>');print_r($strval);print_r('</pre>');exit;
							// 		$val = $val[$name];
							// 	}
							// }
						} elseif (str_contains($exval[$i], 'count(')) {
							$countpattern = '/count\((.*?)\)/'; // Match everything between parentheses after "count"
							preg_match($countpattern, $exval[$i], $matches);
							if (isset($matches[1])) {
								$countval = $matches[1];
								$excountval = explode('>', $countval);
								if (count($excountval) > 1) {
									$sumcountnested = 0;
									foreach ($val[$excountval[0]] as $countnestedkey => $countnestedval) {
										$sumcountnested += $countnestedval[$excountval[1]];
									}
									$val = $sumcountnested;
								} else {
									$val = count($val[$countval]);
								}
							}
						} else {
							$val = $val[$exval[$i]];
						}
					}
					$pos_penjualan[$datakey][$presetval['preset_detail_left']] = $val;
				} else {
					$pos_penjualan[$datakey][$presetval['preset_detail_left']] = $dataval[$presetval['preset_detail_right']];
				}
			}
			$pos_penjualan[$datakey]['penjualan_source'] = 'OAPI';
			array_push($pos_penjualan_detail, [
				'penjualan_detail_id' => gen_uuid(),
				'penjualan_detail_parent' => $pos_penjualan[$datakey]['penjualan_id'],
				'penjualan_detail_nama_barang' => $pos_penjualan[$datakey]['penjualan_kode'],
				'penjualan_detail_qty' => $pos_penjualan[$datakey]['penjualan_total_qty']
			]);

			// data untuk tabel pos_app table pajak_realisasi
			$pos_realisasi[$datakey]['realisasi_id'] = gen_uuid('pajak_realiasi');
			$pos_realisasi[$datakey]['realisasi_no'] = $pos_penjualan[$datakey]['penjualan_kode'];
			$pos_realisasi[$datakey]['realisasi_tanggal'] = date("Y-m-d H:i:s", strtotime($pos_penjualan[$datakey]['penjualan_tanggal']));
			$pos_realisasi[$datakey]['realisasi_sub_total'] = $pos_penjualan[$datakey]['penjualan_sub_total'];
			$pos_realisasi[$datakey]['realisasi_jasa'] = $pos_penjualan[$datakey]['penjualan_jasa'];
			$pos_realisasi[$datakey]['realisasi_pajak'] = $pos_penjualan[$datakey]['penjualan_total_nilai_pajak'];
			$pos_realisasi[$datakey]['realisasi_total'] = $pos_penjualan[$datakey]['penjualan_total_grand'];
			$pos_realisasi[$datakey]['realisasi_created_at'] = date("Y-m-d H:i:s");
			$pos_realisasi[$datakey]['realisasi_wajibpajak_id'] = $wp['toko_wajibpajak_id'];
			$pos_realisasi[$datakey]['realisasi_wajibpajak_npwpd'] = $wp['toko_wajibpajak_npwpd'];
		}

		try {
			$push_penjualan = $this->OAPI->insertToPenjualan($pos_penjualan, $pos_penjualan_detail, $wp['toko_kode']);
			$push_detail = $this->OAPI->insertToRealisasi($pos_realisasi);

			file_put_contents(FCPATH . "assets/log/push_penjualan.log", date("Y-m-d H:i:s") . " - " . $wp['toko_kode'] . " - " . json_encode($push_penjualan));
			file_put_contents(FCPATH . "assets/log/push_detail.log", date("Y-m-d H:i:s") . " - " . $wp['toko_kode'] . " - " . json_encode($push_detail));

			if ($isMessageBroker) {
				return true;
			} else {
				$this->response([
					'success' => true,
					'message' => 'Wajib pajak berhasil melakukan pengiriman data',
				]);
			}
		} catch (\Throwable $th) {
			if ($isMessageBroker) {
				return false;
			} else {
				$errorMessage = $th->getMessage();
				$errorCode = $th->getCode();
				$response = [
					'success' => false,
					'message' => 'Wajib pajak gagal melakukan pengiriman data',
					'error_message' => $errorMessage,
					'error_code' => $errorCode,
				];
				$this->response($response);
			}
		}
	}

	private function get_oapi_curl($ch, $endpoint, $wp)
	{
		// curl_setopt($ch, CURLOPT_URL, $endpoint);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $output = curl_exec($ch);
		// curl_close($ch);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Cookie: bapenda_kota_malang=B2MAPVZnVD0BelV2ADVVZQU3WTBWdQMkWm1dLwUgAD5RbVM5DFABPVIzDitUa1BwVmwBYltmUGsGJgc1UmoAMVBnXD8EYVNmBGEAYVFqUGMHNwAxVjRUMAE0VTEAa1VjBTNZa1ZhA2Babl04BWAAYlFmU2EMPAFnUm8OK1RrUHBWbAFgW2RQawYmBz1SIwALUGNcOQQ2U3YEMgB3USFQJwc5AHRWaFQ2ATJVPwAtVWUFPlk4VnkDZlo%2BXWQFfQBnUSxTZQw7AWxSdQ4yVCNQOVZnAWFbblBzBnEHJ1I2ACZQXVw8BDVTYQQ5AHBRcFA%2BB3EAPVZgVDYBO1UnAF9VOwV0WX5WOgM2WmZdDgUmADlRdlM%2BDGIBMVJ4Dj5UflA3VmQBf1tkUHMGPwcnUmkAZVAxXGcEcFNoBDYAd1EmUFoHYwBkViZUbgF3VWwAe1UtBSVZMVY%2BA21aOV1rBWEAblE3U28MPQFsUmAOPVRrUHBWbAFoW25QcwZxBydSNgAmUF1cOQQzU3AENgAmUWlQdgc4ADdWaFQlASNVPgBy'
			),
		));

		$output = curl_exec($curl);

		curl_close($curl);


		file_put_contents(FCPATH . "assets/log/push_detail.log", date("Y-m-d H:i:s") . " - " . $wp['toko_kode'] . " - " . $endpoint . " - " . json_encode($output), FILE_APPEND);
		$data = json_decode($output, true);
		return $data;
	}

	function getAllDatesInMonth($dateString)
	{
		$date = new DateTime($dateString);
		$startdate = $date->format('Y-m-01'); // Tanggal awal bulan
		$enddate = $date->format('Y-m-t');    // Tanggal akhir bulan

		$dates = [];
		$currentDate = new DateTime($startdate);
		$endDate = new DateTime($enddate);

		while ($currentDate <= $endDate) {
			$dates[] = $currentDate->format('Y-m-d');
			$currentDate->modify('+1 day');
		}

		return $dates;
	}
}

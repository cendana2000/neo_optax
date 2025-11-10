<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
			$connection = new AMQPStreamConnection($_SERVER['RMQ_HOST'], $_SERVER['RMQ_PORT'], $_SERVER['RMQ_USERNAME'], $_SERVER['RMQ_PASSWORD']);
			$channel = $connection->channel();

			$channel->queue_declare($_SERVER['APP_NAME'] . "-posting_pajak_outer", false, false, false, false);

			$dataWP = $this->OAPI->getToko();

			foreach ($dataWP as $key => $value) {
				$msg = new AMQPMessage(json_encode(['toko_kode' => $value['toko_kode'], 'date' => date('Y-m-d')]));
				$channel->basic_publish($msg, '', $_SERVER['APP_NAME'] . "-posting_pajak_outer");
			}
			return $this->response([
				'success' => true,
				'message' => 'Berhasil memasukan sinkronisasi OAPI dalam antrian',
				// 'datawp' => $dataWP
			]);
		} catch (Exception $e) {
			return $this->response([
				'success' => false,
				'message' => 'Gagal sinkronisasi OAPI: ' . $e->getMessage(),
			]);
		}
	}

	public function consumeData()
	{
		try {
			ini_set('display_errors', 0);
			$connection = new AMQPStreamConnection($_SERVER['RMQ_HOST'], $_SERVER['RMQ_PORT'], $_SERVER['RMQ_USERNAME'], $_SERVER['RMQ_PASSWORD']);
			$channel = $connection->channel();
			$channel->queue_declare($_SERVER['APP_NAME'] . "-posting_pajak_outer", false, false, false, false);
			echo " [*] V3Oapi Waiting for messages. To exit press CTRL+C" . PHP_EOL;
			$channel->basic_consume($_SERVER['APP_NAME'] . "-posting_pajak_outer", '', false, true, false, false, function ($msg) {
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

		if (!empty($wp['toko_jadwal_before'])) {
			$now = date('Y-m-d', strtotime($now . "-" . $wp['toko_jadwal_before'] . " days"));
		}

		$preset = $this->preset->read($wp['toko_preset_id']);
		$presetDetail = $this->db->get_where('pajak_preset_detail_api', ['preset_detail_parent_id' => $preset['preset_id']])->result_array();

		$endpoint = str_replace("{{startdate}}", $now, $wp['toko_api_penjualan']);
		$endpoint = str_replace("{{enddate}}", $now, $endpoint);

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

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
			$pos_realisasi[$datakey]['realisasi_tanggal'] = $pos_penjualan[$datakey]['penjualan_tanggal'];
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
}

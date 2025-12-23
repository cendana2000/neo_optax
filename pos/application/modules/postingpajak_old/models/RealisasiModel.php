<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RealisasiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_realisasi',
				'primary' => 'realisasi_id',
				'fields' => array(
					array('name' => 'realisasi_id'),
					array('name' => 'realisasi_no'),
					array('name' => 'realisasi_wajibpajak_id'),
					array('name' => 'realisasi_wajibpajak_npwpd'),
					array('name' => 'realisasi_tanggal'),
					array('name' => 'realisasi_sub_total'),
					array('name' => 'realisasi_jasa'),
					array('name' => 'realisasi_pajak'),
					array('name' => 'realisasi_total'),
					array('name' => 'realisasi_created_at'),
					array('name' => 'realisasi_created_by'),
					array('name' => 'realisasi_updated_at'),
					array('name' => 'realisasi_updated_by'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'realisasi_id',
						'realisasi_no',
						'realisasi_wajibpajak_id',
						'realisasi_wajibpajak_npwpd',
						'realisasi_tanggal',
						'realisasi_sub_total',
						'realisasi_jasa',
						'realisasi_pajak',
						'realisasi_total',
						'realisasi_created_at',
						'realisasi_created_by',
						'realisasi_updated_at',
						'realisasi_updated_by',
					),
					'datatable' => array(
						'realisasi_id',
						'realisasi_tanggal',
						'realisasi_sub_total',
						'realisasi_jasa',
						'realisasi_pajak',
						'realisasi_total',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function process_store($payload)
	{
		$data = $payload['data'];
		$user = $payload['user'];

		// print_r($this->db->database);

		$period = new DatePeriod(
			new DateTime($data['tanggal'] . '-01'),
			new DateInterval('P1D'),
			(new DateTime($data['tanggal'] . '-01'))->modify('+1 Month')
		);

		$already_exist = [];
		$save_success = [];

		foreach ($period as $key => $val) {

			$realisasi_id = gen_uuid($this->get_table());
			$npwpd = $user['toko_wajibpajak_npwpd'];
			$periode = $val->format('Y-m-d');
			$batch = [];

			// Use PAJAK DB
			$dataWp = $this->db->get_where('pajak_wajibpajak', array('wajibpajak_npwpd' => $npwpd))->row();

			$existrealisasi = $this->db->get_where('pajak_realisasi', array(
				'realisasi_wajibpajak_id' => $dataWp->wajibpajak_id,
				'realisasi_wajibpajak_npwpd' => $dataWp->wajibpajak_npwpd,
				'realisasi_tanggal' => $periode,
			))->row();

			if (empty($existrealisasi)) {
				// $where = array(
				// 	'penjualan_tanggal' => $periode,
				// );
				// $dataDetail = $this->penjualan->select(array('filters_static' => $where))['data'];

				$where = '';
				if ($wp_id = $this->session->userdata('wajibpajak_id')) {
					$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
				}
				$dataDetail = $this->db->query("select * from v_pos_penjualan 
				where penjualan_total_bayar >= (penjualan_total_grand - coalesce (penjualan_total_retur, 0)) 
				and penjualan_status_aktif is null 
				and penjualan_tanggal = '" . $periode . "' $where
				order by penjualan_created asc")->result_array();

				// $dataDetail = [];
				$subtotal = 0;
				$serviceCharge = 0;
				$pajak = 0;
				$total = 0;

				foreach ($dataDetail as $value) {
					$batch[] = [
						'realisasi_detail_id' => gen_uuid('pajak_realisasi_detail'),
						'realisasi_detail_parent' => $realisasi_id,
						'realisasi_detail_time' => date_format(new DateTime($value['penjualan_created']), 'H:i:s'),
						'realisasi_detail_penjualan_kode' => $value['penjualan_kode'],
						'realisasi_detail_sub_total' => $value['penjualan_total_harga'],
						'realisasi_detail_jasa' => $value['penjualan_jasa'],
						'realisasi_detail_pajak' => ($value['penjualan_pajak_persen'] / 100) * $value['penjualan_total_harga'],
						'realisasi_detail_total' => $value['penjualan_total_harga'] + ($value['penjualan_pajak_persen'] . '%' * $value['penjualan_total_harga']) + $value['penjualan_jasa'],
						'realisasi_detail_npwpd' => $npwpd,
					];

					$subtotal += $value['penjualan_total_harga'];
					$serviceCharge += $value['penjualan_jasa'];
					$pajak += ($value['penjualan_pajak_persen'] / 100) * $value['penjualan_total_harga'];
					$total += $value['penjualan_total_harga'] + ($value['penjualan_pajak_persen'] . '%' * $value['penjualan_total_harga']) + $value['penjualan_jasa'];
				}

				// Use PAJAK DB
				if (count($batch) > 0) {
					foreach ($dataDetail as $key => $value) {
						$this->db->update('pos_penjualan', array('penjualan_lock' => '1'), array('penjualan_id' => $value['penjualan_id']));
					}
					$this->db->insert_batch('pajak_realisasi_detail', $batch);
				}

				// Insert data parent laporan realisasi
				// Use PAJAK DB
				$this->db->insert('pajak_realisasi', [
					'realisasi_id' => $realisasi_id,
					'realisasi_no' => date('ymd') . '-' . substr(uniqid('', true), strlen(uniqid('', true)) - 4, strlen(uniqid('', true))),
					'realisasi_wajibpajak_id' => $dataWp->wajibpajak_id,
					'realisasi_wajibpajak_npwpd' => $npwpd,
					'realisasi_tanggal' => $periode,
					'realisasi_sub_total' => $subtotal,
					'realisasi_jasa' => $serviceCharge,
					'realisasi_pajak' => $pajak,
					'realisasi_total' => $total,
					'realisasi_created_at' => date('Y-m-d H:i:s'),
					'realisasi_created_by' => $user['user_id'],
				]);

				// print_r($this->dbmp->last_query());exit;

				echo date('Y-m-d H:i:s') . ' # ' . $user['session_db'] . ' # Lapor Baru # ' . $periode . "\n";
				array_push($save_success, $periode);
			} else {
				echo date('Y-m-d H:i:s') . ' # ' . $user['session_db'] . ' # Terdapat Data Lama # ' . $periode . "\n";
				array_push($already_exist, $periode);
			}
		}
		echo date('Y-m-d H:i:s') . " # " . $user['session_db'] . ' # Akhir Eksekusi Lapor Pajak # ' . $periode . "\n";

		// print_r($already_exist);
		// print_r('OK');

		// $this->response([
		// 	'success' => true,
		// 	'message' => 'Berhasil melapor pajak',
		// 	'data' => (object) [
		// 		'lapor_pajak_lama' => $already_exist,
		// 		'lapor_pajak_baru' => $save_success,
		// 	],
		// ]);
	}
}

/* End of file RealisasiModel.php */
/* Location: ./application/modules/postingpajak/models/RealisasiModel.php */
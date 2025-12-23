<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Postingpajak extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'RealisasiModel' => 'realisasi',
			'RealisasiDetailModel' => 'realisasiDetail',
			'transaksipenjualan/TransaksipenjualanModel' => 'penjualan',
			'wajibpajak/WajibpajakModel' => 'wajibpajak',
			'postingpajak/RealisasipajakparentfiltercreatedModel' => 'RealisasipajakparentfiltercreatedModel'
		));
	}


	public function index()
	{
		$user = $this->session->userdata();
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['toko_wajibpajak_npwpd'];
		$opr = $this->select_dt(varPost(), 'realisasi', 'datatable', true, $where);
		$this->response(
			$opr
		);
	}

	public function getDtNew()
	{
		$user = $this->session->userdata();
		$where = [
			'realisasi_parent_wajibpajak_status' => '2',
			'realisasi_parent_npwpd' => $user['toko_wajibpajak_npwpd'],
			'realisasi_created_at is not null' => null
		];

		$opr = $this->select_dt(varPost(), 'RealisasipajakparentfiltercreatedModel', 'table', true, $where);
		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$sumtotal = $this->db->select('sum(realisasi_parent_omzet) as omzet, 
			sum(realisasi_parent_total_pajak) as pajak,
			sum(realisasi_parent_jml_transaksi) as jml_trf')->where($where)->get('v_realisasi_parent_filter_with_created')->row();
		$opr['sumtotal'] = [
			'omzet' => $sumtotal->omzet,
			'pajak' => $sumtotal->pajak,
			'jml_trf' => $sumtotal->jml_trf
		];

		return $this->response(
			$opr
		);
	}

	function read($value = '')
	{
		$this->response($this->realisasi->read(varPost()));
	}

	public function select($value = '')
	{
		$user = $this->session->userdata();

		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['toko_wajibpajak_npwpd'];

		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$op = $this->db->get_where('pajak_realisasi', $where)->result_array();
		if (count($op) > 0) {
			$this->response(array(
				'success' => true,
				'data' => $op,
			));
		} else {
			$this->response(array(
				'success' => false,
			));
		}
	}

	public function select_mobile($value = '')
	{
		$data = varPost();

		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$this->db->where('toko_wajibpajak_id', $wp_id);
		}
		$toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => explode('_', $data['mobileDb'])[1]])->row_array();

		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];

		$op = $this->db->query("SELECT DATE_TRUNC('month',realisasi_tanggal) as realisasi_tanggal, sum(realisasi_pajak) as realisasi_pajak
		FROM pajak_realisasi WHERE realisasi_deleted_at IS NULL 
		AND realisasi_wajibpajak_npwpd = '{$toko['toko_wajibpajak_npwpd']}'
		GROUP BY DATE_TRUNC('month',realisasi_tanggal) ORDER BY realisasi_tanggal DESC")->result_array();

		if (count($op) > 0) {
			$this->response(array(
				'success' => true,
				'data' => $op,
			));
		} else {
			$this->response(array(
				'success' => true,
				'data' => [],
				'msg' => 'Data Posting Pajak tidak ditemukan'
			));
		}
	}


	public function select_mobile_detail($value = '')
	{
		$data = varPost();

		$toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => explode('_', $data['mobileDb'])[1]])->row_array();

		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$this->db->where('realisasi_wajibpajak_id', $wp_id);
		}
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
		$where['to_char(realisasi_tanggal, \'YYYY-MM\') = \'' . $data['realisasi_tanggal'] . '\''] = null;
		$this->db->order_by('realisasi_tanggal DESC');
		$op = $this->db->get_where('pajak_realisasi', $where)->result_array();


		if (count($op) > 0) {
			$this->response(array(
				'success' => true,
				'data' => $op,
			));
		} else {
			$this->response(array(
				'success' => true,
				'data' => [],
				'msg' => 'Data Posting Pajak Detail tidak ditemukan'
			));
		}
	}

	public function store()
	{
		$data = varPost();
		$user = $this->session->userdata();

		if (empty($data['tanggal'])) {
			return $this->response([
				'success' => false,
				'message' => 'Mohon isi tanggal!',
				'data' => null,
			]);
		}

		if ($data['tanggal'] == date('Y-m')) {
			return $this->response([
				'success' => false,
				'message' => 'Tidak dapat melapor pajak pada bulan ini',
				'data' => null
			]);
		}

		$tanggaltime = strtotime(date($data['tanggal'] . '-01'));
		$currenttime = strtotime(date('Y-m-01'));

		if ($tanggaltime > $currenttime) {
			return $this->response([
				'success' => false,
				'message' => 'Tidak dapat melapor pajak pada bulan yang belum terlewati',
				'data' => null
			]);
		}

		addJobToRabbit('store_posting_pajak', [
			'data' => $data,
			'user' => $user,
		]);

		// $this->realisasi->process_store([
		// 	'data' => $data,
		// 	'user' => $user,
		// ]);

		$this->response([
			'success' => true,
			'message' => 'Terima kasih sudah melaporkan pajak.'
		]);
	}

	public function process_store($payload)
	{
		$data = $payload['data'];
		$user = $payload['user'];
		$period = new DatePeriod(
			new DateTime($data['tanggal'] . '-01'),
			new DateInterval('P1D'),
			(new DateTime($data['tanggal'] . '-01'))->modify('+1 Month')
		);

		$already_exist = [];
		$save_success = [];
		foreach ($period as $key => $val) {

			$realisasi_id = gen_uuid($this->realisasi->get_table());
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
				if ($wp_id = $this->session->userdata('wajipajak_id')) {
					$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
				}
				$dataDetail = $this->db->query("select * from v_pos_penjualan 
				where penjualan_total_bayar >= (penjualan_total_grand - coalesce (penjualan_total_retur, 0)) 
				and penjualan_status_aktif is null $where
				and penjualan_tanggal = '" . $periode . "' 
				order by penjualan_created asc")->result_array();

				// $dataDetail = [];
				$subtotal = 0;
				$serviceCharge = 0;
				$pajak = 0;
				$total = 0;


				foreach ($dataDetail as $value) {
					$batch[] = [
						'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
						'realisasi_detail_parent' => $realisasi_id,
						'realisasi_detail_time' => date_format(new DateTime($value['penjualan_created']), 'H:i:s'),
						'realisasi_detail_penjualan_kode' => $value['penjualan_kode'],
						'realisasi_detail_sub_total' => $value['penjualan_total_harga'],
						'realisasi_detail_jasa' => $value['penjualan_jasa'],
						'realisasi_detail_pajak' => ($value['penjualan_pajak_persen'] / 100) * $value['penjualan_total_harga'],
						'realisasi_detail_total' => $value['penjualan_total_harga'] + ($value['penjualan_pajak_persen'] . '%' * $value['penjualan_total_harga']) + $value['penjualan_jasa'],
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

				// print_r($this->db->last_query());exit;

				array_push($save_success, $periode);
			} else {
				array_push($already_exist, $periode);
			}
		}

		$this->response([
			'success' => true,
			'message' => 'Berhasil melapor pajak',
			'data' => (object) [
				'lapor_pajak_lama' => $already_exist,
				'lapor_pajak_baru' => $save_success,
			],
		]);
	}

	public function store_mobile()
	{
		$data = varPost();
		$user = $this->db->get_where('v_pajak_pos', ['toko_kode' => explode('_', $data['mobileDb'])[1]])->row_array();

		if (array_key_exists('mobileDb', varPost())) {

			if (empty($data['tanggal'])) {
				return $this->response([
					'success' => false,
					'message' => 'Mohon isi tanggal!',
					'data' => null,
				]);
			}

			if ($data['tanggal'] == date('Y-m')) {
				return $this->response([
					'success' => false,
					'message' => 'Tidak dapat melapor pajak pada bulan ini',
					'data' => null
				]);
			}

			$tanggaltime = strtotime(date($data['tanggal'] . '-01'));
			$currenttime = strtotime(date('Y-m-01'));

			if ($tanggaltime > $currenttime) {
				return $this->response([
					'success' => false,
					'message' => 'Tidak dapat melapor pajak pada bulan yang belum terlewati',
					'data' => null
				]);
			}

			$period = new DatePeriod(
				new DateTime($data['tanggal'] . '-01'),
				new DateInterval('P1D'),
				(new DateTime($data['tanggal'] . '-01'))->modify('+1 Month')
			);

			$already_exist = [];
			$save_success = [];
			foreach ($period as $key => $val) {

				$realisasi_id = gen_uuid($this->realisasi->get_table());
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

					$dataDetail = $this->db->query("select * from v_pos_penjualan 
				where penjualan_total_bayar >= (penjualan_total_grand - coalesce (penjualan_total_retur, 0)) 
				and penjualan_status_aktif is null 
				and penjualan_tanggal = '" . $periode . "' 
				order by penjualan_created asc")->result_array();

					// $dataDetail = [];
					$subtotal = 0;
					$serviceCharge = 0;
					$pajak = 0;
					$total = 0;


					foreach ($dataDetail as $value) {
						$batch[] = [
							'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
							'realisasi_detail_parent' => $realisasi_id,
							'realisasi_detail_time' => date_format(new DateTime($value['penjualan_created']), 'H:i:s'),
							'realisasi_detail_penjualan_kode' => $value['penjualan_kode'],
							'realisasi_detail_sub_total' => $value['penjualan_total_harga'],
							'realisasi_detail_jasa' => $value['penjualan_jasa'],
							'realisasi_detail_pajak' => ($value['penjualan_pajak_persen'] / 100) * $value['penjualan_total_harga'],
							'realisasi_detail_total' => $value['penjualan_total_harga'] + ($value['penjualan_pajak_persen'] . '%' * $value['penjualan_total_harga']) + $value['penjualan_jasa'],
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

					// print_r($this->db->last_query());exit;

					array_push($save_success, $periode);
				} else {
					array_push($already_exist, $periode);
				}
			}

			$this->response([
				'success' => true,
				'message' => 'Berhasil melapor pajak',
				'data' => (object) [
					'lapor_pajak_lama' => $already_exist,
					'lapor_pajak_baru' => $save_success,
				],
			]);
		}
	}



	public function update()
	{
		$data = varPost();
		$this->response($this->realisasi->update(varPost('id', varExist($data, $this->realisasi->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['realisasi_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->realisasi->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->realisasi->delete(varPost('id', varExist($data, $this->realisasi->get_primary(true))));
		$this->response($operation);
	}

	public function header($txt, $hal)
	{
		return '
		<table>
			<tr>
				<td>' . $txt . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
		<tr>
			<th class="t-center">NO</th>
			<th class="t-center">Time</th>
			<th class="t-center">Receipt NO</th>
			<th class="t-center">Sub Total</th>
			<th class="t-center">Jasa</th>
			<th class="t-center">Diskon</th>
			<th class="t-center">Pajak</th>
			<th class="t-center">Total</th>
		</tr>';
	}

	// public function getlaporan_old()
	// {
	// 	$data = varPost();
	// 	// Change string to date tanggal
	// 	// $replacetanggal = str_replace('/', '-', $data['periode_tanggal']);
	// 	$data['first_date'] = date("Y-m-d", strtotime($data['periode_tanggal'] . '-01'));
	// 	$data['end_date'] = date_format((new DateTime($data['first_date']))->modify('+1 Month -1 day'), 'Y-m-d');
	// 	$bulan = phpChgDate($data['periode_tanggal']);
	// 	$hal = 1;
	// 	$html = '<style>
	// 		*, table, p, li{
	// 			line-height:1.5;
	// 			font-size:9px;
	// 		}
	// 		.kop{
	// 			text-align: center;
	// 			display:block;
	// 			margin:0 auto;
	// 		}
	// 		.kop h1{
	// 			font-size: 10px;
	// 		}

	// 		.left{
	// 			padding:2px;
	// 		}

	// 		.right{

	// 			text-align:right;
	// 			padding: 2px;
	// 		}
	// 		.t-center{
	// 			vertical-align:middle!important;
	// 			text-align:center;
	// 			background-color : #5a8ed1;
	// 		}
	// 		.t-block{
	// 			background-color : #ccc;
	// 		}

	// 		.divider{
	// 			border-right: 1px solid black;
	// 		}

	// 		.laporan td {
	// 			border: 1px solid black;
	// 			border-collapse: collapse;
	// 			padding:0px 10px;
	// 			line-height:18px;
	// 		}
	// 		.laporan tfoot td{
	// 			font-weight:bold;
	// 		}

	// 		.ttd{
	// 			border: 1px solid black;
	// 			border-collapse: collapse;
	// 			padding : 0px 3px;
	// 			text-align:center;
	// 			vertical-align:top;
	// 		}

	// 		.ttd td {
	// 			border : 0px 1px solid black;
	// 			border-collapse: collapse;
	// 			padding:0px 3px;
	// 			height:40px;
	// 		}

	// 		.ttd .top{
	// 			text-align:center;
	// 			vertical-align:top;
	// 			border-right : 1px solid black;
	// 			border-collapse: collapse;
	// 		}

	// 		.ttd .bottom{
	// 			text-align:center;
	// 			vertical-align:bottom;
	// 			border-right : 1px solid black;
	// 			border-collapse: collapse;
	// 		}

	// 		.laporan .total {
	// 			border-top: 1px solid black;
	// 			border-bottom: 1px solid black;
	// 			border-collapse: collapse;
	// 			padding: 0px 10px;
	// 		}	

	// 		table{
	// 			border-collapse: collapse;
	// 			width:100%;
	// 		}
	// 		.laporan th {
	// 			border: 1px solid black;
	// 			border-collapse: collapse;
	// 		}
	// 	</style>';
	// 	$dtCaption = '';
	// 	$filter = [];
	// 	// $filter = ['penjualan_tanggal=' => $data['periode_tanggal']];
	// 	$filter = "penjualan_tanggal between '" . $data['first_date'] . "' AND '" . $data['end_date'] . "'";
	// 	$dtCaption = 'Bulan : ' . $bulan;
	// 	$penjualan = $this->db->select('*')
	// 		->from('v_pos_penjualan')
	// 		// ->where('penjualan_total_bayar >= (penjualan_total_grand - coalesce (penjualan_total_retur, 0))', null)
	// 		->where($filter)
	// 		->where('penjualan_status_aktif', NULL)
	// 		->order_by('penjualan_created', 'asc')
	// 		// ->order_by('anggota_kode', 'asc')
	// 		->get()->result_array();

	// 	foreach ($penjualan as $key => $value) {
	// 		$totalpajak += $value['penjualan_total_harga'] * ($value['penjualan_pajak_persen'] / 100);
	// 	};
	// 	$html .= '<table style="width:100%;">
	// 		<tr>
	// 			<td class="left">
	// 				<p>' . $this->session->userdata('toko_nama') . '</p>
	// 			</td>
	// 			<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
	// 		</tr>
	// 		<tr>
	// 			<td colspan="2" class="kop">
	// 					<h4> LAPORAN DATA PAJAK </h4><br>
	// 			</td>
	// 		</tr>
	// 		<tr>
	// 			<td>' . $dtCaption . '</td>
	// 			<td class="right">Hal. : ' . $hal . '</td>
	// 		</tr>
	// 		<tr>
	// 			<td> Pajak Yang Harus Di Bayar :<b> Rp ' . number_format($totalpajak) . ' </td>
	// 		</tr>
	// 	</table>
	// 	<br>
	// 	<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
	// 	<tr>
	// 		<th class="t-center">NO</th>
	// 		<th class="t-center">Time</th>
	// 		<th class="t-center">Receipt NO</th>
	// 		<th class="t-center">Sub Total</th>
	// 		<th class="t-center">Jasa</th>
	// 		<th class="t-center">Diskon</th>
	// 		<th class="t-center">Pajak</th>
	// 		<th class="t-center">Total</th>
	// 	</tr>';
	// 	// $penjualan = $this->db->select('*')
	// 	// 	->from('v_pos_penjualan')
	// 	// 	->where($filter)
	// 	// 	->where('penjualan_status_aktif', NULL)
	// 	// 	->order_by('penjualan_created', 'asc')
	// 	// 	->get()->result_array();

	// 	$item = $tunai = $kredit = 0;
	// 	$no = $total = 1;
	// 	if (count($penjualan) == 0) {
	// 		$html .= '<tr><td colspan="8" style="text-align:center">Belum ada record transaksi!</td></tr>';
	// 	}
	// 	$subtotal = 0;
	// 	$total_jasa = 0;
	// 	$pajak = 0;
	// 	$grand_total = 0;
	// 	$diskon = 0;
	// 	foreach ($penjualan as $key => $value) {
	// 		$item += intval($value['penjualan_total_item']);
	// 		$ctunai = intval($value['penjualan_total_bayar_tunai']) - intval($value['penjualan_total_kembalian'])  + intval($value['penjualan_total_potongan']);
	// 		$tunai += $ctunai;
	// 		// $vkredit = intval($value['penjualan_total_kredit']) + intval($value['penjualan_total_bayar_voucher']);
	// 		// $kredit += $vkredit;
	// 		$html .= '<tr>
	// 				<td>' . ($key + 1) . '</td>
	// 				<td>' . $value['penjualan_tanggal'] . ' ' . date_format(new DateTime($value['penjualan_created']), 'H:i:s') . '</td>
	// 				<td>' . $value['penjualan_kode'] . '</td>
	// 				<td class="right">Rp. ' . number_format($value['penjualan_total_harga']) . '</td>
	// 				<td class="right">Rp. ' . number_format($value['penjualan_total_harga'] * ($value['penjualan_jasa'] / 100)) . '</td>
	// 				<td class="right">Rp. ' . number_format($value['penjualan_total_harga'] * ($value['penjualan_total_potongan_persen'] / 100)) . '</td>
	// 				<td class="right">Rp. ' . number_format($value['penjualan_total_harga'] * ($value['penjualan_pajak_persen'] / 100)) . '</td>
	// 				<td class="right">Rp. ' . number_format($value['penjualan_total_grand']) . '</td>
	// 				</tr>';

	// 		// Total section
	// 		$subtotal += $value['penjualan_total_harga'];
	// 		$total_jasa += $value['penjualan_total_harga'] * ($value['penjualan_jasa'] / 100);
	// 		$pajak += $value['penjualan_total_harga'] * ($value['penjualan_pajak_persen'] / 100);
	// 		$grand_total += $value['penjualan_total_grand'];
	// 		$diskon += $value['penjualan_total_harga'] * ($value['penjualan_total_potongan_persen'] / 100);
	// 		$no++;
	// 		if ($hal == 1) $total = 32;
	// 		else $total = 34;
	// 		if ($no > $total) {
	// 			$no = 1;
	// 			$hal++;
	// 			$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
	// 		}
	// 	}
	// 	// $grand_total = ($subtotal + ($subtotal * $total_jasa / 100)) + ($subtotal + $total_jasa) * ($pajak / 100);
	// 	$html .= '<tfoot><tr>
	// 			<td colspan="3">TOTAL</td>
	// 			<td class="right">Rp. ' . number_format($subtotal) . '</td>
	// 			<td class="right">Rp. ' . number_format($total_jasa) . '</td>
	// 			<td class="right">Rp. ' . number_format($diskon) . '</td>
	// 			<td class="right">Rp. ' . number_format($pajak) . '</td>
	// 			<td class="right">Rp. ' . number_format($grand_total) . '</td>
	// 		</tr></tfoot>';

	// 	$html .= '</table>
	// 	</div>';
	// 	createPdf(array(
	// 		'data'          => $html,
	// 		'json'          => true,
	// 		'paper_size'    => 'A4',
	// 		'file_name'     => 'Laporan Realisasi Pajak',
	// 		'title'         => 'Laporan Realisasi Pajak',
	// 		'stylesheet'    => './assets/laporan/print.css',
	// 		'margin'        => '10 5 10 5',
	// 		'font_face'     => 'sans_fonts',
	// 		'font_size'     => '10',
	// 	));
	// }

	public function getlaporan()
	{
		$data = varPost();
		$data['first_date'] = date("Y-m-d", strtotime($data['periode_tanggal'] . '-01'));
		$data['end_date'] = date_format((new DateTime($data['first_date']))->modify('+1 Month -1 day'), 'Y-m-d');
		$bulan = phpChgDate($data['periode_tanggal']);

		$bulan_explode = explode('-', $data['periode_tanggal']);
		$b_date		= $bulan_explode[0] . "-" . $bulan_explode[1] . "-01";
		$preset_date = $bulan_explode[1] . "-" . $bulan_explode[0];
		$lastDay 	 = (int)date("t", strtotime($b_date));

		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.6;
				font-size:11px;
			}
			.kop{
				text-align: center;
				display:block;
				margin:0 auto;
			}
			.kop h1{
				font-size: 10px;
			}

			.left{
				padding:2px;
			}

			.right{

				text-align:right;
				padding: 2px;
			}
			.t-center{
				vertical-align:middle!important;
				text-align:center;
				background-color : #5a8ed1;
				width: 1px;
    			white-space: nowrap;
			}

			.divider{
				border-right: 1px solid black;
			}

			.laporan td, .laporan th{
				border: 1px solid black;
				border-collapse: collapse;
				padding:0px 10px;
			}

			.ttd{
				border: 1px solid black;
				border-collapse: collapse;
				padding : 0px 3px;
				text-align:center;
				vertical-align:top;
			}

			.ttd td {
				border : 0px 1px solid black;
				border-collapse: collapse;
				padding:0px 3px;
				height:40px;
			}

			.ttd .top{
				text-align:center;
				vertical-align:top;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.ttd .bottom{
				text-align:center;
				vertical-align:bottom;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.laporan .total {
				border-top: 1px solid black;
				border-bottom: 1px solid black;
				border-collapse: collapse;
				padding: 0px 10px;
			}	

			table{				
				border-collapse: collapse;
				width:100%;
			}
			.laporan th {
				border: 1px solid black;
				border-collapse: collapse;
			}
			body {
                background-image: url(' . base_url("dokumen/dashboard_rzl/logo_malang.png") . '); 
				background-repeat: no-repeat; 
				background-size: cover;               
            }
			.laporan {								
				position:relative;
				border: solid 1px #000 ;
			}
			.laporan_wrapper {
				position:absolute;
				top:200px;
				width: 95%;
				border: solid 1px #000 ;
			}
			.image_wrapper {
				position:absolute;
				top:250px;
				left:140px;
				width: 65%;
				height: 65%;
				transform: translate(-50%, -50%); /* Menggeser elemen ke tengah menggunakan translate */
    			opacity: 0.01; /* Mengatur tingkat transparansi (0.0 - 1.0), semakin kecil semakin transparan */
				filter: grayscale(100%) opacity(0.7); /* Membuat gambar hitam putih dan transparan */
			}
			.image_wrapper img {
				width: 100%;
				height: auto;
				display: block;
				filter: grayscale(100%); /* Membuat gambar hitam putih */
			}
		</style>';
		$dtCaption = '';
		$filter = [];
		$filter = "penjualan_tanggal between '" . $data['first_date'] . "' AND '" . $data['end_date'] . "'";
		$dtCaption = 'Bulan : ' . $bulan;
		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$filter['wajibpajak_id'] = $wp_id;
		}
		$penjualan = $this->db->select(
			"penjualan_tanggal, penjualan_pajak_persen, 
			SUM(penjualan_jasa) AS penjualan_jasa,
			SUM(penjualan_total_item) AS penjualan_item, 
			SUM(penjualan_total_harga) AS penjualan_subtotal, 
			SUM(penjualan_total_grand) AS penjualan_totalbayar"
		)
			->from('v_pos_penjualan')
			->where($filter)
			->where('penjualan_status_aktif', NULL)
			->group_by('penjualan_tanggal, penjualan_pajak_persen')
			->get()->result_array();

		foreach ($penjualan as $key => $value) {
			$totalpajak += $value['penjualan_subtotal'] * ($value['penjualan_pajak_persen'] / 100);
		};
		$html .= '<body>		
		
		<table style="width:100%;">						
			<tr>
				<td class="left">
					<p>OPTAX</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>PELAPORAN OMZET WAJIB PAJAK</h4><br>
				</td>
			</tr>
		</table>

		<table style="width:100%;">
			<tr>
				<td width="20%">Masa Pajak</td>
				<td>: ' . $bulan . '</td>
			</tr>					
			<tr>
				<td width="20%">Nama Wajib Pajak</td>
				<td>: ' . $this->session->userdata('toko_nama') . '</td>
			</tr>			
			<tr>
				<td width="20%">NPWPD</td>
				<td>: ' . $this->session->userdata('toko_wajibpajak_npwpd') . '</td>
			</tr>
			<tr>
				<td width="20%">Pajak Yang Dibayarkan</td>				
				<td>: ' . number_format($totalpajak) . ' </td>
			</tr>
		</table>
		<br>
		
		<div class="image_wrapper">	
			<!-- <img src="' . base_url('/assets/master/kasir/logo_malang.png') . '"/> -->
			<img src="' . str_replace('https://', 'http://', base_url('/assets/master/kasir/logo_malang.png')) . '"/>
			<!-- <img src="https://3.bp.blogspot.com/-XVU8ID7Yisc/VOrjEimzqEI/AAAAAAAABCw/-OhwC0ddFFc/s1600/Logo%2Bkota%2Bmalang%2Bjawa%2Btimur.png"/>  -->
		</div>
		<div class="laporan_wrapper">		
		<table class="laporan" cellspacing=0 style="border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">Tanggal</th>
				<th class="t-center">Subtotal</th>
				<th class="t-center">Service Charge</th>				
				<th class="t-center">Pajak</th>
				<th class="t-center">Total</th>
			</tr>';
		$item = $tunai = $kredit = 0;
		$no = $total = $tbl_no = $hal = 1;
		if (count($penjualan) == 0) {
			$html .= '<tr><td colspan="6" style="text-align:center">Belum ada record transaksi!</td></tr>';
		} else {
			$subtotal_total = 0;
			$grand_total_total = 0;
			$pajak_total = 0;
			$diskon = 0;
			for ($i = 1; $i <= $lastDay; $i++) {
				$subtotal = 0;
				$total_jasa = 0;
				$pajak = 0;
				$grand_total = 0;
				# code...					
				foreach ($penjualan as $key => $value) {
					if (date('d-m-Y', strtotime($value['penjualan_tanggal'])) == sprintf("%02d", $i) . "-" . $preset_date) {
						# code...
						$subtotal 					= $value['penjualan_subtotal'];
						$total_jasa 				+= $value['penjualan_total_jasa'];
						$pajak 						= $value['penjualan_subtotal'] / 10;
						$grand_total 				= $value['penjualan_totalbayar'];
						$subtotal_total 			+= $value['penjualan_subtotal'];
						$pajak_total 				+= $value['penjualan_subtotal'] * ($value['penjualan_pajak_persen'] / 100);
						$grand_total_total  		+= $value['penjualan_totalbayar'];
						$total_jasa 				+= $value['penjualan_subtotal'] * ($value['penjualan_jasa'] / 100);
						$item += intval($value['penjualan_item']);
					}
				}
				$html .= '<tr>
					<td style="text-align: center;">' . $tbl_no . '</td>
					<td style="text-align: center;">' . sprintf("%02d", $i) . "-" . $preset_date . '</td>
					<td style="text-align: right;">' . number_format($subtotal) . '</td>
					<td style="text-align: right;">' . number_format($total_jasa) . '</td>
					<td style="text-align: right;">' . number_format($pajak) . '</td>
					<td style="text-align: right;">' . number_format($grand_total) . '</td>
					</tr>';
				// Total section
				$tbl_no++;
				$no++;
				if ($hal == 1) $total = 32;
				else $total = 34;
				if ($no > $total) {
					$no = 1;
					$hal++;
					$html .= '</table><div style="page-break-after: always"></div>' . $this->header($dtCaption, $hal);
				}
			};
			// $grand_total = ($subtotal + ($subtotal * $total_jasa / 100)) + ($subtotal + $total_jasa) * ($pajak / 100);
			$html .= '<tfoot><tr>
				<td style="text-align: center;" colspan="2"><b>TOTAL</b></td>
				<td class="right"><b>Rp. ' . number_format($subtotal_total) . '</b></td>
				<td class="right"><b>Rp. ' . number_format($total_jasa) . '</b></td>				
				<td class="right"><b>Rp. ' . number_format($pajak_total) . '</b></td>
				<td class="right"><b>Rp. ' . number_format($grand_total_total) . '</b></td>
			</tr></tfoot>';
		}
		$html .= '</table>
		</div>
		</div>
		</body>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Realisasi Pajak',
			'title'         => 'Laporan Realisasi Pajak',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10',
			'orientation'	=> 'P',
		));
	}

	public function get_laporan_rekap()
	{
		$data = varPost();
		$data['first_date'] = date("Y-m-d", strtotime($data['periode_tanggal'] . '-01'));
		$data['end_date'] = date_format((new DateTime($data['first_date']))->modify('+1 Month -1 day'), 'Y-m-d');
		$bulan = date('m', strtotime($data['first_date']));
		$tahun = date('Y', strtotime($data['first_date']));
		$ym = date('Y-m', strtotime($data['first_date']));
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.5;
				font-size:9px;
			}
			.kop{
				text-align: center;
				display:block;
				margin:0 auto;
			}
			.kop h1{
				font-size: 10px;
			}

			.left{
				padding:2px;
			}

			.right{

				text-align:right;
				padding: 2px;
			}
			.t-center{
				vertical-align:middle!important;
				text-align:center;
				background-color : #5a8ed1;
			}

			.divider{
				border-right: 1px solid black;
			}

			.laporan td {
				border: 1px solid black;
				border-collapse: collapse;
				padding:0px 10px;
				line-height:18px;
			}
			.laporan tfoot td{
				font-weight:bold;
			}

			.ttd{
				border: 1px solid black;
				border-collapse: collapse;
				padding : 0px 3px;
				text-align:center;
				vertical-align:top;
			}

			.ttd td {
				border : 0px 1px solid black;
				border-collapse: collapse;
				padding:0px 3px;
				height:40px;
			}

			.ttd .top{
				text-align:center;
				vertical-align:top;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.ttd .bottom{
				text-align:center;
				vertical-align:bottom;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.laporan .total {
				border-top: 1px solid black;
				border-bottom: 1px solid black;
				border-collapse: collapse;
				padding: 0px 10px;
			}	

			table{
				border-collapse: collapse;
				width:100%;
			}
			.laporan th {
				border: 1px solid black;
				border-collapse: collapse;
			}
		</style>';
		$dtCaption = '';
		$filter = [];
		$dtCaption = 'Bulan : ' . phpChgMonth(intval($bulan)) . ' ' . $tahun;
		$filter = ['to_char(penjualan_tanggal, \'YYYY-MM\') =' => $ym];
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>--- ---- --- </u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4> LAPORAN DATA PAJAK </h4><br>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center" rowspan="2">TGL.</th>
				<th class="t-center" colspan="2">TUNAI</th>
				<th class="t-center" colspan="2">KREDIT</th>
				<th class="t-center" rowspan="2">TOTAL</th>
				<th class="t-center" rowspan="2">TOTAL PAJAK</th>
				<!-- <th class="t-center" rowspan="2">HPP</th> -->
			</tr>
			<tr>
				<th>Jm. Invoice</th>
				<th>JUMLAH</th>
				<th>Jm. Invoice</th>
				<th>JUMLAH</th>
			</tr>';
		// $penjualan = $this->db->query('select penjualan_tanggal, COUNT(IF(penjualan_total_bayar>0,penjualan_total_bayar, null)) total_tunai, sum(penjualan_total_bayar) tunai, COUNT(IF(penjualan_total_kredit>0, penjualan_total_kredit, null)) total_kredit, sum(penjualan_total_kredit) kredit FROM pos_penjualan WHERE DATE_FORMAT(penjualan_tanggal, "%Y-%m") = "'.$data['bulan'].'" GROUP BY penjualan_tanggal')->result_array();
		$where = '';
		if ($wp_id = $this->session->userdata('wajipajak_id')) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}
		$penjualan = $this->db->query('SELECT 
				penjualan_tanggal, 
				COUNT(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) total_tunai, 
				sum(case when(penjualan_metode != \'K\') then penjualan_total_bayar else null end) tunai, 
				sum(penjualan_total_potongan) potongan, 
				sum(penjualan_total_kembalian) kembalian, 
				COUNT(case when(penjualan_metode = \'K\') then penjualan_total_bayar else null end) total_kredit, 
				sum(penjualan_total_kredit) kredit, 
				hpp,
				penjualan_pajak_persen
			FROM pos_penjualan 
			LEFT JOIN (
					SELECT 
						SUM(penjualan_detail_hpp) hpp, 
						penjualan_detail_tanggal 
					FROM pos_penjualan_detail 
					GROUP BY penjualan_detail_tanggal
				) as hpp 
				on penjualan_detail_tanggal=penjualan_tanggal 
			WHERE penjualan_status_aktif IS NULL 
			AND to_char(penjualan_tanggal, \'YYYY-MM\') = \'' . $ym . '\' ' . $where . '
			GROUP BY penjualan_tanggal, hpp, penjualan_pajak_persen')->result_array();

		$total = $nota_tunai = $nota_kredit = $total_tunai = $total_kredit = $hpp = 0;
		if (count($penjualan) == 0) {
			$html .= '<tr><td colspan="6" style="text-align:center">Belum ada record transaksi!</td></tr>';
		}
		// print_r('<pre>');print_r($penjualan);print_r('</pre>');exit;
		foreach ($penjualan as $key => $value) {
			$tunai = $value['tunai'] - $value['kembalian']  + $value['potongan'];
			$kredit = ($value['kredit'] + $value['titipan_belanja']);
			$item += intval($value['penjualan_total_item']);
			$rtotal = $tunai + $kredit;
			$total += $rtotal;
			$nota_tunai += $value['total_tunai'];
			$total_tunai += $tunai;
			$nota_kredit += $value['total_kredit'];
			$total_kredit += $kredit;
			$hpp += $value['hpp'];
			$pajak = $rtotal * ($value['penjualan_pajak_persen'] / 100);
			$total_pajak += $pajak;
			$html .= '<tr>
					<td>' . date_format(new DateTime($value['penjualan_tanggal']), 'd-m-Y') . '</td>
					<td style="text-align:right;padding-right:15px">' . $value['total_tunai'] . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($tunai) . '</td>
					<td style="text-align:right;padding-right:15px">' . $value['total_kredit'] . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($kredit) . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($rtotal) . '</td>
					<td style="text-align:right;padding-right:15px">Rp. ' . number_format($pajak) . '</td>
					<!-- <td style="text-align:right;padding-right:15px">' . number_format($value['hpp']) . '</td> -->
				</tr>';
		}
		$html .= '<tfoot><tr>
				<td>TOTAL</td>
				<td style="text-align:right;padding-right:15px">' . number_format($nota_tunai) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total_tunai) . '</td>
				<td style="text-align:right;padding-right:15px">' . number_format($nota_kredit) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total_kredit) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total) . '</td>
				<td style="text-align:right;padding-right:15px">Rp. ' . number_format($total_pajak) . '</td>
				<!-- <td style="text-align:right;padding-right:15px">' . number_format($hpp) . '</td> -->
			</tr></tfoot>';
		$html .= '</table>';
		// $html .= $this->get_daftar_kredit($penjualan, $dtCaption);
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Rekap Nota Penjualan',
			'title'         => 'Rekap Nota Penjualan',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}
}

/* End of file Postingpajak.php */
/* Location: ./application/modules/postingpajak/controllers/Postingpajak.php */
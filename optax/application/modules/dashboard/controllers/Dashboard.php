<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'dashboard/dashboardModel' 				=> 'dashboard',
			'dashboard/LogPenjualanWpModel' 		=> 'logpenjualanwp',
			'lastactivitywp/LastactivitywpModel' 	=> 'lastactivitywp',
		));
	}

	public function index()
	{
		$var = varGet();

		if ($var['type'] == "tanggal") {
			$begin = new DateTime($var['awal_tanggal']);
			$end = (new DateTime($var['akhir_tanggal']))->modify('+1 day');
			$rawbegin = $var['awal_tanggal'];
			$rawend = $var['akhir_tanggal'];
			$rawtahun = date_format($begin, 'Y');
		} else if ($var['type'] == "bulan") {
			$bulan = $var['bulan'] . '-01';
			$begin = new DateTime($bulan);
			$end = (new DateTime($bulan))->modify('+1 month');
			$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
			$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
			$rawtahun = date_format(new DateTime($bulan), 'Y');
		}

		$interval = DateInterval::createFromDateString('1 Day');
		$period = new DatePeriod($begin, $interval, $end);

		$data['chart_nominal_pajak'] = array();
		$data['chart_upload_pajak'] = array();
		$categories = array();
		foreach ($period as $dt) {
			array_push($data['chart_nominal_pajak'], (object) array('total_pajak_masuk' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
			array_push($data['chart_upload_pajak'], (object) array('total_upload' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
			array_push($categories, $dt->format("d M Y"));
		}

		$src1 = $this->db->query("
			SELECT 
				SUM(pr.realisasi_pajak) AS total,
				pr.realisasi_tanggal::date AS tanggal
			FROM pajak_realisasi pr
			WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
			AND pr.realisasi_deleted_at IS NULL
			GROUP BY pr.realisasi_tanggal::date
		")->result_array();

		$src2 = $this->db->query("
			SELECT 
				SUM(lpw.log_penjualan_wp_total / 11) AS total,
				lpw.log_penjualan_wp_penjualan_tanggal::date AS tanggal
			FROM log_penjualan_wp lpw
			WHERE lpw.log_penjualan_wp_penjualan_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
			AND lpw.log_penjualan_deleted_at IS NULL
			GROUP BY lpw.log_penjualan_wp_penjualan_tanggal::date
		")->result_array();

		$mergeMap = [];
		foreach ($src1 as $row) {
			$key = date("d M Y", strtotime($row['tanggal']));
			if (!isset($mergeMap[$key])) $mergeMap[$key] = 0;
			$mergeMap[$key] += floatval($row['total']);
		}
		foreach ($src2 as $row) {
			$key = date("d M Y", strtotime($row['tanggal']));
			if (!isset($mergeMap[$key])) $mergeMap[$key] = 0;
			$mergeMap[$key] += floatval($row['total']);
		}

		foreach ($categories as $i => $cat) {
			if (isset($mergeMap[$cat])) {
				$data['chart_nominal_pajak'][$i]->total_pajak_masuk = $mergeMap[$cat];
			}
		}

		$query_total = $this->db->query("
			SELECT 
				COALESCE((
					SELECT SUM(pr.realisasi_pajak)
					FROM pajak_realisasi pr
					WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
					AND pr.realisasi_deleted_at IS NULL
				), 0)
				+
				COALESCE((
					SELECT SUM(lpw.log_penjualan_wp_total / 11)
					FROM log_penjualan_wp lpw
					WHERE lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' AND '$rawend'
					AND lpw.log_penjualan_deleted_at IS NULL
				), 0)
				AS total_pajak_masuk
		")->row_array();
		$data['total_pajak_masuk'] = $query_total['total_pajak_masuk'];

		// $opchartnominal = $this->db->query("SELECT DISTINCT SUM(realisasi_pajak) AS total_pajak_masuk, realisasi_tanggal::date
		// FROM pajak_realisasi WHERE realisasi_tanggal::date BETWEEN '" . $rawbegin . "' and '" . $rawend . "' AND realisasi_deleted_at IS NULL
		// GROUP BY realisasi_tanggal::date")->result_array();
		// foreach ($opchartnominal as $key => $val) {
		// 	$opdate = array_search(date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'), $categories);
		// 	$data['chart_nominal_pajak'][$opdate] = (object) array('total_pajak_masuk' => $val['total_pajak_masuk'], 'realisasi_tanggal' => date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'));
		// }

		// $opchartupload = $this->db->query("SELECT DISTINCT COUNT(realisasi_wajibpajak_npwpd) AS total_upload, realisasi_tanggal::date 
		// FROM pajak_realisasi WHERE realisasi_tanggal::date BETWEEN '" . $rawbegin . "' and '" . $rawend . "' AND realisasi_deleted_at IS NULL
		// GROUP BY realisasi_tanggal::date")->result_array();
		// foreach ($opchartupload as $key => $val) {
		// 	$opdate = array_search(date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'), $categories);
		// 	$data['chart_upload_pajak'][$opdate] = (object) array('total_upload' => $val['total_upload'], 'realisasi_tanggal' => date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'));
		// }

		// TOTAL ALL
		// $query_total = $this->db->query("
		// 	SELECT 
		// 		COALESCE((
		// 			SELECT SUM(pr.realisasi_pajak)
		// 			FROM pajak_realisasi pr
		// 			WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
		// 			AND pr.realisasi_deleted_at IS NULL
		// 		), 0)
		// 		+
		// 		COALESCE((
		// 			SELECT SUM(lpw.log_penjualan_wp_total / 11)
		// 			FROM log_penjualan_wp lpw
		// 			WHERE lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' AND '$rawend'
		// 			AND lpw.log_penjualan_deleted_at IS NULL
		// 		), 0)
		// 	AS total_pajak_masuk
		// ")->row_array();
		// $data['total_pajak_masuk'] = $query_total['total_pajak_masuk'];

		// TOTAL RESTO
		$query_resto = $this->db->query("
			SELECT
				COALESCE((
					SELECT SUM(pr.realisasi_pajak)
					FROM pajak_realisasi pr
					JOIN pajak_wajibpajak pw ON pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					JOIN pajak_jenis pj ON pj.jenis_id = pw.wajibpajak_sektor_nama
					WHERE 
						(pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = 'PAJAK RESTORAN'
						))
						AND pr.realisasi_tanggal BETWEEN '$rawbegin' AND '$rawend'
						AND pr.realisasi_deleted_at IS NULL						
						AND pw.wajibpajak_deleted_at IS NULL
				), 0)
				+
				COALESCE((
					SELECT SUM(lpw.log_penjualan_wp_total / 11)
					FROM log_penjualan_wp lpw
					LEFT JOIN pajak_toko pt ON pt.toko_kode = lpw.log_penjualan_code_store
					LEFT JOIN pajak_wajibpajak pw2 ON pw2.wajibpajak_id = pt.toko_wajibpajak_id 
					LEFT JOIN pajak_jenis pj ON pj.jenis_id = pw2.wajibpajak_sektor_nama
					WHERE 
						(pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = 'PAJAK RESTORAN'
						))
						AND lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' AND '$rawend'
						AND lpw.log_penjualan_deleted_at IS NULL
						AND pw2.wajibpajak_deleted_at IS NULL
				), 0)
			AS total_pajak_resto;
		")->row_array();
		$data['total_pajak_resto'] = $query_resto['total_pajak_resto'];

		$query_hotel = $this->db->query("
			SELECT
				COALESCE((
					SELECT SUM(pr.realisasi_pajak)
					FROM pajak_realisasi pr
					JOIN pajak_wajibpajak pw ON pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					JOIN pajak_jenis pj ON pj.jenis_id = pw.wajibpajak_sektor_nama
					WHERE 
						(pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = 'PAJAK HOTEL'
						))
						AND pr.realisasi_tanggal BETWEEN '$rawbegin' AND '$rawend'
						AND pr.realisasi_deleted_at IS NULL
						AND pw.wajibpajak_deleted_at IS NULL
				), 0)
				+
				COALESCE((
					SELECT SUM(lpw.log_penjualan_wp_total / 11)
					FROM log_penjualan_wp lpw
					LEFT JOIN pajak_toko pt ON pt.toko_kode = lpw.log_penjualan_code_store
					LEFT JOIN pajak_wajibpajak pw2 ON pw2.wajibpajak_id = pt.toko_wajibpajak_id 
					LEFT JOIN pajak_jenis pj ON pj.jenis_id = pw2.wajibpajak_sektor_nama
					WHERE 
						(pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = 'PAJAK HOTEL'
						))
						AND lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' AND '$rawend'
						AND lpw.log_penjualan_deleted_at IS NULL
						AND pw2.wajibpajak_deleted_at IS NULL
				), 0)
			AS total_pajak_hotel;
		")->row_array();
		$data['total_pajak_hotel'] = $query_hotel['total_pajak_hotel'];
		// Query Old
		// $data['total_pajak_masuk'] = $this->db->query("SELECT SUM(realisasi_pajak) AS total_pajak_masuk FROM pajak_realisasi WHERE realisasi_tanggal::date BETWEEN '" . $rawbegin . "' and '" . $rawend . "' AND realisasi_deleted_at IS NULL")->row_array()['total_pajak_masuk'];

		$data['total_pajak_masuk_pertahun'] = $this->db->query("SELECT SUM(realisasi_pajak) AS total_pajak_masuk FROM pajak_realisasi WHERE to_char(realisasi_tanggal, 'YYYY') = '" . $rawtahun . "' AND realisasi_deleted_at IS NULL")->row_array()['total_pajak_masuk'];
		$data['target_pajak_tahun'] = $rawtahun;
		$sql['total_realisasi_wajib_pajak_query'] = "SELECT COUNT(distinct(pr.realisasi_wajibpajak_npwpd)) AS total_realisasi_wajib_pajak 
			FROM pajak_realisasi pr
			JOIN pajak_wajibpajak pw ON pr.realisasi_wajibpajak_npwpd = pw.wajibpajak_npwpd
			WHERE realisasi_tanggal::date BETWEEN '$rawbegin' and '$rawend' 
			AND realisasi_deleted_at IS null
			and pw.wajibpajak_status = '2'
		";
		$data['total_realisasi_wajib_pajak'] = $this->db->query($sql['total_realisasi_wajib_pajak_query'])->row_array()['total_realisasi_wajib_pajak'];

		//tambahan query wp resto
		$sql = "SELECT
				count(x.wajibpajak_npwpd) as total_resto
			from
				(
					select
						pw.wajibpajak_npwpd,
						pw.wajibpajak_sektor_nama,
						pj.jenis_parent,
						(
							select
								pj2.jenis_nama
							from
								pajak_jenis pj2
							where
								pj2.jenis_id = pj.jenis_parent
						)
					from
						pajak_wajibpajak pw
						left join pajak_jenis pj on pj.jenis_id = pw.wajibpajak_sektor_nama
					where 
						pw.wajibpajak_status = '2'
						and pw.wajibpajak_deleted_at is null
				) x
			where
				x.jenis_nama = 'PAJAK RESTORAN'
		";
		$data['total_wp_resto'] = $this->db->query($sql)->row_array()['total_resto'];

		//tambahan query wp hotel
		$sql = "SELECT
				count(x.wajibpajak_npwpd) as total_hotel
			from
				(
					select
						pw.wajibpajak_npwpd,
						pw.wajibpajak_sektor_nama,
						pj.jenis_parent,
						(
							select
								pj2.jenis_nama
							from
								pajak_jenis pj2
							where
								pj2.jenis_id = pj.jenis_parent
						)
					from
						pajak_wajibpajak pw
						left join pajak_jenis pj on pj.jenis_id = pw.wajibpajak_sektor_nama
					where 
						pw.wajibpajak_status = '2'
						and pw.wajibpajak_deleted_at is null
				) x
			where
				x.jenis_nama = 'PAJAK HOTEL'
		";
		$data['total_wp_hotel'] = $this->db->query($sql)->row_array()['total_hotel'];


		$sql = "SELECT
				COUNT(pw.wajibpajak_npwpd) AS total
			--	pw.wajibpajak_npwpd,
			--	pw.wajibpajak_nama 
			from
				pajak_wajibpajak pw
			WHERE
				pw.wajibpajak_status = '2'
				AND pw.wajibpajak_deleted_at IS null
		";
		$data['total_wajib_pajak'] = $this->db->query($sql)->row_array()['total'];

		$data['sektor_usaha'] = $this->db->query("SELECT pjparent.jenis_nama as jenis_nama, COUNT(wajibpajak_sektor_nama) as total 
		FROM pajak_jenis pjdetail
		JOIN pajak_wajibpajak pw 
			ON pw.wajibpajak_sektor_nama = jenis_id and pw.wajibpajak_deleted_at IS null and pw.wajibpajak_status = '2'
		left join pajak_jenis pjparent 
			on pjdetail.jenis_parent = pjparent.jenis_id and pjparent.jenis_tipe = 'parent'
		where pjdetail.jenis_tipe = 'detail' 
		GROUP BY pjparent.jenis_nama
		")->result_array();

		$data['target_pajak'] = $this->db->query("SELECT SUM(target_nominal) AS target_pajak FROM pajak_target WHERE target_tahun = '" . $rawtahun . "' AND target_deleted_at IS NULL")->row_array()['target_pajak'];

		$data['toko_baru'] = $this->db->query("SELECT wajibpajak_nama_penanggungjawab, wajibpajak_nama FROM pajak_wajibpajak
		WHERE wajibpajak_status = '2'
		ORDER BY wajibpajak_created_at DESC
		LIMIT 6")->result_array();

		$data['transaksi_terakhir'] = $this->dashboard->getTransaksiTerakhir();

		$this->response($data);
	}

	function stats_nominal_jenis_usaha()
	{
		$var = varPost();
		$id = $var['id'];

		// print_r('<pre>');print_r($var);print_r('</pre>');exit;

		if ($var['type'] == "tanggal") {
			$begin = new DateTime($var['awal_tanggal']);
			$end = (new DateTime($var['akhir_tanggal']))->modify('+1 day');
			$rawbegin = $var['awal_tanggal'];
			$rawend = $var['akhir_tanggal'];
		} else if ($var['type'] == "bulan") {
			$bulan = $var['bulan'] . '-01';
			$begin = new DateTime($bulan);
			$end = (new DateTime($bulan))->modify('+1 month');
			$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
			$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		}

		$interval = DateInterval::createFromDateString('1 Day');
		$period = new DatePeriod($begin, $interval, $end);

		$data['chart_nominal_pajak'] = array();
		$categories = array();
		foreach ($period as $dt) {
			array_push($data['chart_nominal_pajak'], (object) array('total_pajak_masuk' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
			array_push($categories, $dt->format("d M Y"));
		}

		$opchartnominal = $this->db->query("SELECT DISTINCT SUM(realisasi_pajak) AS total_pajak_masuk, realisasi_tanggal::date
		FROM pajak_realisasi pr
		LEFT JOIN pajak_wajibpajak pw ON pr.realisasi_wajibpajak_npwpd = pw.wajibpajak_npwpd
		LEFT JOIN pajak_jenis pj ON pw.wajibpajak_sektor_nama = pj.jenis_id 
		WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' and '$rawend'
		AND pr.realisasi_deleted_at IS NULL
		AND pj.jenis_parent = '$id' 
		GROUP BY pr.realisasi_tanggal")->result_array();
		foreach ($opchartnominal as $key => $val) {
			$opdate = array_search(date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'), $categories);
			$data['chart_nominal_pajak'][$opdate] = (object) array('total_pajak_masuk' => $val['total_pajak_masuk'], 'realisasi_tanggal' => date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'));
		}

		$this->response($data);
	}

	function read($value = '')
	{
		$this->response($this->dashboard->read(varPost()));
	}

	function select($value = '')
	{
		$where['satuan_deleted_at'] = null;
		$this->response($this->dashboard->select(array('filters_static' => $where)));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->dashboard->insert(gen_uuid($this->dashboard->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->dashboard->update(varPost('id', varExist($data, $this->dashboard->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['satuan_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->dashboard->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->dashboard->delete(varPost('id', varExist($data, $this->dashboard->get_primary(true))));
		$this->response($operation);
	}

	public function dashboardPOS()
	{
		$data = varPost();
		$id = $data['id'];

		if ($data['type'] == "tanggal") {
			$begin = new DateTime($data['awal_tanggal']);
			$end = (new DateTime($data['akhir_tanggal']))->modify('+1 day');
			$rawbegin = $data['awal_tanggal'];
			$rawend = $data['akhir_tanggal'];
			$rawtahun = date_format($begin, 'Y');
		} else if ($data['type'] == "bulan") {
			$bulan = $data['bulan'] . '-01';
			$begin = new DateTime($bulan);
			$end = (new DateTime($bulan))->modify('+1 month');
			$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
			$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
			$rawtahun = date_format(new DateTime($bulan), 'Y');
		}

		$interval = DateInterval::createFromDateString('1 Day');
		$period = new DatePeriod($begin, $interval, $end);

		$categories = array();
		$stats['barChart'] = array();
		foreach ($period as $dt) {
			array_push($stats['barChart'], (object) array('total' => 0, 'tanggal' => $dt->format("d M Y")));
			array_push($categories, $dt->format("d M Y"));
		}

		// Data Total Penjualan
		$total_penjualan = $this->db->query("SELECT SUM(log_penjualan_wp_total) as res FROM log_penjualan_wp")->row_array();

		if (!empty($id)) {
			$where = "WHERE lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' and '$rawend' 
			AND pj.jenis_parent = '$id'";
		} else {
			$where = "WHERE lpw.log_penjualan_wp_penjualan_tanggal BETWEEN '$rawbegin' and '$rawend'";
		}

		$barChart = $this->db->query("SELECT lpw.log_penjualan_wp_penjualan_tanggal as x, SUM(lpw.log_penjualan_wp_total) as y FROM log_penjualan_wp lpw
		left JOIN pajak_toko pt ON lpw.log_penjualan_code_store = pt.toko_kode
		left join pajak_wajibpajak pw on pt.toko_wajibpajak_id = pw.wajibpajak_id
		LEFT JOIN pajak_jenis pj ON pw.wajibpajak_sektor_nama = pj.jenis_id
		$where
		GROUP BY lpw.log_penjualan_wp_penjualan_tanggal")->result_array();

		foreach ($barChart as $key => $val) {
			$opdate = array_search(date_format(new DateTime($val['x']), 'd M Y'), $categories);
			$stats['barChart'][$opdate] = (object) array('total' => $val['y'], 'tanggal' => date_format(new DateTime($val['x']), 'd M Y'));
		}

		$this->response([
			'total_penjualan' => $total_penjualan,
			'barChart' => $stats['barChart'],
		]);
	}

	public function dashboardwp()
	{
		$data = varPost('filter');
		$npwpd = $this->session->userdata('wajibpajak_npwpd');

		$pajak = $this->db->query("select date_trunc('month', realisasi_tanggal) AS key,sum(realisasi_pajak) as value from pajak_realisasi
		where realisasi_wajibpajak_npwpd = '{$npwpd}' and realisasi_tanggal::text LIKE '%{$data}%'
		group by date_trunc('month', realisasi_tanggal)")->result_array();
		$omzet = $this->db->query("select date_trunc('month', realisasi_tanggal) AS key,sum(realisasi_total) as value  from pajak_realisasi
		where realisasi_wajibpajak_npwpd = '{$npwpd}' and realisasi_tanggal::text LIKE '%{$data}%'
		group by date_trunc('month', realisasi_tanggal)")->result_array();

		$this->response([
			'data' => [
				'pajak' => $pajak,
				'omzet' => $omzet
			]
		]);
	}

	function onlineActivityUser()
	{
		$data = varPost();
		$page = 0;
		if (isset($data['page'])) {
			$page = $data['page'];
		}
		$limit = 4;
		$start = $page * $limit;
		$opr = $this->lastactivitywp->select([
			'sort_static' => 'tanggal_last_transaksi desc NULLS last',
			'limit' => $limit,
			'start' => $start
		]);
		$opr['page'] = $page;
		$opr['limit'] = $limit;
		$this->response(
			$opr
		);
	}
}

/* End of file satuan.php */
/* Location: ./application/modules/dashboard/controllers/satuan.php */
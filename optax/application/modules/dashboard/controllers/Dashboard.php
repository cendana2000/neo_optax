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
			'lastactivitywp/LastactivitywpModel' 	=> 'lastactivitywp',
			'statusdevice/StatusDeviceModel' 		=> 'statusdevice',
		));
	}

	public function index()
	{
		$var = varGet();

		if (empty($var['type'])) {
			$bulan = date('Y-m-01');
			$begin = new DateTime($bulan);
			$end   = (new DateTime($bulan))->modify('+1 month');
			$rawbegin = $begin->format('Y-m-d');
			$rawend   = (clone $end)->modify('-1 day')->format('Y-m-d');
			$rawtahun = $begin->format('Y');
		} else if ($var['type'] == "tanggal") {
			$begin = new DateTime($var['awal_tanggal']);
			$end   = (new DateTime($var['akhir_tanggal']))->modify('+1 day');
			$rawbegin = $var['awal_tanggal'];
			$rawend   = $var['akhir_tanggal'];
			$rawtahun = $begin->format('Y');
		} else if ($var['type'] == "bulan") {
			$bulan = $var['bulan'] . '-01';
			$begin = new DateTime($bulan);
			$end   = (new DateTime($bulan))->modify('+1 month');
			$rawbegin = $begin->format('Y-m-d');
			$rawend   = (clone $end)->modify('-1 day')->format('Y-m-d');
			$rawtahun = $begin->format('Y');
		}

		$interval = DateInterval::createFromDateString('1 Day');
		$period = new DatePeriod($begin, $interval, $end);

		// $data['chart_nominal_pajak'] = array();
		$data['chart_upload_pajak'] = array();
		$categories = array();
		foreach ($period as $dt) {
			// array_push($data['chart_nominal_pajak'], (object) array('total_pajak_masuk' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
			array_push($data['chart_upload_pajak'], (object) array('total_upload' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
			array_push($categories, $dt->format("d M Y"));
		}

		$where = '';
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where = 'AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE realisasi_wajibpajak_id=pajak_wajibpajak.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')';
		}

		$src1 = $this->db->query("
			SELECT 
				SUM(pr.realisasi_pajak) AS total,
				pr.realisasi_tanggal::date AS tanggal
			FROM pajak_realisasi pr
			WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
			$where
			AND pr.realisasi_deleted_at IS NULL
			GROUP BY pr.realisasi_tanggal::date
		")->result_array();

		$where = '';
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where = 'AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=lpw.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')';
		}

		$src2 = $this->db->query("
			SELECT 
				SUM(lpw.penjualan_total_grand / 11) AS total,
				lpw.penjualan_tanggal::date AS tanggal
			FROM pos_penjualan lpw
			WHERE lpw.penjualan_tanggal::date BETWEEN '$rawbegin' AND '$rawend'
			$where
			AND lpw.penjualan_deleted_at IS NULL
			GROUP BY lpw.penjualan_tanggal::date
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
				// $data['chart_nominal_pajak'][$i]->total_pajak_masuk = $mergeMap[$cat];
			}
		}

		// TOTAL PAJAK PERJENIS
		foreach (['RESTORAN', 'HOTEL', 'PARKIR', 'HIBURAN'] as $jenis) {
			$result = $this->dashboard->get_total_pajak_by_jenis("PAJAK $jenis", $rawbegin, $rawend);
			$data["total_pajak_" . strtolower($jenis)]     = $result['total_pajak'];
			$data["total_sub_total_" . strtolower($jenis)] = $result['total_sub_total'];
		}

		// Total Transaksi & Total Pajak All
		$total_pajak = $this->dashboard->get_total_pajak_masuk($rawbegin, $rawend);
		$data['total_pajak_masuk'] = $total_pajak['total_pajak_masuk'];
		$data['total_transaksi']  = $total_pajak['total_transaksi'];

		// TOTAL WP PERJENIS
		foreach (['RESTORAN', 'HOTEL', 'PARKIR', 'HIBURAN'] as $jenis) {
			$result = $this->dashboard->get_total_wp_by_jenis("PAJAK $jenis");
			$data["total_wp_" . strtolower($jenis)] = (int) $result['total_wp'];
		}

		// TOTAL WP ALL
		$where = '';
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where = 'AND pemda_id=' . $this->db->escape($pemda_id);
		}
		$sql = "SELECT
				COUNT(pw.wajibpajak_npwpd) AS total
			from
				pajak_wajibpajak pw
			WHERE
				pw.wajibpajak_status = '2'
				AND pw.wajibpajak_deleted_at IS null
				$where
		";
		$data['total_wajib_pajak'] = $this->db->query($sql)->row_array()['total'];

		// TRANSAKSI TERAKHIR
		$data['transaksi_terakhir'] = $this->dashboard->getTransaksiTerakhir(10);
		$data['transaksi_terakhir_all'] = $this->dashboard->getTransaksiTerakhir(50);

		$query_toko = $this->db
			->select('wajibpajak_nama_penanggungjawab, wajibpajak_nama')
			->from('pajak_wajibpajak')
			->where('wajibpajak_status', '2')
			->order_by('wajibpajak_created_at', 'DESC')
			->limit(6)
			->get()
			->result_array();
		$data['toko_baru'] = $query_toko;

		$tahun 			= (int) date('Y');
		$awal_tahun 	= "$tahun-01-01";
		$akhir_tahun 	= ($tahun + 1) . "-01-01";

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')');
		}

		$this->db->select("
			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-01-01'
				AND penjualan_tanggal <  DATE '$tahun-02-01'
			)) AS januari,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-02-01'
				AND penjualan_tanggal <  DATE '$tahun-03-01'
			)) AS februari,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-03-01'
				AND penjualan_tanggal <  DATE '$tahun-04-01'
			)) AS maret,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-04-01'
				AND penjualan_tanggal <  DATE '$tahun-05-01'
			)) AS april,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-05-01'
				AND penjualan_tanggal <  DATE '$tahun-06-01'
			)) AS mei,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-06-01'
				AND penjualan_tanggal <  DATE '$tahun-07-01'
			)) AS juni,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-07-01'
				AND penjualan_tanggal <  DATE '$tahun-08-01'
			)) AS juli,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-08-01'
				AND penjualan_tanggal <  DATE '$tahun-09-01'
			)) AS agustus,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-09-01'
				AND penjualan_tanggal <  DATE '$tahun-10-01'
			)) AS september,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-10-01'
				AND penjualan_tanggal <  DATE '$tahun-11-01'
			)) AS oktober,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-11-01'
				AND penjualan_tanggal <  DATE '$tahun-12-01'
			)) AS november,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-12-01'
				AND penjualan_tanggal <  DATE '" . ($tahun + 1) . "-01-01'
			)) AS desember
		", false);

		$result							= $this->db->from('pos_penjualan')
			->where('penjualan_tanggal >=', $awal_tahun)
			->where('penjualan_tanggal <', $akhir_tahun)
			->get()
			->row();

		$data['chart_nominal_pajak']	= [
			[
				'bulan'	=> 'Januari',
				'total'	=> $result->januari ?? 0
			],
			[
				'bulan'	=> 'Februari',
				'total'	=> $result->februari ?? 0
			],
			[
				'bulan'	=> 'Maret',
				'total'	=> $result->maret ?? 0
			],
			[
				'bulan'	=> 'April',
				'total'	=> $result->april ?? 0
			],
			[
				'bulan'	=> 'Mei',
				'total'	=> $result->mei ?? 0
			],
			[
				'bulan'	=> 'Juni',
				'total'	=> $result->juni ?? 0
			],
			[
				'bulan'	=> 'Juli',
				'total'	=> $result->juli ?? 0
			],
			[
				'bulan'	=> 'Agustus',
				'total'	=> $result->agustus ?? 0
			],
			[
				'bulan'	=> 'September',
				'total'	=> $result->september ?? 0
			],
			[
				'bulan'	=> 'Oktober',
				'total'	=> $result->oktober ?? 0
			],
			[
				'bulan'	=> 'November',
				'total'	=> $result->november ?? 0
			],
			[
				'bulan'	=> 'Desember',
				'total'	=> $result->desember ?? 0
			],
		];

		$this->response($data);
	}

	function stats_nominal_jenis_usaha()
	{
		// $var = varPost();
		// $id = $var['id'];

		// // print_r('<pre>');print_r($var);print_r('</pre>');exit;

		// if ($var['type'] == "tanggal") {
		// 	$begin = new DateTime($var['awal_tanggal']);
		// 	$end = (new DateTime($var['akhir_tanggal']))->modify('+1 day');
		// 	$rawbegin = $var['awal_tanggal'];
		// 	$rawend = $var['akhir_tanggal'];
		// } else if ($var['type'] == "bulan") {
		// 	$bulan = $var['bulan'] . '-01';
		// 	$begin = new DateTime($bulan);
		// 	$end = (new DateTime($bulan))->modify('+1 month');
		// 	$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		// 	$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		// }

		// $interval = DateInterval::createFromDateString('1 Day');
		// $period = new DatePeriod($begin, $interval, $end);

		// $data['chart_nominal_pajak'] = array();
		// $categories = array();
		// foreach ($period as $dt) {
		// 	array_push($data['chart_nominal_pajak'], (object) array('total_pajak_masuk' => 0, 'realisasi_tanggal' => $dt->format("d M Y")));
		// 	array_push($categories, $dt->format("d M Y"));
		// }

		// $where = '';
		// if ($pemda_id = $this->session->userdata('pemda_id')) {
		// 	$where = 'AND pw.pemda_id=' . $this->db->escape($pemda_id);
		// }

		// $opchartnominal = $this->db->query("SELECT DISTINCT SUM(realisasi_pajak) AS total_pajak_masuk, realisasi_tanggal::date
		// FROM pajak_realisasi pr
		// LEFT JOIN pajak_wajibpajak pw ON pr.realisasi_wajibpajak_npwpd = pw.wajibpajak_npwpd
		// LEFT JOIN pajak_jenis pj ON pw.wajibpajak_sektor_nama = pj.jenis_id 
		// WHERE pr.realisasi_tanggal::date BETWEEN '$rawbegin' and '$rawend'
		// AND pr.realisasi_deleted_at IS NULL
		// AND pj.jenis_parent = '$id' 
		// $where
		// GROUP BY pr.realisasi_tanggal")->result_array();
		// foreach ($opchartnominal as $key => $val) {
		// 	$opdate = array_search(date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'), $categories);
		// 	$data['chart_nominal_pajak'][$opdate] = (object) array('total_pajak_masuk' => $val['total_pajak_masuk'], 'realisasi_tanggal' => date_format(new DateTime($val['realisasi_tanggal']), 'd M Y'));
		// }

		$tahun 			= (int) date('Y');
		$awal_tahun 	= "$tahun-01-01";
		$akhir_tahun 	= ($tahun + 1) . "-01-01";

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')');
		}

		$this->db->select("
			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-01-01'
				AND penjualan_tanggal <  DATE '$tahun-02-01'
			)) AS januari,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-02-01'
				AND penjualan_tanggal <  DATE '$tahun-03-01'
			)) AS februari,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-03-01'
				AND penjualan_tanggal <  DATE '$tahun-04-01'
			)) AS maret,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-04-01'
				AND penjualan_tanggal <  DATE '$tahun-05-01'
			)) AS april,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-05-01'
				AND penjualan_tanggal <  DATE '$tahun-06-01'
			)) AS mei,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-06-01'
				AND penjualan_tanggal <  DATE '$tahun-07-01'
			)) AS juni,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-07-01'
				AND penjualan_tanggal <  DATE '$tahun-08-01'
			)) AS juli,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-08-01'
				AND penjualan_tanggal <  DATE '$tahun-09-01'
			)) AS agustus,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-09-01'
				AND penjualan_tanggal <  DATE '$tahun-10-01'
			)) AS september,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-10-01'
				AND penjualan_tanggal <  DATE '$tahun-11-01'
			)) AS oktober,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-11-01'
				AND penjualan_tanggal <  DATE '$tahun-12-01'
			)) AS november,

			round(avg(penjualan_total_grand) FILTER (
				WHERE penjualan_tanggal >= DATE '$tahun-12-01'
				AND penjualan_tanggal <  DATE '" . ($tahun + 1) . "-01-01'
			)) AS desember
		", false);

		$result							= $this->db->from('pos_penjualan')
			->where('penjualan_tanggal >=', $awal_tahun)
			->where('penjualan_tanggal <', $akhir_tahun)
			->get()
			->row();

		$data							= array();
		$data['chart_nominal_pajak']	= [
			[
				'bulan'	=> 'Januari',
				'total'	=> $result->januari ?? 0
			],
			[
				'bulan'	=> 'Februari',
				'total'	=> $result->februari ?? 0
			],
			[
				'bulan'	=> 'Maret',
				'total'	=> $result->maret ?? 0
			],
			[
				'bulan'	=> 'April',
				'total'	=> $result->april ?? 0
			],
			[
				'bulan'	=> 'Mei',
				'total'	=> $result->mei ?? 0
			],
			[
				'bulan'	=> 'Juni',
				'total'	=> $result->juni ?? 0
			],
			[
				'bulan'	=> 'Juli',
				'total'	=> $result->juli ?? 0
			],
			[
				'bulan'	=> 'Agustus',
				'total'	=> $result->agustus ?? 0
			],
			[
				'bulan'	=> 'September',
				'total'	=> $result->september ?? 0
			],
			[
				'bulan'	=> 'Oktober',
				'total'	=> $result->oktober ?? 0
			],
			[
				'bulan'	=> 'November',
				'total'	=> $result->november ?? 0
			],
			[
				'bulan'	=> 'Desember',
				'total'	=> $result->desember ?? 0
			],
		];

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

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where .= 'AND pw.pemda_id=' . $this->db->escape($pemda_id);
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
		$pemda_id = $this->session->userdata('pemda_id');
		$params = [
			'sort_static' => 'tanggal_last_transaksi desc NULLS last',
			'limit'       => $limit,
			'start'       => $start,
		];
		if (empty($pemda_id)) {
			$params['without_global_scope'] = true;
		} else {
			$params['filters_static'] =
				'v_status_device.pemda_id = ' . $this->db->escape($pemda_id);
		}
		$opr = $this->statusdevice->select($params);
		$opr['page'] = $page;
		$opr['limit'] = $limit;
		$this->response(
			$opr
		);
	}
}

/* End of file satuan.php */
/* Location: ./application/modules/dashboard/controllers/satuan.php */
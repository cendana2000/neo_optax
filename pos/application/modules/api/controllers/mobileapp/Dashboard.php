<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Base_Controller
{
	protected $auth;
	protected $db;
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array());
		$this->auth = AUTHORIZATION::Auth();
		if (!empty($this->auth->session_db)) {
			$this->db = $this->load->database(multidb_connect($this->auth->session_db), true);
		}
	}

	public function index()
	{
		$data = varGet();
		$dateToday = date('Y-m-d');
		$year = $data['year'] ?? date('Y');
		$startDate = "$year-01-01";
		$endDate = "$year-12-31";

		$query_today = $this->db->query("SELECT COALESCE(sum(penjualan_total_grand), 0) as penjualan_total_grand
		FROM pos_penjualan 
		WHERE penjualan_tanggal::date = '$dateToday'")->row_array();

		$query_omzet = $this->db->query("WITH months AS (
		SELECT generate_series(1, 12) AS month_number
		),
		month_names AS (
			SELECT
				month_number,
				CASE month_number
					WHEN 1 THEN 'Jan'
					WHEN 2 THEN 'Feb'
					WHEN 3 THEN 'Mar'
					WHEN 4 THEN 'Apr'
					WHEN 5 THEN 'May'
					WHEN 6 THEN 'Jun'
					WHEN 7 THEN 'Jul'
					WHEN 8 THEN 'Aug'
					WHEN 9 THEN 'Sep'
					WHEN 10 THEN 'Oct'
					WHEN 11 THEN 'Nov'
					WHEN 12 THEN 'Dec'
				END AS month_name
			FROM months
		)
		SELECT
			mn.month_number,
			mn.month_name,
			COALESCE(SUM(pos_penjualan.penjualan_total_grand), 0) AS total
		FROM
			month_names mn
		LEFT JOIN
			pos_penjualan
			ON EXTRACT(MONTH FROM pos_penjualan.penjualan_tanggal) = mn.month_number AND pos_penjualan.penjualan_tanggal >= '$startDate' AND pos_penjualan.penjualan_tanggal <= '$endDate'
		GROUP BY
			mn.month_number,
			mn.month_name
		ORDER BY
			mn.month_number;
		")->result_array();

		$query_transaction = $this->db->query("WITH months AS (
		SELECT generate_series(1, 12) AS month_number
		),
		month_names AS (
			SELECT
				month_number,
				CASE month_number
					WHEN 1 THEN 'Jan'
					WHEN 2 THEN 'Feb'
					WHEN 3 THEN 'Mar'
					WHEN 4 THEN 'Apr'
					WHEN 5 THEN 'May'
					WHEN 6 THEN 'Jun'
					WHEN 7 THEN 'Jul'
					WHEN 8 THEN 'Aug'
					WHEN 9 THEN 'Sep'
					WHEN 10 THEN 'Oct'
					WHEN 11 THEN 'Nov'
					WHEN 12 THEN 'Dec'
				END AS month_name
			FROM months
		)
		SELECT
			mn.month_number,
			mn.month_name,
			COALESCE(COUNT(pos_penjualan.penjualan_tanggal), 0) AS total
		FROM
			month_names mn
		LEFT JOIN
			pos_penjualan
			ON EXTRACT(MONTH FROM pos_penjualan.penjualan_tanggal) = mn.month_number AND pos_penjualan.penjualan_tanggal >= '$startDate' AND pos_penjualan.penjualan_tanggal <= '$endDate'
		GROUP BY
			mn.month_number,
			mn.month_name
		ORDER BY
			mn.month_number;
		")->result_array();

		$totalOmzet = 0;
		$totalTransaction = 0;

		foreach ($query_omzet as $q) {
			$totalOmzet += $q['total'];
		}

		foreach ($query_transaction as $q) {
			$totalTransaction += $q['total'];
		}

		return $this->response([
			"status" => true,
			"data" => [
				"today_income" => $query_today['penjualan_total_grand'],
				"chart" => [
					"omzet" => $query_omzet,
					"transaction" => $query_transaction
				],
				"counter" => [
					"omzet" => $totalOmzet,
					"transaction" => $totalTransaction
				]
			]
		]);
	}
}

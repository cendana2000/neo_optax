<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;


class Transaksipenjualan extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksipenjualanModelV2' 		=> 'transaksipenjualan',
			'transaksipenjualanModel2' 		=> 'transaksipenjualan2',
			'transaksipenjualandetailModel' => 'transaksipenjualandetail',
			'transaksipenjualandetailhiburanModel' => 'transaksipenjualandetailhiburan',
			'stokkartu/stokkartuModel' 		=> 'stokkartu',
			'anggota/anggotaModel' 			=> 'anggota',
			'barang/barangModel' 			=> 'barang',
			'barang/barangbarcodeModel' 	=> 'barangbarcode',
			'barang/BarangsatuanModel' 		=> 'barangsatuan',
			'pengajuanpinjaman/PengajuanPinjamanModel'	=> 'pengajuan',
			'kartupinjaman/kartupinjamanModel'	=> 'kartupinjaman',
			'kartusimpanan/kartusimpananModel'	=> 'kartusimpanan',
			'customer/CustomerModel'	=> 'customer',
			'pembayaranpiutang/PembayaranpiutangModel' 		=> 'pembayaran',
			'pembayaranpiutang/PembayaranpiutangdetailModel' => 'pembayarandetail',
			'pembayaranpiutang/PembayaranpiutangdetailpembayaranModel' 	=> 'multipayment',
			'kategori/KategoriModel' => 'kelompokbarang',
			'rekening/RekeningModel' => 'rekening',
		));
	}

	public function index()
	{
		$var = varPost();
		$bulan = $var['bulan'];
		$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		// $rawend ori
		// $rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		$rawend = date_format(
			(new DateTime($bulan))->modify('+1 month')->modify('-1 second'),
			'Y-m-d H:i:s'
		);

		$where = [
			// 'penjualan_status_aktif' => null,
			"penjualan_tanggal BETWEEN '" . $rawbegin . "' AND '" . $rawend . "'" => null,
		];
		$this->response(
			$this->select_dt(varPost(), 'transaksipenjualan2', 'table', false, $where)
		);
	}

	public function loadRental()
	{
		// $var = varPost();
		$bulan = $this->input->post('bulan'); // Ambil data bulan dari POST request
		$begin = new DateTime($bulan);
		$end = (new DateTime($bulan))->modify('+1 month');
		$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		$where = [
			'penjualan_status_aktif' => null,
			"penjualan_tanggal BETWEEN '" . $rawbegin . "' AND '" . $rawend . "'" => null,
			"barang_aktif = '2' OR barang_aktif = '3' AND penjualan_tanggal BETWEEN '" . $rawbegin . "' AND '" . $rawend . "'" => null,
		];

		$data = $this->select_dt(varPost(), 'transaksipenjualan', 'table', false, $where);
		// print_r($data);
		// print_r($data);
		// die;
		$this->response(
			$data
		);
	}

	public function loadRentalLunas()
	{
		// $var = varPost();
		$bulan = $this->input->post('bulan'); // Ambil data bulan dari POST request
		$begin = new DateTime($bulan);
		$end = (new DateTime($bulan))->modify('+1 month');
		$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		$where = [
			'penjualan_status_aktif' => null,
			"DATE(penjualan_tanggal) BETWEEN '" . $rawbegin . "' AND '" . $rawend . "' AND penjualan_bayar_sisa < '1'" => null,
			"barang_aktif = '2' OR barang_aktif = '3' AND DATE(penjualan_tanggal) BETWEEN '" . $rawbegin . "' AND '" . $rawend . "' AND penjualan_bayar_sisa < '1'" => null,
			'penjualan_status_aktif IS NULL' => null
		];

		$data = $this->select_dt(varPost(), 'transaksipenjualan', 'table', false, $where);
		// print_r($data);
		// print_r($data);
		// die;
		$this->response(
			$data
		);
	}

	public function loadRentalBelumLunas()
	{
		// $var = varPost();
		$bulan = $this->input->post('bulan'); // Ambil data bulan dari POST request
		$begin = new DateTime($bulan);
		$end = (new DateTime($bulan))->modify('+1 month');
		$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		$where = [
			'penjualan_status_aktif' => null,
			"DATE(penjualan_tanggal) BETWEEN '" . $rawbegin . "' AND '" . $rawend . "' AND penjualan_bayar_sisa > '0'" => null,
			"barang_aktif = '2' OR barang_aktif = '3' AND DATE(penjualan_tanggal) BETWEEN '" . $rawbegin . "' AND '" . $rawend . "' AND penjualan_bayar_sisa > '0'" => null,
			'penjualan_status_aktif IS NULL' => null
		];

		$data = $this->select_dt(varPost(), 'transaksipenjualan', 'table', false, $where);
		// print_r($data);
		// print_r($data);
		// die;
		$this->response(
			$data
		);
	}

	public function loadHiburan()
	{
		// $var = varPost();
		$bulan = $this->input->post('bulan'); // Ambil data bulan dari POST request
		$begin = new DateTime($bulan);
		$end = (new DateTime($bulan))->modify('+1 month');
		$rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
		$rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
		$where = [
			'penjualan_status_aktif' => null,
			"penjualan_tanggal BETWEEN '" . $rawbegin . "' AND '" . $rawend . "'" => null,
		];

		$data = $this->select_dt(varPost(), 'transaksipenjualan', 'table', false, $where);
		// print_r($data);
		// print_r($data);
		// die;
		$this->response(
			$data
		);
	}


	// Original index2
	public function index2()
	{
		$var = varPost();
		$this->response(
			$this->select_dt(varPost(), 'transaksipenjualan', 'table', false, array(
				'penjualan_tanggal BETWEEN "' . $var['tanggal1'] . '" AND "' . $var['tanggal2'] . '" ' => null,
			))
		);
	}

	public function load_data_mobile()
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		$var = varPost();
		$filter = trim(varPost('valSearch'));

		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$user['session_db'] = varPost('mobileDb');
			$this->session->set_userdata($user);
		}

		if ($filter != NULL) {
			$filter = "penjualan_kode LIKE '%$filter%' AND";
		} else {
			$filter = "";
		}

		$data['aaData'] = $this->db->query("SELECT * FROM v_pos_penjualan WHERE $filter 
		 penjualan_status_aktif IS NULL AND penjualan_platform = 'Mobile' ORDER BY penjualan_created DESC")->result_array();
		$data['iTotalRecords'] = count($data['aaData']);
		$data['iTotalDisplayRecords'] = count($data['aaData']);

		$this->response($data);
	}

	public function get_barang()
	{
		$barang = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text, barang_stok saved FROM v_pos_barang_barcode WHERE barang_barcode_kode = "' . varPost('val') . '" LIMIT 1')->result_array();
		$this->response($barang);
	}

	public function get_barang_satuan_data()
	{
		$this->db->where(varPost());
		$res  = $this->db->get('pos_barang_satuan')->row_array();

		$this->response($res);
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(anggota_id) total FROM pos_anggota WHERE anggota_nama like "' . $data['q'] . '%" OR anggota_kode like "' . $data['q'] . '%" and anggota_is_aktif="" ORDER BY anggota_nama ASC')->result_array();
		$return = $this->db->query('SELECT anggota_id as id, concat(anggota_kode, " - ", anggota_nama, " - ", IFNULL(substring(anggota_alamat, 1, 10), "")) as text, anggota_kode FROM pos_anggota WHERE anggota_nama like "' . $data['q'] . '%" OR anggota_kode like "' . $data['q'] . '%"  and anggota_is_aktif="" ORDER BY anggota_nama ASC LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function rest_load_menu()
	{
		$headers = $this->input->request_headers();
		if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
			$decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
			if ($decodedToken != false) {
				// $this->response($decodedToken);
				if (!empty($decodedToken->session_db)) {
					$this->db = $this->load->database(multidb_connect($decodedToken->session_db), true);
					$this->load_menu();
					// $this->response($decodedToken);
					return;
				}
			}
		}

		$this->response(array('success' => false));
	}

	public function get_category_hierarchy($parent_id = '')
	{
		$categories = $this->get_child_categories($parent_id);
		static $res = '';
		if (!empty($categories)) {
			foreach ($categories as $category) {
				$category->children = $this->get_category_hierarchy($category->kategori_barang_id);
				$res .= "OR barang_kategori_barang = '" . $category->kategori_barang_id . "' ";
			}
		}

		return $res;
	}

	public function get_child_categories($parent_id = '')
	{
		$this->db->select('kategori_barang_id, kategori_barang_kode, kategori_barang_nama');
		$this->db->from('pos_kategori');
		$this->db->where('kategori_barang_parent', $parent_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function load_menu($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
		}


		$valSearch = trim(varPost('valSearch'));
		$valKategori = trim(varPost('valKategori'));
		$valOrder = trim(varPost('valOrder'));
		$limitProduk = $_POST['limitProduk'];
		$page = varPost('page');
		$row = 12;
		$limit = "";

		if (isset($page) && $page != null) {
			$countlimit = ($page - 1) * $row;
			$limit = "LIMIT $row OFFSET $countlimit";
		}

		$filter = "WHERE barang_deleted_at IS NULL AND pos_barang.barang_kode is not null";

		if ($valKategori != NULL) {
			$childKategori = $this->get_category_hierarchy($valKategori);
			$filter .= " AND pos_barang.barang_kategori_barang = '$valKategori' " . $childKategori;
		}

		if (isset($valOrder) && $valOrder != NULL) {
			$order = "ORDER BY $valOrder DESC";
			if ($valOrder == "tersedia") {
				$order = "";
				$filter .= " AND barang_stok > 0";
			} else if ($valOrder == "kosong") {
				$order = "";
				$filter .= " AND barang_stok = 0";
			} else if ($valOrder == "semua") {
				$order = "";
			} else if ($valOrder == "barang_stok_kecil") {
				$order = "ORDER BY barang_stok ASC";
			} else if ($valOrder == "rental_booking") {
				$order = "";
				$filter .= " AND barang_aktif = '2'";
			} else if ($valOrder == "rental_booked") {
				$order = "";
				$filter .= " AND barang_aktif = '3'";
			}
		}

		if ($valSearch != NULL) {
			$filter .= " AND lower(barang_nama) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_kode) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_barcode_kode) LIKE lower('%" . $valSearch . "%')";
		}

		if (array_key_exists('mobileDb', varPost())) {
			// Limit produk from mobile
			if ($limitProduk != NULL) {
				$limit = "LIMIT 12 OFFSET $limitProduk";
			} else {
				$limit = "LIMIT 12 OFFSET 0";
			}
		}

		$query = "SELECT barang_id as id, barang_kode, barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok as stok_now, total_jual, barang_stok_min, jenis_include_stok, barang_kategori_barang, barang_aktif
		FROM pos_barang 
		LEFT JOIN pos_barang_satuan 
			ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) as total_jual FROM pos_penjualan_detail GROUP BY penjualan_detail_barang_id)	as jual
			ON penjualan_detail_barang_id = barang_id
		LEFT JOIN pos_jenis 
			ON pos_barang.barang_jenis_barang = pos_jenis.jenis_id
		LEFT JOIN pos_barang_barcode 
			ON pos_barang.barang_id = pos_barang_barcode.barang_barcode_parent
		$filter
		GROUP BY barang_id, barang_kode,barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok, barang_stok_min, jenis_include_stok, jual.total_jual, barang_kategori_barang, barang_aktif, pos_barang.barang_created_at
		$order
		-- $limit
		";

		$return = $this->db->query($query)->result_array();

		// print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;
		$total = count($return);



		$this->response(array('items' => $return, 'total_count' => $total));
	}
	public function load_menu_rental($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
		}


		$valSearch = trim(varPost('valSearch'));
		$valKategori = trim(varPost('valKategori'));
		$valOrder = trim(varPost('valOrder'));
		$limitProduk = $_POST['limitProduk'];
		$page = varPost('page');
		$row = 12;
		$limit = "";

		if (isset($page) && $page != null) {
			$countlimit = ($page - 1) * $row;
			$limit = "LIMIT $row OFFSET $countlimit";
		}

		$filter = "WHERE pos_barang.barang_deleted_at IS NULL AND pos_barang.barang_kode is not null AND pos_barang.barang_aktif = '2' AND pos_barang.barang_aktif <> '3'";

		if ($valKategori != NULL) {
			$childKategori = $this->get_category_hierarchy($valKategori);
			$filter .= " AND pos_barang.barang_kategori_barang = '$valKategori' " . $childKategori;
		}

		if (isset($valOrder) && $valOrder != NULL) {
			$order = "ORDER BY $valOrder DESC";
			if ($valOrder == "tersedia") {
				$order = "";
				$filter .= " AND barang_stok > 0";
			} else if ($valOrder == "kosong") {
				$order = "";
				$filter .= " AND barang_stok = 0";
			} else if ($valOrder == "semua") {
				$order = "";
			} else if ($valOrder == "barang_stok_kecil") {
				$order = "ORDER BY barang_stok ASC";
			} else if ($valOrder == "rental_booking") {
				$order = "";
				$filter .= " AND barang_aktif = '2'";
			} else if ($valOrder == "rental_booked") {
				$order = "";
				$filter .= " AND barang_aktif = '3'";
			}
		}

		if ($valSearch != NULL) {
			$filter .= " AND lower(barang_nama) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_kode) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_barcode_kode) LIKE lower('%" . $valSearch . "%')";
		}

		if (array_key_exists('mobileDb', varPost())) {
			// Limit produk from mobile
			if ($limitProduk != NULL) {
				$limit = "LIMIT 12 OFFSET $limitProduk";
			} else {
				$limit = "LIMIT 12 OFFSET 0";
			}
		}

		$query = "SELECT barang_id as id, barang_kode, barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok as stok_now, total_jual, barang_stok_min, jenis_include_stok, barang_kategori_barang, barang_aktif
		FROM pos_barang 
		LEFT JOIN pos_barang_satuan 
			ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) as total_jual FROM pos_penjualan_detail GROUP BY penjualan_detail_barang_id)	as jual
			ON penjualan_detail_barang_id = barang_id
		LEFT JOIN pos_jenis 
			ON pos_barang.barang_jenis_barang = pos_jenis.jenis_id
		LEFT JOIN pos_barang_barcode 
			ON pos_barang.barang_id = pos_barang_barcode.barang_barcode_parent
		$filter
		GROUP BY barang_id, barang_kode,barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok, barang_stok_min, jenis_include_stok, jual.total_jual, barang_kategori_barang, barang_aktif, pos_barang.barang_created_at
		$order
		-- $limit
		";

		$return = $this->db->query($query)->result_array();
		$total = count($return);

		$this->response(array('items' => $return, 'total_count' => $total, 'sql' => $this->db->last_query()));
		// $this->response(array('items' => $return, 'total_count' => $total, 'query' => $this->db->last_query()));
	}

	public function load_menu_hiburan($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
		}


		$valSearch = trim(varPost('valSearch'));
		$valKategori = trim(varPost('valKategori'));
		$valOrder = trim(varPost('valOrder'));
		$limitProduk = $_POST['limitProduk'];
		$page = varPost('page');
		$row = 12;
		$limit = "";

		if (isset($page) && $page != null) {
			$countlimit = ($page - 1) * $row;
			$limit = "LIMIT $row OFFSET $countlimit";
		}

		$filter = "WHERE barang_deleted_at IS NULL AND pos_barang.barang_kode is not null";

		if ($valKategori != NULL) {
			$childKategori = $this->get_category_hierarchy($valKategori);
			$filter .= " AND pos_barang.barang_kategori_barang = '$valKategori' " . $childKategori;
		}

		if (isset($valOrder) && $valOrder != NULL) {
			$order = "ORDER BY $valOrder DESC";
			if ($valOrder == "tersedia") {
				$order = "";
				$filter .= " AND barang_stok > 0";
			} else if ($valOrder == "kosong") {
				$order = "";
				$filter .= " AND barang_stok = 0";
			} else if ($valOrder == "semua") {
				$order = "";
			} else if ($valOrder == "barang_stok_kecil") {
				$order = "ORDER BY barang_stok ASC";
			} else if ($valOrder == "rental_booking") {
				$order = "";
				$filter .= " AND barang_aktif = '2'";
			} else if ($valOrder == "rental_booked") {
				$order = "";
				$filter .= " AND barang_aktif = '3'";
			}
		}

		if ($valSearch != NULL) {
			$filter .= " AND lower(barang_nama) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_kode) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_barcode_kode) LIKE lower('%" . $valSearch . "%')";
		}

		if (array_key_exists('mobileDb', varPost())) {
			// Limit produk from mobile
			if ($limitProduk != NULL) {
				$limit = "LIMIT 12 OFFSET $limitProduk";
			} else {
				$limit = "LIMIT 12 OFFSET 0";
			}
		}

		$query = "SELECT barang_id as id, barang_kode, barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok as stok_now, total_jual, barang_stok_min, jenis_include_stok, barang_kategori_barang, barang_aktif
		FROM pos_barang 
		LEFT JOIN pos_barang_satuan 
			ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) as total_jual FROM pos_penjualan_detail GROUP BY penjualan_detail_barang_id)	as jual
			ON penjualan_detail_barang_id = barang_id
		LEFT JOIN pos_jenis 
			ON pos_barang.barang_jenis_barang = pos_jenis.jenis_id
		LEFT JOIN pos_barang_barcode 
			ON pos_barang.barang_id = pos_barang_barcode.barang_barcode_parent
		$filter
		GROUP BY barang_id, barang_kode,barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok, barang_stok_min, jenis_include_stok, jual.total_jual, barang_kategori_barang, barang_aktif, pos_barang.barang_created_at
		$order
		-- $limit
		";

		$return = $this->db->query($query)->result_array();
		$total = count($return);

		$this->response(array('items' => $return, 'total_count' => $total));
		// $this->response(array('items' => $return, 'total_count' => $total, 'query' => $this->db->last_query()));
	}

	public function load_menu_mobile($dbname = '')
	{
		if (!empty($dbname)) {
			$this->db = $this->load->database(multidb_connect($dbname), true);
		}

		$isMobile = false;
		$jenisUsaha = '';

		if (array_key_exists('mobileDb', varPost())) {
			$user['session_db'] = varPost('mobileDb');
			$this->session->userdata($user);
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$isMobile = true;

			$kode_toko = explode('_', varPost('mobileDb'));
			$toko = $this->dbmp->where([
				'toko_kode' => $kode_toko[1]
			])->get('v_pajak_toko')->row_array();

			$getJenis = $this->dbmp->get_where('pajak_jenis', [
				'jenis_nama' => $toko['jenis_nama']
			])->row_array();
			$getJenisParent = $this->dbmp->get_where('pajak_jenis', [
				'jenis_id' => $getJenis['jenis_parent']
			])->row_array();

			$jenisUsaha = $getJenisParent['jenis_nama'];
		}


		$valSearch = trim(varPost('valSearch'));
		$valKategori = trim(varPost('kategori_id'));
		$valOrder = trim(varPost('valOrder'));
		$limitProduk = $_POST['limitProduk'];
		$page = varPost('page');
		$row = 12;
		$limit = "";

		if (isset($page) && $page != null) {
			$countlimit = ($page - 1) * $row;
			$limit = "LIMIT $row OFFSET $countlimit";
		}

		// $filter = "WHERE jenis_include_stok != 2 AND barang_deleted_at IS NULL";

		$filter = "WHERE barang_deleted_at IS NULL AND pos_barang.barang_kode is not null";
		if ($jenisUsaha == 'PAJAK HOTEL' || $jenisUsaha == 'PAJAK HIBURAN') {
			$filter = "WHERE pos_barang.barang_deleted_at IS NULL AND pos_barang.barang_kode is not null AND pos_barang.barang_aktif = '2' AND pos_barang.barang_aktif <> '3'";
		}

		if ($valKategori != NULL) {
			$childKategori = $this->get_category_hierarchy($valKategori);
			$filter .= " AND pos_barang.barang_kategori_barang = '$valKategori' " . $childKategori;
		}

		if (isset($valOrder) && $valOrder != NULL) {
			$order = "ORDER BY $valOrder DESC";
			if ($valOrder == "tersedia") {
				$order = "";
				$filter .= " AND barang_stok > 0";
			} else if ($valOrder == "kosong") {
				$order = "";
				$filter .= " AND barang_stok = 0";
			} else if ($valOrder == "semua") {
				$order = "";
			} else if ($valOrder == "barang_stok_kecil") {
				$order = "ORDER BY barang_stok ASC";
			} else if ($valOrder == "rental_booking") {
				$order = "";
				$filter .= " AND barang_aktif = '2'";
			} else if ($valOrder == "rental_booked") {
				$order = "";
				$filter .= " AND barang_aktif = '3'";
			}
		}

		if ($valSearch != NULL) {
			$filter .= " AND lower(barang_nama) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_kode) LIKE lower('%" . $valSearch . "%') 
			OR lower(barang_barcode_kode) LIKE lower('%" . $valSearch . "%')";
		}

		// Limit produk from mobile
		if ($limitProduk != NULL) {
			$limit = "LIMIT 9 OFFSET $limitProduk";
		} else if ($limitProduk == NULL) {
			// $limit = "LIMIT 9 OFFSET 0";
		}

		$selectThumbnail = 'barang_thumbnail';
		if ($isMobile) {
			$selectThumbnail = 'CASE 
        WHEN barang_thumbnail IS NULL THEN NULL
        ELSE CONCAT(\'' . $_ENV['BASE_URL'] . '\', barang_thumbnail) 
    END as barang_thumbnail';
		}

		$query = "SELECT barang_id as id, barang_kode, barang_nama, $selectThumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok as stok_now, total_jual, barang_stok_min, jenis_include_stok, barang_kategori_barang, barang_aktif
		FROM pos_barang 
		LEFT JOIN pos_barang_satuan 
			ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) as total_jual FROM pos_penjualan_detail GROUP BY penjualan_detail_barang_id)	as jual
			ON penjualan_detail_barang_id = barang_id
		LEFT JOIN pos_jenis 
			ON pos_barang.barang_jenis_barang = pos_jenis.jenis_id
		LEFT JOIN pos_barang_barcode 
			ON pos_barang.barang_id = pos_barang_barcode.barang_barcode_parent
		$filter
		GROUP BY barang_id, barang_kode,barang_nama, barang_thumbnail, pos_barang.barang_satuan_kode, barang_harga, barang_stok, barang_stok_min, jenis_include_stok, jual.total_jual, barang_kategori_barang, barang_aktif, pos_barang.barang_created_at
		$order
		-- $limit
		";

		$return = $this->db->query($query)->result_array();
		$total = count($return);
		$this->response(array('items' => $return, 'total_count' => $total));
	}

	public function barang_ajax($value = '')
	{
		$data = varPost();

		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id']) ? 'barang_supplier_id = "' . $data['fdata']['barang_supplier_id'] . '" AND ' : '';
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR barang_kode like "' . $data['q'] . '%") ')->result_array();

		// Query Lama
		$return = $this->db->query("SELECT barang_id as id, barang_kode, barang_nama, barang_harga, 
		barang_stok, barang_stok as saved FROM pos_barang JOIN pos_barang_satuan 
		ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent")->result_array();

		// Query Baru
		$return = $this->db->query("SELECT barang_id as id, barang_kode, barang_nama, barang_harga, SUM(pos_pembelian_barang_detail.pembelian_detail_qty) as barang_stok, barang_stok as saved 
		FROM pos_barang 
		JOIN pos_barang_satuan 
			ON pos_barang.barang_id = pos_barang_satuan.barang_satuan_parent
		JOIN  pos_pembelian_barang_detail 
			ON  pos_barang.barang_id  = pos_pembelian_barang_detail.pembelian_detail_barang_id 
		GROUP BY pos_pembelian_barang_detail.pembelian_detail_barang_id")->result_array();

		// , " (stok: ", barang_stok, ")"
		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 45px;">' . $value['barang_kode'] . '</span><span class="detail-barang-select"  style="width: 320px;">' . $value['barang_nama'] . '</span><span class="detail-barang-select" style="width: 100px;">' . number_format($value['barang_harga']) . '</span><span class="detail-barang-select" style="width: 65px;">Stok : ' . $value['barang_stok'] . '</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_kode'] . '-' . $value['barang_nama']
			];
		}
		$this->response(array('items' => $new_return, 'total_count' => $total[0]['total']));
	}

	public function batal_transaksi()
	{
		$id_penjualan['penjualan_id'] = varPost('penjualan_id');

		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$user['session_db'] = varPost('mobileDb');
			$this->session->set_userdata($user);
		}

		$this->db->where($id_penjualan);
		$dPenjualan = $this->db->get('pos_penjualan')->row_array();

		$dPenjualanDetail = $this->db->get_where('pos_penjualan_detail', ['penjualan_detail_parent' => $dPenjualan['penjualan_id']])->result_array();
		$cBarangNonStok = $this->db->get_where('pos_barang', ['barang_id' => $dPenjualanDetail['penjualan_detail_barang_id']])->row_array()['barang_stok'];

		if ($cBarangNonStok != null) {
			# code...
			foreach ($dPenjualanDetail as $key => $value) {
				$satuan = $this->db->get_where('pos_barang_satuan', ['barang_satuan_parent' => $value['penjualan_detail_barang_id']])->row_array();
				$konversi = $satuan['barang_satuan_konversi'];
				$qtyBarangJual = $value['penjualan_detail_qty_barang'];
				$hasilBarangJual = $qtyBarangJual * $konversi;

				$cBarangStok = $this->db->get_where('pos_barang', ['barang_id' => $value['penjualan_detail_barang_id']])->row_array()['barang_stok'];

				$hasil = $cBarangStok + $hasilBarangJual;

				$this->db->set('barang_stok', $hasil);
				$this->db->set('barang_aktif', 2);
				$this->db->where('barang_id', $value['penjualan_detail_barang_id']);
				$this->db->update('pos_barang');
			}
		}

		// update status di pos_penjualan
		$this->db->set('penjualan_status_aktif', date("Y-m-d H:i:s"));
		$this->db->where($id_penjualan);
		$update_penjualan['pos_penjualan'] = $this->db->update('pos_penjualan');

		//update trigger status retur di pos_penjualan_detail
		foreach ($dPenjualanDetail as $key => $value) {
			# code...
			if ($update_penjualan == true) {
				# code...
				$this->db->set('penjualan_detail_retur', 1);
				$this->db->where('penjualan_detail_parent', $value['penjualan_detail_parent']);
				$this->db->update('pos_penjualan_detail');
			}
		}

		// update kartu stok keluar di pos_kartu_stok
		$this->db->set('kartu_stok_keluar', 0);
		$this->db->where('kartu_transaksi_kode', $dPenjualan['penjualan_kode']);
		$update_penjualan['kartu_stok'] = $this->db->update('pos_kartu_stok');

		if ($update_penjualan['pos_penjualan'] == true && $update_penjualan['kartu_stok'] == true) {
			$this->response(array('status' => true));
		} else {
			$this->response(array('status' => false));
		}
	}


	function read($value = '')
	{
		$this->response($this->transaksipenjualan->read(varPost()));
	}

	function read_detail($value = '')
	{
		$operation = $this->transaksipenjualandetail->read(varPost());
		$this->response($operation);
	}

	public function mobile_detail()
	{
		$data = varPost();
		$id['penjualan_id'] = varPost('penjualan_id');
		$parent = $this->transaksipenjualan->read_mobile($data['mobileDb'], $id);
		$detail = $this->transaksipenjualandetail->select_mobile($data['mobileDb'], ['filters_static' => ['penjualan_detail_parent' => $data['penjualan_id']]]);

		$this->response([
			'parent' => $parent,
			'detail' => $detail,
		]);
	}

	function edit_detail($value = '')
	{
		$data = varPost();
		if (array_key_exists('mobileDb', $data)) {
			$user['session_db'] = $data['mobileDb'];
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$this->session->set_userdata($user);
		}
		$detail = $this->transaksipenjualandetail->select(['filters_static' => ['penjualan_detail_parent' => $data['penjualan_id']]]);

		$html = '';
		$row = 1;
		foreach ($detail['data'] as $key => $value) {
			$satuan = $this->barangsatuan->select(['filters_static' => ['barang_satuan_parent' => $value['penjualan_detail_barang_id']], 'sort_static' => 'barang_satuan_order']);
			$html .= '<tr class="barang_' . $row . '">
					<td scope="row">
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_id'] . '" name="penjualan_detail_id[' . $row . ']" id="penjualan_detail_id_' . $row . '">						
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_jenis_barang'] . '" name="penjualan_detail_jenis_barang[' . $row . ']" id="penjualan_detail_jenis_barang_' . $row . '">						
						<select class="form-control barang_id" value="' . $value['penjualan_detail_barang_id'] . '" name="penjualan_detail_barang_id[' . $row . ']" id="penjualan_detail_barang_id_' . $row . '" data-id="' . $row . '" onchange="setSatuan(' . $row . ')" style="width: 260px;white-space: nowrap">
								<option value="' . $value['penjualan_detail_barang_id'] . '" selected>' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '</option>
						</select></td>
					<td><select class="form-control" value="' . $value['penjualan_detail_satuan'] . '" name="penjualan_detail_satuan[' . $row . ']" id="penjualan_detail_satuan_' . $row . '" style="width: 100%" onchange="getHarga(' . $row . ')">';

			foreach ($satuan['data'] as $k => $v) {
				// $html .= '<option value="'.$v['barang_satuan_id'].'" data-barang_satuan_harga_beli="'.$v['barang_satuan_harga_beli'].'" data-barang_satuan_konversi="'.$v['barang_satuan_konversi'].'" data-barang_satuan_keuntungan="'.$v['barang_satuan_keuntungan'].'" '.($v['barang_satuan_id'] == $value['pembelian_detail_satuan']?'selected':'').'>'.$v['barang_satuan_kode'].'</option>';
				$html .= '<option value="' . $v['barang_satuan_id'] . '" data-barang_satuan_harga_jual="' . $v['barang_satuan_harga_jual'] . '" data-barang_satuan_harga_beli="' . $v['barang_harga_pokok'] . '" data-barang_satuan_disc="' . $v['barang_satuan_disc'] . '" data-barang_satuan_konversi="' . $v['barang_satuan_konversi'] . '" data-barang_kategori="' . $v['kategori_barang_parent'] . '" ' . ($v['barang_satuan_id'] == $v['penjualan_detail_satuan'] ? 'selected' : '') . '>' . $v['barang_satuan_kode'] . '(' . $v['barang_satuan_konversi'] . ')' . '</option>';
			}
			$html .= '</select>
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_satuan_kode'] . '" name="penjualan_detail_satuan_kode[' . $row . ']" id="penjualan_detail_satuan_kode_' . $row . '" >						
					</td>					
					<td>
						<input class="form-control number" type="text" value="' . $value['penjualan_detail_harga'] . '" name="penjualan_detail_harga[' . $row . ']" id="penjualan_detail_harga_' . $row . '" onchange="countRow(' . $row . ')" readonly="">
					</td>
					<td>
						<input class="form-control qty" type="number" value="' . $value['penjualan_detail_qty'] . '" name="penjualan_detail_qty[' . $row . ']" id="penjualan_detail_qty_' . $row . '" onkeyup="countRow(' . $row . ')" onchange="countRow(' . $row . ')" value="1">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_qty_barang'] . '" name="penjualan_detail_qty_barang[' . $row . ']" id="penjualan_detail_qty_barang_' . $row . '">						
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_harga_beli'] . '" name="penjualan_detail_harga_beli[' . $row . ']" id="penjualan_detail_harga_beli_' . $row . '">
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_hpp'] . '" name="penjualan_detail_hpp[' . $row . ']" id="penjualan_detail_hpp_' . $row . '">					
					</td>
					<td>
						<input class="form-control disc" type="text" value="' . $value['penjualan_detail_potongan_persen'] . '" name="penjualan_detail_potongan_persen[' . $row . ']" id="penjualan_detail_potongan_persen_' . $row . '" onkeyup="countRow(' . $row . ')">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_potongan'] . '" name="penjualan_detail_potongan[' . $row . ']" id="penjualan_detail_potongan_' . $row . '">
					</td>
					<td><input class="form-control number jumlah" type="text" value="' . $value['penjualan_detail_subtotal'] . '" name="penjualan_detail_subtotal[' . $row . ']" id="penjualan_detail_subtotal_' . $row . '" readonly=""></td>
					<td style="text-align: center;"><a href="javascript:;" data-id="' . $row . '" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
		          		<span class="la la-trash"></span> Hapus</a></td>
				</tr>';
			$row++;
		}

		$opsparent = $this->transaksipenjualan->read($data);
		$opsparent['customer'] = $this->customer->read($opsparent['pos_penjualan_customer_id']);
		if ($opsparent['penjualan_bank'] != null) {
			$opsparent['bank'] = $this->rekening->read($opsparent['penjualan_bank']);
		}

		$this->response([
			'parent' => $opsparent,
			'detail' => $detail,
		]);
	}

	function edit_detail_rental($value = '')
	{
		$data = varPost();
		if (array_key_exists('mobileDb', $data)) {
			$user['session_db'] = $data['mobileDb'];
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$this->session->set_userdata($user);
		}
		$detail = $this->transaksipenjualandetail->select(['filters_static' => ['penjualan_detail_parent' => $data['penjualan_id']]]);

		$html = '';
		$row = 1;
		foreach ($detail['data'] as $key => $value) {
			$satuan = $this->barangsatuan->select(['filters_static' => ['barang_satuan_parent' => $value['penjualan_detail_barang_id']], 'sort_static' => 'barang_satuan_order']);
			$html .= '<tr class="barang_' . $row . '">
					<td scope="row">
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_id'] . '" name="penjualan_detail_id[' . $row . ']" id="penjualan_detail_id_' . $row . '">						
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_jenis_barang'] . '" name="penjualan_detail_jenis_barang[' . $row . ']" id="penjualan_detail_jenis_barang_' . $row . '">						
						<select class="form-control barang_id" value="' . $value['penjualan_detail_barang_id'] . '" name="penjualan_detail_barang_id[' . $row . ']" id="penjualan_detail_barang_id_' . $row . '" data-id="' . $row . '" onchange="setSatuan(' . $row . ')" style="width: 260px;white-space: nowrap">
								<option value="' . $value['penjualan_detail_barang_id'] . '" selected>' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '</option>
						</select></td>
					<td><select class="form-control" value="' . $value['penjualan_detail_satuan'] . '" name="penjualan_detail_satuan[' . $row . ']" id="penjualan_detail_satuan_' . $row . '" style="width: 100%" onchange="getHarga(' . $row . ')">';

			foreach ($satuan['data'] as $k => $v) {
				// $html .= '<option value="'.$v['barang_satuan_id'].'" data-barang_satuan_harga_beli="'.$v['barang_satuan_harga_beli'].'" data-barang_satuan_konversi="'.$v['barang_satuan_konversi'].'" data-barang_satuan_keuntungan="'.$v['barang_satuan_keuntungan'].'" '.($v['barang_satuan_id'] == $value['pembelian_detail_satuan']?'selected':'').'>'.$v['barang_satuan_kode'].'</option>';
				$html .= '<option value="' . $v['barang_satuan_id'] . '" data-barang_satuan_harga_jual="' . $v['barang_satuan_harga_jual'] . '" data-barang_satuan_harga_beli="' . $v['barang_harga_pokok'] . '" data-barang_satuan_disc="' . $v['barang_satuan_disc'] . '" data-barang_satuan_konversi="' . $v['barang_satuan_konversi'] . '" data-barang_kategori="' . $v['kategori_barang_parent'] . '" ' . ($v['barang_satuan_id'] == $v['penjualan_detail_satuan'] ? 'selected' : '') . '>' . $v['barang_satuan_kode'] . '(' . $v['barang_satuan_konversi'] . ')' . '</option>';
			}
			$html .= '</select>
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_satuan_kode'] . '" name="penjualan_detail_satuan_kode[' . $row . ']" id="penjualan_detail_satuan_kode_' . $row . '" >						
					</td>					
					<td>
						<input class="form-control number" type="text" value="' . $value['penjualan_detail_harga'] . '" name="penjualan_detail_harga[' . $row . ']" id="penjualan_detail_harga_' . $row . '" onchange="countRow(' . $row . ')" readonly="">
					</td>
					<td>
						<input class="form-control qty" type="number" value="' . $value['penjualan_detail_qty'] . '" name="penjualan_detail_qty[' . $row . ']" id="penjualan_detail_qty_' . $row . '" onkeyup="countRow(' . $row . ')" onchange="countRow(' . $row . ')" value="1">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_qty_barang'] . '" name="penjualan_detail_qty_barang[' . $row . ']" id="penjualan_detail_qty_barang_' . $row . '">						
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_harga_beli'] . '" name="penjualan_detail_harga_beli[' . $row . ']" id="penjualan_detail_harga_beli_' . $row . '">
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_hpp'] . '" name="penjualan_detail_hpp[' . $row . ']" id="penjualan_detail_hpp_' . $row . '">					
					</td>
					<td>
						<input class="form-control disc" type="text" value="' . $value['penjualan_detail_potongan_persen'] . '" name="penjualan_detail_potongan_persen[' . $row . ']" id="penjualan_detail_potongan_persen_' . $row . '" onkeyup="countRow(' . $row . ')">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_potongan'] . '" name="penjualan_detail_potongan[' . $row . ']" id="penjualan_detail_potongan_' . $row . '">
					</td>
					<td><input class="form-control number jumlah" type="text" value="' . $value['penjualan_detail_subtotal'] . '" name="penjualan_detail_subtotal[' . $row . ']" id="penjualan_detail_subtotal_' . $row . '" readonly=""></td>
					<td style="text-align: center;"><a href="javascript:;" data-id="' . $row . '" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
		          		<span class="la la-trash"></span> Hapus</a></td>
				</tr>';
			$row++;
		}

		$opsparent = $this->transaksipenjualan->read($data);
		$opsparent['customer'] = $this->customer->read($opsparent['pos_penjualan_customer_id']);
		if ($opsparent['penjualan_bank'] != null) {
			$opsparent['bank'] = $this->rekening->read($opsparent['penjualan_bank']);
		}

		$this->response([
			'parent' => $opsparent,
			'detail' => $detail,
		]);
	}

	function edit_detail_hiburan($value = '')
	{
		$data = varPost();
		if (array_key_exists('mobileDb', $data)) {
			$user['session_db'] = $data['mobileDb'];
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$this->session->set_userdata($user);
		}
		$detail = $this->transaksipenjualandetailhiburan->select(['filters_static' => ['penjualan_detail_parent' => $data['penjualan_id']]]);

		$html = '';
		$row = 1;
		foreach ($detail['data'] as $key => $value) {
			$satuan = $this->barangsatuan->select(['filters_static' => ['barang_satuan_parent' => $value['penjualan_detail_barang_id']], 'sort_static' => 'barang_satuan_order']);
			$html .= '<tr class="barang_' . $row . '">
					<td scope="row">
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_id'] . '" name="penjualan_detail_id[' . $row . ']" id="penjualan_detail_id_' . $row . '">						
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_jenis_barang'] . '" name="penjualan_detail_jenis_barang[' . $row . ']" id="penjualan_detail_jenis_barang_' . $row . '">						
						<select class="form-control barang_id" value="' . $value['penjualan_detail_barang_id'] . '" name="penjualan_detail_barang_id[' . $row . ']" id="penjualan_detail_barang_id_' . $row . '" data-id="' . $row . '" onchange="setSatuan(' . $row . ')" style="width: 260px;white-space: nowrap">
								<option value="' . $value['penjualan_detail_barang_id'] . '" selected>' . $value['barang_kode'] . ' - ' . $value['barang_nama'] . '</option>
						</select></td>
					<td><select class="form-control" value="' . $value['penjualan_detail_satuan'] . '" name="penjualan_detail_satuan[' . $row . ']" id="penjualan_detail_satuan_' . $row . '" style="width: 100%" onchange="getHarga(' . $row . ')">';

			foreach ($satuan['data'] as $k => $v) {
				// $html .= '<option value="'.$v['barang_satuan_id'].'" data-barang_satuan_harga_beli="'.$v['barang_satuan_harga_beli'].'" data-barang_satuan_konversi="'.$v['barang_satuan_konversi'].'" data-barang_satuan_keuntungan="'.$v['barang_satuan_keuntungan'].'" '.($v['barang_satuan_id'] == $value['pembelian_detail_satuan']?'selected':'').'>'.$v['barang_satuan_kode'].'</option>';
				$html .= '<option value="' . $v['barang_satuan_id'] . '" data-barang_satuan_harga_jual="' . $v['barang_satuan_harga_jual'] . '" data-barang_satuan_harga_beli="' . $v['barang_harga_pokok'] . '" data-barang_satuan_disc="' . $v['barang_satuan_disc'] . '" data-barang_satuan_konversi="' . $v['barang_satuan_konversi'] . '" data-barang_kategori="' . $v['kategori_barang_parent'] . '" ' . ($v['barang_satuan_id'] == $v['penjualan_detail_satuan'] ? 'selected' : '') . '>' . $v['barang_satuan_kode'] . '(' . $v['barang_satuan_konversi'] . ')' . '</option>';
			}
			$html .= '</select>
						<input type="hidden" class="form-control" value="' . $value['penjualan_detail_satuan_kode'] . '" name="penjualan_detail_satuan_kode[' . $row . ']" id="penjualan_detail_satuan_kode_' . $row . '" >						
					</td>					
					<td>
						<input class="form-control number" type="text" value="' . $value['penjualan_detail_harga'] . '" name="penjualan_detail_harga[' . $row . ']" id="penjualan_detail_harga_' . $row . '" onchange="countRow(' . $row . ')" readonly="">
					</td>
					<td>
						<input class="form-control qty" type="number" value="' . $value['penjualan_detail_qty'] . '" name="penjualan_detail_qty[' . $row . ']" id="penjualan_detail_qty_' . $row . '" onkeyup="countRow(' . $row . ')" onchange="countRow(' . $row . ')" value="1">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_qty_barang'] . '" name="penjualan_detail_qty_barang[' . $row . ']" id="penjualan_detail_qty_barang_' . $row . '">						
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_harga_beli'] . '" name="penjualan_detail_harga_beli[' . $row . ']" id="penjualan_detail_harga_beli_' . $row . '">
		                <input class="form-control number" type="text" style="display:none"  value="' . $value['penjualan_detail_hpp'] . '" name="penjualan_detail_hpp[' . $row . ']" id="penjualan_detail_hpp_' . $row . '">					
					</td>
					<td>
						<input class="form-control disc" type="text" value="' . $value['penjualan_detail_potongan_persen'] . '" name="penjualan_detail_potongan_persen[' . $row . ']" id="penjualan_detail_potongan_persen_' . $row . '" onkeyup="countRow(' . $row . ')">
						<input class="form-control number" type="hidden" value="' . $value['penjualan_detail_potongan'] . '" name="penjualan_detail_potongan[' . $row . ']" id="penjualan_detail_potongan_' . $row . '">
					</td>
					<td><input class="form-control number jumlah" type="text" value="' . $value['penjualan_detail_subtotal'] . '" name="penjualan_detail_subtotal[' . $row . ']" id="penjualan_detail_subtotal_' . $row . '" readonly=""></td>
					<td style="text-align: center;"><a href="javascript:;" data-id="' . $row . '" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
		          		<span class="la la-trash"></span> Hapus</a></td>
				</tr>';
			$row++;
		}

		$opsparent = $this->transaksipenjualan->read($data);
		$opsparent['customer'] = $this->customer->read($opsparent['pos_penjualan_customer_id']);

		$this->response([
			'parent' => $opsparent,
			'detail' => $detail,
		]);
	}

	public function table_detail_barang()
	{

		$this->response(
			$this->select_dt(varPost(), 'transaksipenjualandetail', 'table', true, varPost('jual'))
		);
	}

	public function table_faktur()
	{
		$filter = varPost('penjualan_customer_id');


		$where = ['penjualan_status_aktif' => null, 'penjualan_metode' => 'K', 'penjualan_bayar_sisa > 0' => null, 'pos_penjualan_customer_id' => $filter];

		$opr = $this->select_dt(varPost(), 'transaksipenjualan', 'table', false, $where);
		// print_r('<pre>');print_r($opr);print_r('</pre>');exit;

		foreach ($opr['aaData'] as $key => $val) {
			$detpenjualan = $this->transaksipenjualandetail->select([
				'filters_static' => [
					'penjualan_detail_parent' => $val['penjualan_id'],
				]
			]);
			$detbarang = [];
			foreach ($detpenjualan['data'] as $dkey => $dval) {
				array_push($detbarang, $dval['barang_nama']);
			}
			$opr['aaData'][$key]['barang_nama'] = implode(', ', $detbarang);
			// print_r('<pre>');print_r();print_r('</pre>');exit;
		}

		$this->response(
			$opr
		);

		// $data['aaData'] = $this->db->query("SELECT * FROM v_pos_penjualan WHERE penjualan_status_aktif IS NULL AND penjualan_metode = 'K' AND penjualan_bayar_sisa > 0 AND pos_penjualan_customer_id = '$filter'")->result_array();
		// $data["iTotalRecords"] = count($data['aaOData']);
		// $data["iTotalDisplayRecords"] = count($data['aaData']);
		// $data["sEcho"] = 0;
		// $data["sColumns"] = "";
		// $this->response($data);
	}


	public function store()
	{
		$data = varPost();

		$db['default']['db_debug'] = TRUE;

		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$user['session_db'] = varPost('mobileDb');
			$this->session->set_userdata($user);
		}

		foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
			$data['penjualan_detail_qty'][$key] = $data['penjualan_detail_qty_barang'][$key];
			$data['penjualan_detail_qty_barang'][$key] = $data['konversi_barang'][$key] * $data['penjualan_detail_qty_barang'][$key];
		}

		// Penyesuaian pembayaran
		if ($data['penjualan_metode'] == 'K') {
			$data['penjualan_total_kredit'] = $data['penjualan_total_grand'];
			$data['penjualan_bayar_sisa'] = $data['penjualan_total_grand'];
			$data['penjualan_total_bayar'] = 0;
		}

		$data['penjualan_kode'] 		= $this->transaksipenjualan->gen_kode_penjualan(true, [
			'penjualan_tanggal' => $data['penjualan_tanggal'] . ' ' . date('H:i:s'),
			'penjualan_metode'  => $data['penjualan_metode'],
		]);

		$data['penjualan_no_antrian'] = $this->transaksipenjualan->gen_nomor_antrian(false);
		$data['penjualan_user_id'] 		= $this->session->userdata('user_id');
		$data['penjualan_user_nama']	= $this->session->userdata('user_nama');
		$data['penjualan_tanggal'] 		= $data['penjualan_tanggal'] . ' ' . date('H:i:s');
		$data['penjualan_aktif'] 		= '1';
		$data['penjualan_updated'] 		= date('Y-m-d H:i:s');
		$data['penjualan_created'] 		= date('Y-m-d H:i:s');
		$data['penjualan_bayar_jumlah'] = 0;
		$data['penjualan_retur'] 		= 0;
		$data['penjualan_first_item'] 	= array_shift(array_values($data['penjualan_detail_barang_id']));
		$error = [];
		$debit = [];
		if ($data['penjualan_metode'] !== 'B') {
			$data['penjualan_bank'] = null;
		} else {
			$debit[$data['penjualan_bank']] = $data['penjualan_total_bayar_bank'];
		}

		if ($data['penjualan_metode'] !== 'K') {
			$data['penjualan_jatuh_tempo'] = null;
		}
		$id = gen_uuid($this->transaksipenjualan->get_table());



		if (in_array("1", $data['penjualan_detail_jenis_barang'])) {
			$data['penjualan_jenis_barang'] = 'K';
		} else {
			$data['penjualan_jenis_barang'] = 'N';
		}

		if (is_array($data['penjualan_total_harga'])) {
			$data['penjualan_total_harga'] = $data['penjualan_total_harga'][0];
		}
		if (empty($data['penjualan_total_potongan_persen'])) {
			$data['penjualan_total_potongan_persen'] = 0;
		}
		$data = cVarNull($data);

		$operation = $this->transaksipenjualan->insert($id, $data, function ($res) use ($data) {
			$user  = $this->session->userdata();

			$query_log_penjualan = $this->dbmp->query("SELECT * FROM log_penjualan_wp where log_penjualan_wp_penjualan_id = '" . $res['id'] . "'");
			$result_cek = $query_log_penjualan->result_array();
			if (count($result_cek) == 0) {
				$dataSend = [
					'log_penjualan_id' => md5(time()),
					'log_penjualan_wp_penjualan_id' => $res['id'],
					'log_penjualan_wp_penjualan_tanggal' => $data['penjualan_tanggal'],
					'log_penjualan_wp_total' => $data['penjualan_total_grand'],
					'log_penjualan_code_store' => $user['toko']['toko_kode'],
					'log_penjualan_wp_penjualan_kode' => $data['penjualan_kode'],
				];

				$this->dbmp->insert(
					'log_penjualan_wp',
					$dataSend
				);
			}

			$detail = [];
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'penjualan_detail_parent' 		=> $res['id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_satuan_kode' 	=> $data['penjualan_detail_satuan_kode'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_harga_beli' 	=> $data['penjualan_detail_harga_beli'][$key],
					'penjualan_detail_hpp' 			=> $data['penjualan_detail_hpp'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_potongan_persen' => $data['penjualan_detail_potongan_persen'][$key],
					'penjualan_detail_potongan' 	=> $data['penjualan_detail_potongan'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_notes' 		=> $data['penjualan_detail_notes'][$key],
					'penjualan_detail_custom_menu' 		=> $data['penjualan_custom_menu'][$key],
					'penjualan_detail_order' 		=> $key,
				];

				$id_detail = gen_uuid($this->transaksipenjualandetail->get_table());
				$det_opr = $this->transaksipenjualandetail->insert($id_detail, $detail);

				if (!$det_opr['success']) $error[] = $det_opr;
				else {
					if ($data['jenis_include_stok'][$key] == 1) {
						$kartu = $this->stokkartu->insert_kartu([
							'kartu_id' 			=> $id_detail,
							'kartu_tanggal' 	=> $dt['penjualan_tanggal'],
							'kartu_barang_id' 	=> $value,
							'kartu_satuan_id' 	=> $data['penjualan_detail_satuan'][$key],
							'kartu_stok_keluar'	=> $data['penjualan_detail_qty_barang'][$key],
							'kartu_stok_masuk'  => 0,
							'kartu_transaksi' 	=> 'Penjualan',
							'kartu_keterangan' 	=> 'On Insert',
							'kartu_harga'			=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_harga_transaksi'	=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_nilai'			=> $data['penjualan_detail_subtotal'][$key],
							'kartu_transaksi_kode' => $dt['penjualan_kode'],
							'kartu_user' 		=> $dt['penjualan_user'],
							'kartu_created_at' 	=> date('Y-m-d H:i:s'),
						], 'J');
						if ($kartu) $error[] = [$kartu, $dt['penjualan_kode'], $value];
					}

					if ($data['jenis_include_stok'][$key] == 2) {
						$this->db->set('barang_aktif', 3);
						$this->db->where('barang_id', $value);
						$this->db->update('pos_barang');
					}
				}
			}
		});
		$operation['error_log'] = $error;
		if ($this->session->userdata('jenis_wp') === 'RESTO') {
			if (isset($data['cetak']) && $data['cetak']) $operation['tprint'] = $this->tprint($operation['id']);
		} else {
			if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id'], 'pdf', true);
		}
		$this->output->set_content_type('application/json');
		$this->response($operation);
	}


	public function store_mobile()
	{
		$data = varPost();

		$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);

		$user = $this->db->get_where('v_user', ['user_email' => $data['user_email']])->row_array();
		$user['code_store'] = explode('_', $data['mobileDb'])[1];
		$this->session->set_userdata($user);


		foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
			$data['penjualan_detail_qty'][$key] = $data['penjualan_detail_qty_barang'][$key];
			$data['penjualan_detail_qty_barang'][$key] = $data['konversi_barang'][$key] * $data['penjualan_detail_qty_barang'][$key];
		}

		// Penyesuaian pembayaran
		if ($data['penjualan_metode'] == 'K') {
			$data['penjualan_total_kredit'] = $data['penjualan_total_grand'];
			$data['penjualan_bayar_sisa'] = $data['penjualan_total_grand'];
			$data['penjualan_total_bayar'] = 0;
		}

		$data['penjualan_tanggal'] 		= $data['penjualan_tanggal'] ?? date('Y-m-d H:i:s');
		$data['penjualan_kode'] 		= $this->transaksipenjualan->gen_kode_penjualan(false, [
			'penjualan_tanggal' => $data['penjualan_tanggal'],
			'penjualan_metode'  => $data['penjualan_metode'],
		]);
		$data['penjualan_no_antrian'] 	= $this->transaksipenjualan->gen_nomor_antrian(true, varPost('mobileDb'));
		$data['penjualan_user_id'] 		= $this->session->userdata('user_id');
		$data['penjualan_user_nama']	= $this->session->userdata('user_nama') ?? $data['penjualan_user_nama'];
		$data['penjualan_aktif'] 		= '1';
		$data['penjualan_updated'] 		= date('Y-m-d H:i:s');
		$data['penjualan_created'] 		= date('Y-m-d H:i:s');
		$data['penjualan_bayar_jumlah'] = 0;
		$data['penjualan_retur'] 		= 0;
		$data['penjualan_first_item'] 	= $data['penjualan_detail_barang_id'][0];

		$error = [];
		$debit = [];
		if ($data['penjualan_metode'] !== 'B') {
			$data['penjualan_bank'] = null;
		} else {
			$debit[$data['penjualan_bank']] = $data['penjualan_total_bayar_tunai'];
		}

		if ($data['penjualan_metode'] !== 'K') {
			$data['penjualan_jatuh_tempo'] = null;
		}

		$id = gen_uuid($this->transaksipenjualan->get_table());



		if (in_array("1", $data['penjualan_detail_jenis_barang'])) {
			$data['penjualan_jenis_barang'] = 'K';
		} else {
			$data['penjualan_jenis_barang'] = 'N';
		}

		$operation = $this->transaksipenjualan->insert_mobile(varPost('mobileDb'), $id, $data, function ($res) use ($data) {

			$user  = $this->session->userdata();
			$dataSend = [
				'log_penjualan_id' => md5(time()),
				'log_penjualan_wp_penjualan_id' => $res['id'],
				'log_penjualan_wp_penjualan_tanggal' => $data['penjualan_tanggal'],
				'log_penjualan_wp_total' => $data['penjualan_total_grand'],
				'log_penjualan_code_store' => $user['code_store']
			];

			$this->dbmp->insert(
				'log_penjualan_wp',
				$dataSend
			);

			$detail = [];
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'penjualan_detail_parent' 		=> $res['id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_satuan_kode' 	=> $data['penjualan_detail_satuan_kode'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_harga_beli' 	=> $data['penjualan_detail_harga_beli'][$key],
					'penjualan_detail_hpp' 			=> $data['penjualan_detail_hpp'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_potongan_persen' => $data['penjualan_detail_potongan_persen'][$key],
					'penjualan_detail_potongan' 	=> $data['penjualan_detail_potongan'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_order' 		=> $key,
					'penjualan_detail_notes' 	=> $data['penjualan_detail_notes'][$key],
					'penjualan_detail_custom_menu' 		=> $data['penjualan_detail_custom_menu'][$key],
				];
				$jenis_barang = [];

				$id_detail = gen_uuid($this->transaksipenjualandetail->get_table());
				$det_opr = $this->transaksipenjualandetail->insert_mobile(varPost('mobileDb'), $id_detail, $detail);

				if (!$det_opr['success']) $error[] = $det_opr;
				else {
					if ($data['jenis_include_stok'][$key] == 1) {
						$kartu = $this->stokkartu->insert_kartu_mobile(varPost('mobileDb'), [
							'kartu_id' 			=> $id_detail,
							'kartu_tanggal' 	=> $dt['penjualan_tanggal'],
							'kartu_barang_id' 	=> $value,
							'kartu_satuan_id' 	=> $data['penjualan_detail_satuan'][$key],
							'kartu_stok_keluar'	=> $data['penjualan_detail_qty_barang'][$key],
							'kartu_stok_masuk'  => 0,
							'kartu_transaksi' 	=> 'Penjualan',
							'kartu_keterangan' 	=> 'On Insert',
							'kartu_harga'			=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_harga_transaksi'	=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_nilai'			=> $data['penjualan_detail_subtotal'][$key],
							'kartu_transaksi_kode' => $dt['penjualan_kode'],
							'kartu_user' 		=> $dt['penjualan_user'],
							'kartu_created_at' 	=> date('Y-m-d H:i:s'),
						], 'J');
						if ($kartu) $error[] = [$kartu, $dt['penjualan_kode'], $value];
					}
				}
			}
		});
		$operation['error_log'] = $error;

		if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id']);
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$error = [];
		$debit = [];

		$db['default']['db_debug'] = TRUE;

		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$user['session_db'] = varPost('mobileDb');
			$this->session->set_userdata($user);
		}

		foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
			$data['penjualan_detail_qty'][$key] = $data['penjualan_detail_qty_barang'][$key];
			$data['penjualan_detail_qty_barang'][$key] = $data['konversi_barang'][$key] * $data['penjualan_detail_qty_barang'][$key];
		}

		// Penyesuaian pembayaran
		if ($data['penjualan_metode'] == 'K') {
			$data['penjualan_total_kredit'] = $data['penjualan_total_grand'];
			$data['penjualan_bayar_sisa'] = $data['penjualan_total_grand'];
			$data['penjualan_total_bayar'] = 0;
		}

		$data['penjualan_updated'] 		= date('Y-m-d H:i:s');

		if ($data['penjualan_metode'] !== 'B') {
			$data['penjualan_bank'] = null;
		} else {
			$debit[$data['penjualan_bank']] = $data['penjualan_total_bayar_bank'];
		}

		if ($data['penjualan_metode'] !== 'K') {
			$data['penjualan_jatuh_tempo'] = null;
		}

		$data['penjualan_total_harga'] = $data['penjualan_total_harga'][0];
		$data = cVarNull($data);

		$query = 'SELECT * from pos_penjualan_detail WHERE penjualan_detail_parent = \'' . $data['penjualan_id'] . '\'';
		$lastDetailId = $this->db->query($query)->result();
		// print_r($lastDetailId[0]->penjualan_detail_id);
		// die;

		$operation = $this->transaksipenjualan->update($data['penjualan_id'], $data, function ($res) use ($data) {
			$user  = $this->session->userdata();

			$query_log = 'SELECT * FROM log_penjualan_wp WHERE log_penjualan_wp_penjualan_id = \'' . $data['penjualan_id'] . '\'';
			$getQueryLog =	$this->dbmp->query($query_log)->result();

			if (!empty($getQueryLog)) {
				foreach ($getQueryLog as $row) {
					// Memeriksa apakah ada baris dengan log_penjualan_wp_penjualan_id tertentu
					if ($row->log_penjualan_wp_penjualan_id == $data['penjualan_id']) {
						$this->dbmp->set('log_penjualan_deleted_at', date('Y-m-d H:i:s'));
						$this->dbmp->where('log_penjualan_wp_penjualan_id', $data['penjualan_id']);
						$this->dbmp->update('log_penjualan_wp');
						break; // Keluar dari loop setelah update dilakukan
					}
				}
			}

			$dataSend = [
				'log_penjualan_id' => md5(time()),
				'log_penjualan_wp_penjualan_id' => $res['id'],
				'log_penjualan_wp_penjualan_tanggal' => $data['penjualan_tanggal'],
				'log_penjualan_wp_total' => $data['penjualan_total_grand'],
				'log_penjualan_code_store' => $user['toko']['toko_kode'],
				'log_penjualan_wp_penjualan_kode' => $data['penjualan_kode'],
			];

			$this->dbmp->insert(
				'log_penjualan_wp',
				$dataSend
			);

			$detail = [];
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'penjualan_detail_parent' 		=> $res['id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_satuan_kode' 	=> $data['penjualan_detail_satuan_kode'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_harga_beli' 	=> $data['penjualan_detail_harga_beli'][$key],
					'penjualan_detail_hpp' 			=> $data['penjualan_detail_hpp'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_potongan_persen' => $data['penjualan_detail_potongan_persen'][$key],
					'penjualan_detail_potongan' 	=> $data['penjualan_detail_potongan'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_notes' 		=> $data['penjualan_detail_notes'][$key],
					'penjualan_detail_custom_menu' 		=> $data['penjualan_custom_menu'][$key],
					'penjualan_detail_order' 		=> $key,
				];

				$idDetail = gen_uuid($this->transaksipenjualandetail->get_table());
				$det_opr = $this->transaksipenjualandetail->insert($idDetail, $detail);

				if (!$det_opr['success']) $error[] = $det_opr;
				else {
					if ($data['jenis_include_stok'][$key] == 1) {
						$stokKeluarBarangUpdate = 0;
						$stokMasukBarangUpdate = 0;
						$checkStokBarangUpdate = $this->transaksipenjualandetail->read([
							'penjualan_detail_id' => $data['penjualan_detail_id'][$key],
							'penjualan_detail_barang_id' => $data['penjualan_detail_barang_id'][$key]
						])['penjualan_detail_qty'];

						if ($checkStokBarangUpdate != $data['penjualan_detail_qty'][$key]) {
							$selisihBarangUpdate = $checkStokBarangUpdate - $data['penjualan_detail_qty'][$key];
							if ($selisihBarangUpdate < 0) {
								$stokKeluarBarangUpdate = $selisihBarangUpdate * -1;
							} else if ($selisihBarangUpdate > 0) {
								$stokMasukBarangUpdate = $selisihBarangUpdate;
							}
						}

						$kartu = $this->stokkartu->insert_kartu([
							'kartu_id' 			=> $data['penjualan_detail_id'][$key],
							'kartu_tanggal' 	=> $dt['penjualan_tanggal'],
							'kartu_barang_id' 	=> $value,
							'kartu_satuan_id' 	=> $data['penjualan_detail_satuan'][$key],
							'kartu_stok_keluar'	=> $stokKeluarBarangUpdate,
							'kartu_stok_masuk'  => $stokMasukBarangUpdate,
							'kartu_transaksi' 	=> 'Penjualran',
							'kartu_keterangan' 	=> 'On Inset',
							'kartu_harga'			=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_harga_transaksi'	=> $data['penjualan_detail_harga_beli'][$key],
							'kartu_nilai'			=> $data['penjualan_detail_subtotal'][$key],
							'kartu_transaksi_kode' => $dt['penjualan_kode'],
							'kartu_user' 		=> $dt['penjualan_user'],
							'kartu_created_at' 	=> date('Y-m-d H:i:s'),
						], 'J');
						if ($kartu) $error[] = [$kartu, $dt['penjualan_kode'], $value];
					}

					if ($data['jenis_include_stok'][$key] == 2) {
						$this->db->set('barang_aktif', 3);
						$this->db->where('barang_id', $value);
						$this->db->update('pos_barang');
					}
				}
			}
		});

		foreach ($lastDetailId as $id) {
			// if (!in_array($id->penjualan_detail_id, $data['penjualan_detail_id'])) {
			// 	$this->stokkartu->insert_kartu([
			// 		'kartu_id' 			=> $id->penjualan_detail_id,
			// 		'kartu_tanggal' 	=> $id->penjualan_detail_tanggal,
			// 		'kartu_barang_id' 	=> $id->penjualan_detail_barang_id,
			// 		'kartu_satuan_id' 	=> $id->penjualan_detail_satuan,
			// 		'kartu_stok_keluar'	=> 0,
			// 		'kartu_stok_masuk'  => $id->penjualan_detail_qty,
			// 		'kartu_transaksi' 	=> 'Penjualran',
			// 		'kartu_keterangan' 	=> 'On Inset',
			// 		'kartu_harga'			=> $id->penjualan_detail_harga_beli,
			// 		'kartu_harga_transaksi'	=> $id->penjualan_detail_harga_beli,
			// 		'kartu_nilai'			=> $id->penjualan_detail_sub_total,
			// 		'kartu_transaksi_kode' => $data['penjualan_kode'],
			// 		'kartu_user' 		=> $data['penjualan_user'],
			// 		'kartu_created_at' 	=> date('Y-m-d H:i:s'),
			// 	], 'J');
			// 	// if ($kartu) $error[] = [$kartu, $dt['penjualan_kode'], $value];
			// }
			$this->db->delete('pos_penjualan_detail', array('penjualan_detail_id' => $id->penjualan_detail_id));
		}

		$operation['error_log'] = $error;
		if ($this->session->userdata('jenis_wp') === 'RESTO') {
			if (isset($data['cetak']) && $data['cetak']) $operation['tprint'] = $this->tprint($operation['id']);
		} else {
			if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id'], 'pdf', true);
		}
		$this->response($operation);
	}

	public function update_old()
	{

		$data = varPost();

		unset($data['penjualan_tanggal']);
		if ($data['penjualan_metode'] !== 'K') {
			$data['penjualan_jatuh_tempo'] = null;
		}

		// Penyesuaian pembayaran
		if ($data['penjualan_metode'] == 'K') {
			$data['penjualan_total_kredit'] = $data['penjualan_total_bayar'];
			$data['penjualan_bayar_sisa'] = $data['penjualan_total_bayar'];
			$data['penjualan_total_bayar'] = 0;
		}

		// $operation = $this->transaksipenjualan->update($data['penjualan_id'], $data, function (&$res) use ($data) {
		$operation = $this->transaksipenjualan->update($data['penjualan_id'], $data, function ($res) use ($data) {
			$detail = $id_detail = [];
			$dt = $res['record'];

			$last_detail = $this->transaksipenjualandetail->select(array('filters_static' => array('penjualan_detail_parent' => $data['penjualan_id']), 'sort_static' => 'penjualan_detail_order asc'))['data'];
			$delete = $last_detail;
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$detail = [
					'penjualan_detail_parent' 		=> $res['record']['penjualan_id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_harga_beli' 	=> $data['penjualan_detail_harga_beli'][$key],
					'penjualan_detail_hpp' 			=> $data['penjualan_detail_hpp'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_order' 		=> $key,
				];

				$kartu = [
					'kartu_id' 				=> $data['penjualan_detail_id'][$key],
					'kartu_tanggal' 		=> $dt['penjualan_tanggal'],
					'kartu_barang_id' 		=> $value,
					'kartu_satuan_id' 		=> $data['penjualan_detail_satuan'][$key],
					'kartu_stok_keluar' 	=> $data['penjualan_detail_qty_barang'][$key],
					'kartu_transaksi' 		=> 'Penjualan',
					//tambahan
					'kartu_harga'			=> $data['penjualan_detail_harga_beli'][$key],
					'kartu_harga_transaksi'	=> ($data['penjualan_detail_subtotal'][$key] / $data['penjualan_detail_qty_barang'][$key]),
					'kartu_nilai'			=> $data['penjualan_detail_subtotal'][$key],
					//end tambahan
					'kartu_transaksi_kode' 	=> $dt['penjualan_kode'],
					'kartu_user' 			=> $dt['penjualan_user'],
					'kartu_created_at' 		=> date('Y-m-d H:i:s'),
					'kartu_keterangan' 		=> 'On Updated',
				];

				foreach ($last_detail as $i => $v) {
					if ($v['penjualan_detail_id'] == $data['penjualan_detail_id'][$key]) unset($delete[$i]);
				}
				$res_detail = $this->transaksipenjualandetail->update($data['penjualan_detail_id'][$key], $detail);

				if (!$res_detail['success']) {
					$res_detail = $this->transaksipenjualandetail->insert(gen_uuid($this->transaksipenjualandetail->get_table()), $detail);
					if ($res_detail['success']) {
						$id_detail[] = $res_detail['id'];
						$kartu['kartu_id'] = $res_detail['id'];
						$kartu['kartu_stok_masuk'] = 0;
						$kartu['kartu_keterangan'] = 'Insert On Updated';
						$kartu = $this->stokkartu->insert_kartu($kartu, 'J');
					}
				} else {
					$id_detail[] = $res_detail['id'];
					$xkartu = $this->stokkartu->update_kartu($kartu, 'J');
				}

				if ($data['penjualan_total_bayar_voucher_khusus'] > 0) {
					$debit['111101'] = $data['penjualan_total_bayar_voucher_khusus'];

					$voucher_khusus = $this->kartusimpanan->update_kartu([
						'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
						'kartu_simpanan_tanggal'		=> date("Y-m-d"),
						'kartu_simpanan_saldo_masuk'	=> 0,
						'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher_khusus'],
						'kartu_simpanan_transaksi'		=> 'Voucher BHR',
						'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
						'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
						'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
						'kartu_simpanan_keterangan'		=> 'On Update',
						'kartu_simpanan_referensi_id'	=> $res['record']['penjualan_id'],
					], 'BHR');
				}
				if ($data['penjualan_total_bayar_voucher'] > 0) {
					$voucher = $this->kartusimpanan->update_kartu([
						'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
						'kartu_simpanan_tanggal'		=> date("Y-m-d"),
						'kartu_simpanan_saldo_masuk'	=> 0,
						'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher'],
						'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
						'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
						'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
						'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
						'kartu_simpanan_keterangan'		=> 'On Update',
						'kartu_simpanan_referensi_id'	=> $res['record']['penjualan_id'],
					], 'V');
				}

				$id = implode(', ', $id_detail);
				$res['id_detail'] = $id;
			}

			foreach ($delete as $n => $value) {
				$del = $this->transaksipenjualandetail->delete($value['penjualan_detail_id']);
				if ($del['success']) {
					$kartu = [
						'kartu_id' 				=> $value['penjualan_detail_id'],
						'kartu_stok_keluar' 	=> 0,
						'kartu_transaksi' 		=> 'Penjualan',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					$this->db->delete('pos_penjualan_detail', array('penjualan_detail_id' => $value['penjualan_detail_id']));
					$this->stokkartu->update_kartu($kartu, 'J');
				}
			}
		});

		if ($operation['success'] == true) {
			if ($data['penjualan_total_bayar_voucher_khusus'] > 0) {
				$debit['111101'] = $data['penjualan_total_bayar_voucher_khusus'];

				$voucher_khusus = $this->kartusimpanan->update_kartu([
					'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher_khusus'],
					'kartu_simpanan_transaksi'		=> 'Voucher BHR',
					'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
					'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
					'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_simpanan_keterangan'		=> 'On Update',
					'kartu_simpanan_referensi_id'	=> $res['record']['penjualan_id'],
				], 'BHR');
			}
			if ($data['penjualan_total_bayar_voucher_lain'] > 0) {
				$voucher_khusus = $this->kartusimpanan->update_kartu([
					'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher_lain'],
					'kartu_simpanan_transaksi'		=> 'Voucher Giveaway',
					'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
					'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
					'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_simpanan_keterangan'		=> 'On Update',
					'kartu_simpanan_referensi_id'	=> $res['record']['penjualan_id'],
				], 'VB');
			}
			if ($data['penjualan_total_bayar_voucher'] > 0) {
				$voucher = $this->kartusimpanan->update_kartu([
					'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher'],
					'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
					'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
					'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
					'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_simpanan_keterangan'		=> 'On Update',
					'kartu_simpanan_referensi_id'	=> $res['record']['penjualan_id'],
				], 'V');
			}
		}
		// if ($operation['success'] == true && $data['penjualan_metode'] == 'K') {
		// 	$d_current = date('d', strtotime($data['penjualan_tanggal']));
		// 	if ($d_current >= 20) {
		// 		$bulan_tertagih = date("Y-m", strtotime("+2 months", strtotime($data['penjualan_tanggal'])));
		// 	} else {
		// 		$bulan_tertagih = date("Y-m", strtotime("+1 months", strtotime($data['penjualan_tanggal'])));
		// 	}

		// 	$ada_pinjaman = $this->pengajuan->read(['pengajuan_no_pinjam' => $data['penjualan_kode'], 'pengajuan_penjualan_id' => $operation['id']]);

		// if (!$ada_pinjaman) {
		// 	$id_pinjam = gen_uuid($this->pengajuan->get_table());
		// 	$kode_pinjam = $this->pengajuan->gen_kode();

		// 	$d_current = date('d', strtotime($data['penjualan_tanggal']));
		// 	if ($d_current >= 20) {
		// 		$bulan_tertagih = date("Y-m", strtotime("+2 months", strtotime($data['penjualan_tanggal'])));
		// 	} else {
		// 		$bulan_tertagih = date("Y-m", strtotime("+1 months", strtotime($data['penjualan_tanggal'])));
		// 	}
		// 	$pinjam = $this->pengajuan->insert($id_pinjam, array(
		// 		'pengajuan_tgl' 			=> $operation['record']['penjualan_tanggal'],
		// 		'pengajuan_tgl_realisasi'   => $operation['record']['penjualan_tanggal'],
		// 		'pengajuan_no' 				=> $kode_pinjam,
		// 		'pengajuan_no_pinjam'		=> $data['penjualan_kode'],
		// 		'pengajuan_anggota' 		=> $operation['record']['penjualan_anggota_id'],
		// 		'pengajuan_jumlah_pinjaman' => $data['penjualan_total_kredit'],
		// 		'pengajuan_tenor' 			=> $data['penjualan_total_cicilan_qty'],
		// 		'pengajuan_jasa' 			=> $data['penjualan_total_jasa'], //test again
		// 		'pengajuan_pokok' 			=> $data['penjualan_total_kredit'],
		// 		'pengajuan_penjualan_id' 	=> $operation['id'],
		// 		'pengajuan_jenis' 			=> 'B',
		// 		'pengajuan_tag_jenis' 		=> $data['penjualan_jenis_potongan'],
		// 		'pengajuan_tag_bulan'		=> $bulan_tertagih,
		// 		'pengajuan_status' 			=> 2,
		// 		'pengajuan_proteksi' 		=> 0,
		// 		'pengajuan_proteksi_nilai'	=> 0,
		// 		'pengajuan_tag_awal'		=> $data['penjualan_kredit_awal'],
		// 		'pengajuan_jatuh_tempo'		=> $data['penjualan_jatuh_tempo'],
		// 		'pengajuan_create_at'		=> date('Y-m-d H:i:s'),
		// 		'pengajuan_create_by'		=> $this->session->userdata('pegawai_id'),
		// 		'pengajuan_aktif'			=> 1,
		// 		'pengajuan_pokok_bulanan'	=> $data['penjualan_total_cicilan'],
		// 		'pengajuan_jasa_bulanan'	=> $data['penjualan_total_jasa_nilai'],
		// 		'pengajuan_sisa_angsuran'	=> $data['penjualan_total_kredit'],
		// 		'pengajuan_tunggakan_jasa'	=> $data['penjualan_total_jasa'],

		// 	));
		// } else {
		// 	$pinjam = $this->pengajuan->update(['pengajuan_penjualan_id' => $operation['id']], array(
		// 		'pengajuan_tgl' 			=> $operation['record']['penjualan_tanggal'],
		// 		'pengajuan_tgl_realisasi'   => $operation['record']['penjualan_tanggal'],
		// 		'pengajuan_no' 				=> $kode_pinjam,
		// 		'pengajuan_no_pinjam'		=> $data['penjualan_kode'],
		// 		'pengajuan_anggota' 		=> $operation['record']['penjualan_anggota_id'],
		// 		'pengajuan_jumlah_pinjaman' => $data['penjualan_total_kredit'],
		// 		'pengajuan_tenor' 			=> $data['penjualan_total_cicilan_qty'],
		// 		'pengajuan_jasa' 			=> $data['penjualan_total_jasa'], //test again
		// 		'pengajuan_pokok' 			=> $data['penjualan_total_kredit'],
		// 		'pengajuan_jenis' 			=> 'B',
		// 		'pengajuan_tag_jenis' 		=> $data['penjualan_jenis_potongan'],
		// 		'pengajuan_tag_bulan'		=> $bulan_tertagih,
		// 		'pengajuan_status' 			=> 2,
		// 		'pengajuan_proteksi' 		=> 0,
		// 		'pengajuan_proteksi_nilai'	=> 0,
		// 		'pengajuan_tag_awal'		=> $data['penjualan_kredit_awal'],
		// 		'pengajuan_jatuh_tempo'		=> $data['penjualan_jatuh_tempo'],
		// 		'pengajuan_create_at'		=> date('Y-m-d H:i:s'),
		// 		'pengajuan_create_by'		=> $this->session->userdata('pegawai_id'),
		// 		'pengajuan_aktif'			=> 1,
		// 		'pengajuan_pokok_bulanan'	=> $data['penjualan_total_cicilan'],
		// 		'pengajuan_jasa_bulanan'	=> $data['penjualan_total_jasa_nilai'],
		// 		'pengajuan_sisa_angsuran'	=> $data['penjualan_total_kredit'],
		// 		'pengajuan_tunggakan_jasa'	=> $data['penjualan_total_jasa'],
		// 	));
		// }

		// $pinjaman = $this->kartupinjaman->update_kartu([
		// 	'kartu_pinjaman_anggota'		=> $operation['record']['penjualan_anggota_id'],
		// 	'kartu_pinjaman_tanggal'		=> $operation['record']['penjualan_tanggal'],
		// 	'kartu_pinjaman_id' 			=> $pinjam['record']['pengajuan_id'],
		// 	'kartu_pinjaman_saldo_pinjam'	=> $data['pengajuan_jumlah_pinjaman'],
		// 	'kartu_pinjaman_transaksi'		=> 'Pencairan Pinjaman Barang',
		// 	'kartu_pinjaman_transaksi_kode'	=> $data['penjualan_kode'],
		// 	'kartu_pinjaman_create_by' 		=> $this->session->userdata('pegawai_id'),
		// 	'kartu_pinjaman_create_at' 		=> date('Y-m-d H:i:s'),
		// 	'kartu_pinjaman_referensi_id'	=> $operation['id']
		// ], 'B');
		// $debit['1131'] = $data['penjualan_total_kredit'];
		// } else {
		// 	$kartu_pinjam = $this->kartupinjaman->read(array(
		// 		'kartu_pinjaman_transaksi_id' => $operation['id'],
		// 		'kartu_pinjaman_transaksi_kode' => $data['penjualan_kode']
		// 	));
		// 	$pengajuan_pinjaman = $this->pengajuan->read(['pengajuan_no_pinjam' => $data['penjualan_kode'], 'pengajuan_penjualan_id' => $operation['id']]);
		// 	if ($kartu_pinjam && $pengajuan_pinjaman) {
		// 		$this->kartupinjaman->delete($kartu_pinjam['kartu_pinjaman_id']);
		// 		$this->pengajuan->delete($pengajuan_pinjaman['pengajuan_id']);
		// 	}
		// }
		if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id']);
		$this->response($operation);
	}

	/*
	 * id string
	 * type string | print, pdf	
	 */
	public function tprint($id, $type = 'print', $return = false)
	{
		$data = varPost();

		// print settings
		$ispajak = 'display: none;';
		$isjasa = 'display: none;';
		if (is_array($data['settings_show'])) {
			foreach ($data['settings_show'] as $key => $val) {
				switch ($val) {
					case 'pajak':
						$ispajak = '';
						break;
					case 'jasa':
						$isjasa = '';
						break;
					default:
						break;
				}
			}
		}

		$jual = $this->db->select('*')
			->where('penjualan_id', $id)
			->from('v_pos_penjualan')->get()->result_array();
		// die(json_encode($jual));

		$html = '';
		if ($jual) {
			$jual = $jual[0];
		}
		$metode_pembayaran = '';
		if ($jual['penjualan_metode'] == 'T') {
			$metode_pembayaran = 'Cash';
		} elseif ($jual['penjualan_metode'] == 'B') {
			$metode_pembayaran = 'Cash';
		} else {
			$metode_pembayaran = 'Kredit';
		}

		$detail = $this->db->select('*')
			->where('penjualan_detail_parent', $id)
			->where('penjualan_detail_retur', null)
			->order_by('penjualan_detail_order', 'asc')
			->from('v_pos_penjualan_detail')->get()->result_array();

		$isBayar = ($jual['penjualan_metode'] == 'T' || $jual['penjualan_metode'] == 'B');

		if ($type === 'pdf') {
			$htmls = $this->html_tprint_pdf($detail, $jual, $metode_pembayaran);

			return createPdf(array(
				'data'          => $htmls,
				'json'          => true,
				'paper_size'    => 'A4',
				'file_name'     => 'BUKTI PEMBELIAN',
				'title'         => 'BUKTI PEMBELIAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
				'return'				=> $return,
			));
		} else {
			$htmls = $this->html_tprint_print($detail, $jual, $metode_pembayaran);


			$this->response(array('tprint' => base64_encode($htmls)));
		}
		return base64_encode($htmls);
	}

	public function html_tprint_print($detail, $jual, $metode_pembayaran)
	{
		$rowData = '';

		foreach ($detail as $key => $value) {


			// convert custom menu
			$arrCustomMenu = explode(',', $value['penjualan_detail_custom_menu']);
			foreach ($arrCustomMenu as $key2 => $value2) {
				$arrCustomMenu[$key2] = $this->db->get_where('pos_custom_menu', ['custom_menu_id' => $value2])->row_array()['custom_menu_nama'];
			}

			$notes =  (!empty($value['penjualan_detail_notes'])) ? 'Catatan : ' . $value['penjualan_detail_notes'] : 'Catatan : -';

			$customMenu =  (!empty(implode(',', $arrCustomMenu))) ? 'Custom : ' . implode(',', $arrCustomMenu) : 'Custom : -';

			$rowData .= '
				<tr>
					<td align="left" class="txt-left">' . $value['barang_nama'] . '<br> <span style="font-size:7px">' . $notes . '</span>
					<br> <span style="font-size:7px">' . $customMenu . '</span>
					</td>
					<td align="center" class="txt-center" style="padding-left: 10px;">' . round($value['penjualan_detail_qty']) . '</td>
					<td align="right" class="txt-right" style="padding-left: 10px;">' . $value['penjualan_detail_harga_beli'] . '</td>
					<td align="right" class="txt-right" style="padding-left: 10px;">' . number_format($value['penjualan_detail_harga_beli'] * $value['penjualan_detail_qty']) . '</td>
				</tr>
			';
		}

		$htmls = '<html>
				<head>
					<title>Cetak Nota</title>
					<style>
						@page { /*size: 58mm; height: 100mm;*/ margin: 0; }
						body.struk { margin: 0; font-size:10px;font-family: monospace;}
						td { font-size:10px; }
						.sheet {
							margin: 0;
							overflow: hidden;
							position: relative;
							box-sizing: border-box;
							page-break-after: always;
						}
						
						/** Paper sizes **/
						body.struk .sheet { width: 58mm; }
						body.struk .sheet { padding: 2mm; }
						
						.txt-left { text-align: left;}
						.txt-center { text-align: center;}
						.txt-right { text-align: right;}
						.txt-middle { vertical-align: middle;}
						.img-middle { margin-top: auto; margin-bottom: auto;}
						.s-py-2 { padding-top: 2px; padding-bottom: 1px }
						
						/** For screen preview **/
						@media screen {
							body.struk { background: #e0e0e0;font-family: monospace; }
							.sheet {
								background: white;
								box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
								margin: 5mm;
							}
						}
						
						/** Fix for Chrome issue #273306 **/
						@media print {
								body.struk { font-family: monospace; }
								body.struk { width: 58mm; text-align: left;}
								body.struk .sheet { padding: 2mm; }
								.txt-left { text-align: left;}
								.txt-center { text-align: center;}
								.txt-right { text-align: right;}
						}
					</style>
				</head>
				<body class="struk" onload="printOut()">
					<section class="sheet">
						<table cellpadding="0" cellspacing="0" style="width:100%">
							<tr>
								<td align="center" class="text-center" style="' . ($this->config->item('struk_is_logo') == 'true' ? '' : 'display: none;') . '"><img src="' . base_url('assets/master/kasir/' . $this->config->item('struk_logo')) . '" style="width: 100px"/></td>
							</tr>
							<tr>
								<td align="center" class="text-center" style="font-weight: 700; ' . ($this->config->item('struk_is_title_show') == 'true' ? '' : 'display: none;') . '">' . $this->session->userdata('toko_nama') . '</td>
							</tr>
							<tr>
								<td align="center" class="text-center">' . $this->config->item('struk_header') . '</td>
							</tr>
							<tr>
								<td style="border-bottom: 1px dashed #000000; padding-bottom: 5px; ' . ($this->config->item('struk_is_antrian') == 'true' ? '' : 'display: none;') . '"></td>
							</tr>
							<tr>
								<td align="center" class="text-center" style="padding-top: 5px; ' . ($this->config->item('struk_is_antrian') == 'true' ? '' : 'display: none;') . '">Antrian ' . $jual['penjualan_no_antrian'] . '</td>
							</tr>
							<tr>
								<td style="border-bottom: 1px dashed #000000; padding-bottom: 5px;"></td>
							</tr>
							<tr>
								<td style="padding-bottom: 5px;"></td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="width:100%">
							<tr>
								<td align="left" class="txt-left">Nota&nbsp;</td>
								<td align="left" class="txt-left">:</td>
								<td align="left" class="txt-left">&nbsp;' . $jual['penjualan_kode'] . '</td>
							</tr>
							<tr>
								<td align="left" class="txt-left">Cust.&nbsp;</td>
								<td align="left" class="txt-left">:</td>
								<td align="left" class="txt-left">&nbsp;' . $jual['customer_nama'] . '</td>
							</tr>
							<tr>
								<td align="left" class="txt-left">Kasir</td>
								<td align="left" class="txt-left">:</td>
								<td align="left" class="txt-left">&nbsp;' . $this->session->userdata('user_nama') . '</td>
							</tr>
							<tr>
								<td align="left" class="txt-left">Tgl.&nbsp;</td>
								<td align="left" class="txt-left">:</td>
								<td align="left" class="txt-left">&nbsp;' . $jual['penjualan_tanggal'] . '</td>
							</tr>
							<tr>
								<td align="left" class="txt-left">Metode</td>
								<td align="left" class="txt-left">:</td>
								<td align="left" class="txt-left">&nbsp;' . $metode_pembayaran . '</td>
							</tr>
							<tr>
								<td style="padding-bottom: 5px;"></td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="width:100%">
								<tr>
										<td align="left" class="txt-left">Item</td>
										<td align="center" class="txt-center" style="padding-left: 10px;">Qty</td>
										<td align="right" class="txt-right" style="padding-left: 10px;">Harga</td>
										<td align="right" class="txt-right" style="padding-left: 10px;">Total</td>
								</tr>
								<tr>
									<td colspan="4" style="border-bottom: 1px dashed #000000; padding-bottom: 5px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="padding-bottom: 5px;"></td>
								</tr>
								' . $rowData . '
								<tr>
									<td colspan="4" style="border-bottom: 1px dashed #000000; padding-bottom: 5px;"></td>
								</tr>
								<tr>
									<td colspan="4" style="padding-bottom: 5px;"></td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Subtotal :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format($jual['penjualan_total_harga']) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Jasa :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format($jual['penjualan_total_harga'] * ($jual['penjualan_jasa'] / 100)) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Diskon :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format(($jual['penjualan_total_harga'] + ($jual['penjualan_total_harga'] * $jual['penjualan_jasa'] / 100)) * ($jual['penjualan_total_potongan_persen'] / 100)) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Pajak :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format(($jual['penjualan_total_harga'] + ($jual['penjualan_total_harga'] * $jual['penjualan_jasa'] / 100)) * ($jual['penjualan_pajak_persen'] / 100)) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Grand Total :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format($jual['penjualan_total_grand']) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Bayar :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format($jual['penjualan_total_bayar']) . '</td>
								</tr>
								<tr>
									<td align="right" class="txt-right" colspan="3">Kembali :&nbsp;</td>
									<td align="right" class="txt-right">' . number_format($jual['penjualan_total_kembalian']) . '</td>
								</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="width:100%">
							<tr>
								<td colspan="2" style="border-bottom: 1px dashed #000000; padding-bottom: 5px;"></td>
							</tr>
							<tr>
								<td colspan="2" style="padding-bottom: 5px;"></td>
							</tr>
							<tr>
								<td align="center" class="txt-center s-py-2" style="' . ($this->config->item('struk_fb') != '' ? '' : 'display: none;') . '"><img src="' . base_url('assets/socmed/fb.png') . '" class="img-middle" style="width: 12px;"/></td>
								<td align="left" class="txt-left s-py-2">' . $this->config->item('struk_fb') . '</td>
							</tr>
							<tr>
								<td align="center" class="txt-center s-py-2" style="' . ($this->config->item('struk_ig') != '' ? '' : 'display: none;') . '"><img src="' . base_url('assets/socmed/ig.png') . '" class="img-middle" style="width: 12px;"/></td>
								<td align="left" class="txt-left s-py-2">' . $this->config->item('struk_ig') . '</td>
							</tr>
							<tr>
								<td align="center" class="txt-center s-py-2" style="' . ($this->config->item('struk_wa') != '' ? '' : 'display: none;') . '"><img src="' . base_url('assets/socmed/wa.png') . '" class="img-middle" style="width: 12px;"/></td>
								<td align="left" class="txt-left s-py-2">' . $this->config->item('struk_wa') . '</td>
							</tr>
							<tr>
								<td align="center" class="txt-center s-py-2" style="' . ($this->config->item('struk_tw') != '' ? '' : 'display: none;') . '"><img src="' . base_url('assets/socmed/tw.png') . '" class="img-middle" style="width: 12px;"/></td>
								<td align="left" class="txt-left s-py-2">' . $this->config->item('struk_tw') . '</td>
							</tr>
							<tr>
								<td align="center" class="txt-center s-py-2" style="' . ($this->config->item('struk_yt') != '' ? '' : 'display: none;') . '"><img src="' . base_url('assets/socmed/yt.png') . '" class="img-middle" style="width: 12px;"/></td>
								<td align="left" class="txt-left s-py-2">' . $this->config->item('struk_yt') . '</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="width:100%">
							<tr>
								<td colspan="2" style="border-bottom: 1px dashed #000000; padding-bottom: 5px;"></td>
							</tr>
							<tr>
								<td style="padding-bottom: 5px;"></td>
							</tr>
							<tr>
								<td align="center" class="txt-center">' . $this->config->item('struk_footer') . '</td>
							</tr>
						</table>
						<br/><br/><br/><br/><br/><p>&nbsp;</p>
						</section>
						<!--<script>
							var lama = 1000;
							t = null;
							function printOut(){
									window.print();
									t = setTimeout("self.close()",lama);
							}
						</script>-->
						</body>
					</html>';
		return $htmls;
	}

	public function html_tprint_pdf($detail, $jual, $metode_pembayaran)
	{
		$rowData = '';
		foreach ($detail as $key => $value) {
			$notes =  (!empty($value['penjualan_detail_notes'])) ? 'Catatan : ' . $value['penjualan_detail_notes'] : '';
			$rowData .= '
				<tr>
					<td align="left" class="left">' . $value['barang_nama'] . '<br> <span style="font-size:7px">' . $notes . '</span></td>
					<td align="center" class="center" style="padding-left: 10px;">' . round($value['penjualan_detail_qty']) . '</td>
					<td align="right" class="right" style="padding-left: 10px;">' . $value['barang_harga'] . '</td>
					<td align="right" class="right" style="padding-left: 10px;">' . number_format($value['barang_harga'] * $value['penjualan_detail_qty']) . '</td>
				</tr>
			';
		}

		if (!empty($jual['penjualan_room_id'])) {
			$roomquery = $this->db->query("SELECT * FROM pos_room where room_id = '{$jual['penjualan_room_id']}'")->row_array();
			if (!empty($roomquery)) {
				$roomhtml = '<tr>
					<th align="right" colspan="3" class="right">Room :</th>room_price
					<th align="right" class="right">' . number_format($roomquery['room_price']) . '</th>
				</tr>';
			}
		}

		$htmls = '<html>
		<head>
			<title>Cetak Invoice</title>
			<style>
				*{
					font-family: Arial, Helvetica, sans-serif;
				}
				*, table, p, li{
					line-height:1.5;
				}

				table{
					border-radius: 25px;
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
					text-align: left;
					padding:2px;
				}

				.right{
					text-align:right;
					padding: 2px;
				}
				.center{
					text-align: center;
					padding: 2px;
				}

				.t-center{
					vertical-align:middle!important;
					text-align:center;
					background-color : #6f7bd9;
				}

				.divider{
					border-right: 1px solid black;
				}

				.laporan td {
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
					te	xt-align:center;
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
			</style>
		</head><body>';

		$htmls .= '<table style="width:100%; border-collapse: collapse;">
				<tr>
					<td class="left">
						<p>' . $this->session->userdata('toko_nama') . '</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> Invoice </h4><br>
					</td>
				</tr>
				<tr>
					<td>Invoice Number : ' . $jual['penjualan_kode'] . '</td>
					<td class="right">Metode : ' . ($metode_pembayaran ? $metode_pembayaran : "-") . '</td>
				</tr>
				<tr>
					<td>Tanggal : ' . ($jual['penjualan_tanggal'] ? date("d/m/Y", strtotime($jual['penjualan_tanggal']))  : "-") . '</td>
					<td class="right">Kasir : ' . ($this->session->userdata('user_nama') ? $this->session->userdata('user_nama') : "-") . '</td>
				</tr>
				<tr>
					<td colspan="2">Cust: ' . ($jual['customer_nama'] ? $jual['customer_nama'] : "-") . '</td>
				</tr>
			</table>
			<br>
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">Item.</th>
					<th class="t-center">Qty / Day</th>
					<th class="t-center">Harga</th>
					<th class="t-center">Total</th>
				</tr>
				' . $rowData . '
				<tr>
					<th align="right" colspan="3" class="right">Subtotal :</th>
					<th align="right" class="right">' . number_format($jual['penjualan_total_harga']) . '</th>
				</tr>
				' . $roomhtml . '
				<tr>
					<th align="right" colspan="3" class="right">Jasa :</th>
					<th align="right" class="right">' . number_format($jual['penjualan_total_harga'] * ($jual['penjualan_jasa'] / 100)) . '</th>
				</tr>
				<tr>
					<th align="right" colspan="3" class="right">Diskon :</th>
					<th align="right" class="right">' . number_format(($jual['penjualan_total_harga'] + ($jual['penjualan_total_harga'] * $jual['penjualan_jasa'] / 100)) * ($jual['penjualan_total_potongan_persen'] / 100)) . '</th>
				</tr>
				<tr>
					<th align="right" colspan="3" class="right">Pajak :</th>
					<th align="right" class="right">' . number_format(($jual['penjualan_total_harga'] + ($jual['penjualan_total_harga'] * $jual['penjualan_jasa'] / 100)) * ($jual['penjualan_pajak_persen'] / 100)) . '</th>
				</tr>
				<tr>
					<th align="right" colspan="3" class="right">Grand Total :</th>
					<th align="right" class="right">' . number_format($jual['penjualan_total_grand']) . '</th>
				</tr>
				<tr>
					<th align="right" colspan="3" class="right">Bayar :</th>
					<th align="right" class="right">' . number_format($jual['penjualan_total_bayar']) . '</th>
				</tr>
				<tr>
					<th align="right" colspan="3" class="right">Kembali :</th>
					<th align="right" class="right">' . number_format($jual['penjualan_total_kembalian']) . '</th>
				</tr>
			</table>
			<table style="width:500px;" class="ttd">
				<tr>
					<td class="top">Dibuat :</td>
					<td class="top">Disetujui :</td>
					<td class="top">Diterima :</td>
				</tr>
				<tr>
					<td class="bottom">' . $this->session->userdata('user_nama') . '</td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table>
		</body>
		</html>';
		return $htmls;
	}

	public function tprint2($id)
	{
		$data = varPost();
		$jual = $this->db->select('*')
			->where('penjualan_id', $id)
			->from('v_pos_penjualan')->get()->result_array();

		$html = '';
		if ($jual) {
			$jual = $jual[0];
			$detail = $this->db->select('*')
				->where('penjualan_detail_parent', $id)
				->order_by('penjualan_detail_order', 'asc')
				->from('v_pos_penjualan_detail')->get()->result_array();
			$nprint = 1;
			if ($jual['penjualan_total_voucher'] > 0) $nprint = 2;
			if ($jual['penjualan_metode'] == 'K') $nprint++;
			$break = '<div style="page-break-after: always;"></div></br></br>';

			$html .= '
                <style>
                @media print {
                	*{
                		font-family: "arial";
                		
                	}
                    .section .print{
                        width: 6cm;

                    }
                    @page {
                        size: 7cm 10in portrait;
                        margin:0;
                    }
                }
                .print table{
                    font-size: 11px;
                }
                .text-left{
                    text-align: left;
                }
                .text-right{
                    text-align: right;
                }
                .print table{
                    width: 100%;
                }
                </style>

                <div class="section print">';
			for ($i = 1; $i <= $nprint; $i++) {
				if ($i > 1) $html .= $break;
				$html .= '<h1 style="font-size:13px;text-align:center;margin-bottom:0">POS PTPIS <br>
							KANTOR --- ---- ---</h1>
							<h2 style="font-size:12px;text-align:center; margin-top:2px">TELEPON (0341) *****</h2>

						<hr style="border-top: 1px dashed black;">
						<h2 style="text-align:center;font-size:13px;">* NOTA ' . ($jual['penjualan_metode'] == 'K' ? 'KREDIT' : 'TUNAI') . ' *</h2>
			            <table>
			                <tbody>
			                    <tr>
			                    	<td style="margin-left:400px;">Tgl</td>
			                    	<td>:</td>
			                    	<td>' . date('d/m/Y', strtotime($jual['penjualan_tanggal'])) . '</td>
			                    	<td>Opt </td>
			                    	<td>:</td>
			                    	<td style="text-transform:uppercase">' . $jual['penjualan_user_nama'] . '</td>
			                    </tr>
			                    <tr>
			                    	<td>Nota</td>
			                    	<td>:</td>
			                    	<td>' . $jual['penjualan_kode'] . '</td>
			                    	<td colspan="4">' . date('H:i:s', strtotime($jual['penjualan_created'])) . '</td>
			                    </tr>
			                </tbody>
			            </table>';
				$html .= '<table>
			            	<hr style="border-top: 1px dashed;margin:0">

			            	';
				$totalpotongan = 0;
				foreach ($detail as $key => $value) {
					$subtotal = $value['penjualan_detail_subtotal'] + $value['penjualan_detail_potongan'];
					$totalpotongan += $value['penjualan_detail_potongan'];
					$html .= '<tr>
			                        <td class="text-left">' . substr($value['barang_nama'], 0, 13) . '</td>
			                        <td class="text-left">' . $value['penjualan_detail_qty_barang'] . '</td>
			                        <td class="text-right">' . number_format($value['barang_harga']) . '</td>
			                        <td class="text-right">' . number_format($value['barang_harga'] * $value['penjualan_detail_qty_barang']) . '</td>
			                    </tr>';
					if ($value['penjualan_detail_potongan']) {
						$html .= '<tr>
			                            <td class="text-right" colspan="3">DISKON :</td>
			                            <td class="text-right">(' . number_format($value['penjualan_detail_potongan']) . ')</td>
			                        </tr>';
					}
				}
				$totalpotongan += $jual['penjualan_total_potongan'];

				$html .= '<table>
			            <hr style="border-top: 1px dashed black;width:200px;" align="right">
			            <tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Subtotal :</td>
			                <td class="text-right">' . number_format($jual['penjualan_total_harga']) . '</td>
							
			            </tr>
						<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Jasa :</td>
			                <td class="text-right">' . number_format($jual['penjualan_jasa']) . '</td>
			            </tr>
						<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">PPN :</td>
			                <td class="text-right">' . number_format(($jual['penjualan_total_harga'] + $jual['penjualan_jasa']) * ($jual['penjualan_pajak_persen'] / 100)) . ' (' . number_format($jual['penjualan_pajak_persen']) . '%)</td>
			            </tr>
						<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Potongan :</td>
			                <td class="text-right">' . number_format(($jual['penjualan_total_harga'] + $jual['penjualan_jasa']) * ($jual['penjualan_total_potongan_persen'] / 100)) . ' (' . number_format($jual['penjualan_total_potongan_persen']) . '%)</td>
			            </tr>
						<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Total  :</td>
			                <td class="text-right">' . number_format($jual['penjualan_total_grand']) . '</td>
			            </tr>';
				$tangota = $jual['penjualan_total_bayar_voucher'] + $jual['penjualan_total_bayar_voucher_khusus'] + $jual['penjualan_total_kredit'] + $jual['penjualan_total_bayar_voucher_lain'];
				if ($jual['anggota_nama'] && $tangota > 0) {
					$html .= '<tr>
			                    <td class="text-right" colspan="3">Titip Belanja :</td>
			                    <td class="text-right">' . number_format($jual['penjualan_total_bayar_voucher']) . '</td>
			                </tr><tr>
			                    <td class="text-right" colspan="3">V. Belanja :</td>
			                    <td class="text-right">' . number_format($jual['penjualan_total_bayar_voucher_khusus']) . '</td>
			                </tr><tr>
			                    <td class="text-right" colspan="3">V. Lain2 :</td>
			                    <td class="text-right">' . number_format($jual['penjualan_total_bayar_voucher_lain']) . '</td>
			                </tr><tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Kredit :</td>
			                <td class="text-right">' . number_format($jual['penjualan_total_kredit']) . '</td>
			            </tr>';
				}
				$html .= '<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Bayar :</td>
			                <td class="text-right">' . number_format($jual['penjualan_total_bayar']) . '</td>
			            </tr>
						<tr>
			                <td class="text-right" colspan="3" style="text-transform: capitalize;">Kembali :</td>
			                <td class="text-right">' . number_format($jual['penjualan_total_kembalian']) . '</td>
			            </tr>';
				$html .= '
			            </table>
			            <hr style="border-top: 1px dashed black;">';
				$html .= '<table>
			                    <tbody>';

				if ($jual['penjualan_metode'] == 'K' && $tangota > 0) {
					$html .= '
			                    <tr>
			                        <td>Jatuh Tempo</td>
			                        <td>:</td>
			                        <td>' . date('d/m/Y', strtotime($jual['penjualan_jatuh_tempo'])) . '</td>
			                    </tr>';
					// $alamat = '';
				}

				if ($jual['anggota_nama'] && $tangota > 0) {
					$html .= '<tr>
			                    <td style="text-transform: capitalize;width:10%">Group</td>
			                    <td>:</td>
			                    <td colspan="4"> (' . $jual['grup_gaji_kode'] . ') ' . $jual['grup_gaji_nama'] . '</td>
			                </tr>
			                <tr>
			                    <td>Alamat</td>
			                    <td>:</td>
			                    <td colspan="4"> ' . $jual['anggota_kota'] . ' </td>
			                </tr>
			                <tr>
			                    <td>NIP</td>
			                    <td>:</td>
			                    <td colspan="4"> ' . $jual['anggota_nip'] . ' </td>
			                </tr>
			            </table>
			            <table>	                    
			                <tbody>
			                    <tr>
			                        <td colspan="4" style="text-transform: capitalize;">Sld. Titipbelanja(' . date('d/m/Y', strtotime($jual['penjualan_tanggal'])) . ')</td>
			                        <td>:</td>
			                        <td colspan="2">' . number_format($jual['anggota_saldo_simp_titipan_belanja']) . ' </td>
			                    </tr>
			                </tbody>
			            </table>
			            <hr style="border-top: 1px dashed black;">';
					$html .= '<table>              
			                    <tr>
			                        <td class="text-left" style="text-transform: capitalize;">' . (($jual['anggota_nama']) ? 'Nasabah :' . $jual['anggota_kode'] : '') . ' </td>
			                        <td class="" style="text-transform: capitalize;text-align:center">' . (($jual['anggota_nama']) ? 'Kasir ' : '') . '</td>
			                    </tr>
			            	<tr>
			            		<td colspan="2"><p></p></td>
			            	</tr>
			                <tr>
			                	<td class="text-left">' . (($jual['anggota_nama']) ? '(' . $jual['anggota_nama'] . ')' : '') . '</td>
			                	<td class="text-right">' . (($jual['anggota_nama']) ? '(' . $jual['penjualan_user_nama'] . ')' : ' ') . '</td>
			                </tr>
			        </table>
			        <hr style="border-top: 1px dashed black;">';
				} else {
					$html .= '</tbody>
			            </table><hr style="border-top: 1px dashed black;">';
				}

				if ($totalpotongan) {
					$html .= '
					    <h2 style="text-align:center;font-size:11px;margin-top:0px;margin-bottom:10px;">Selamat anda hemat sebesar(' . number_format($totalpotongan) . ')</h2>';
				}

				$html .= '
			        	<table>              
			                    <tr>
			                        <td style="font-size:11px!important">*Tidak menerima pengembalian barang</td>
			                    </tr>
			                    <tr>
			                    	<td style="font-size:11px!important">*Terimakasih atas kunjungan anda</td>
			                    </tr>
			            </table>';
			}

			$html .= '</div>';
		}

		if (isset($data['tjson'])) $this->response(array('tprint' => $html));
		return $html;
	}


	public function get_detail()
	{
		$data = varPost();
		$this->response($this->transaksipenjualandetail->select(array('filters_static' => $data, 'sort_static' => 'penjualan_detail_order')));
	}


	public function destroy()
	{
		$data = varPost();
		$lock = $this->transaksipenjualan->read($data['id']);
		if ($lock['penjualan_lock'] !== '1') {
			$operation = $this->transaksipenjualan->delete(varPost('id', varExist($data, $this->transaksipenjualan->get_primary(true))));
			$last_detail = $this->transaksipenjualandetail->select(array('filters_static' => array('penjualan_detail_parent' => $data['id']), 'sort_static' => 'penjualan_detail_order asc'))['data'];
			foreach ($last_detail as $key => $value) {
				$kartu = [
					'kartu_id' 				=> $value['penjualan_detail_id'],
					'kartu_barang_id'		=> $value['penjualan_detail_barang_id'],
					'kartu_stok_keluar'		=> 0,
					'kartu_transaksi' 		=> 'Penjualan',
					'kartu_keterangan' 		=> 'Deleted',
				];
				$this->stokkartu->update_kartu($kartu, 'J');
			}
			$operation = $this->transaksipenjualandetail->delete(array('penjualan_detail_parent' => $data['id']));
			$this->response($operation);
		} else {
			$this->response(['success' => false, 'message' => 'Tidak dapat menghapus data penjualan ini, transaksi telah ditutup!.']);
		}
	}


	public function cetak($value = '')
	{
		if ($value) {
			$data = $this->db->where('penjualan_id', $value)
				->get('v_pos_penjualan_barang')
				->row_array();
			$detail = $this->db->where('penjualan_detail_parent', $value)
				->get('v_pos_penjualan_barang_detail')
				->result_array();
			// print_r(array("data"=>$data,"detail"=> $detail));
			// exit();

			$html = '<style>
				*, table, p, li{
					line-height:1.5;
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

			$html .= '<table style="width:100%;">
				<tr>
					<td class="left">
						<p>UKM MART KPRI EKO KAPTI</p>
						<p>KANTOR REMENAG KAB.MALANG</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI PEMBELIAN BARANG </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['penjualan_tanggal'] ? date("d/m/Y", strtotime($data['penjualan_tanggal']))  : "-") . '</td>
					<td class="right">Supplier : ' . ($data['anggota_kode'] ? $data['supplier_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td>No. Transaksi : ' . ($data['penjualan_faktur'] ? $data['penjualan_faktur'] : "-") . '</td>
					<td class="right">' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Jatuh Tempo: ' . ($data['penjualan_jatuh_tempo'] ? $data['penjualan_jatuh_tempo'] : "-") . '</td>
					<td class="right">' . ($data['supplier_alamat'] ? $data['supplier_alamat'] : "-") . ' / ' . ($data['supplier_telp'] ? $data['supplier_telp'] : "-") . '</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode</th>
					<th class="t-center">Nama Barang</th>
					<th class="t-center">Qty</th>
					<th class="t-center">Harga</th>
					<th class="t-center">Discount</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Netto</th>
					<th class="t-center">H. Jual</th>
					<th class="t-center">($)</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			foreach ($detail as $key => $value) {
				$percentase = 5;
				$hrgJual = $value['penjualan_detail_harga'] + ($percentase / 100 * $value['penjualan_detail_harga']);
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
						<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
						<td>' . ($value['penjualan_detail_qty'] ? $value['penjualan_detail_qty'] : "-") . '</td>
						<td>' . ($value['penjualan_detail_harga'] ? number_format($value['penjualan_detail_harga'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['penjualan_detail_discount'] ? number_format($value['penjualan_detail_discount'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['penjualan_detail_jumlah'] ? number_format($value['penjualan_detail_jumlah'], 2, ',', '.')  : "-") . '</td>
						<td class="divider">' . ($value['penjualan_detail_harga'] ? number_format($value['penjualan_detail_harga'], 2, ',', '.')  : "-") . '</td>
						<td>' . number_format($hrgJual, 2, ',', '.') . '</td>
						<td>' . $percentase . '</td>
					</tr>';
				$totalJml += $value['penjualan_detail_jumlah'];
				$totalQty += $value['penjualan_detail_qty'];
			}


			$html .= '<tr>
					<td colspan="3" class="total">Total</td>
					<td colspan="3" class="total">' . $totalQty . '</td>
					<td colspan="4" class="total">' . number_format($totalJml, 2, ',', '.') . '</td>
				</tr>
			</table>
			<br>
			<br>
			<table style="width:500px;" class="ttd">
				<tr>
					<td class="top">Dibuat :</td>
					<td class="top">Disetujui :</td>
					<td class="top">Diterima :</td>
				</tr>
				<tr>
					<td class="bottom">NURS</td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table></br></br>
			';
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4-L',
				'file_name'     => 'BUKTI PEMBELIAN',
				'title'         => 'BUKTI PEMBELIAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}

	public function shortcutBayar()
	{
		$dcPenjualan = $this->transaksipenjualan->read(varPost());

		$piutangID = md5(time());
		$data = array(
			'pembayaran_piutang_id' => $piutangID,
			'pembayaran_piutang_status' => '1',
			'pembayaran_piutang_kode' => 'PY.' + time(),
			'pembayaran_piutang_tanggal' => date('Y-m-d'),
			'pembayaran_piutang_customer_id' => $dcPenjualan['penjualan_customer_id'],
			'pembayaran_piutang_invoice' => 'INV.' + time(),
			'pembayaran_piutang_keterangan' => 'Pembayaran piutang via shorcut' + date('Y-m-d'),
			'pembayaran_piutang_detail_id' => array(
				"{$piutangID}" => null
			),
			'pembayaran_piutang_detail_penjualan_id' => array(
				"{$piutangID}" => "{$piutangID}"
			),

			'pembayaran_piutang_detail_jatuh_tempo' => array(
				"{$piutangID}" => date('Y-m-d')
			),

			'pembayaran_piutang_detail_tagihan' => array(
				"{$piutangID}" => $dcPenjualan['penjualan_total_kredit']
			),
			'pembayaran_piutang_detail_potongan' => array(
				"{$piutangID}" => $dcPenjualan['penjualan_total_potongan']
			),
			'pembayaran_piutang_detail_sisa' => array(
				"{$piutangID}" => 0
			),
			'pembayaran_piutang_detail_bayar' => array(
				"{$piutangID}" => $dcPenjualan['penjualan_total_kredit']
			),
			'pembayaran_piutang_detail_bayar_last' => array(
				"{$piutangID}" => date('Y-m-d')
			),
			'pembayaran_piutang_tagihan' => $dcPenjualan['penjualan_total_kredit'],
			'pembayaran_piutang_retur' => 0,
			'pembayaran_piutang_potongan' => 0,
			'pembayaran_piutang_sisa' => $dcPenjualan['penjualan_total_kredit'],
			'pembayaran_piutang_bayar' => $dcPenjualan['penjualan_total_kredit'],
			'pembayaran_piutang_detail_pembayaran_id' => array(
				'1' => '',
			),
			'pembayaran_piutang_detail_pembayaran_tanggal' => array(
				'1' => date('Y-m-d'),
			),
			'pembayaran_piutang_detail_pembayaran_cara_bayar' => array(
				'1' => 'Cash',
			),
			'pembayaran_piutang_detail_pembayaran_total' => array(
				'1' => $dcPenjualan['penjualan_total_kredit']
			),
			'cetak_checkbox' => 'cetak',
			'pembayaran_piutang_user' => $this->session->userdata('user_id'),
			'pembayaran_piutang_aktif' => 1,
			'pembayaran_piutang_created_at' => date('Y-m-d H:i:s'),
		);

		// get keperluan update rental
		$dc_barang = $this->db->get_where('v_pos_barang', ['barang_id' => $dcPenjualan['penjualan_first_item']])->row_array();

		if ($dc_barang['jenis_include_stok'] == 2) {
			$this->db->set('barang_aktif', 2);
			$this->db->where('barang_id', $dcPenjualan['penjualan_first_item']);
			$this->db->update('pos_barang');
		}
		$error = [];
		$sales = $this->db->select('sales_id')
			->get_where('pos_sales', array(
				'sales_nama' 		=> $data['pembayaran_piutang_sales'],
				'sales_supplier_id' => $data['pembayaran_piutang_supplier_id'],
			))
			->result_array();
		if (count($sales) < 1) {
			$this->db->insert('pos_sales', array(
				'sales_id' 			=> gen_uuid($this->pembayaran->get_table()),
				'sales_supplier_id' => $data['pembayaran_piutang_supplier_id'],
				'sales_nama' 		=> $data['pembayaran_piutang_sales'],
			));
		}

		// Update sisa baya penjualan
		$this->db->where('penjualan_id', varPost('penjualan_id'));
		$this->db->set('penjualan_total_bayar', $dcPenjualan['penjualan_total_kredit']);
		$this->db->set('penjualan_total_bayar_tunai', $dcPenjualan['penjualan_total_kredit']);
		$this->db->set('penjualan_bayar_sisa', 0);
		$this->db->where('penjualan_id', varPost('penjualan_id'));
		$this->db->update('pos_penjualan');

		$data = cVarNull($data);

		$operation = $this->pembayaran->insert(gen_uuid($this->pembayaran->get_table()), $data, function ($res) use ($data, $dcPenjualan) {

			$detail = [];
			// Multi Payment
			foreach ($data['pembayaran_piutang_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'pembayaran_piutang_detail_pembayaran_id' => $value,
					'pembayaran_piutang_detail_pembayaran_parent' => $res['record']['pembayaran_piutang_id'],
					'pembayaran_piutang_detail_pembayaran_tanggal' => $data['pembayaran_piutang_detail_pembayaran_tanggal'][$key],
					'pembayaran_piutang_detail_pembayaran_cara_bayar' => $data['pembayaran_piutang_detail_pembayaran_cara_bayar'][$key],
					'pembayaran_piutang_detail_pembayaran_total' => $data['pembayaran_piutang_detail_pembayaran_total'][$key],
				];
				$detail_pembayaran = cVarNull($detail_pembayaran);
				$det_opr_pembayaran = $this->multipayment->insert(gen_uuid($this->multipayment->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}


			// handle detail
			foreach ($data['pembayaran_piutang_detail_id'] as $key => $value) {
				$detail = [
					'pembayaran_piutang_detail_parent' => $res['record']['pembayaran_piutang_id'],
					'pembayaran_piutang_detail_penjualan_id' => $data['pembayaran_piutang_detail_penjualan_id'][$key],
					'pembayaran_piutang_detail_jatuh_tempo'	=> $data['pembayaran_piutang_detail_jatuh_tempo'][$key],
					'pembayaran_piutang_detail_tagihan' 	=> $data['pembayaran_piutang_detail_tagihan'][$key],
					'pembayaran_piutang_detail_retur' 		=> $data['pembayaran_piutang_detail_retur'][$key],
					'pembayaran_piutang_detail_potongan' 	=> $data['pembayaran_piutang_detail_potongan'][$key],
					'pembayaran_piutang_detail_sisa' 		=> $data['pembayaran_piutang_detail_sisa'][$key],
					'pembayaran_piutang_detail_bayar' 		=> $data['pembayaran_piutang_detail_bayar'][$key],
				];
				$tag = $data['pembayaran_piutang_detail_tagihan'][$key] - $data['pembayaran_piutang_detail_retur'][$key];
				// bayar tidak boleh melebihi tagihan
				$bayar = intval($data['pembayaran_piutang_detail_bayar'][$key]);
				$sisa = $tag - $bayar;
				$detail = cVarNull($detail);
				$det_opr = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()), $detail);
			}
		});
		$operation['error'] = $error;
		$this->response($operation);
	}
}

/* End of file Transaksipenjualan.php */
/* Location: ./application/modules/Transaksipenjualan/controllers/Transaksipenjualan.php */
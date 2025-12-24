<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array());
		$this->auth = AUTHORIZATION::Auth();
	}

	public function index()
	{
		$data = varGet();
		$userEmail = $this->auth->posUser->pos_user_email;
		$user = $this->db->query("SELECT * from pos_user WHERE user_email = '$userEmail'")->row_array();

		$data['pageNo'] = empty($data['pageNo']) ? 1 : intval($data['pageNo']);
		$data['pageSize'] = empty($data['pageSize']) ? 1 : intval($data['pageSize']);
		$offset = $data['pageNo'] > 1 ? ($data['pageNo'] - 1) * $data['pageSize'] : 0;
		if (!empty($data['startDate']) && !empty($data['endDate'])) {
			$data['startDate'] = date_format(new DateTime($data['startDate']), 'Y-m-d');
			$data['endDate'] = date_format(new DateTime($data['endDate']), 'Y-m-d');
		}

		$column = [
			"penjualan_id",
			"penjualan_tanggal",
			"penjualan_kode",
			"penjualan_total_item",
			"penjualan_total_harga",
			"penjualan_total_grand",
			"penjualan_total_bayar_tunai",
			"penjualan_total_kembalian",
			"penjualan_created",
			"penjualan_user_nama",
			"penjualan_meja_id",
			"pos_penjualan_customer_id",
			"penjualan_jasa",
			"penjualan_pajak_persen",
			"penjualan_platform",
			"penjualan_total_potongan_persen",
			"penjualan_lock",
			"customer_nama",
			"meja_nama",
		];
		$select = implode(",", $column);

		$search = !empty($data['search']) ? " AND penjualan_kode ilike '%$data[search]%'
		OR customer_nama ilike '%$data[search]%'" : '';
		$filterdate = (!empty($data['startDate']) && !empty($data['endDate'])) ? " AND penjualan_tanggal >= '$data[startDate]'
		AND penjualan_tanggal <= '$data[endDate]'" : '';

		$where = '';
		if ($wp_id = $this->auth->posUser->pos_user_wajibpajak_id) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}

		$countpenjualan = $this->db->query("SELECT count(penjualan_id) FROM v_pos_penjualan 
		WHERE penjualan_status_aktif IS NULL
		AND penjualan_user_id = '$user[user_id]'
		$search
		$filterdate
		$where
		AND penjualan_platform = 'Mobile'")->row_array();
		$listpenjualan = $this->db->query("SELECT $select FROM v_pos_penjualan 
		WHERE penjualan_status_aktif IS NULL
		AND penjualan_user_id = '$user[user_id]'
		$search
		$filterdate
		$where
		AND penjualan_platform = 'Mobile'
		ORDER BY penjualan_created DESC
		LIMIT $data[pageSize] OFFSET $offset ")->result_array();

		$arrPenjualanId = array_column($listpenjualan, 'penjualan_id');
		$inParentId = "'" . implode("','", $arrPenjualanId) . "'";

		$detailcolumn = [
			"penjualan_detail_id as id",
			"penjualan_detail_parent",
			"penjualan_detail_barang_id",
			"barang_kode",
			"barang_nama",
			"CONCAT('" . $_ENV['BASE_URL'] . "', barang_thumbnail) as barang_thumbnail",
			"barang_satuan_kode",
			"penjualan_detail_harga_beli as barang_harga",
			"penjualan_detail_qty_barang as barang_quantity"
		];
		$detailselect = implode(",", $detailcolumn);
		$detailpenjualan = $this->db->query("SELECT $detailselect FROM pos_penjualan_detail
		left join pos_barang on barang_id = penjualan_detail_barang_id
		WHERE penjualan_detail_parent IN ($inParentId) $where")
			->result_array();

		foreach ($listpenjualan as $key => $val) {
			$detailfilter = array_values(array_filter($detailpenjualan, function ($item) use ($val) {
				return $item['penjualan_detail_parent'] == $val['penjualan_id'];
			}));
			$listpenjualan[$key]['items'] = $detailfilter;
		}

		return $this->response([
			'status' => true,
			'pageNo' => $data['pageNo'],
			'pageSize' => $data['pageSize'],
			'totalPage' => floor($countpenjualan['count'] / $data['pageSize']),
			'totalSize' => $countpenjualan['count'],
			'data' => $listpenjualan
		]);
	}

	public function detail($id)
	{
		$column = [
			"penjualan_id",
			"penjualan_tanggal",
			"penjualan_kode",
			"penjualan_total_item",
			"penjualan_total_harga",
			"penjualan_total_grand",
			"penjualan_total_bayar_tunai",
			"penjualan_total_kembalian",
			"penjualan_created",
			"penjualan_user_nama",
			"penjualan_meja_id",
			"pos_penjualan_customer_id",
			"penjualan_jasa",
			"penjualan_pajak_persen",
			"penjualan_total_potongan_persen",
			"penjualan_lock",
			"penjualan_platform",
			"customer_nama",
			"meja_nama",
		];
		$where = '';
		if ($wp_id = $this->auth->posUser->pos_user_wajibpajak_id) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}

		$select = implode(",", $column);
		$listpenjualan = $this->db->query("SELECT $select FROM v_pos_penjualan
		WHERE penjualan_status_aktif IS NULL
		AND penjualan_platform = 'Mobile'
		AND penjualan_id = '$id' $where
		ORDER BY penjualan_created DESC")->row_array();

		if (empty($listpenjualan)) {
			return $this->response([
				"status" => false,
				"message" => "Detail transaction not found",
				"data" => []
			], 404);
		}

		$detailcolumn = [
			"penjualan_detail_id as id",
			"penjualan_detail_parent",
			"penjualan_detail_barang_id",
			"barang_kode",
			"barang_nama",
			"CONCAT('" . $_ENV['BASE_URL'] . "', barang_thumbnail) as barang_thumbnail",
			"barang_satuan_kode",
			"penjualan_detail_harga_beli as barang_harga",
			"penjualan_detail_qty_barang as barang_quantity"
		];
		$detailselect = implode(",", $detailcolumn);
		$detailpenjualan = $this->db->query("SELECT $detailselect FROM pos_penjualan_detail
		left join pos_barang on barang_id = penjualan_detail_barang_id
		WHERE penjualan_detail_parent = '$id'")
			->result_array();

		$listpenjualan['items'] = $detailpenjualan;

		return $this->response([
			"status" => true,
			"data" => $listpenjualan
		]);
	}
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stokkartu extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'StokkartuModel' => 'Stokkartu'
		));
	}

	public function index()
	{
		$filter = varPost('filter');
		if (!$filter['kartu_transaksi']) unset($filter['kartu_transaksi']);
		if ($filter['periode'] == 'tanggal') {
			$filter['to_date(cast(kartu_tanggal as TEXT), \'YYYY-MM-DD\') >= \'' . $filter['kartu_tanggal'] . '\''] = null;
			$filter['to_date(cast(kartu_tanggal as TEXT), \'YYYY-MM-DD\') <= \'' . $filter['kartu_tanggal_akhir'] . '\''] = null;
		} else {
			$filter['to_char(kartu_tanggal, \'YYYY-MM\') >='] = $filter['kartu_bulan'];
			$filter['to_char(kartu_tanggal, \'YYYY-MM\') <='] = $filter['kartu_bulan_akhir'];
		}
		unset($filter['periode'], $filter['kartu_tanggal'], $filter['kartu_tanggal_akhir'], $filter['kartu_bulan'], $filter['kartu_bulan_akhir']);

		$this->response(
			$this->select_dt(varPost(), 'Stokkartu', 'table', false, $filter)
		);
		// $this->response(
		// 	$this->db->get('v_pos_kartu_stok')->result_array()
		// );
	}

	public function barang_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id']) ? 'barang_supplier_id = \'' . $data['fdata']['barang_supplier_id'] . '\' AND ' : '';
		// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE ' . $where . ' (barang_nama like \'%' . $data['q'] . '%\' OR barang_kode like \'%' . $data['q'] . '%\') ')->result_array();
		$return = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama, barang_harga, barang_stok, barang_kode as saved FROM v_pos_barang 
		WHERE jenis_include_stok = 1 
		AND ' . $where . ' (barang_nama like \'%' . $data['q'] . '%\' OR barang_kode like \'%' . $data['q'] . '%\') 
		ORDER BY barang_nama 
		LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		// , " (stok: ", barang_stok, ")"
		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 85px;">' . $value['barang_kode'] . '</span> - <span class="detail-barang-select"  style="width: 150px;">' . $value['barang_nama'] . '</span><span class="detail-barang-select" style="width: 70px;">' . number_format($value['barang_harga']) . '</span><span class="detail-barang-select" style="width: 80px;">Stok : ' . $value['barang_stok'] . '</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_nama']
			];
		}
		$this->response(array('items' => $new_return, 'total_count' => $total[0]['total']));
	}
}

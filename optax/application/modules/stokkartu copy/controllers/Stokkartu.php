<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stokkartu extends Base_Controller {

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
		if(!$filter['kartu_transaksi']) unset($filter['kartu_transaksi']);
		if($filter['periode'] == 'tanggal'){
			$filter['DATE_FORMAT(kartu_tanggal, "%Y-%m-%d") >= "'.$filter['kartu_tanggal'].'"'] = null;
			$filter['DATE_FORMAT(kartu_tanggal, "%Y-%m-%d") <= "'.$filter['kartu_tanggal_akhir'].'"'] = null;
		}else{
			$filter['DATE_FORMAT(kartu_tanggal, "%Y-%m") >='] = $filter['kartu_bulan'];
			$filter['DATE_FORMAT(kartu_tanggal, "%Y-%m") <='] = $filter['kartu_bulan_akhir'];
		}
		unset($filter['periode'],$filter['kartu_tanggal'],$filter['kartu_tanggal_akhir'],$filter['kartu_bulan'],$filter['kartu_bulan_akhir']);
		
		$this->response(
			$this->select_dt(varPost(), 'Stokkartu','table', false, $filter)
		);
	}
	
	public function barang_ajax($value='')
	{
		$data = varPost();
		if(strlen($data['q'])>10){
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if(isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id'])?'barang_supplier_id = "'.$data['fdata']['barang_supplier_id'].'" AND ':'';
		$data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';
		$total = $this->db->query('SELECT count(barang_id) total FROM ms_barang WHERE '.$where.' (barang_nama like "'.$data['q'].'%" OR barang_kode like "'.$data['q'].'%") ')->result_array();
		$return = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama, barang_harga, barang_stok, barang_kode as saved FROM v_ms_barang WHERE '.$where.' (barang_nama like "'.$data['q'].'%" OR barang_kode like "'.$data['q'].'%") ORDER BY barang_nama LIMIT '.$data['page'].$data['limit'])->result_array();
		// , " (stok: ", barang_stok, ")"
		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 45px;">'.$value['barang_kode'].'</span><span class="detail-barang-select"  style="width: 320px;">'.$value['barang_nama'].'</span><span class="detail-barang-select" style="width: 100px;">'.number_format($value['barang_harga']).'</span><span class="detail-barang-select" style="width: 65px;">Stok : '.$value['barang_stok'].'</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_nama']
			];
		}
		$this->response(array('items'=>$new_return, 'total_count'=>$total[0]['total']));
	}

}
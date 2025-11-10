<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReturpenjualandetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_retur_penjualan_detail',
				'primary' => 'retur_penjualan_detail_id',
				'fields' => array(
					array('name' => 'retur_penjualan_detail_id'),
					array('name' => 'retur_penjualan_detail_parent'),
					array('name' => 'retur_penjualan_detail_detail_id'),
					array('name' => 'retur_penjualan_detail_barang_id'),
					array('name' => 'retur_penjualan_detail_satuan_id'),
					array('name' => 'retur_penjualan_detail_jual'),
					array('name' => 'retur_penjualan_detail_qty'),
					array('name' => 'retur_penjualan_detail_retur_qty'),
					array('name' => 'retur_penjualan_detail_retur_qty_barang'),
					array('name' => 'retur_penjualan_detail_sisa_qty'),
					array('name' => 'retur_penjualan_detail_harga'),
					array('name' => 'retur_penjualan_detail_jumlah'),
					array('name' => 'retur_penjualan_detail_tanggal'),
					array('name' => 'retur_penjualan_detail_order'),
					array('name' => 'penjualan_detail_qty', 		'view' => true),
					array('name' => 'penjualan_detail_qty_barang', 	'view' => true),
					array('name' => 'barang_kode', 					'view' => true),
					array('name' => 'barang_nama', 					'view' => true),
					array('name' => 'barang_harga', 					'view' => true),
					array('name' => 'satuan_kode', 					'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_retur_penjualan_detail',
			)
		);
		parent::__construct($model);
	}
}

/* End of file TransaksipenjualanModel.php */
/* Location: ./application/modules/penjualan/models/TransaksipenjualanModel.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReturpembeliandetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_retur_pembelian_barang_detail',
				'primary' => 'retur_pembelian_detail_id',
				'fields' => array(
					array('name' => 'retur_pembelian_detail_id'),
					array('name' => 'retur_pembelian_detail_parent'),
					array('name' => 'retur_pembelian_detail_detail_id'),
					array('name' => 'retur_pembelian_detail_barang_id'),
					array('name' => 'retur_pembelian_detail_satuan'),
					array('name' => 'retur_pembelian_detail_satuan_kode'),
					array('name' => 'retur_pembelian_detail_qty'),
					// array('name' => 'retur_pembelian_detail_qty_barang'),
					array('name' => 'retur_pembelian_detail_retur_qty'),
					array('name' => 'retur_pembelian_detail_retur_qty_barang'),
					array('name' => 'retur_pembelian_detail_sisa_qty'),
					// array('name' => 'retur_pembelian_detail_sisa_qty_barang'),
					array('name' => 'retur_pembelian_detail_harga'),
					array('name' => 'retur_pembelian_detail_jumlah'),
					array('name' => 'retur_pembelian_detail_tanggal'),
					array('name' => 'retur_pembelian_detail_order'),
					array('name' => 'barang_kode', 						'view' => true),
					array('name' => 'barang_nama', 						'view' => true),
					array('name' => 'barang_stok', 						'view' => true),
					array('name' => 'barang_satuan_konversi', 			'view' => true),
					array('name' => 'pembelian_detail_harga', 			'view' => true),
					array('name' => 'pembelian_detail_harga_barang', 	'view' => true),
					array('name' => 'pembelian_detail_qty', 			'view' => true),
					array('name' => 'pembelian_detail_qty_barang', 		'view' => true),
					array('name' => 'pembelian_detail_satuan', 			'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_retur_pembelian_detail',
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file TransaksipembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
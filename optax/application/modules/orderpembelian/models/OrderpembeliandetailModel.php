<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderpembeliandetailModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_order_pembelian_detail',
				'primary' => 'order_detail_id',
				'fields' => array(
					array('name' => 'order_detail_id'),
					array('name' => 'order_detail_parent'),
					array('name' => 'order_detail_barang_id'),
					array('name' => 'order_detail_satuan'),
					array('name' => 'order_detail_qty'),
					array('name' => 'order_detail_qty_barang'),
					array('name' => 'order_detail_harga'),
					array('name' => 'order_detail_harga_barang'),
					array('name' => 'order_detail_jumlah'),
					array('name' => 'order_detail_order'),
					array('name' => 'barang_kode', 			'view' => true),
					array('name' => 'barang_nama', 			'view' => true),
					array('name' => 'barang_satuan_kode',	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_order_pembelian_detail',
				'mode' => array(
					'table' => array(
						'pembelian_detail_id',
						'barang_kode',
						'barang_nama',
						'satuan_kode',
						'pembelian_detail_harga',
						'pembelian_detail_qty',
						'pembelian_detail_jumlah',
						'detail_id',
					)
				)
			)

		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file OrderpembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
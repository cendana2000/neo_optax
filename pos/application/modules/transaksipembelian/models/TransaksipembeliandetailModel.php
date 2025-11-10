<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksipembeliandetailModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembelian_barang_detail',
				'primary' => 'pembelian_detail_id',
				'fields' => array(
					array('name' => 'pembelian_detail_id'),
					array('name' => 'pembelian_detail_parent'),
					array('name' => 'pembelian_detail_barang_id'),
					array('name' => 'pembelian_detail_satuan'),
					array('name' => 'pembelian_detail_harga'),
					array('name' => 'pembelian_detail_harga_barang'),
					array('name' => 'pembelian_detail_hpp'),
					array('name' => 'pembelian_detail_konversi'),
					array('name' => 'pembelian_detail_qty'),
					array('name' => 'pembelian_detail_qty_barang'),
					array('name' => 'pembelian_detail_retur_qty'),
					array('name' => 'pembelian_detail_diskon'),
					array('name' => 'pembelian_detail_jumlah'),
					array('name' => 'pembelian_detail_tanggal'),
					array('name' => 'pembelian_detail_order'),
					array('name' => 'barang_kode', 				'view' => true),
					array('name' => 'barang_nama', 				'view' => true),
					array('name' => 'barang_satuan_kode', 		'view' => true),
					array('name' => 'barang_satuan_satuan_id', 	'view' => true),
					array('name' => 'barang_satuan_konversi', 	'view' => true),
					array('name' => 'barang_satuan_harga_beli', 'view' => true),
					array('name' => 'barang_satuan_keuntungan', 'view' => true),
					array('name' => 'detail_id', 				'view' => true),
					array('name' => 'barang_stok', 				'view' => true),
					array('name' => 'current_stok', 				'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembelian_barang_detail',
				'mode' => array(
					'table' => array(
						'pembelian_detail_id',
						'pembelian_detail_barang_id',
						'barang_kode',
						'barang_nama',
						'barang_satuan_kode',
						'pembelian_detail_harga',
						'pembelian_detail_qty',
						'pembelian_detail_qty_barang',
						'pembelian_detail_jumlah',
						'detail_id',
						'barang_stok',
						'current_stok',
						'barang_satuan_konversi',
					)
				)
			)

		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file TransaksipembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
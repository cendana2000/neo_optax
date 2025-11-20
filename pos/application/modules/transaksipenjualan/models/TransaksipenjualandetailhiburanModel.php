<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksipenjualandetailhiburanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_penjualan_detail',
				'primary' => 'penjualan_detail_id',
				'fields' => array(
					array('name' => 'penjualan_detail_id'),
					array('name' => 'penjualan_detail_parent'),
					array('name' => 'penjualan_detail_barang_id'),
					array('name' => 'penjualan_detail_satuan'),
					array('name' => 'penjualan_detail_satuan_kode'),
					array('name' => 'penjualan_detail_harga'),
					array('name' => 'penjualan_detail_harga_beli'),
					array('name' => 'penjualan_detail_hpp'),
					array('name' => 'penjualan_detail_qty'),
					array('name' => 'penjualan_detail_qty_barang'),
					array('name' => 'penjualan_detail_potongan'),
					array('name' => 'penjualan_detail_potongan_persen'),
					array('name' => 'penjualan_detail_subtotal'),
					array('name' => 'penjualan_detail_retur'),
					array('name' => 'penjualan_detail_tanggal'),
					array('name' => 'penjualan_detail_order'),
					array('name' => 'penjualan_detail_retur'),
					array('name' => 'penjualan_detail_notes'),
					array('name' => 'penjualan_detail_custom_menu'),
					array('name' => 'jenis_include_stok', 		'view' => true),
					array('name' => 'barang_kode', 		'view' => true),
					array('name' => 'barang_nama', 		'view' => true),
					array('name' => 'barang_stok',		'view' => true),
					array('name' => 'barang_barcode', 	'view' => true),
					array('name' => 'barang_satuan', 	'view' => true),
					array('name' => 'barang_satuan_opt', 'view' => true),
					array('name' => 'barang_isi', 		'view' => true),
					array('name' => 'barang_harga', 	'view' => true),
					array('name' => 'barang_thumbnail', 	'view' => true),
					array('name' => 'penjualan_id', 	'view' => true),
					array('name' => 'room_id', 	'view' => true),
					array('name' => 'room_code', 	'view' => true),
					array('name' => 'room_name', 	'view' => true),
					array('name' => 'current_stok', 'view' => true),
					array('name' => 'room_price', 	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_penjualan_detail_hiburan',
				'mode' => array(
					'table' => array(
						'penjualan_detail_id',
                        'penjualan_id',
						'barang_kode',
						'barang_nama',
						'barang_harga',
						'barang_thumbnail',
						'penjualan_detail_satuan_kode',
						'penjualan_detail_harga',
						'penjualan_detail_harga_beli',
						'penjualan_detail_qty',
						'penjualan_detail_subtotal',
						'penjualan_detail_qty_barang',
						'penjualan_detail_retur',
						'jenis_include_stok',
						'current_stok',
						'penjualan_detail_notes',
						'penjualan_detail_custom_menu',
                        'room_id',
                        'room_code',
                        'room_name',
					)
				)
			)

		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file TransaksipenjualanModel.php */
/* Location: ./application/modules/penjualan/models/TransaksipenjualanModel.php */
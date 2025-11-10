<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostingsaldodetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_posting_saldo_detail',
				'primary' => 'posting_detail_id',
				'fields' => array(
					array('name' => 'posting_detail_id'),
					array('name' => 'posting_detail_parent'),
					array('name' => 'posting_detail_kategori_id'),
					array('name' => 'posting_detail_kategori_parent'),
					array('name' => 'posting_detail_barang_id'),
					array('name' => 'posting_detail_hpp'),
					array('name' => 'posting_detail_awal_stok'),
					array('name' => 'posting_detail_awal_nilai'),
					// array('name' => 'posting_detail_masuk_stok'),
					// array('name' => 'posting_detail_masuk_nilai'),
					array('name' => 'posting_detail_opname_qty'), 
					array('name' => 'posting_detail_opname_nilai'),
					array('name' => 'posting_detail_akhir_stok'),
					array('name' => 'posting_detail_akhir_nilai'),
					array('name' => 'posting_detail_pembelian_qty'),
					array('name' => 'posting_detail_pembelian_nilai'),
					array('name' => 'posting_detail_retur_pembelian_qty'),
					array('name' => 'posting_detail_retur_pembelian_nilai'),
					array('name' => 'posting_detail_penjualan_qty'),
					array('name' => 'posting_detail_penjualan_nilai'),
					array('name' => 'posting_detail_retur_penjualan_qty'),
					array('name' => 'posting_detail_retur_penjualan_nilai'),
					array('name' => 'posting_detail_bulan'),
					array('name' => 'posting_detail_created'),
					array('name' => 'posting_detail_is_konsinyasi'),
					array('name' => 'posting_detail_satuan'),
					array('name' => 'posting_detail_satuan_kode'),
					array('name' => 'barang_kode', 	'view'=> true),
					array('name' => 'barang_nama', 	'view'=> true),
					array('name' => 'saldo_masuk', 	'view'=> true),
					array('name' => 'saldo_keluar', 'view'=> true),
				)
			),	
			'view' => array(
				'name' => 'v_posting_saldo_detail',
				'mode' => array(
					'table' => [
						'posting_detail_id',
						'barang_kode',
						'barang_nama',
						'posting_detail_awal_stok',
						'saldo_masuk',
						'saldo_keluar',
						'posting_detail_opname_qty',
						'posting_detail_akhir_stok',
						'posting_detail_hpp',
						'posting_detail_akhir_nilai',
					],
				)
			)
		);
		parent::__construct($model);
	}

}

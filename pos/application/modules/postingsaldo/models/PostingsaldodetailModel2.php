<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostingsaldodetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_posting_saldo_detail',
				'primary' => 'posting_id',
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
					// array('name' => 'posting_detail_keluar_stok'),
					// array('name' => 'posting_detail_keluar_nilai'),
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
				)
			),	
			'view' => array(
				'mode' => array(
					'table' => [
						'posting_id',
						'posting_bulan',
						'posting_awal_stok',
						'posting_masuk_stok',
						'posting_keluar_stok',
						'posting_akhir_stok',
						'posting_awal_nilai',
						'posting_masuk_nilai',
						'posting_keluar_nilai',
						'posting_akhir_nilai',
						'posting_created',
					],
				)
			)
		);
		parent::__construct($model);
	}

}

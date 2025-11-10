<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostingsaldoModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_posting_saldo',
				'primary' => 'posting_id',
				'fields' => array(
					array('name' => 'posting_id'),
					array('name' => 'posting_bulan'),
					array('name' => 'posting_awal_qty'),
					array('name' => 'posting_awal_nilai'),
					array('name' => 'posting_masuk_qty'),
					array('name' => 'posting_masuk_nilai'),
					array('name' => 'posting_keluar_qty'),
					array('name' => 'posting_keluar_nilai'),
					array('name' => 'posting_penjualan_qty'),
					array('name' => 'posting_penjualan_nilai'),
					array('name' => 'posting_pembelian_qty'),
					array('name' => 'posting_pembelian_nilai'),
					array('name' => 'posting_pembelian_retur_qty'),
					array('name' => 'posting_pembelian_retur_nilai'),
					array('name' => 'posting_penjualan_retur_qty'),
					array('name' => 'posting_penjualan_retur_nilai'),
					array('name' => 'posting_mutasi_qty'),
					array('name' => 'posting_mutasi_nilai'),
					array('name' => 'posting_stok'),
					array('name' => 'posting_stok_nilai'),
					array('name' => 'posting_hpp'),
					array('name' => 'posting_laba'),
					array('name' => 'posting_persediaan_photobox'),
					array('name' => 'posting_persediaan_photocopy'),
					array('name' => 'posting_created'),
					array('name' => 'posting_aktif'),
				)
			),
			'view' => array(
				// 'name' => 'v_pos_posting_stok',
				'mode' => array(
					'table' => [
						'posting_id',
						'posting_bulan',
						'posting_awal_nilai',
						'posting_masuk_nilai',
						'posting_keluar_nilai',
						'posting_stok_nilai',
						'posting_hpp',
						'posting_laba',
					],
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

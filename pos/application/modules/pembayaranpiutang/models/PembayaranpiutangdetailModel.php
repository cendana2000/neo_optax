<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembayaranpiutangdetailModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembayaran_piutang_detail',
				'primary' => 'pembayaran_piutang_detail_id',
				'fields' => array(
					array('name' => 'pembayaran_piutang_detail_id'),
					array('name' => 'pembayaran_piutang_detail_parent'),
					array('name' => 'pembayaran_piutang_detail_penjualan_id'),
					array('name' => 'pembayaran_piutang_detail_jatuh_tempo'),
					array('name' => 'pembayaran_piutang_detail_tagihan'),
					array('name' => 'pembayaran_piutang_detail_retur'),
					array('name' => 'pembayaran_piutang_detail_potongan'),
					array('name' => 'pembayaran_piutang_detail_bayar'),
					array('name' => 'pembayaran_piutang_detail_penjualan_kode'),
					array('name' => 'penjualan_kode', 'view' => true),
					array('name' => 'penjualan_tanggal', 'view' => true),
					array('name' => 'penjualan_first_item', 'view' => true),
					array('name' => 'barang_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembayaran_piutang_detail',
				'mode' => array(
					'table' => array(
						'pembayaran_piutang_detail_id',
						'pembayaran_piutang_detail_kode',
						'pembayaran_piutang_detail_tanggal',
						'penjualan_faktur',
						'supplier_nama',
						'pembayaran_piutang_detail_tagihan',
						'pembayaran_piutang_detail_retur',
						'pembayaran_piutang_detail_total',
						'pembayaran_piutang_detail_created',
						'penjualan_first_item',
						'barang_nama',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file ReturpenjualanModel.php */
/* Location: ./application/modules/penjualan/models/ReturpenjualanModel.php */
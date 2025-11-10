<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PembayarandetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembayaran_detail',
				'primary' => 'pembayaran_detail_id',
				'fields' => array(
					array('name' => 'pembayaran_detail_id'),
					array('name' => 'pembayaran_detail_parent'),
					array('name' => 'pembayaran_detail_pembelian_id'),
					array('name' => 'pembayaran_detail_jatuh_tempo'),
					array('name' => 'pembayaran_detail_tagihan'),
					array('name' => 'pembayaran_detail_retur'),
					array('name' => 'pembayaran_detail_potongan'),
					array('name' => 'pembayaran_detail_bayar'),
					array('name' => 'pembayaran_detail_pembelian_kode'),
					array('name' => 'pembelian_kode', 'view' => true),
					array('name' => 'pembelian_tanggal', 'view' => true),
					array('name' => 'pembelian_faktur', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembayaran_detail',
				/*'mode' => array(
					'table' => array(
						'pembayaran_detail_id',
						'pembayaran_detail_kode',
						'pembayaran_detail_tanggal',
						'pembelian_faktur',
						'supplier_nama',
						'pembayaran_detail_tagihan',
						'pembayaran_detail_retur',
						'pembayaran_detail_total',
						'pembayaran_detail_created',
					)
				)*/
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

}

/* End of file ReturpembelianModel.php */
/* Location: ./application/modules/pembelian/models/ReturpembelianModel.php */
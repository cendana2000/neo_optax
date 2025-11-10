<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReturpenjualanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_retur_penjualan',
				'primary' => 'retur_penjualan_id',
				'fields' => array(
					array('name' => 'retur_penjualan_id'),
					array('name' => 'retur_penjualan_kode'),
					array('name' => 'retur_penjualan_tanggal'),
					array('name' => 'retur_penjualan_penjualan_id'),
					array('name' => 'retur_penjualan_customer_id'),
					array('name' => 'retur_penjualan_nilai'),
					array('name' => 'retur_penjualan_total_qty'),
					array('name' => 'retur_penjualan_total_item'),
					array('name' => 'retur_penjualan_total'),
					array('name' => 'retur_penjualan_created'),
					array('name' => 'retur_penjualan_user'),
					array('name' => 'retur_penjualan_aktif'),
					array('name' => 'customer_nama', 	'view' => true),
					array('name' => 'penjualan_kode', 	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_retur_penjualan',
				'mode' => array(
					'table' => array(
						'retur_penjualan_id',
						'retur_penjualan_kode',
						'retur_penjualan_tanggal',
						'penjualan_kode',
						'customer_nama',
						'retur_penjualan_nilai',
						'retur_penjualan_total_qty',
						'retur_penjualan_total_item',
						'retur_penjualan_total',
						'retur_penjualan_created',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode_penjualan($value=false)
	{
		return parent::generate_kode(array(
				'pattern'       => 'RTP.{date}.{#}',
	            'date_format'   =>'ym',
	            'field'         =>'retur_penjualan_kode',
	            'index_format'  =>'000',
	            'index_mask'    =>$value
		));
	}
}

/* End of file ReturpenjualanModel.php */
/* Location: ./application/modules/penjualan/models/ReturpenjualanModel.php */
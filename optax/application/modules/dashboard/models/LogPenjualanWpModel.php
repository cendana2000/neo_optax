<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogPenjualanWpModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'log_penjualan_wp',
				'primary' => 'log_penjualan_id',
				'fields' => array(
					array('name' => 'log_penjualan_id'),
					array('name' => 'log_penjualan_wp_penjualan_id'),
					array('name' => 'log_penjualan_wp_penjualan_tanggal'),
					array('name' => 'log_penjualan_wp_total'),
					array('name' => 'log_penjualan_code_store'),
					array('name' => 'toko_nama', 'view' => true),
					array('name' => 'toko_wajibpajak_npwpd', 'view' => true ),
					array('name' => 'wajibpajak_nama_penanggungjawab', 'view' => true),
					array('name' => 'wajibpajak_sektor_id', 'view' => true),
					array('name' => 'wajibpajak_sektor_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pajak_penjualan_wp',
				'mode' => array(
					'table' => array(
						'log_penjualan_id',
						'log_penjualan_wp_penjualan_id',
						'log_penjualan_wp_penjualan_tanggal',
						'log_penjualan_wp_total',
						'log_penjualan_code_store',
						'toko_nama',
						'toko_wajibpajak_npwpd',
						'wajibpajak_nama_penanggungjawab',
						'wajibpajak_sektor_id',
						'wajibpajak_sektor_nama',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
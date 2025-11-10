<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UploadDetailModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_realisasi_detail',
				'primary' => 'realisasi_detail_id',
				'fields' => array(
					array('name' => 'realisasi_detail_id'),
					array('name' => 'realisasi_detail_parent'),
					array('name' => 'realisasi_detail_penjualan_kode'),
					array('name' => 'realisasi_detail_time'),
					array('name' => 'realisasi_detail_sub_total'),
					array('name' => 'realisasi_detail_jasa'),
					array('name' => 'realisasi_detail_pajak'),
					array('name' => 'realisasi_detail_total'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'realisasi_detail_id',
						'realisasi_detail_parent',
						'realisasi_detail_penjualan_kode',
						'realisasi_detail_time',
						'realisasi_detail_sub_total',
						'realisasi_detail_jasa',
						'realisasi_detail_pajak',
						'realisasi_detail_total',
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
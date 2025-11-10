<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UploadModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_realisasi',
				'primary' => 'realisasi_id',
				'fields' => array(
					array('name' => 'realisasi_id'),
					array('name' => 'realisasi_no'),
					array('name' => 'realisasi_wajibpajak_id'),
					array('name' => 'realisasi_wajibpajak_npwpd'),
					array('name' => 'realisasi_tanggal'),
					array('name' => 'realisasi_sub_total'),
					array('name' => 'realisasi_jasa'),
					array('name' => 'realisasi_pajak'),
					array('name' => 'realisasi_total'),
					array('name' => 'realisasi_created_at'),
					array('name' => 'realisasi_created_by'),
					array('name' => 'realisasi_updated_at'),
					array('name' => 'realisasi_updated_by'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'realisasi_id',
						'realisasi_no',
						'realisasi_wajibpajak_id',
						'realisasi_wajibpajak_npwpd',
						'realisasi_tanggal',
						'realisasi_sub_total',
						'realisasi_jasa',
						'realisasi_pajak',
						'realisasi_total',
						'realisasi_created_at',
						'realisasi_created_by',
						'realisasi_updated_at',
						'realisasi_updated_by',
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
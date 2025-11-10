<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_jenis',
				'primary' => 'jenis_id',
				'fields' => array(
					array('name' => 'jenis_id'),
					array('name' => 'jenis_nama'),
					array('name' => 'jenis_tipe'),
					array('name' => 'jenis_parent'),
					array('name' => 'jenis_parent_path'),
					array('name' => 'jenis_tarif'),
					array('name' => 'jenis_keterangan'),
					array('name' => 'jenis_status'),
					array('name' => 'jenis_created_at'),
					array('name' => 'jenis_created_by'),
					array('name' => 'jenis_updated_at'),
					array('name' => 'jenis_updated_by'),
					array('name' => 'jenis_deleted_at'),
					array('name' => 'jenis_deleted_by'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'jenis_id',
						'jenis_nama',
						'jenis_tarif',
						'jenis_keterangan',
						'jenis_tipe',
						'jenis_created_at',
						'jenis_updated_at',
						'jenis_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
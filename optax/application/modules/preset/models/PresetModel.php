<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PresetModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_api_preset',
				'primary' => 'preset_id',
				'fields' => array(
					array('name' => 'preset_id'),
					array('name' => 'preset_nama'),
					array('name' => 'preset_created_at'),
					array('name' => 'preset_updated_at'),
					array('name' => 'preset_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'preset_id',
						'preset_nama',
						'preset_created_at',
						'preset_updated_at',
						'preset_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TargetModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_target',
				'primary' => 'target_id',
				'fields' => array(
					array('name' => 'target_id'),
					array('name' => 'target_tahun'),
					array('name' => 'target_nominal'),
					array('name' => 'target_keterangan'),
					array('name' => 'target_status'),
					array('name' => 'target_created_at'),
					array('name' => 'target_created_by'),
					array('name' => 'target_updated_at'),
					array('name' => 'target_updated_by'),
					array('name' => 'target_deleted_at'),
					array('name' => 'target_deleted_by'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'target_id',
						'target_tahun',
						'target_nominal',
						'target_keterangan',
						'target_status',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file targetanggotaModel.php */
/* Location: ./application/modules/targetanggota/models/targetanggotaModel.php */
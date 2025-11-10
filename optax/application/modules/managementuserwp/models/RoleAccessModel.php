<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoleAccessModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_role_access',
				'primary' => 'role_access_id',
				'fields' => array(
					array('name' => 'role_access_id'),
					array('name' => 'role_access_kode'),
					array('name' => 'role_access_nama'),
					array('name' => 'role_access_status'),
					array('name' => 'role_access_keterangan'),
					array('name' => 'role_access_is_super'),
					array('name' => 'role_access_created_at'),
					array('name' => 'role_access_created_by'),
					array('name' => 'role_access_updated_at'),
					array('name' => 'role_access_updated_by'),
					array('name' => 'role_access_deleted_at'),
					array('name' => 'role_access_deleted_by'),
        )
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'role_access_id',
						'role_access_kode',
						'role_access_nama',
						'role_access_status',
						'role_access_keterangan',
						'role_access_is_super',
						'role_access_created_at',
						'role_access_created_by',
						'role_access_updated_at',
						'role_access_updated_by',
						'role_access_deleted_at',
						'role_access_deleted_by',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file RoleAccessModel.php */
/* Location: ./application/modules/jenisanggota/models/RoleAccessModel.php */
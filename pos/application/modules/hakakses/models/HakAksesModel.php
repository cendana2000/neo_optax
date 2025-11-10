<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HakAksesModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'sys_role',
				'primary' => 'role_id',
				'fields' => array(
					array('name'=>'role_id', 'unique' => true),
					array('name'=>'role_code'),
					array('name'=>'role_name'),
					array('name'=>'role_descriptionn'),
					array('name'=>'role_created_at'),
					array('name'=>'role_created_by'),
					array('name'=>'role_updated_at'),
					array('name'=>'role_updated_by'),
					array('name'=>'role_status'),
				)
			),
			'view' => array(
				'name' => 'sys_role',
				'mode' => array(
					'datatable' => array(
						'role_id',
						'role_code',
						'role_name',
						'role_descriptionn',
						'role_created_at',
						'role_created_by',
						'role_updated_at',
						'role_updated_by',
						'role_status',
					)
				)
			)
		);
		parent::__construct($model);		
	}
}
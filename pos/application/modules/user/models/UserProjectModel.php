<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserProjectModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'user_project',
				'primary' => 'user_project_id',
				'fields' => array(
					array('name' => 'user_project_id', 'unique' => true),
					array('name' => 'user_project_user_id'),
					array('name' => 'user_project_project_id'),
					array('name' => 'project_code', 'view' => true),
					array('name' => 'project_description', 'view' => true),
					array('name' => 'project_start_date', 'view' => true),
					array('name' => 'project_end_date', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_user_project',
				'mode' => array(
					'datatable' => array(
						'user_project_id',
						'project_code',
						'user_project_user_id',
						'user_project_project_id',
						'project_description',
						'project_start_date',
						'project_end_date',
					)
				)
			)
		);
		parent::__construct($model);
	}

}

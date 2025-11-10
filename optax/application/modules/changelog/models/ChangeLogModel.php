<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ChangeLogModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'sys_change_log',
				'primary' => 'change_log_id',
				'fields' => array(
					array('name' => 'change_log_id', 'unique' => true),
					array('name' => 'change_log_number'),
					array('name' => 'change_log_name'),
					array('name' => 'change_log_description'),
					array('name' => 'change_log_change_list'),
					array('name' => 'change_log_change_date'),
					array('name' => 'change_log_create_at'),
					array('name' => 'change_log_create_by'),
					array('name' => 'change_log_which_app'),
				)
			),
			'view' => array(
				'name' => 'sys_change_log',
				'mode' => array(
					'datatable' => array(
						'change_log_id',
						'change_log_number',
						'change_log_name',
						'change_log_description',
						'change_log_change_list',
						'change_log_change_date',
						'change_log_create_at',
						'change_log_create_by',
						'change_log_which_app',
					)
				)
			)
		);
		parent::__construct($model);
	}
}

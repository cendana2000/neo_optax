<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataStatusModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'data_status',
				'primary' => 'data_status_id',
				'fields' => array(
					array('name'=>'data_status_id', 'unique' => true),
					array('name'=>'data_status_code'),
					array('name'=>'data_status_description'),
					array('name'=>'data_status_created_by'),
					array('name'=>'data_status_created_at'),
					array('name'=>'data_status_updated_at'),
					array('name'=>'data_status_deleted_at'),
				)
			),
			'view' => array(
				'name' => 'data_status',
				'mode' => array(
					'datatable' => array(
						'data_status_id',
						'data_status_code',
						'data_status_description',
						'data_status_created_by',
						'data_status_created_at',
						'data_status_updated_at',
						'data_status_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);		
	}
}
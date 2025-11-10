<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigurationModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_config',
				'primary' => 'conf_id',
				'fields' => array(
					array('name'=>'conf_id', 'unique' => true),
					array('name'=>'conf_code'),
					array('name'=>'conf_title'),
					array('name'=>'conf_value'),
					array('name'=>'conf_info'),
					array('name'=>'conf_group'),
					array('name'=>'conf_type'),
					array('name'=>'conf_active'),
				)
			)
		);
		parent::__construct($model);		
	}
}
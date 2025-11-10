<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileDeviceModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'mobile_device',
				'primary' => 'device_id',
				'fields' => array(
					array('name' => 'device_id'),
					array('name' => 'device_metadata'),
					array('name' => 'device_token'),
					array('name' => 'device_last_activity'),
					array('name' => 'device_user_id'),
					array('name' => 'device_user_kode'),
				)
			),
		);
		parent::__construct($model);
		
	}
}

/* End of file MobileDeviceModel.php */
/* Location: ./application/modules/api/models/MobileDeviceModel.php */
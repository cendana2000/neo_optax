<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileLoginModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'sys_user_mobile_login',
				'primary' => 'mobile_login_id',
				'fields' => array(
					array('name' => 'mobile_login_id'),
					array('name' => 'mobile_login_anggota_id'),
					array('name' => 'mobile_login_login_datetime'),
					array('name' => 'mobile_login_limit_datetime'),
					array('name' => 'mobile_login_app_token'),
					array('name' => 'mobile_login_fcm_token'),
					array('name' => 'mobile_login_status'),
				)

			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file MobileLoginModel.php */
/* Location: ./application/modules/api/models/MobileLoginModel.php */
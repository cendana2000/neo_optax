<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserLoginModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'conf_user_login',
				'primary' => 'user_login_id',
				'fields' => array(
					array('name' => 'user_login_id','unique' => true),
					array('name' => 'user_login_user_id'),
					array('name' => 'user_login_fcm'),
					array('name' => 'user_login_datetime_login'),
					array('name' => 'user_login_datetime_logout'),
					array('name' => 'user_login_app'),
					array('name' => 'pemda_id'),
				)
			)
		);
		parent::__construct($model);
	}
}

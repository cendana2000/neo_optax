<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserTokenModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'user_token',
				'primary' => 'user_token_id',
				'fields' => array(
					array('name'=>'user_token_id', 'unique' => true),
					array('name'=>'user_token_user_id'),
					array('name'=>'user_token_token'),
					array('name'=>'user_token_date'),
					array('name'=>'user_token_metadata'),
					array('name'=>'user_token_posisi'),
					array('name'=>'user_token_region'),
				)
			)
		);
		parent::__construct($model);		
	}
}
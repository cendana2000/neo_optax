<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserInboxModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'conf_inbox',
				'primary' => 'inbox_id',
				'fields' => array(
					['name'=>'inbox_id','unique' => true],
					['name'=>'inbox_title'],
					['name'=>'inbox_message'],
					['name'=>'inbox_sender_id'],
					['name'=>'inbox_receipt_id'],
					['name'=>'inbox_datetime'],
					['name'=>'inbox_fcm_token'],
					['name'=>'inbox_status'],
					['name'=>'inbox_receipt_type'],
					['name'=>'inbox_feature_id'],
					['name'=>'inbox_opened'],
					['name'=>'inbox_jenis'],
					['name'=>'inbox_feature_type'],
					['name'=>'inbox_note'],
				)
			),
		);
		parent::__construct($model);
	}
}

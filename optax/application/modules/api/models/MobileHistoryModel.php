<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileHistoryModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'mobile_history',
				'primary' => 'history_id',
				'fields' => array(
					array('name' => 'history_id'),
					array('name' => 'history_anggota_id'),
					array('name' => 'history_datetime'),
					array('name' => 'history_content'),
					array('name' => 'history_type'),
					array('name' => 'history_title'),
					array('name' => 'history_reference_id'),
				)

			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file MobileLoginModel.php */
/* Location: ./application/modules/api/models/MobileLoginModel.php */
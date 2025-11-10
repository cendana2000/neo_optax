<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileMessageInfoModel extends Base_Model {

	public function __construct()
		{

			$model = array(
				'table' => array(
					'name' => 'mobile_message_info',
					'primary' => 'message_info_id',
					'fields' => array(
						array('name' => 'message_info_id'),
						array('name' => 'message_info_id_anggota'),
						array('name' => 'message_info_lastupdate'),
						array('name' => 'message_info_datetime')
					)
				)
			);
			parent::__construct($model);
		}	

}

/* End of file MobileMessageInfoModel.php */
/* Location: ./application/modules/api/models/MobileMessageInfoModel.php */
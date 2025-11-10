<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileActivationModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'mobile_activation',
				'primary' => 'activate_id',
				'fields' => array(
					array('name' => 'activate_id'),
					array('name' => 'activate_kodeanggota'),
					array('name' => 'activate_namaanggota'),
					array('name' => 'activate_pin'),
					array('name' => 'activate_anggota_id'),
					array('name' => 'activate_fcmtoken'),
					array('name' => 'activate_device'),
					array('name' => 'activate_status'),
					array('name' => 'activate_timestamp'),
					array('name' => 'activate_lastupdate'),
					array('name' => 'activate_byname'),
					array('name' => 'activate_useremail'),
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file MobileActivationModel.php */
/* Location: ./application/modules/api/models/MobileActivationModel.php */
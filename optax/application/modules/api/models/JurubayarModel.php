<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JurubayarModel extends Base_Model {

	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ms_juru_bayar',
				'primary' => 'juru_bayar_id',
				'fields' => array(
					array('name' => 'juru_bayar_id'),
					array('name' => 'juru_bayar_nama'),
					array('name' => 'juru_bayar_alamat'),
					array('name' => 'juru_bayar_telp'),
					array('name' => 'juru_bayar_status'),
					array('name' => 'juru_bayar_username'),
					array('name' => 'juru_bayar_pin'),
					array('name' => 'fcmtoken'),
					array('name' => 'juru_bayar_nip'),
					array('name' => 'juru_bayar_deleted_at')
				)
			)
		);
		parent::__construct($model);
	}

}

/* End of file JurubayarModel.php */
/* Location: ./application/modules/api/models/JurubayarModel.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BulanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ms_bulan',
				'primary' => 'bulan_id',
				'fields' => array(
					array('name' => 'bulan_id'),
					array('name' => 'bulan_kode'),
					array('name' => 'bulan_nama'),
				)
			),
		);
		parent::__construct($model);		
	}
}

/* End of file KabupatenModel.php */
/* Location: ./application/modules/anggota/models/KabupatenModel.php */
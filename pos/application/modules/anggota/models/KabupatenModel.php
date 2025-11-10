<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KabupatenModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ref_kabupaten',
				'primary' => 'kab_id',
				'fields' => array(
					array('name' => 'kab_id'),
					array('name' => 'kab_prov_id'),
					array('name' => 'kab_nama'),
					array('name' => 'prov_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_ref_kabupaten'
			)
		);
		parent::__construct($model);		
	}
}

/* End of file KabupatenModel.php */
/* Location: ./application/modules/anggota/models/KabupatenModel.php */
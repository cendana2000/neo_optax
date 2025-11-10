<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KecamatanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ref_kecamatan',
				'primary' => 'kec_id',
				'fields' => array(
					array('name' => 'kec_id'),
					array('name' => 'kec_kab_id'),
					array('name' => 'kec_nama'),
					array('name' => 'kab_nama',  'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_ref_kecamatan'
			)
		);
		parent::__construct($model);		
	}
}

/* End of file KecamatanModel.php */
/* Location: ./application/modules/anggota/models/KecamatanModel.php */
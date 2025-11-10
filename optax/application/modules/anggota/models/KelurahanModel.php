<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KelurahanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ref_kelurahan',
				'primary' => 'kel_id',
				'fields' => array(
					array('name' => 'kel_id'),
					array('name' => 'kel_kec_id'),
					array('name' => 'kel_nama'),
					array('name' => 'kab_nama',  'view' => true),
					array('name' => 'kec_nama',  'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_ref_kelurahan'
			)
		);
		parent::__construct($model);		
	}
}

/* End of file KelurahanModel.php */
/* Location: ./application/modules/anggota/models/KelurahanModel.php */
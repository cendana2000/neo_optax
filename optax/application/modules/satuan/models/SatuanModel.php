<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SatuanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_satuan',
				'primary' => 'satuan_id',
				'fields' => array(
					array('name' => 'satuan_id'),
					array('name' => 'satuan_nama'),
					array('name' => 'satuan_kode'),
					array('name' => 'satuan_created_at'),
					array('name' => 'satuan_updated_at'),
					array('name' => 'satuan_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'satuan_id', 'satuan_kode', 'satuan_nama', 'satuan_nama',				'satuan_created_at',
						'satuan_updated_at',
						'satuan_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
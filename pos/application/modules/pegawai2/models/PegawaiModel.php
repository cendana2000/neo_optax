<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PegawaiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_kasir',
				'primary' => 'kasir_id',
				'fields' => array(
					array('name' => 'kasir_id'),
					array('name' => 'kasir_kode'),
					array('name' => 'kasir_nama'),
					array('name' => 'kasir_ip'),
					array('name' => 'kasir_avatar'),
					array('name' => 'kasir_created_at'),
					array('name' => 'kasir_updated_at'),
					array('name' => 'kasir_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'kasir_id',
						'kasir_kode',
						'kasir_nama',
						'kasir_ip',
						'kasir_avatar',
						'kasir_created_at',
						'kasir_updated_at',
						'kasir_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file kasiranggotaModel.php */
/* Location: ./application/modules/kasiranggota/models/kasiranggotaModel.php */
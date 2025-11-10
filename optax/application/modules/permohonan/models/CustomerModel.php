<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_customer',
				'primary' => 'customer_id',
				'fields' => array(
					array('name' => 'customer_id'),
					array('name' => 'customer_nama'),
					array('name' => 'customer_kode'),
					array('name' => 'customer_membership'),
					array('name' => 'customer_created_at'),
					array('name' => 'customer_updated_at'),
					array('name' => 'customer_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'customer_id', 'customer_kode', 'customer_nama', 'customer_nama', 'customer_membership', 'customer_created_at',
						'customer_updated_at',
						'customer_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file customeranggotaModel.php */
/* Location: ./application/modules/customeranggota/models/customeranggotaModel.php */
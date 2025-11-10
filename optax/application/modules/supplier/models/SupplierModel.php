<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_supplier',
				'primary' => 'supplier_id',
				'fields' => array(
					array('name' => 'supplier_id'),
					array('name' => 'supplier_kode'),
					array('name' => 'supplier_nama'),
					array('name' => 'supplier_alamat'),
					array('name' => 'supplier_telp'),
					array('name' => 'supplier_rekening'),
					array('name' => 'supplier_created_at'),
					array('name' => 'supplier_updated_at'),
					array('name' => 'supplier_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_telp', 'supplier_alamat', 'supplier_nama', 'supplier_created_at',
					'supplier_updated_at',
					'supplier_deleted_at',)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
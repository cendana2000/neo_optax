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
					'table' => array(
						'supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_telp', 'supplier_alamat', 'supplier_nama', 'supplier_created_at',
						'supplier_updated_at',
						'supplier_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
	public function gen_kode($value = false, $kelompok = '')
	{
		return parent::generate_kode(array(
			'pattern'       => $kelompok . '.{#}',
			'field'         => 'supplier_kode',
			'index_format'  => '0000',
			'index_mask'    => $value
		));
	}

	public function checkRelasi($data)
	{
		return $this->db->query("select count(supplier_nama) as res from pos_pembelian_barang join pos_supplier on pembelian_supplier_id = supplier_id  where supplier_id = '{$data['id']}'")->row_array()['res'];
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
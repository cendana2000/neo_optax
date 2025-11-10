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
					array('name' => 'satuan_aktif'),
					array('name' => 'satuan_created_at'),
					array('name' => 'satuan_updated_at'),
					array('name' => 'satuan_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'satuan_id',
						'satuan_kode',
						'satuan_nama',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function checkRelasi($data)
	{
		return $this->db->query("select count(satuan_nama) as res from pos_barang_satuan join pos_satuan on barang_satuan_satuan_id = satuan_id  where satuan_id = '{$data['id']}'")->row_array()['res'];
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
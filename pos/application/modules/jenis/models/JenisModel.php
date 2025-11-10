<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_jenis',
				'primary' => 'jenis_id',
				'fields' => array(
					array('name' => 'jenis_id'),
					array('name' => 'jenis_nama'),
					array('name' => 'jenis_include_stok'),
					array('name' => 'jenis_deskripsi'),
					array('name' => 'jenis_created_at'),
					array('name' => 'jenis_updated_at'),
					array('name' => 'jenis_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'jenis_id',
						'jenis_deskripsi',
						'jenis_nama',
						'jenis_include_stok',
						'jenis_created_at',
						'jenis_updated_at',
						'jenis_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function checkRelasi($data)
	{
		return $this->db->query("select count(jenis_nama) as res from pos_barang join pos_jenis on barang_jenis_barang = jenis_id where jenis_id = '{$data['id']}'")->row_array()['res'];
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
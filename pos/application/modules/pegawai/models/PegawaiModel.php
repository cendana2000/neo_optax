<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PegawaiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pegawai',
				'primary' => 'pegawai_id',
				'fields' => array(
					array('name' => 'pegawai_id'),
					array('name' => 'pegawai_nik'),
					array('name' => 'pegawai_nama'),
					array('name' => 'pegawai_agama'),
					array('name' => 'pegawai_alamat'),
					array('name' => 'pegawai_hp'),
					array('name' => 'pegawai_jk'),
					array('name' => 'pegawai_jabatan'),
					array('name' => 'pegawai_is_aktif'),
					array('name' => 'pegawai_deleted_at'),
				)
			),
			'view' => array(
				// 'name' => 'v_sys_user',
				'mode' => array(
					'table' => array(
						'pegawai_id',
						'pegawai_nik',
						'pegawai_nama',
						'pegawai_jk',
						'pegawai_jabatan',
						'pegawai_is_aktif',
						'pegawai_deleted_at'
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file PegawaiModel.php */
/* Location: ./application/modules/Pegawai/models/PegawaiModel.php */
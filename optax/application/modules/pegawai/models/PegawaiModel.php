<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PegawaiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_pegawai',
				'primary' => 'pegawai_id',
				'fields' => array(
					array('name' => 'pegawai_id'),
					array('name' => 'pegawai_nip'),
					array('name' => 'pegawai_nama'),
					array('name' => 'pegawai_alamat'),
					array('name' => 'pegawai_hp'),
					array('name' => 'pegawai_jk'),
					array('name' => 'pegawai_jabatan'),
					array('name' => 'pegawai_role_access_id'),
					array('name' => 'pegawai_email'),
					array('name' => 'pegawai_password'),
					array('name' => 'pegawai_last_change_password'),
					array('name' => 'pegawai_status'),
					array('name' => 'pegawai_foto'),
					array('name' => 'pegawai_created_at'),
					array('name' => 'pegawai_created_by'),
					array('name' => 'pegawai_upated_at'),
					array('name' => 'pegawai_upated_by'),
					array('name' => 'pegawai_deleted_at'),
					array('name' => 'pegawai_deleted_by'),
					array('name' => 'pemda_id'),
				)
			),
			'view' => array(
				// 'name' => 'v_sys_user',
				'mode' => array(
					'table' => array(
						'pegawai_id',
						'pegawai_nip',
						'pegawai_nama',
						'pegawai_jk',
						'pegawai_jabatan',
						'pegawai_status',
						'pegawai_deleted_at'
					),
					'datatable' => array(
						'pegawai_id',
						'pegawai_nip',
						'pegawai_nama',
						'pegawai_jk',
						'pegawai_jabatan',
						'pegawai_status',
						'pegawai_email',
						'pegawai_hp',
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
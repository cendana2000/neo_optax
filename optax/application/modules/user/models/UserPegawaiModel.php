<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserPegawaiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'v_pajak_pegawai',
				'primary' => 'pegawai_id',
				'fields' => array(
					array('name' => 'pegawai_id'),
					array('name' => 'pegawai_nip'),
					array('name' => 'pegawai_nama'),
					array('name' => 'pegawai_alamat'),
					array('name' => 'pegawai_role_access_id'),
					array('name' => 'pegawai_email'),
					array('name' => 'pegawai_password'),
					array('name' => 'pegawai_status'),
					array('name' => 'pegawai_foto'),
					array('name' => 'pemda_nama'),
					array('name' => 'role_access_kode'),
					array('name' => 'kabkota_nama'),
				)
			),
			'view' => array(
				'name' => 'v_pajak_pegawai',
				'mode' => array(
					'table' => array(
						'pegawai_id',
						'pegawai_nip',
						'pegawai_nama',
						'pegawai_status',
						'pegawai_alamat',
						'pegawai_role_access_id',
						'pegawai_email',
						'pegawai_password',
						'pegawai_foto',
						'pemda_nama',
						'role_access_kode',
						'kabkota_nama'
					),
					'datatable' => array(
						'pegawai_id',
						'pegawai_nip',
						'pegawai_nama',
						'pegawai_status',
						'pegawai_alamat',
						'pegawai_role_access_id',
						'pegawai_email',
						'pegawai_password',
						'pegawai_foto',
						'pemda_nama',
						'role_access_kode',
						'kabkota_nama'
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
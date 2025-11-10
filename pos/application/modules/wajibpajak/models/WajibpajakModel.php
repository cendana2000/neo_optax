<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WajibpajakModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_wajibpajak',
				'primary' => 'wajibpajak_id',
				'fields' => array(
					array('name' => 'wajibpajak_id'),
					array('name' => 'wajibpajak_npwpd'),
					array('name' => 'wajibpajak_nama_penanggungjawab'),
					array('name' => 'wajibpajak_sektor_id'),
					array('name' => 'wajibpajak_sektor_nama'),
					array('name' => 'wajibpajak_nama'),
					array('name' => 'wajibpajak_alamat'),
					array('name' => 'wajibpajak_wp_id'),
					array('name' => 'wajibpajak_email'),
					array('name' => 'wajibpajak_telp'),
					array('name' => 'wajibpajak_password'),
					array('name' => 'wajibpajak_created_at'),
					array('name' => 'wajibpajak_created_by'),
					array('name' => 'wajibpajak_updated_at'),
					array('name' => 'wajibpajak_updated_by'),
					array('name' => 'wajibpajak_deleted_at'),
					array('name' => 'wajibpajak_deleted_by'),
				)
			),
			'view' => array(
				'mode' => array(
					
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */          
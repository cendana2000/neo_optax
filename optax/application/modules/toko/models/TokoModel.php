<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TokoModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_toko',
				'primary' => 'toko_id',
				'fields' => array(
					array('name' => 'toko_id'),
					array('name' => 'toko_kode'),
					array('name' => 'toko_nama'),
					array('name' => 'toko_wajibpajak_id'),
					array('name' => 'toko_wajibpajak_npwpd'),
					array('name' => 'toko_logo'),
					array('name' => 'toko_registered_at'),
					array('name' => 'toko_verified_at'),
					array('name' => 'toko_verified_by'),
					array('name' => 'toko_status'),
					array('name' => 'wajibpajak_nama',          'view' => true),
					array('name' => 'wajibpajak_npwpd',         'view' => true),
					array('name' => 'wajibpajak_sektor_nama',   'view' => true),
					array('name' => 'wajibpajak_nama_penanggungjawab',	'view' => true),
					array('name' => 'wajibpajak_sektor_nama',         	'view' => true),
					array('name' => 'wajibpajak_alamat',   				'view' => true),
					array('name' => 'wajibpajak_email',  				'view' => true),
					array('name' => 'wajibpajak_telp',  				'view' => true),
					array('name' => 'jenis_nama',  				'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pajak_toko',
				'mode' => array(
					'table' => array(
						'toko_id',
						'wajibpajak_npwpd',
						'wajibpajak_nama',
						'toko_kode',
						'toko_registered_at',
						'toko_status',
						'wajibpajak_sektor_nama',
						'jenis_nama',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
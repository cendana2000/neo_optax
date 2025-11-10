<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KonfigurasiModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'sys_conf',
				'primary' => 'konfigurasi_id',
				'fields' => array(
					array('name' => 'konfigurasi_id'),
					array('name' => 'konfigurasi_register_kode'),
					array('name' => 'konfigurasi_jasa_pinjaman'),
					array('name' => 'konfigurasi_gudang_id'),
					array('name' => 'konfigurasi_perusahaan_nama'),
					array('name' => 'konfigurasi_perusahaan_alamat'),
					array('name' => 'konfigurasi_perusahaan_telp'),
					array('name' => 'konfigurasi_jasa_msk'),
					array('name' => 'konfigurasi_jasa_tht'),
					array('name' => 'konfigurasi_jasa_swk'),
					array('name' => 'konfigurasi_jasa_simp_khusus'),
					array('name' => 'konfigurasi_updated'),
					array('name' => 'konfigurasi_user'),
					array('name' => 'konfigurasi_user_nama'),
					array('name' => 'konfigurasi_jml_talangan'),
					array('name' => 'konfigurasi_jml_tenor'),
					array('name' => 'konfigurasi_jml_porsi'),
					array('name' => 'konfigurasi_nama_bank'),
					array('name' => 'konfigurasi_rek_bank'),
				)
			),
		);
		parent::__construct($model);
		//Do your magic here
	}

}

/* End of file KonfigurasiModel.php */
/* Location: ./application/modules/penjualan/models/KonfigurasiModel.php */
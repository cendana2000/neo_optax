<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JurnalDetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ak_jurnal_umum_detail',
				'primary' => 'jurnal_umum_detail_id',
				'fields' => array(
					array('name' => 'jurnal_umum_detail_id'),
					array('name' => 'jurnal_umum_detail_jurnal_umum'),
					array('name' => 'jurnal_umum_detail_company'),
					array('name' => 'jurnal_umum_detail_uraian'),
					array('name' => 'jurnal_umum_detail_akun'),
					array('name' => 'jurnal_umum_detail_tipe'),
					array('name' => 'jurnal_umum_detail_total'),
					array('name' => 'jurnal_umum_detail_debit'),
					array('name' => 'jurnal_umum_detail_kredit'),
					array('name' => 'jurnal_umum_detail_lawan_transaksi'),
					array('name' => 'jurnal_umum_detail_no'),

					array('name' => 'akun_kode',	'map'=> 'jurnal_umum_detail_akun_kode',		'view' => true),
					array('name' => 'akun_is_kas_bank', 'view' => true),
					array('name' => 'akun_nama',	'map'=> 'jurnal_umum_detail_akun_nama',		'view' => true),
					array('name' => 'lawan_transaksi_nama',	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_ak_jurnal_umum_detail',
				'mode' => array(
					'datatable' => array('jurnal_umum_detail_jurnal_umum','jurnal_umum_detail_uraian','jurnal_umum_detail_akun','jurnal_umum_detail_tipe','jurnal_umum_detail_total')
				)
			)
		);
		parent::__construct($model);		
	}
	
}

/* End of file agamaModel.php */
/* Location: .//X/rsmh/app/modules/agama/models/agamaModel.php */
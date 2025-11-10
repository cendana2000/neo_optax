<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembayaranModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembayaran',
				'primary' => 'pembayaran_id',
				'fields' => array(
					array('name' => 'pembayaran_id'),
					array('name' => 'pembayaran_kode'),
					array('name' => 'pembayaran_tanggal'),
					array('name' => 'pembayaran_supplier_id'),
					array('name' => 'pembayaran_tagihan'),
					array('name' => 'pembayaran_retur'),
					array('name' => 'pembayaran_potongan'),
					array('name' => 'pembayaran_sisa'),
					array('name' => 'pembayaran_bayar'),
					array('name' => 'pembayaran_akun_id'),
					array('name' => 'pembayaran_referensi'),
					array('name' => 'pembayaran_created'),
					array('name' => 'pembayaran_user'),
					array('name' => 'pembayaran_aktif'),
					array('name' => 'pembayaran_keterangan'),
					array('name' => 'pembayaran_status'),
					array('name' => 'pembayaran_invoice'),
					array('name' => 'pembayaran_tanggal_invoice'),
					array('name' => 'pembayaran_sales'),
					array('name' => 'pembayaran_created_at'),
					array('name' => 'supplier_kode', 	'view' => true),
					array('name' => 'supplier_nama', 	'view' => true),
					array('name' => 'supplier_alamat', 	'view' => true),
					array('name' => 'akun_kode', 	'view' => true),
					array('name' => 'akun_nama', 	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembayaran',
				'mode' => array(
					'table' => array(
						'pembayaran_id',
						'pembayaran_supplier_id',
						'pembayaran_kode',
						'pembayaran_tanggal',
						'supplier_nama',
						'pembayaran_tagihan',
						'pembayaran_bayar',
						'pembayaran_referensi',
						'pembayaran_created',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode_pembayaran($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'PY.{date}.{#}',
			'date_format'   => 'ym',
			'field'         => 'pembayaran_kode',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}
}

/* End of file ReturpembelianModel.php */
/* Location: ./application/modules/pembelian/models/ReturpembelianModel.php */
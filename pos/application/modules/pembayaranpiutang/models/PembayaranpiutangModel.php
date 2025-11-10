<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembayaranpiutangModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembayaran_piutang',
				'primary' => 'pembayaran_piutang_id',
				'fields' => array(
					array('name' => 'pembayaran_piutang_id'),
					array('name' => 'pembayaran_piutang_kode'),
					array('name' => 'pembayaran_piutang_tanggal'),
					array('name' => 'pembayaran_piutang_customer_id'),
					array('name' => 'pembayaran_piutang_tagihan'),
					array('name' => 'pembayaran_piutang_retur'),
					array('name' => 'pembayaran_piutang_potongan'),
					array('name' => 'pembayaran_piutang_sisa'),
					array('name' => 'pembayaran_piutang_bayar'),
					array('name' => 'pembayaran_piutang_referensi'),
					array('name' => 'pembayaran_piutang_created'),
					array('name' => 'pembayaran_piutang_user'),
					array('name' => 'pembayaran_piutang_aktif'),
					array('name' => 'pembayaran_piutang_keterangan'),
					array('name' => 'pembayaran_piutang_status'),
					array('name' => 'pembayaran_piutang_invoice'),
					array('name' => 'pembayaran_piutang_tanggal_invoice'),
					array('name' => 'pembayaran_piutang_sales'),
					array('name' => 'pembayaran_piutang_created_at'),
					array('name' => 'customer_kode', 	'view' => true),
					array('name' => 'customer_nama', 	'view' => true),
					array('name' => 'customer_alamat', 	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembayaran_piutang',
				'mode' => array(
					'table' => array(
						'pembayaran_piutang_id',
						'pembayaran_piutang_customer_id',
						'pembayaran_piutang_kode',
						'pembayaran_piutang_tanggal',
						'customer_nama',
						'pembayaran_piutang_tagihan',
						'pembayaran_piutang_bayar',
						'pembayaran_piutang_referensi',
						'pembayaran_piutang_created',
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
			'date_format'   => 'ymd',
			'field'         => 'pembayaran_piutang_kode',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}

	public function gen_invoice_pembayaran($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'INV.{date}.{#}',
			'date_format'   => 'ymd',
			'field'         => 'pembayaran_piutang_invoice',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}
}

/* End of file ReturpembelianModel.php */
/* Location: ./application/modules/pembelian/models/ReturpembelianModel.php */
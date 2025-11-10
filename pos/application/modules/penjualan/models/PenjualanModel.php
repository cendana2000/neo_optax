<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_penjualan',
				'primary' => 'penjualan_id',
				'fields' => array(
					array('name' => 'penjualan_id'),
					array('name' => 'penjualan_tanggal'),
					array('name' => 'penjualan_kode'),
					array('name' => 'penjualan_customer_id'),
					array('name' => 'penjualan_total_item'),
					array('name' => 'penjualan_total_qty'),
					array('name' => 'penjualan_total_harga'),
					array('name' => 'penjualan_total_grand'),
					array('name' => 'penjualan_total_bayar'),
					array('name' => 'penjualan_total_bayar_tunai'),
					array('name' => 'penjualan_total_bayar_voucher'),
					array('name' => 'penjualan_total_bayar_voucher_khusus'),
					array('name' => 'penjualan_total_bayar_voucher_lain'),
					array('name' => 'penjualan_total_potongan_persen'),
					array('name' => 'penjualan_total_potongan'),
					array('name' => 'penjualan_total_kembalian'),
					array('name' => 'penjualan_total_kredit'),
					array('name' => 'penjualan_total_cicilan'),
					array('name' => 'penjualan_total_cicilan_qty'),
					array('name' => 'penjualan_total_retur'),
					array('name' => 'penjualan_kredit_awal'),
					array('name' => 'penjualan_jatuh_tempo'),
					array('name' => 'penjualan_user_id'),
					array('name' => 'penjualan_created_by'),
					array('name' => 'penjualan_created_at'),
					array('name' => 'penjualan_updated_by'),
					array('name' => 'penjualan_updated_at'),
					array('name' => 'penjualan_user_nama'),
					array('name' => 'penjualan_keterangan'),
					array('name' => 'penjualan_total_jasa'),
					array('name' => 'penjualan_total_jasa_nilai'),
					array('name' => 'penjualan_jenis_potongan'),
					array('name' => 'penjualan_is_konsinyasi'),
					array('name' => 'penjualan_metode'),
					array('name' => 'penjualan_kasir'),
					array('name' => 'penjualan_bank_id'),
					array('name' => 'penjualan_bank_ref'),
					array('name' => 'penjualan_jenis_barang'),
					array('name' => 'penjualan_lock'),
					array('name' => 'penjualan_no_transaksi'),
					array('name' => 'penjualan_jenis_penjualan'),
					array('name' => 'penjualan_tanggal_jatuh_tempo'),

					)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'penjualan_id',
						'penjualan_tanggal',
						'penjualan_kode',
						'penjualan_customer_id',
						'penjualan_total_item',
						'penjualan_total_qty',
						'penjualan_total_harga',
						'penjualan_total_grand',
						'penjualan_total_bayar',
						'penjualan_total_bayar_tunai',
						'penjualan_total_bayar_voucher',
						'penjualan_total_bayar_voucher_khusus',
						'penjualan_total_bayar_voucher_lain',
						'penjualan_total_potongan_persen',
						'penjualan_total_potongan',
						'penjualan_total_kembalian',
						'penjualan_total_kredit',
						'penjualan_total_cicilan',
						'penjualan_total_cicilan_qty',
						'penjualan_total_retur',
						'penjualan_kredit_awal',
						'penjualan_jatuh_tempo',
						'penjualan_user_id',
						'penjualan_created_by',
						'penjualan_created_at',
						'penjualan_updated_by',
						'penjualan_updated_at',
						'penjualan_user_nama',
						'penjualan_keterangan',
						'penjualan_total_jasa',
						'penjualan_total_jasa_nilai',
						'penjualan_jenis_potongan',
						'penjualan_is_konsinyasi',
						'penjualan_metode',
						'penjualan_kasir',
						'penjualan_bank_id',
						'penjualan_bank_ref',
						'penjualan_jenis_barang',
						'penjualan_lock',
						'penjualan_no_transaksi',
						'penjualan_jenis_penjualan',
						'penjualan_tanggal_jatuh_tempo',
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
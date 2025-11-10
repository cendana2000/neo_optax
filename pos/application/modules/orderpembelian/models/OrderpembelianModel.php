<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderpembelianModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_order_pembelian',
				'primary' => 'order_id',
				'fields' => array(
					array('name' => 'order_id'),
					array('name' => 'order_kode'),
					array('name' => 'order_supplier_id'),
					array('name' => 'order_tanggal'),
					array('name' => 'order_tanggal_dikirim'),
					array('name' => 'order_total_item'),
					array('name' => 'order_total_qty'),	
					array('name' => 'order_total'),
					array('name' => 'order_created'),
					array('name' => 'order_user'),
					array('name' => 'order_aktif'),
					array('name' => 'order_jenis_pembayaran'),
					array('name' => 'order_jatuh_tempo'),
					array('name' => 'order_no_transaksi'),
					array('name' => 'order_deleted_at'),
					array('name' => 'supplier_kode', 'view' => true),
					array('name' => 'supplier_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_order_pembelian',
				'mode' => array(
					'table' => array(
						'order_id',
						'order_kode',
						'order_tanggal',
						'supplier_nama',
						'order_total',
						'order_total_item',
						'order_total_qty',
						'order_kode',
						'order_jenis_pembayaran',
						'order_jatuh_tempo',
						'order_no_transaksi',
						'order_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
	}

	public function gen_kode_pembelian($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'OR.{date}.{#}',
			'date_format'   => 'ym',
			'field'         => 'order_kode',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}
}

/* End of file TransaksipembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
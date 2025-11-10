<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReturpembelianModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_retur_pembelian_barang',
				'primary' => 'retur_pembelian_id',
				'fields' => array(
					array('name' => 'retur_pembelian_id'),
					array('name' => 'retur_pembelian_kode'),
					array('name' => 'retur_pembelian_tanggal'),
					array('name' => 'retur_pembelian_pembelian_id'),
					array('name' => 'retur_pembelian_supplier_id'),
					array('name' => 'retur_pembelian_jumlah_item'),
					array('name' => 'retur_pembelian_jumlah_qty'),
					array('name' => 'retur_pembelian_total'),
					array('name' => 'retur_pembelian_created_at'),
					array('name' => 'retur_pembelian_created_by'),
					array('name' => 'retur_pembelian_updated_at'),
					array('name' => 'retur_pembelian_updated_by'),
					array('name' => 'retur_pembelian_user'),
					array('name' => 'retur_pembelian_aktif'),
					array('name' => 'supplier_nama', 	'view' => true),
					array('name' => 'supplier_kode', 	'view' => true),
					array('name' => 'pembelian_kode', 	'view' => true),
					/* array('name' => 'pembelian_bayar_sisa', 'view' => true),
					array('name' => 'pembelian_bayar_grand_total', 'view' => true),
					array('name' => 'pembelian_jatuh_tempo', 'view' => true), */
				)
			),
			'view' => array(
				'name' => 'v_pos_retur_pembelian',
				'mode' => array(
					'table' => array(
						'retur_pembelian_id',
						'retur_pembelian_pembelian_id',
						'retur_pembelian_kode',
						'retur_pembelian_tanggal',
						'pembelian_kode',
						'supplier_nama',
						'retur_pembelian_jumlah_item',
						'retur_pembelian_jumlah_qty',
						'retur_pembelian_total',
						'retur_pembelian_created_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value=false)
	{
		return parent::generate_kode(array(
				'pattern'       => 'RB.{date}.{#}',
	            'date_format'   =>'ym',
	            'field'         =>'retur_pembelian_kode',
	            'index_format'  =>'000',
	            'index_mask'    =>$value
		));
	}
}

/* End of file ReturpembelianModel.php */
/* Location: ./application/modules/pembelian/models/ReturpembelianModel.php */
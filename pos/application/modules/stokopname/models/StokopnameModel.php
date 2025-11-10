<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokopnameModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_stock_opname',
				'primary' => 'opname_id',
				'fields' => array(
					array('name' => 'opname_id'),
					array('name' => 'opname_kode'),
					array('name' => 'opname_tanggal'),
					array('name' => 'opname_kategori_barang'),
					array('name' => 'opname_total_item'),
					array('name' => 'opname_total_qty_data'),
					array('name' => 'opname_total_qty_fisik'),
					array('name' => 'opname_total_qty_koreksi'),
					array('name' => 'opname_total_nilai'),
					array('name' => 'opname_keterangan'),
					array('name' => 'opname_operator'),
					array('name' => 'opname_created_at'),
					array('name' => 'opname_user'),
					array('name' => 'opname_user_id'),
					array('name' => 'opname_aktif'),
					array('name' => 'kategori_barang_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_stock_opname',
				'mode' => array(
					'table' => array(
						'opname_id',
						'opname_kode',
						'opname_tanggal',
						'opname_total_item',
						'opname_total_qty_data',
						'opname_total_qty_fisik',
						'opname_total_qty_koreksi',
						'opname_total_nilai',
						'opname_keterangan',
						'opname_created_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'SO.{date}.{#}',
			'date_format'   => 'ym',
			'field'         => 'opname_kode',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}
}

/* End of file TransaksipembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokopnamedetailModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_stock_opname_detail',
				'primary' => 'opname_detail_id',
				'fields' => array(
					array('name' => 'opname_detail_id'),
					array('name' => 'opname_detail_parent'),
					array('name' => 'opname_detail_barang_id'),
					array('name' => 'opname_detail_satuan_id'),
					array('name' => 'opname_detail_satuan_kode'),
					array('name' => 'opname_detail_harga'),
					array('name' => 'opname_detail_qty_data'),
					array('name' => 'opname_detail_qty_fisik'),
					array('name' => 'opname_detail_qty_koreksi'),
					array('name' => 'opname_detail_nilai'),
					array('name' => 'opname_detail_tanggal'),
					array('name' => 'opname_detail_order'),
					array('name' => 'barang_kode', 		'view' => true),
					array('name' => 'barang_nama', 		'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_stock_opname_detail',
			)
			
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file OrderpembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
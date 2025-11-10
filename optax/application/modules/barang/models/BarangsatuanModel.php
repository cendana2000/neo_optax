<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangsatuanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_barang_satuan',
				'primary' => 'barang_satuan_id',
				'fields' => array(
					array('name' => 'barang_satuan_id'),
					array('name' => 'barang_satuan_parent'),
					array('name' => 'barang_satuan_satuan_id'),
					array('name' => 'barang_satuan_kode'),
					array('name' => 'barang_satuan_konversi'),
					array('name' => 'barang_satuan_harga_beli'),
					array('name' => 'barang_satuan_keuntungan'),
					array('name' => 'barang_satuan_harga_jual'),
					array('name' => 'barang_satuan_order'),
					array('name' => 'barang_satuan_disc'),
				)
			),
			'view' => array(
				// 'name' => 'v_pos_barang_satuan',
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang_satuan/models/BarangModel.php */

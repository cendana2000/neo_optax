<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangbarcodeModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_barang_barcode',
				'primary' => 'barang_barcode_id',
				'fields' => array(
					array('name' => 'barang_barcode_id'),
					array('name' => 'barang_barcode_parent'),
					array('name' => 'barang_barcode_tanggal'),
					array('name' => 'barang_barcode_kode'),
					array('name' => 'barang_kode', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_barang_barcode',
				'mode' => array(
					'table' => array(
						'barang_barcode_id',
						'barang_barcode_tanggal',
						'barang_barcode_kode',
						'barang_barcode_parent',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeranjangModel extends Base_Model {

	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_keranjang',
				'primary' => 'keranjang_id',
				'fields' => array(
					array('name' => 'keranjang_id'),
					array('name' => 'barang_id'),
					array('name' => 'anggota_id'),
					array('name' => 'barang_qty'),
					array('name' => 'keranjang_status'),
					array('name' => 'keranjang_tgl_pesan'),
					array('name' => 'barang_kode', 'view'=> true),
					array('name' => 'barang_nama', 'view'=> true),
					array('name' => 'barang_stok', 'view'=> true),
					array('name' => 'barang_harga', 'view'=> true),
					array('name' => 'anggota_nama', 'view'=> true),
				)
			),
			'view' => [
				'name' => 'v_pos_keranjang',
				'mode' => array(
					'table'=> array(
						array('keranjang_id','barang_id','anggota_id','barang_qty','barang_nama','barang_stok','barang_harga','keranjang_status','keranjang_tgl_pesan')
					)
				)
			]
		);
		parent::__construct($model);
	}

}

/* End of file KeranjangModel.php */
/* Location: ./application/modules/api/models/KeranjangModel.php */
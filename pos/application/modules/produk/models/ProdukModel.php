<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdukModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_barang',
				'primary' => 'barang_id',
				'fields' => array(
					array('name' => 'barang_id'),
					array('name' => 'barang_kode'),
					array('name' => 'barang_nama'),
					array('name' => 'barang_jenis_barang'),
					array('name' => 'barang_isi'),
					array('name' => 'barang_kategori_barang'),
					array('name' => 'barang_kategori_parent'),
					array('name' => 'barang_satuan'),
					array('name' => 'barang_satuan_kode'),
					array('name' => 'barang_satuan_opt'),
					array('name' => 'barang_satuan_opt_kode'),
					array('name' => 'barang_stok_min'),
					array('name' => 'barang_harga'),
					array('name' => 'barang_harga_opt'),
					array('name' => 'barang_harga_beli'),
					array('name' => 'barang_harga_pokok'),
					array('name' => 'barang_updated'),
					array('name' => 'barang_user'),
					array('name' => 'barang_aktif'),
					array('name' => 'barang_stok'),
					array('name' => 'barang_disc'),
					array('name' => 'barang_barcode'),
					array('name' => 'barang_persen_untung'),
					array('name' => 'barang_supplier_id'),
					array('name' => 'barang_satuan_opt2_kode'),
					array('name' => 'barang_harga_opt2'),
					array('name' => 'barang_yearly'),
					array('name' => 'barang_created_at'),
					array('name' => 'barang_updated_at'),
					array('name' => 'barang_deleted_at'),
					array('name' => 'kategori_barang_nama', 'view' => true),
					array('name' => 'supplier_nama', 		'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_barang',
				'mode' => array(
					'table' => array(
						'barang_id',
						'barang_kode',
						'barang_nama',
						'kategori_barang_nama',
						'supplier_nama',
						'barang_satuan_kode',
						'barang_harga',
						'barang_satuan_opt_kode',
						'barang_harga_opt',
						'barang_stok',
						'barang_satuan_opt2_kode',
						'barang_harga_opt2',
					),
					'pricelist' => array(
						'barang_id',
						'barang_kode',
						'barang_nama',
						'kategori_barang_nama',
						'barang_stok',
						'barang_satuan_kode',
						'barang_harga',
						'barang_satuan_opt_kode',
						'barang_harga_opt',
						'barang_satuan_opt2_kode',
						'barang_harga_opt2',
					),
					'stok-table' => array(
						'barang_id',
						'barang_kode',
						'barang_nama',
						'kategori_barang_nama',
						'barang_satuan_kode',
						'barang_harga',
						'barang_stok',
						'supplier_nama',
					),
					'barang_barcode' => array(
						'barang_id',
						'barang_kode',
						'barang_nama',
						'kategori_barang_nama',
						// 'supplier_nama',
						'barang_satuan_kode',
						'barang_harga',
						'barang_satuan_opt2_kode',
						'barang_harga_opt2',
						'barang_stok',
						'barang_satuan_opt_kode',
						'barang_harga_opt',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value = false, $kelompok = '')
	{
		return parent::generate_kode(array(
			'pattern'       => $kelompok . '.{#}',
			// 'date_format'   =>'ymd',
			'field'         => 'barang_kode',
			'index_format'  => '0000',
			'index_mask'    => $value
		));
	}
}

/* End of file ProdukModel.php */
/* Location: ./application/modules/barang/models/ProdukModel.php */
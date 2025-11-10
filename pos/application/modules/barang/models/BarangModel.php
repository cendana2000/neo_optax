<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangModel extends Base_Model
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
					array('name' => 'barang_persen_untung'),
					array('name' => 'barang_supplier_id'),
					array('name' => 'barang_satuan_opt2_kode'),
					array('name' => 'barang_harga_opt2'),
					array('name' => 'barang_yearly'),
					array('name' => 'barang_deleted_at'),
					array('name' => 'barang_thumbnail'),
					array('name' => 'barang_image'),
					array('name' => 'kategori_barang_nama', 'view' => true),
					array('name' => 'supplier_nama', 		'view' => true),
					array('name' => 'barang_barcode', 		'view' => true),
					array('name' => 'jenis_nama', 		'view' => true),
					array('name' => 'jenis_include_stok', 		'view' => true),
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
						'jenis_nama',
						'barang_harga',
						'barang_stok_min',
						'barang_stok',
						'barang_aktif',
						'kategori_barang_nama_parent',
						'supplier_nama',
						'barang_satuan_kode',
						'barang_satuan_opt_kode',
						'barang_harga_opt',
						'barang_satuan_opt2_kode',
						'barang_harga_opt2',
						'barang_thumbnail',
						'jenis_include_stok',
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

	public function setAktif($dataPost)
	{
		$dataPost = $dataPost['data'];
		$this->db->where('barang_id', $dataPost['barang_id']);
		if ($dataPost['barang_status'] == '0') {
			$this->db->set('barang_aktif', 1);
		}
		if ($dataPost['barang_status'] == '1') {
			$this->db->set('barang_aktif', 0);
		}
		if ($dataPost['barang_status'] == '2') {
			$this->db->set('barang_aktif', 3);
		}
		if ($dataPost['barang_status'] == '3') {
			$this->db->set('barang_aktif', 2);
		}

		return $this->db->update('pos_barang');
	}
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */
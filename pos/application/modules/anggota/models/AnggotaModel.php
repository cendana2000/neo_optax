<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnggotaModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ms_anggota',
				'primary' => 'anggota_id',
				'fields' => array(
					array('name' => 'anggota_id'),
					array('name' => 'anggota_kode'),
					array('name' => 'anggota_grup_gaji'),
					array('name' => 'anggota_kelompok'),
					array('name' => 'anggota_nama'),
					array('name' => 'anggota_nomor_ktp'),
					array('name' => 'anggota_nip'),
					array('name' => 'anggota_kota'),
					array('name' => 'anggota_kecamatan'),
					array('name' => 'anggota_kelurahan'),
					array('name' => 'anggota_alamat'),
					array('name' => 'anggota_jk'),
					array('name' => 'anggota_telp'),
					array('name' => 'anggota_tgl_lahir'),
					array('name' => 'anggota_pekerjaan'),
					array('name' => 'anggota_tgl_gabung'),
					array('name' => 'anggota_tgl_keluar'),
					array('name' => 'anggota_tgl_pensiun'),
					array('name' => 'anggota_is_proteksi'),
					array('name' => 'anggota_simp_pokok'),
					array('name' => 'anggota_simp_manasuka'),
					array('name' => 'anggota_simp_wajib'),
					array('name' => 'anggota_simp_wajib_khusus'),
					array('name' => 'anggota_simp_tabungan_hari_tua'),
					array('name' => 'anggota_simp_titipan_belanja'),
					array('name' => 'anggota_tag_pokok'),
					array('name' => 'anggota_tag_manasuka'),
					array('name' => 'anggota_tag_wajib'),
					array('name' => 'anggota_tag_wajib_khusus'),
					array('name' => 'anggota_tag_tabungan_hari_tua'),
					array('name' => 'anggota_tag_titipan_belanja'),
					array('name' => 'anggota_is_aktif'),
					array('name' => 'anggota_foto'),
					array('name' => 'anggota_foto_ktp'),
					array('name' => 'anggota_token'),
					array('name' => 'anggota_create'),
					array('name' => 'anggota_create_by'),
					array('name' => 'anggota_user'),
					array('name' => 'anggota_password'),
					array('name' => 'anggota_saldo_simp_pokok'),
					array('name' => 'anggota_saldo_simp_manasuka'),
					array('name' => 'anggota_saldo_simp_wajib'),
					array('name' => 'anggota_saldo_simp_wajib_khusus'),
					array('name' => 'anggota_saldo_simp_tabungan_hari_tua'),
					array('name' => 'anggota_saldo_simp_titipan_belanja'),
					array('name' => 'anggota_saldo_simp_khusus'),
					array('name' => 'anggota_saldo_voucher'),
					array('name' => 'anggota_saldo_voucher_exp_date'),
					array('name' => 'anggota_saldo_bhr'),
					array('name' => 'anggota_saldo_bhr_exp_date'),
					array('name' => 'anggota_update_by'),
					array('name' => 'anggota_update_at'),
					array('name' => 'anggota_tagihan_bulan_last'),
					array('name' => 'anggota_tagihan_ke'),
					array('name' => 'anggota_keterangan'),
					array('name' => 'anggota_is_aktif_status', 'view'=>true),
					array('name' => 'grup_gaji_kode', 'view'=>true),
					array('name' => 'grup_gaji_nama', 'view'=>true),
					array('name' => 'grup_gaji_potong', 'view'=>true),
					array('name' => 'kelompok_anggota_nama', 'view'=>true),
					array('name' => 'kelompok_anggota_id', 'view'=>true),
					array('name' => 'kelompok_anggota_potong_gaji', 'view'=>true),
					array('name' => 'nama_grup', 'view'=>true),
				)
			),
			'view' => array(
				'name' => 'v_ms_anggota',
				'mode' => array(
					'table' => array(
						'anggota_id', 'anggota_kode', 'anggota_nama', 'anggota_tgl_gabung', 'nama_grup','anggota_alamat', 'anggota_is_aktif_status','anggota_is_aktif'
					),
					/*'table_transaksi' => array(
						'anggota_id', 'anggota_kode', 'anggota_nama', 'anggota_saldo_simp_pokok', 'anggota_saldo_simp_manasuka', 'anggota_saldo_simp_wajib', 'anggota_saldo_simp_wajib_khusus', 'anggota_saldo_simp_tabungan_hari_tua', 'anggota_saldo_simp_titipan_belanja'
					)*/
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($kelompok_id, $value=false)
	{
		$data = $this->db->query('SELECT kelompok_anggota_kode FROM ms_kelompok_anggota WHERE kelompok_anggota_id = "'.$kelompok_id.'" ORDER BY kelompok_anggota_kode DESC LIMIT 1')->result_array();
		return parent::generate_kode(array(
				'pattern'       =>'{#}'.$data[0]['kelompok_anggota_kode'],
	            'date_format'   =>'ymd',
	            'field'         =>'anggota_kode',
	            'index_format'  =>'0000',
	            'index_mask'    =>$value
		));
	}
}

/* End of file PegawaiModel.php */
/* Location: ./application/modules/Pegawai/models/PegawaiModel.php */

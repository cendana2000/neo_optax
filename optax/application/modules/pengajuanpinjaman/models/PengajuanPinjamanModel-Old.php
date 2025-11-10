<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengajuanPinjamanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ksp_pengajuan_pinjaman',
				'primary' => 'pengajuan_id',
				'fields' => array(
					array('name' => 'pengajuan_id'),
					array('name' => 'pengajuan_tgl'),
					array('name' => 'pengajuan_no'),
					array('name' => 'pengajuan_create_at'),
					array('name' => 'pengajuan_create_by'),
					array('name' => 'pengajuan_updated_at'),
					array('name' => 'pengajuan_updated_by'),
					array('name' => 'pengajuan_anggota'),
					array('name' => 'pengajuan_gaji_bersih'),
					array('name' => 'pengajuan_sisa_pinjaman_kpri'),
					array('name' => 'pengajuan_status'),
					array('name' => 'pengajuan_telp'),
					array('name' => 'pengajuan_jumlah_pinjaman'),
					/*array('name' => 'pengajuan_sisa_pinjaman_lainnya'),
					array('name' => 'pengajuan_alamat'),
					array('name' => 'pengajuan_gaji_lainnya'),
					array('name' => 'pengajuan_jml_tanggungan'),
					array('name' => 'pengajuan_waktu_pensiun'),
					array('name' => 'pengajuan_pekerjaan'),
					array('name' => 'pengajuan_tgl_lahir'),*/
					array('name' => 'pengajuan_angsuran'),
					array('name' => 'pengajuan_tenor'),
					array('name' => 'pengajuan_keterangan'),
					array('name' => 'pengajuan_jasa'),
					array('name' => 'pengajuan_jenis'),
					array('name' => 'pengajuan_no_pinjam'),
					array('name' => 'pengajuan_penjualan_id'),
					array('name' => 'pengajuan_penjualan_no'),
					array('name' => 'pengajuan_tgl_realisasi'),
					array('name' => 'pengajuan_jatuh_tempo'),
					array('name' => 'pengajuan_pokok_bulanan'),
					array('name' => 'pengajuan_jasa_bulanan'),
					array('name' => 'pengajuan_angsur_jumlah'),
					array('name' => 'pengajuan_jasa_jumlah'),
					array('name' => 'pengajuan_sisa_angsuran'),
					array('name' => 'pengajuan_tag_awal'),
					array('name' => 'pengajuan_tag_bulan'),
					array('name' => 'pengajuan_tag_akhir'),
					array('name' => 'pengajuan_tag_jenis'),
					array('name' => 'pengajuan_jumlah_kas'),
					array('name' => 'pengajuan_proteksi'),
					array('name' => 'pengajuan_proteksi_nilai'),
					array('name' => 'pengajuan_pencairan'),
					array('name' => 'pengajuan_bank'),
					array('name' => 'pengajuan_rek_no'),
					array('name' => 'pengajuan_rek_nama'),
					array('name' => 'pengajuan_aktif'),
					array('name' => 'pengajuan_keperluan_tunai'),
					array('name' => 'pengajuan_keperluan_bank'),
					array('name' => 'pengajuan_nominal_bayar_tunai'),
					array('name' => 'pengajuan_nominal_bayar_bank'),
					array('name' => 'pengajuan_no_bkk'),
					array('name' => 'pengajuan_no_bkk_bank'),
					array('name' => 'pengajuan_pengurus'),
					array('name' => 'pengajuan_jasa_harian'),
					array('name' => 'pengajuan_tunggakan_jasa'),
					array('name' => 'pengajuan_tunggakan_pokok'),
					array('name' => 'anggota_nama', 				'view'=>true),
					array('name' => 'anggota_kode', 				'view'=>true),
					array('name' => 'anggota_nip',	 				'view'=>true),
					array('name' => 'anggota_alamat', 				'view'=>true),
					array('name' => 'anggota_pekerjaan', 			'view'=>true),
					array('name' => 'kelompok_anggota_nama', 		'view'=>true),
					array('name' => 'kelompok_anggota_potong_gaji', 'view'=>true),
					array('name' => 'kelompok_anggota_id',	 		'view'=>true),
					array('name' => 'grup_gaji_id', 				'view'=>true),
					array('name' => 'grup_gaji_nama', 				'view'=>true),
					array('name' => 'pengajuan_status_keterangan',	'view'=>true),
					array('name' => 'akun_nama',					'view'=>true),
					array('name' => 'jenis',						'view'=>true),
					array('name' => 'pegawai_nama',						'view'=>true),
					array('name' => 'pegawai_nama_kasir',						'view'=>true),
				)
			),
			'view' => array(
				'name' => 'v_ksp_pengajuan_pinjaman',
				'mode' => array(
					'table' => array(
						'pengajuan_id', 
						'pengajuan_tgl', 
						'pengajuan_no', 
						'anggota_nama', 
						'pengajuan_jenis', 
						'pengajuan_status_keterangan', 
						'pengajuan_id', 
					),
					'table_perjanjian' => array(
						'pengajuan_id', 
						'pengajuan_tgl', 
						'pengajuan_no_pinjam', 
						'anggota_nama', 
						'pengajuan_jenis', 
						'pengajuan_jumlah_pinjaman', 
						'pengajuan_proteksi', 
						'pengajuan_status_keterangan', 
						'pengajuan_id', 
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
				'pattern'       =>'PP-{date}-{#}',
	            'date_format'   =>'ymd',
	            'field'         =>'pengajuan_no',
	            'index_format'  =>'0000',
	            'index_mask'    =>$value
		));
	}

	public function gen_no_pinjam($value=false,$bulan,$tahun)
	{
		return parent::generate_kode(array(
				'pattern'       =>'{#};'.$bulan.';'.$tahun,
	            'date_format'   =>'ymd',
	            'field'         =>'pengajuan_no_pinjam',
	            'index_format'  =>'00',
	            'index_mask'    =>$value
		));
	}
}

/* End of file TransaksiSimpananModel.php */
/* Location: ./application/modules/transaksisimpanan/models/TransaksiSimpananModel.php */
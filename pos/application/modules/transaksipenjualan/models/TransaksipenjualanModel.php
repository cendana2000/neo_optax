<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksipenjualanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_penjualan',
				'primary' => 'penjualan_id',
				'fields' => array(
					array('name' => 'penjualan_id'),
					array('name' => 'penjualan_tanggal'),
					array('name' => 'penjualan_kode'),
					array('name' => 'penjualan_total_item'),
					array('name' => 'penjualan_total_qty'),
					array('name' => 'penjualan_total_harga'),
					array('name' => 'penjualan_total_grand'),
					array('name' => 'penjualan_total_bayar'),
					array('name' => 'penjualan_total_bayar_tunai'),
					array('name' => 'penjualan_total_potongan'),
					array('name' => 'penjualan_total_potongan_persen'),
					array('name' => 'penjualan_total_kembalian'),
					array('name' => 'penjualan_total_kredit'),
					array('name' => 'penjualan_total_cicilan'),
					array('name' => 'penjualan_total_cicilan_qty'),
					array('name' => 'penjualan_total_jasa'),
					array('name' => 'penjualan_total_jasa_nilai'),
					array('name' => 'penjualan_total_retur'),
					array('name' => 'penjualan_kredit_awal'),
					array('name' => 'penjualan_jatuh_tempo'),
					array('name' => 'penjualan_jenis_potongan'),
					array('name' => 'penjualan_user_id'),
					array('name' => 'penjualan_created'),
					array('name' => 'penjualan_user_nama'),
					array('name' => 'penjualan_keterangan'),
					array('name' => 'penjualan_kasir'),
					array('name' => 'penjualan_metode'),
					array('name' => 'penjualan_jenis_barang'),
					array('name' => 'pos_penjualan_customer_id'),
					array('name' => 'penjualan_lock'),
					array('name' => 'penjualan_bank'),
					array('name' => 'penjualan_bank_ref'),
					array('name' => 'penjualan_bank'),
					array('name' => 'penjualan_jasa'),
					array('name' => 'penjualan_total_grand'),
					array('name' => 'penjualan_total_potongan_persen'),
					array('name' => 'penjualan_pajak_persen'),
					array('name' => 'penjualan_total_bayar_bank'),
					array('name' => 'penjualan_total_bayar_tunai'),
					array('name' => 'penjualan_bayar_sisa'),
					array('name' => 'penjualan_platform'),
					array('name' => 'penjualan_first_item'),
					array('name' => 'penjualan_meja_id'),
					array('name' => 'penjualan_no_antrian'),
					array('name' => 'detail_id', 'view' => true),
					array('name' => 'customer_nama', 'view' => true),
					array('name' => 'barang_nama', 'view' => true),
					array('name' => 'barang_aktif', 'view' => true),
					array('name' => 'meja_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_penjualan',
				'mode' => array(
					'table' => array(
						'penjualan_id',
						'penjualan_kode',
						'penjualan_tanggal',
						'customer_nama',
						'penjualan_total_harga',
						'penjualan_total_potongan',
						'penjualan_total_grand',
						'penjualan_total_potongan',
						'pos_penjualan_customer_id',
						'penjualan_bank',
						'penjualan_platform',
						'barang_nama',
						'barang_aktif',
						'penjualan_bayar_sisa',
						'meja_nama',
					),
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode_penjualan($value = false, $trans)
	{
		$kode = $this->db->query('SELECT penjualan_tanggal, penjualan_kode FROM pos_penjualan order by penjualan_created desc limit 1')->result_array();
		if (isset($kode[0]['penjualan_kode'])) {
			if ($kode[0]['penjualan_tanggal'] < date('Y-m-d', strtotime($trans['penjualan_tanggal']))) {
				$last_kode = '001';
			} else {
				$last_kode = substr($kode[0]['penjualan_kode'], 1, 3);
				$last_kode = str_pad($last_kode + 1, 3, 0, STR_PAD_LEFT);
			}
		}
		$tgl_penjualan = substr(str_replace("-", "", $kode[0]['penjualan_tanggal']), 2, 6);
		return 'T' . $last_kode . $trans['penjualan_metode'] . $tgl_penjualan;
		// return 'T' . time() . $trans['penjualan_metode'];
	}

	public function gen_nomor_antrian($isMobile = false, $mobileDb = '')
	{
		if ($isMobile) {
			$this->db = $this->load->database(multidb_connect($mobileDb), true);
		}
		$antrian = $this->db->query('SELECT * FROM pos_penjualan order by penjualan_created desc limit 1')->result_array();

		if (isset($antrian[0]['penjualan_no_antrian'])) {
			if ($antrian[0]['penjualan_tanggal'] < date('Y-m-d')) {
				$last_kode = '001';
			} else {
				$last_kode = substr($antrian[0]['penjualan_no_antrian'], 1, 3);
				$last_kode = str_pad($last_kode + 1, 3, 0, STR_PAD_LEFT);
			}
		} else {
			$last_kode = '001';
		}

		return $last_kode;
	}
}

/* End of file TransaksipenjualanModel.php */
/* Location: ./application/modules/penjualan/models/TransaksipenjualanModel.php */
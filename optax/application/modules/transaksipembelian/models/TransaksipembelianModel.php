<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksipembelianModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_pembelian_barang',
				'primary' => 'pembelian_id',
				'fields' => array(
					array('name' => 'pembelian_id'),
					array('name' => 'pembelian_kode'),
					array('name' => 'pembelian_supplier_id'),
					array('name' => 'pembelian_tanggal'),
					array('name' => 'pembelian_faktur'),
					array('name' => 'pembelian_jatuh_tempo'),
					array('name' => 'pembelian_jatuh_tempo_hari'),
					array('name' => 'pembelian_jumlah_item'),
					array('name' => 'pembelian_jumlah_qty'),
					array('name' => 'pembelian_diskon'),
					array('name' => 'pembelian_diskon_persen'),
					array('name' => 'pembelian_pajak'),
					array('name' => 'pembelian_pajak_persen'),
					array('name' => 'pembelian_is_pajak'),
					array('name' => 'pembelian_total'),
					array('name' => 'pembelian_retur'),
					array('name' => 'pembelian_bayar_opsi'),
					array('name' => 'pembelian_bayar_jumlah'),
					array('name' => 'pembelian_bayar_sisa'),
					array('name' => 'pembelian_bayar_grand_total'),
					array('name' => 'pembelian_akun_id'),
					array('name' => 'pembelian_updated'),
					array('name' => 'pembelian_user'),
					array('name' => 'pembelian_aktif'),
					array('name' => 'pembelian_lock'),
					array('name' => 'pembelian_is_konsinyasi'),
					array('name' => 'pembelian_deleted_at'),
					array('name' => 'pembelian_created_at'),
					array('name' => 'order_kode', 		'view' => true),
					array('name' => 'supplier_kode', 	'view' => true),
					array('name' => 'supplier_nama', 	'view' => true),
					array('name' => 'supplier_telp', 	'view' => true),
					array('name' => 'supplier_alamat', 	'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pos_pembelian_barang',
				'mode' => array(
					'table' => array(
						'pembelian_id',
						'pembelian_akun_id',
						'pembelian_kode',
						'pembelian_tanggal',
						'supplier_nama',
						'pembelian_faktur',
						'pembelian_bayar_grand_total',
						'pembelian_bayar_jumlah',
						'pembelian_bayar_sisa',
						'pembelian_bayar_sisa',
						'pembelian_deleted_at',
					),
					'table_for_pay' => array(
						'pembelian_id',
						'pembelian_kode',
						'pembelian_faktur',
						'pembelian_tanggal',
						'pembelian_jatuh_tempo',
						'pembelian_bayar_grand_total',
						'pembelian_retur',
						'pembelian_bayar_jumlah',
						'pembelian_bayar_sisa',
						'pembelian_id',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode_pembelian($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'FP.{date}.{#}',
			'date_format'   => 'ym',
			'field'         => 'pembelian_kode',
			'index_format'  => '000',
			'index_mask'    => $value
		));
	}


	public function generate_kode($tgl, $prefix = 'T')
	{
		$bulan = date('Ym', strtotime($tgl));
		// if($prefix == 'KS') $bulan = date('Y', strtotime($tgl)).'-';
		if ($prefix == 'KS') $bulan = date('mY', strtotime($tgl)) . '-';
		$kode = $this->db->query('SELECT pembelian_kode FROM pos_pembelian_barang WHERE pembelian_kode like "' . $bulan . '%" order by pembelian_kode desc limit 1')->result_array();
		if (isset($kode[0]['pembelian_kode'])) {
			$last_kode = explode('-', $kode[0]['pembelian_kode']);
			$last_kode = explode('/', $last_kode[1]);
			$last_kode = str_pad($last_kode[0] + 1, 3, 0, STR_PAD_LEFT);
		} else {
			$last_kode = '001';
		}
		$bulan = str_replace('-', '', $bulan);
		return $bulan . '-' . $last_kode . '/' . $prefix;
	}
}

/* End of file TransaksipembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
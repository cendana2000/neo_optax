<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_satuan',
				'primary' => 'satuan_id',
				'fields' => array(
					array('name' => 'satuan_id'),
					array('name' => 'satuan_nama'),
					array('name' => 'satuan_kode'),
					array('name' => 'satuan_created_at'),
					array('name' => 'satuan_updated_at'),
					array('name' => 'satuan_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'satuan_id',
						'satuan_kode',
						'satuan_nama',
						'satuan_nama',
						'satuan_created_at',
						'satuan_updated_at',
						'satuan_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function getTransaksiTerakhir($year = null, $month = null)
	{
		if (!$year) $year = date('Y');
		if (!$month) $month = date('m');

		$sql = "WITH pos_latest as (
				-- ambil baris log_penjualan_wp dengan tanggal terbaru per store
				select
					lpw.*
				from
					log_penjualan_wp lpw
				join (
					select
						log_penjualan_code_store,
						max(log_penjualan_wp_penjualan_tanggal) as max_tgl
					from
						log_penjualan_wp
						-- optional: filter by year/month here, contoh:
						-- AND extract(year from log_penjualan_wp_penjualan_tanggal) = 2025
						-- AND extract(month from log_penjualan_wp_penjualan_tanggal) = 9
					group by
						log_penjualan_code_store
				) m on
					m.log_penjualan_code_store = lpw.log_penjualan_code_store
					and m.max_tgl = lpw.log_penjualan_wp_penjualan_tanggal
				where
					lpw.log_penjualan_deleted_at is null
				),
				pos_agg as (
				select
					pt.toko_wajibpajak_npwpd as npwpd,
					pt.toko_nama as nama_wp,
					(
					lpw.log_penjualan_wp_total
					- (lpw.log_penjualan_wp_total /
						(case
						when pj.jenis_tarif = 5 then 21
						when pj.jenis_tarif = 10 then 11
					end)
					)
					)::float as sub_total,
					(
					lpw.log_penjualan_wp_total /
					(case
						when pj.jenis_tarif = 5 then 21
						when pj.jenis_tarif = 10 then 11
					end)
					)::float as jumlah_pajak,
					'POS'::text as sumber_data,
					lpw.log_penjualan_wp_penjualan_tanggal as tanggal_transaksi
				from
					pos_latest lpw
				join pajak_toko pt on
					pt.toko_kode = lpw.log_penjualan_code_store
				left join pajak_wajibpajak pw3 on
					pw3.wajibpajak_npwpd = pt.toko_wajibpajak_npwpd
				left join pajak_jenis pj on
					pj.jenis_kode = pw3.wajibpajak_sektor_nama
				),
				persada_latest as (
				-- ambil baris pajak_realisasi dengan tanggal terbaru per wajibpajak
				select
					pr.*
				from
					pajak_realisasi pr
				join (
					select
						realisasi_wajibpajak_npwpd,
						max(realisasi_tanggal) as max_tgl
					from
						pajak_realisasi
					where
						realisasi_deleted_at is null
						-- optional: filter by year/month:
						-- AND extract(year from realisasi_tanggal) = 2025
						-- AND extract(month from realisasi_tanggal) = 9
					group by
						realisasi_wajibpajak_npwpd
				) m on
					m.realisasi_wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					and m.max_tgl = pr.realisasi_tanggal
				where
					pr.realisasi_deleted_at is null
				),
				persada_agg as (
				select
					pr.realisasi_wajibpajak_npwpd as npwpd,
					pw.wajibpajak_nama as nama_wp,
					pr.realisasi_sub_total::float as sub_total,
					(pr.realisasi_sub_total * pj.jenis_tarif / 100)::float as jumlah_pajak,
					'PERSADA'::text as sumber_data,
					pr.realisasi_tanggal as tanggal_transaksi
				from
					persada_latest pr
				join pajak_wajibpajak pw on
					pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
				left join pajak_jenis pj on
					pj.jenis_kode = pw.wajibpajak_sektor_nama
				where
					pw.wajibpajak_status = '2'
				)
				select
					hasil.*
				from
					(
					select
						*
					from
						pos_agg
				union all
					select
						*
					from
						persada_agg
				) hasil
				left join pajak_wajibpajak pw2 on
					pw2.wajibpajak_npwpd = hasil.npwpd
				where
					hasil.jumlah_pajak > 0
					-- and pw2.wajibpajak_status = '2'
					-- and hasil.npwpd <> '0437.63.102'
				order by
					hasil.tanggal_transaksi desc
				limit 10 offset 0;";
		return $this->db->query($sql)->result_array();
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
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
		if (!$year)  $year  = date('Y');
		if (!$month) $month = date('m');

		$pemda_id = $this->session->userdata('pemda_id');
		$where_pemda = '';
		if (!empty($pemda_id)) {
			$where_pemda = ' AND cp.pemda_id = ' . $this->db->escape($pemda_id);
		}
		$sql = "WITH pos_agg AS (
				SELECT
					pw.wajibpajak_npwpd AS npwpd,
					pw.wajibpajak_nama AS nama_wp,
					pp.penjualan_kode AS no_transaksi,
					pp.penjualan_total_harga::float AS sub_total,
					(
						pp.penjualan_total_grand /
						COALESCE(
							CASE
								WHEN pj.jenis_tarif = 5 THEN 21
								WHEN pj.jenis_tarif = 10 THEN 11
							END,
							1
						)
					)::float AS jumlah_pajak,
					'POS'::text AS sumber_data,
					to_char(pp.penjualan_tanggal, 'YYYY-MM-DD HH24:MI:SS') AS tanggal_transaksi,
					cp.pemda_id
				FROM
					pos_penjualan pp
				LEFT JOIN pajak_wajibpajak pw
					ON pw.wajibpajak_id = pp.wajibpajak_id
				LEFT JOIN pajak_jenis pj
					ON pj.jenis_kode = pw.wajibpajak_sektor_nama
				LEFT JOIN conf_pemda cp
					ON pw.pemda_id = cp.pemda_id
				WHERE
					pp.penjualan_deleted_at IS NULL
					$where_pemda
			),
			persada_latest AS (
				SELECT pr.*
				FROM pajak_realisasi pr
				JOIN (
					SELECT
						realisasi_wajibpajak_npwpd,
						MAX(realisasi_tanggal) AS max_tgl
					FROM pajak_realisasi
					WHERE realisasi_deleted_at IS NULL
					GROUP BY realisasi_wajibpajak_npwpd
				) m
					ON m.realisasi_wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					AND m.max_tgl = pr.realisasi_tanggal
				WHERE pr.realisasi_deleted_at IS NULL
			),
			persada_agg AS (
				SELECT
					pr.realisasi_wajibpajak_npwpd AS npwpd,
					pw.wajibpajak_nama AS nama_wp,
					pr.realisasi_no AS no_transaksi,
					pr.realisasi_sub_total::float AS sub_total,
					(pr.realisasi_sub_total * pj.jenis_tarif / 100)::float AS jumlah_pajak,
					'PERSADA'::text AS sumber_data,
					to_char(pr.realisasi_tanggal, 'YYYY-MM-DD HH24:MI:SS') AS tanggal_transaksi,
					cp.pemda_id
				FROM persada_latest pr
				JOIN pajak_wajibpajak pw
					ON pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
				LEFT JOIN pajak_jenis pj
					ON pj.jenis_kode = pw.wajibpajak_sektor_id
				LEFT JOIN conf_pemda cp
					ON cp.pemda_id = pw.pemda_id
				WHERE
					pw.wajibpajak_status = '2'
					$where_pemda
			)
			SELECT
				hasil.*
			FROM (
				SELECT * FROM pos_agg
				UNION ALL
				SELECT * FROM persada_agg
			) hasil
			LEFT JOIN pajak_wajibpajak pw2
				ON pw2.wajibpajak_npwpd = hasil.npwpd
			WHERE
				pw2.wajibpajak_status = '2'
			ORDER BY hasil.tanggal_transaksi DESC
			LIMIT 10 OFFSET 0;";
		return $this->db->query($sql)->result_array();
	}

	public function getTransaksiTerakhirAll($year = null, $month = null)
	{
		if (!$year)  $year  = date('Y');
		if (!$month) $month = date('m');

		$pemda_id = $this->session->userdata('pemda_id');
		$where_pemda = '';
		if (!empty($pemda_id)) {
			$where_pemda = ' AND cp.pemda_id = ' . $this->db->escape($pemda_id);
		}
		$sql = "WITH pos_agg AS (
				SELECT
					pw.wajibpajak_npwpd AS npwpd,
					pw.wajibpajak_nama AS nama_wp,
					pp.penjualan_kode AS no_transaksi,
					pp.penjualan_total_harga::float AS sub_total,
					(
						pp.penjualan_total_grand /
						COALESCE(
							CASE
								WHEN pj.jenis_tarif = 5 THEN 21
								WHEN pj.jenis_tarif = 10 THEN 11
							END,
							1
						)
					)::float AS jumlah_pajak,
					'POS'::text AS sumber_data,
					to_char(pp.penjualan_tanggal, 'YYYY-MM-DD HH24:MI:SS') AS tanggal_transaksi,
					cp.pemda_id
				FROM
					pos_penjualan pp
				LEFT JOIN pajak_wajibpajak pw
					ON pw.wajibpajak_id = pp.wajibpajak_id
				LEFT JOIN pajak_jenis pj
					ON pj.jenis_kode = pw.wajibpajak_sektor_nama
				LEFT JOIN conf_pemda cp
					ON pw.pemda_id = cp.pemda_id
				WHERE
					pp.penjualan_deleted_at IS NULL
					$where_pemda
			),
			persada_latest AS (
				SELECT pr.*
				FROM pajak_realisasi pr
				JOIN (
					SELECT
						realisasi_wajibpajak_npwpd,
						MAX(realisasi_tanggal) AS max_tgl
					FROM pajak_realisasi
					WHERE realisasi_deleted_at IS NULL
					GROUP BY realisasi_wajibpajak_npwpd
				) m
					ON m.realisasi_wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					AND m.max_tgl = pr.realisasi_tanggal
				WHERE pr.realisasi_deleted_at IS NULL
			),
			persada_agg AS (
				SELECT
					pr.realisasi_wajibpajak_npwpd AS npwpd,
					pw.wajibpajak_nama AS nama_wp,
					pr.realisasi_no AS no_transaksi,
					pr.realisasi_sub_total::float AS sub_total,
					(pr.realisasi_sub_total * pj.jenis_tarif / 100)::float AS jumlah_pajak,
					'PERSADA'::text AS sumber_data,
					to_char(pr.realisasi_tanggal, 'YYYY-MM-DD HH24:MI:SS') AS tanggal_transaksi,
					cp.pemda_id
				FROM persada_latest pr
				JOIN pajak_wajibpajak pw
					ON pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
				LEFT JOIN pajak_jenis pj
					ON pj.jenis_kode = pw.wajibpajak_sektor_id
				LEFT JOIN conf_pemda cp
					ON cp.pemda_id = pw.pemda_id
				WHERE
					pw.wajibpajak_status = '2'
					$where_pemda
			)
			SELECT
				hasil.*
			FROM (
				SELECT * FROM pos_agg
				UNION ALL
				SELECT * FROM persada_agg
			) hasil
			LEFT JOIN pajak_wajibpajak pw2
				ON pw2.wajibpajak_npwpd = hasil.npwpd
			WHERE
				pw2.wajibpajak_status = '2'
			ORDER BY hasil.tanggal_transaksi DESC
			LIMIT 50 OFFSET 0;";
		return $this->db->query($sql)->result_array();
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
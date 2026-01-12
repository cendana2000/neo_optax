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

	public function get_total_pajak_by_jenis($jenis_nama, $begin, $end)
	{
		$pemda_id = $this->session->userdata('pemda_id');

		$sql = "
				SELECT
					COALESCE(r.total_pajak_realisasi, 0)
					+ COALESCE(p.total_pajak_pos, 0) AS total_pajak,

					COALESCE(r.total_subtotal_realisasi, 0)
					+ COALESCE(p.total_subtotal_pos, 0) AS total_sub_total
				FROM
				(
					SELECT
						SUM(pr.realisasi_pajak)     AS total_pajak_realisasi,
						SUM(pr.realisasi_sub_total) AS total_subtotal_realisasi
					FROM pajak_realisasi pr
					JOIN pajak_wajibpajak pw 
						ON pw.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd
					JOIN pajak_jenis pj 
						ON pj.jenis_id = pw.wajibpajak_sektor_id
					WHERE
						pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = ?
						)
						AND pr.realisasi_tanggal BETWEEN ? AND ?
						AND pr.realisasi_deleted_at IS NULL
						AND pw.wajibpajak_deleted_at IS NULL
						" . ($pemda_id ? "AND pw.pemda_id = ?" : "") . "
				) r
				CROSS JOIN
				(
					SELECT
						SUM(pp.penjualan_total_harga * pp.penjualan_pajak_persen / 100)
							AS total_pajak_pos,
						SUM(pp.penjualan_total_harga)
							AS total_subtotal_pos
					FROM pos_penjualan pp
					LEFT JOIN pajak_wajibpajak pw2 
						ON pw2.wajibpajak_id = pp.wajibpajak_id
					LEFT JOIN pajak_jenis pj 
						ON pj.jenis_id = pw2.wajibpajak_sektor_id
					WHERE
						pj.jenis_parent = (
							SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = ?
						)
						AND pp.penjualan_tanggal BETWEEN ? AND ?
						AND pp.penjualan_deleted_at IS NULL
						AND pw2.wajibpajak_deleted_at IS NULL
						" . ($pemda_id ? "AND pw2.pemda_id = ?" : "") . "
				) p
			";
		$params = [
			$jenis_nama,
			$begin,
			$end,
		];

		if ($pemda_id) {
			$params[] = $pemda_id;
		}

		$params[] = $jenis_nama;
		$params[] = $begin;
		$params[] = $end;

		if ($pemda_id) {
			$params[] = $pemda_id;
		}

		return $this->db->query($sql, $params)->row_array();
	}

	public function get_total_pajak_masuk($rawbegin, $rawend)
	{
		$pemda_id = $this->session->userdata('pemda_id');
		$sql = "
				SELECT
					COALESCE(r.total_pajak_realisasi, 0)
					+ COALESCE(p.total_pajak_pos, 0) AS total_pajak_masuk,

					COALESCE(r.total_transaksi_realisasi, 0)
					+ COALESCE(p.total_transaksi_pos, 0) AS total_transaksi
				FROM
				(
					SELECT
						SUM(pr.realisasi_pajak)     AS total_pajak_realisasi,
						SUM(pr.realisasi_sub_total) AS total_transaksi_realisasi
					FROM pajak_realisasi pr
					WHERE
						pr.realisasi_tanggal::date BETWEEN ? AND ?
						AND pr.realisasi_deleted_at IS NULL
						AND EXISTS (
							SELECT 1
							FROM pajak_wajibpajak pw
							WHERE pw.wajibpajak_id = pr.realisasi_wajibpajak_id
							" . ($pemda_id ? "AND pw.pemda_id = ?" : "") . "
						)
				) r
				CROSS JOIN
				(
					SELECT
						SUM(pp.penjualan_total_harga * pp.penjualan_pajak_persen / 100)
							AS total_pajak_pos,
						SUM(pp.penjualan_total_harga)
							AS total_transaksi_pos
					FROM pos_penjualan pp
					WHERE
						pp.penjualan_tanggal BETWEEN ? AND ?
						AND pp.penjualan_deleted_at IS NULL
						AND EXISTS (
							SELECT 1
							FROM pajak_wajibpajak pw
							WHERE pw.wajibpajak_id = pp.wajibpajak_id
							" . ($pemda_id ? "AND pw.pemda_id = ?" : "") . "
						)
				) p
			";
		$params = [$rawbegin, $rawend];
		if ($pemda_id) {
			$params[] = $pemda_id;
		}
		$params[] = $rawbegin;
		$params[] = $rawend;

		if ($pemda_id) {
			$params[] = $pemda_id;
		}
		return $this->db->query($sql, $params)->row_array();
	}

	public function get_total_wp_by_jenis($jenis_nama)
	{
		$pemda_id = $this->session->userdata('pemda_id');

		$sql = "
			SELECT COUNT(DISTINCT pw.wajibpajak_npwpd) AS total_wp
			FROM pajak_wajibpajak pw
			LEFT JOIN pajak_jenis pj 
				ON pj.jenis_id = pw.wajibpajak_sektor_id
			WHERE
				pw.wajibpajak_status = '2'
				AND pw.wajibpajak_deleted_at IS NULL
				AND pj.jenis_parent = (
					SELECT jenis_id FROM pajak_jenis WHERE jenis_nama = ?
				)
				" . ($pemda_id ? "AND pw.pemda_id = ?" : "") . "
		";
		$params = [$jenis_nama];
		if ($pemda_id) {
			$params[] = $pemda_id;
		}
		return $this->db->query($sql, $params)->row_array();
	}


	public function getTransaksiTerakhir($limit = 10, $offset = 0, $year = null, $month = null)
	{
		if (!$year)  $year  = date('Y');
		if (!$month) $month = date('m');

		$pemda_id = $this->session->userdata('pemda_id');
		$where_pemda = '';
		if (!empty($pemda_id)) {
			$where_pemda = ' AND cp.pemda_id = ' . $this->db->escape($pemda_id);
		}

		$limit  = (int) $limit;
		$offset = (int) $offset;

		$sql = "
			WITH pos_agg AS (
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
				FROM pos_penjualan pp
				LEFT JOIN pajak_wajibpajak pw
					ON pw.wajibpajak_id = pp.wajibpajak_id
				LEFT JOIN pajak_jenis pj
					ON pj.jenis_kode = pw.wajibpajak_sektor_nama
				LEFT JOIN conf_pemda cp
					ON pw.pemda_id = cp.pemda_id
				WHERE
					pp.penjualan_deleted_at IS NULL
					{$where_pemda}
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
					{$where_pemda}
			)
			SELECT hasil.*
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
			LIMIT {$limit} OFFSET {$offset}
		";
		return $this->db->query($sql)->result_array();
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
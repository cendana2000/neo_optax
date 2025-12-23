<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekappajakModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_realisasi',
				'primary' => 'realisasi_id',
				'fields' => array(
					array('name' => 'realisasi_id'),
					array('name' => 'realisasi_no'),
					array('name' => 'realisasi_wajibpajak_id'),
					array('name' => 'realisasi_wajibpajak_npwpd'),
					array('name' => 'realisasi_tanggal'),
					array('name' => 'realisasi_sub_total'),
					array('name' => 'realisasi_jasa'),
					array('name' => 'realisasi_pajak'),
					array('name' => 'realisasi_total'),
					array('name' => 'realisasi_created_at'),
					array('name' => 'realisasi_created_by'),
					array('name' => 'realisasi_updated_at'),
					array('name' => 'realisasi_updated_by'),
					array('name' => 'wajibpajak_nama', 'view' => true),
					array('name' => 'realisasi_total_pajak', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_realisasi_v2',
				'mode' => array(
					'table' => array(
						'distinct(realisasi_id) as realisasi_id',
						'realisasi_no',
						'realisasi_wajibpajak_id',
						'realisasi_wajibpajak_npwpd',
						'realisasi_tanggal',
						'realisasi_sub_total',
						'realisasi_jasa',
						'realisasi_pajak',
						'realisasi_total',
						'realisasi_created_at',
						'realisasi_created_by',
						'realisasi_updated_at',
						'realisasi_updated_by',
						'wajibpajak_nama',
						'realisasi_total_pajak',
					),
					'datatable' => array(
						'realisasi_id',
						'realisasi_tanggal',
						'realisasi_wajibpajak_npwpd',
						'wajibpajak_nama',
						'realisasi_sub_total',
						'realisasi_jasa',
						'realisasi_pajak',
						'realisasi_total_pajak',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function get_realisasi_data($npwpd, $tahun, $bulan, $all)
	{
		$where = ($all) ? "hasil.npwpd LIKE '%$npwpd%'" : "hasil.npwpd = '$npwpd'";
		$sql = "SELECT hasil.*, pw2.wajibpajak_status, pw2.wajibpajak_telp, pw2.wajibpajak_nama_penanggungjawab
			from(
				select * from(
					select
						pt.toko_wajibpajak_npwpd as npwpd,
						pt.toko_nama as nama_wp,
						x.jumlah_pajak::integer,
						x.jenis_tarif,
						'POS' as sumber_data
					from
						pajak_toko pt
					join (
						select
							lpw.log_penjualan_code_store,
							(SUM(lpw.log_penjualan_wp_total) / 
								(case when pj.jenis_tarif = 5 then 21
								when pj.jenis_tarif = 10 then 11 end)
							) as jumlah_pajak,
							pj.jenis_tarif
						from
							log_penjualan_wp lpw
							left join pajak_toko pt2 on pt2.toko_kode = lpw.log_penjualan_code_store
							left join pajak_wajibpajak pw3 on pw3.wajibpajak_npwpd = pt2.toko_wajibpajak_npwpd 
							left join pajak_jenis pj on pj.jenis_kode = pw3.wajibpajak_sektor_nama 
						where
							EXTRACT(YEAR FROM lpw.log_penjualan_wp_penjualan_tanggal) = $tahun AND 
							EXTRACT(MONTH FROM lpw.log_penjualan_wp_penjualan_tanggal) = $bulan AND
							lpw.log_penjualan_deleted_at is NULL
						group by
							lpw.log_penjualan_code_store, pj.jenis_tarif 
					) as x on x.log_penjualan_code_store = pt.toko_kode
					order by pt.toko_nama 
				) y
				union all
				select *
				from(
					select
						x.realisasi_wajibpajak_npwpd as npwpd,
						pw.wajibpajak_nama as nama_wp,
						coalesce(x.jumlah,0)::float as jumlah_pajak,
						x.jenis_tarif,
						'PERSADA' as sumber_data
					from
						pajak_wajibpajak pw
						left join (
							select
								pr.realisasi_wajibpajak_npwpd,
								COUNT(pr.realisasi_tanggal) as jml_trans,
								SUM(pr.realisasi_sub_total * pj.jenis_tarif / 100) as jumlah,
								--SUM(pr.realisasi_pajak) as jumlah,
								pj.jenis_tarif 
							from
								pajak_realisasi pr
								left join pajak_wajibpajak pw2 on pw2.wajibpajak_npwpd = pr.realisasi_wajibpajak_npwpd 
								left join pajak_jenis pj on pj.jenis_kode = pw2.wajibpajak_sektor_nama 
							where
								EXTRACT(year FROM pr.realisasi_tanggal) = $tahun AND 
								EXTRACT( MONTH FROM pr.realisasi_tanggal) = $bulan AND
								pr.realisasi_deleted_at is null AND
								pw2.wajibpajak_status = '2'
							group by
								pr.realisasi_wajibpajak_npwpd,
								--pr.realisasi_pajak,
								pj.jenis_tarif 
						) x on x.realisasi_wajibpajak_npwpd = pw.wajibpajak_npwpd
					where
						x.realisasi_wajibpajak_npwpd is not null and 
						pw.wajibpajak_status = '2'
					order by
						pw.wajibpajak_nama asc
				) z
			) hasil
			left join pajak_wajibpajak pw2 on pw2.wajibpajak_npwpd = hasil.npwpd
			where 
				pw2.wajibpajak_status = '2'
				and hasil.jumlah_pajak > 0
				and hasil.npwpd <> '0437.63.102'				
				and $where
		";
		$query = $this->db->query($sql);
		return array(
			"sql" 	=> $sql,
			"query" => $query->result_array()
		);
	}

	public function get_realisasi_data_custom($sql)
	{
		$query = $this->db->query($sql);
		return array(
			"sql" 	=> $sql,
			"data" => $query->result_array()
		);
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
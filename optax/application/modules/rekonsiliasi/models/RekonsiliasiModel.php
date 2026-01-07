<?php defined('BASEPATH') or exit('No direct script access allowed');

class RekonsiliasiModel extends CI_Model
{
    public function paginate($tahun, $limit, $offset, $search, $sort_col, $sort_dir)
    {
        $where  = '';

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $where .= ' AND wp.pemda_id=' . $this->db->escape($pemda_id);
        }

        if ($search) {
            $where  .= " AND (wp.wajibpajak_npwpd ILIKE '%$search%' OR wp.wajibpajak_nama ILIKE '%$search%') ";
        }

        $sql    = "SELECT
                wp.wajibpajak_npwpd,
                wp.wajibpajak_nama,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS januari,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS februari,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS maret,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS april,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS mei,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS juni,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS juli,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS agustus,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS september,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS oktober,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS november,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) + 
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS desember

            FROM pajak_wajibpajak wp
                LEFT JOIN mv_penjualan_bulanan mp ON mp.wajibpajak_id = wp.wajibpajak_id
                LEFT JOIN mv_oapi_bulanan mo      ON mo.wajibpajak_id = wp.wajibpajak_id

            WHERE 1=1 $where
            GROUP BY wp.wajibpajak_id
            ORDER BY $sort_col $sort_dir
            LIMIT ? OFFSET ?";

        $bindings   = array(
            "$tahun-01-01",
            "$tahun-01-01",
            "$tahun-02-01",
            "$tahun-02-01",
            "$tahun-03-01",
            "$tahun-03-01",
            "$tahun-04-01",
            "$tahun-04-01",
            "$tahun-05-01",
            "$tahun-05-01",
            "$tahun-06-01",
            "$tahun-06-01",
            "$tahun-07-01",
            "$tahun-07-01",
            "$tahun-08-01",
            "$tahun-08-01",
            "$tahun-09-01",
            "$tahun-09-01",
            "$tahun-10-01",
            "$tahun-10-01",
            "$tahun-11-01",
            "$tahun-11-01",
            "$tahun-12-01",
            "$tahun-12-01",
            $limit,
            $offset
        );

        $records    = $this->db->query($sql, $bindings)->result();
        $total      = $this->db->count_all('pajak_wajibpajak');

        $nomor      = $offset + 1;
        foreach ($records as &$record) {
            $val_encoded    = base64_encode(json_encode(['wajibpajak_id' => $record->wajibpajak_id]));
            $input          = '<input type="checkbox" name="checkbox" class="checkbox d-none mx-auto" data-record="' . $val_encoded . '" /><span></span>';
            $record->{0}    = '<span class="not-checkbox">' . $nomor . '.</span> ' . $input;
            $nomor++;
        }

        return [
            'iTotalRecords'         => $total,
            'iTotalDisplayRecords'  => $total,
            'sEcho'                 => 0,
            'sColumns'              => '',
            'aaData'                => $records,
            'modelname'             => 'RekonsiliasiModel',
            'vmode'                 => 'table',
            'column'                => [
                'wajibpajak_npwpd',
                'wajibpajak_nama',
                'jan_penjualan',
                'jan_oapi',
                'feb_penjualan',
                'feb_oapi',
                'mar_penjualan',
                'mar_oapi',
                'apr_penjualan',
                'apr_oapi',
                'mei_penjualan',
                'mei_oapi',
                'jun_penjualan',
                'jun_oapi',
                'jul_penjualan',
                'jul_oapi',
                'agu_penjualan',
                'agu_oapi',
                'sep_penjualan',
                'sep_oapi',
                'okt_penjualan',
                'okt_oapi',
                'nov_penjualan',
                'nov_oapi',
                'des_penjualan',
                'des_oapi',
            ],
        ];
    }

    public function get($tahun)
    {
        $where  = '';

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $where .= ' AND wp.pemda_id=' . $this->db->escape($pemda_id);
        }

        $sql    = "SELECT
                wp.wajibpajak_npwpd,
                wp.wajibpajak_nama,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS jan_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS jan_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS feb_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS feb_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS mar_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS mar_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS apr_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS apr_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS mei_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS mei_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS jun_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS jun_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS jul_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS jul_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS agu_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS agu_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS sep_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS sep_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS okt_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS okt_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS nov_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS nov_oapi,

                COALESCE(SUM(mp.total_pajak) FILTER (WHERE mp.bulan = DATE ?), 0) AS des_penjualan,
                COALESCE(SUM(mo.total_pajak) FILTER (WHERE mo.bulan = DATE ?), 0) AS des_oapi

            FROM pajak_wajibpajak wp
                LEFT JOIN mv_penjualan_bulanan mp ON mp.wajibpajak_id = wp.wajibpajak_id
                LEFT JOIN mv_oapi_bulanan mo      ON mo.wajibpajak_id = wp.wajibpajak_id

            WHERE 1=1 $where
            GROUP BY wp.wajibpajak_id";

        $bindings   = array(
            "$tahun-01-01",
            "$tahun-01-01",
            "$tahun-02-01",
            "$tahun-02-01",
            "$tahun-03-01",
            "$tahun-03-01",
            "$tahun-04-01",
            "$tahun-04-01",
            "$tahun-05-01",
            "$tahun-05-01",
            "$tahun-06-01",
            "$tahun-06-01",
            "$tahun-07-01",
            "$tahun-07-01",
            "$tahun-08-01",
            "$tahun-08-01",
            "$tahun-09-01",
            "$tahun-09-01",
            "$tahun-10-01",
            "$tahun-10-01",
            "$tahun-11-01",
            "$tahun-11-01",
            "$tahun-12-01",
            "$tahun-12-01"
        );

        return $this->db->query($sql, $bindings)->result();
    }
}

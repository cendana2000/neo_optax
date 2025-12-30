<?php defined('BASEPATH') or exit('No direct script access allowed');

class Laporannotrx extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'laporannotrx/LaporanNoTrxModel' => 'laporannotrx'
        ));
    }

    public function index()
    {
        $post       = varPost();
        $periode    = varPost('periode');
        $periodearr = explode(' - ', $periode);
        $enddate    = date('Y-m-d');
        $startdate  = date('Y-m-d');

        if (is_array($periodearr) && count($periodearr) > 1) {
            $startdate  = date('Y-m-d', strtotime($periodearr[0]));
            $enddate    = date('Y-m-d', strtotime($periodearr[1]));
        }

        $where      = [];

        $where["pos_no_trx_tanggal >= '$startdate'"]   = null;
        $where["pos_no_trx_tanggal <= '$enddate'"]     = null;

        if (!empty($post['kecamatan_id'])) {
            $where['kecamatan_id']  = $post['kecamatan_id'];
        }

        if (!empty($post['kelurahan_id'])) {
            $where['kelurahan_id'] = $post['kelurahan_id'];
        }

        if (!empty($post['wajibpajak_id'])) {
            $where['wajibpajak_id'] = $post['wajibpajak_id'];
        }

        $this->response(
            $this->select_dt($post, 'laporannotrx', 'table', true, $where)
        );
    }

    public function select_wp()
    {
        $page = varPost('page');
        $page = $page ? (intval($page) - 1) : '0';
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pajak_wajibpajak.pemda_id', $pemda_id);
        }

        if ($q = varPost('q')) {
            $this->db->where("wajibpajak_nama ILIKE '%$q%'");
        }

        $items  = $this->db
            ->select("wajibpajak_id AS id, CONCAT(toko_kode, ' - ', toko_nama) AS text")
            ->join('pajak_toko', 'pajak_toko.toko_wajibpajak_id = pajak_wajibpajak.wajibpajak_id', 'left')
            ->where('wajibpajak_status', '2')
            ->limit(varPost('limit'), $page)
            ->get('pajak_wajibpajak')
            ->result();

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pajak_wajibpajak.pemda_id', $pemda_id);
        }

        if ($q = varPost('q')) {
            $this->db->where("wajibpajak_nama ILIKE '%$q%'");
        }

        $total_count    = $this->db->count_all_results('pajak_wajibpajak');
        $this->response([
            'items'         => $items,
            'total_count'   => $total_count
        ]);
    }
}

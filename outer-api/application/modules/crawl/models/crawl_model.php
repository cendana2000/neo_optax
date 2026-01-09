<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crawl_model extends Base_Model
{
    var $db2;
    var $optax;

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();

        $this->optax  = $this->load->database('optax', TRUE);
    }

    public function get_data_setting($npwpd)
    {
        $this->db->select("*");
        $this->db->from("tb_setting ts");

        $this->db->where("ts.deleted_at is null");
        $this->db->where("ts.npwpd", $npwpd);

        //UNTUK MERETURN DATA KE CONTROLLER
        $get = $this->db->get();
        return array(
            "data"          => $get->result_array(),
            "num_rows"      => $get->num_rows(),
            "last_query"    => $this->db->last_query()
        );
    }

    public function get_settings()
    {
        $this->db->where('deleted_at is null');
        return $this->db->get_where('tb_setting')->result();
    }

    public function store($npwpd, $source, $records, $start_date, $end_date)
    {
        $wp     = $this->optax->get_where('pajak_wajibpajak', ['wajibpajak_npwpd' => $npwpd])->row();
        if (!$wp) return;

        $this->optax->where('wajibpajak_id', $wp->wajibpajak_id);
        $this->optax->where('penjualan_tanggal >=', $start_date);
        $this->optax->where('penjualan_tanggal <=', $end_date);
        $this->optax->delete('pos_penjualan_pooling');

        $items  = [];
        foreach ($records as $record) {
            $items = [
                'penjualan_id'                  => gen_uuid('pos_penjualan_pooling'),
                'penjualan_tanggal'             => $record['penjualan_tanggal'],
                'penjualan_kode'                => $record['penjualan_kode'],
                'penjualan_total_item'          => $record['penjualan_total_item'],
                'penjualan_total_qty'           => $record['penjualan_total_qty'],
                'penjualan_sub_total'           => $record['penjualan_sub_total'],
                'penjualan_total_nilai_pajak'   => $record['penjualan_total_nilai_pajak'],
                'penjualan_total_grand'         => $record['penjualan_total_grand'],
                'penjualan_nama_customer'       => $record['penjualan_nama_customer'],
                'penjualan_user_nama'           => $record['penjualan_user_nama'],
                'penjualan_jasa'                => $record['penjualan_jasa'],
                'penjualan_source'              => $source,
                'wajibpajak_id'                 => $wp->wajibpajak_id
            ];
        }

        if (!empty($items)) {
            $this->optax->insert_batch('pos_penjualan_pooling', [$items]);
        }
    }
}

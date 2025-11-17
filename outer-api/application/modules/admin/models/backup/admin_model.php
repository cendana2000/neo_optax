<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends Base_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();
    }

    public function get_all_daftar_bonbil($status_mode, $keyword, $order, $page_data, $get_all)
    {
        $this->db->select("*, tb.created_at as tanggal_bonbil_upload");
        $this->db->from("tb_bonbil tb");
        $this->db->join("tb_user tu", "tb.id_user=tu.id_user", "left");

        if ($status_mode == "waiting") {
            $this->db->where("tb.is_valid", "0");
        } else if ($status_mode == "valid") {
            $this->db->where("tb.is_valid", "1");
        } else if ($status_mode == "reject") {
            $this->db->where("tb.is_valid", "2");
        }

        if ($keyword != "" or $keyword != null) {
            $this->db->or_like("tu.username", $keyword, 'both');
            $this->db->or_like("tu.alamat", $keyword, 'both');
            $this->db->or_like("tu.no_telp", $keyword, 'both');
            $this->db->or_like("tb.no_undian", $keyword, 'both');
        }

        $this->db->where('tb.deleted_at is null');

        $columns = array(
            0 => 'tb.id_bonbil',
            1 => 'tu.username',
            2 => 'tu.alamat',
            3 => 'tu.no_telp',
            4 => 'tb.created_at',
            5 => "(CASE WHEN tb.no_undian = '' THEN NULL ELSE tb.no_undian END)",
            6 => 'tb.is_valid'
        );

        if (!$get_all and $page_data["length"] > 0) {
            $offset = ($page_data["page"] - 1) * $page_data["length"];
            $this->db->limit($page_data["length"], $offset);
        }

        $this->db->order_by($columns[$order["column"]], $order["dir"]);

        $get = $this->db->get();

        $this->db->select("*");
        $this->db->from("tb_bonbil tb");
        $this->db->where('tb.is_valid', '0');
        $this->db->where('tb.deleted_at is null');
        $get_2 = $this->db->get();

        return array(
            "data"          => $get->result_array(),
            "num_rows"      => $get->num_rows(),
            "num_menunggu"  => $get_2->num_rows()
        );
    }

    public function get_detail_bonbil_by_id($id_bonbil)
    {
        $this->db->select("*, tb.created_at as tanggal_bonbil_upload, extract(YEAR FROM tb.created_at)::integer as tahun");
        $this->db->from("tb_bonbil tb");
        $this->db->join("tb_user tu", "tb.id_user=tu.id_user", "left");
        $this->db->join("tb_alasan ta", "ta.id_alasan=tb.id_alasan", "left");
        $this->db->order_by("tb.is_valid, tb.created_at", "desc");
        $this->db->where("tb.id_bonbil", $id_bonbil);
        $this->db->where_not_in('tb.deleted_at', NULL);
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function get_daftar_alasan_tolak()
    {
        $this->db->select("*");
        $this->db->from("tb_alasan tb");
        $this->db->where("tb.status", "1");
        $this->db->order_by("tb.alasan");
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function cek_validasi_bonbil_resi($data)
    {
        $this->db->select("*");
        $this->db->from("tb_bonbil tb");
        $this->db->where("tb.npwpd", $data["npwpd"]);
        $this->db->where("tb.no_resi", $data["no_resi"]);
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function cek_validasi_bonbil_tanggal_jam($data)
    {
        $this->db->select("*");
        $this->db->from("tb_bonbil tb");
        $this->db->where("tb.npwpd", $data["npwpd"]);
        $this->db->where("tb.tanggal_jam", $data["tanggal_jam"]);
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function cek_id_alasan($id_alasan)
    {
        $this->db->select("*");
        $this->db->from("tb_alasan ta");
        $this->db->where("ta.id_alasan", $id_alasan);
        $get = $this->db->get();
        return array(
            "data"      => $get->result_array(),
            "num_rows"  => $get->num_rows()
        );
    }

    public function simpan_alasan($data_alasan)
    {
        $this->db->insert('tb_alasan', $data_alasan);

        // Mendapatkan ID baru setelah insert
        $new_id = $this->db->insert_id();

        return array(
            "last_query" => $this->db->last_query(),
            "id"         => $new_id
        );
    }

    public function simpan_detail_bonbil($id_bonbil, $field_data)
    {
        $this->db->where('id_bonbil', $id_bonbil);
        $simpan = $this->db->update('tb_bonbil', $field_data);

        return array(
            "last_query" => $this->db->last_query(),
            "status" => $simpan
        );
    }

    public function get_last_row_no_undian($month)
    {
        $sql = "select no_undian
            from tb_bonbil tb 
            where 
                extract(month from tb.created_at)='$month'
                and no_undian is not null
            order by no_undian desc 
            limit 1
        ";

        $query = $this->db->query($sql);
        return array(
            "result"        => $query->result_array(),
            "num_rows"      => $query->num_rows(),
            "last_query"    => $this->db->last_query(),
        );
    }

    public function hapus_bonbil($field_data, $id_bonbil)
    {
        $this->db->where('id_bonbil', $id_bonbil);
        $simpan = $this->db->update('tb_bonbil', $field_data);

        return array(
            "last_query" => $this->db->last_query(),
            "status" => $simpan
        );
    }
}

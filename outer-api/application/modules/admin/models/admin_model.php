<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends Base_Model
{
    var $db2;

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();
    }

    public function is_unique($table_name, $columns)
    {
        $this->db->select("*");
        $this->db->from($table_name);

        foreach ($columns as $key => $val) {
            if ($val["type"] == null) {
                $this->db->where($val["column"] . " is null");
            } else {
                $this->db->where($val["column"], $val["val"]);
            }
        }

        $get = $this->db->get();
        return array(
            "data"          => $get->result_array(),
            "num_rows"      => $get->num_rows(),
            "last_query"    => $this->db->last_query()
        );
    }

    public function get_all_daftar_setting($status_mode, $keyword, $order, $page_data, $get_all)
    {
        $this->db->select("*");
        $this->db->from("tb_setting ts");

        if ($keyword != "" or $keyword != null) {
            $this->db->where("
                ts.nama_setting ilike '%$keyword%' OR
                ts.npwpd ilike '%$keyword%' OR
                ts.nama_wp ilike '%$keyword%' OR
                ts.preset_curl ilike '%$keyword%'
            ");
        }

        $this->db->where('ts.deleted_at is null');

        $columns = array(
            0 => 'ts.id_setting',
            1 => 'ts.nama_setting',
            2 => 'ts.npwpd',
            3 => 'ts.nama_wp',
            4 => 'ts.preset_curl',
        );

        if (!$get_all and $page_data["length"] > 0) {
            $offset = ($page_data["page"] - 1) * $page_data["length"];
            $this->db->limit($page_data["length"], $offset);
        }

        $this->db->order_by($columns[$order["column"]], $order["dir"]);

        $get = $this->db->get();

        return array(
            "data"          => $get->result_array(),
            "num_rows"      => $get->num_rows(),
        );
    }

    public function get_data_wp_by_npwpd($npwpd)
    {
        $this->db2 = $this->load->database('simpada', 1);
        $this->db2->select("p.NPWPD, wp.NAMA_WP");
        $this->db2->from("PENDAFTARAN p");
        $this->db2->join("WAJIB_PAJAK wp", "wp.ID_PENDAFTARAN=p.ID_PENDAFTARAN", "left");
        $this->db2->where("p.NPWPD='$npwpd'");

        $get = $this->db2->get();

        return array(
            "data"          => $get->result_array(),
            "num_rows"      => $get->num_rows(),
        );
    }

    public function simpan_data_setting($field_data)
    {
        $this->db->insert('tb_setting', $field_data);

        // Mendapatkan ID baru setelah insert
        $new_id = $this->db->insert_id();

        return array(
            "last_query" => $this->db->last_query(),
            "id"         => $new_id
        );
    }

    public function update_data_setting($field_data, $id_setting)
    {
        $this->db->where('id_setting', $id_setting);
        $simpan = $this->db->update('tb_setting', $field_data);

        return array(
            "last_query" => $this->db->last_query(),
            "status" => $simpan
        );
    }

    public function hapus_setting($field_data, $id_supplier)
    {
        $this->db->where('id_setting', $id_supplier);
        $simpan = $this->db->update('tb_setting', $field_data);

        return array(
            "last_query" => $this->db->last_query(),
            "status" => $simpan
        );
    }
}

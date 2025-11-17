<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends Base_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();
    }

    public function get_all_daftar_bonbil()
    {
        $this->db->select("*");
        $this->db->from("tb_bonbil tb");
        $this->db->join("tb_user tu", "tb.id_user=tu.id_user", "left");
        $this->db->order_by("tb.is_valid, tb.created_at", "desc");
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function  get_detail_bonbil_by_id($id_bonbil)
    {
        $this->db->select("*, tb.created_at as tanggal_bonbil_upload, extract(YEAR FROM tb.created_at)::integer as tahun");
        $this->db->from("tb_bonbil tb");
        $this->db->join("tb_user tu", "tb.id_user=tu.id_user", "left");
        $this->db->order_by("tb.is_valid, tb.created_at", "desc");
        $this->db->where("tb.id_bonbil", $id_bonbil);
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }

    public function  get_daftar_alasan_tolak()
    {
        $this->db->select("*");
        $this->db->from("tb_alasan tb");
        $get = $this->db->get();
        return array(
            "data" => $get->result_array(),
            "num_rows" => $get->num_rows()
        );
    }
}

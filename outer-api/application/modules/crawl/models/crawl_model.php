<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crawl_model extends Base_Model
{
    var $db2;

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();
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
}

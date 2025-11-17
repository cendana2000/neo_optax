<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends Base_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->database();
    }

    public function login($data)
    {
        $this->db->select("*");
        $this->db->from("tb_user tu");
        $this->db->where("tu.username", $data["username"]);
        $this->db->where("tu.password", $data["password"]);
        $get = $this->db->get();
        return array(
            "data"      => $get->result_array(),
            "num_rows"  => $get->num_rows()
        );
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Main_Controller
{
    var $css_plugin = array();
    var $js_plugin = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("admin/admin_model", "data_model");

        if (!$this->session->userdata("user") or $this->session->userdata("user")["role"] == "2") {
            redirect(base_url('auth/admin'));
        }
    }

    public function index()
    {
        redirect(base_url('admin/daftar_setting'));
    }

    public function daftar_setting()
    {
        array_splice(
            $this->css_plugin,
            1,
            0,
            array(
                base_url('assets/plugin/datatables/dataTables.bootstrap4.min.css'),
                base_url('assets/plugin/fontawesome/css/all.css'),
                base_url('assets/plugin/datepicker/datepicker3.css'),
                // base_url('assets/plugin/datetimepicker/datetimepicker.css'),
                base_url('assets/plugin/select2/dist/css/select2.min.css'),
                base_url('assets/plugin/sweetalert/sweetalert2.min.css'),
            )
        );

        $data["daftar_request_undian"] = array();
        $data["load_data"] = true;

        $data["css_plugin"]     = $this->css_plugin;
        $data["js_plugin"]      = $this->js_plugin;

        $data["page_title"]     = "DASHBOARD OAPI ETAX MANAGER - OPTAX";

        $data["main_content"]   = $this->load->view("admin/list", $data, true);
        $data["javascript"]     = $this->load->view("admin/javascript", $data, true);

        $data["side_bar"]       = $this->load->view("layout/sidebar", $data, true);
        $data["navbar"]         = $this->load->view("layout/navbar", $data, true);
        $data["content"]        = $this->load->view("layout/content", $data, true);
        $this->load->view('admin/html', $data);
    }

    public function get_all_daftar_setting()
    {
        header("Content-type:application/json");
        $post                       = $this->input->post();
        $get_all_daftar_setting     = $this->data_model->get_all_daftar_setting($post["status_mode"], $post["search"]["value"], $post["order"][0], array("page" => $post["page"], "length" => $post["length"]), false);
        $get_all_daftar_setting_all = $this->data_model->get_all_daftar_setting($post["status_mode"], $post["search"]["value"], $post["order"][0], array("page" => $post["page"], "length" => $post["length"]), true);

        $data = array(
            "rows"              => $get_all_daftar_setting["data"],
            "recordsFiltered"   => $get_all_daftar_setting_all["num_rows"],
            "recordsTotal"      => $get_all_daftar_setting_all["num_rows"]
        );
        print_r(json_encode($data));
    }

    public function get_nama_wp_by_npwpd()
    {
        header("Content-type:application/json");
        $post = $this->input->post();
        $result = array(
            "status" => false,
            "data"   => null,
            "msg"    => "npwpd belum diinputkan."
        );
        if ($post["npwpd"] != "") {
            $data_npwpd = $this->data_model->get_data_wp_by_npwpd($post["npwpd"]);
            if ($data_npwpd["num_rows"] > 0) {
                $result = array(
                    "status" => true,
                    "data"   => array(
                        "nama_wp"   => $data_npwpd["data"][0]["NAMA_WP"],
                        "npwpd"     => $data_npwpd["data"][0]["NPWPD"]
                    ),
                    "msg"    => "Ok."
                );
            } else {
                $result = array(
                    "status" => false,
                    "data"   => null,
                    "msg"    => "wp tidak ditemukan."
                );
            }
        }

        print_r(json_encode($result));
    }

    public function simpan_setting()
    {
        //menerima dara dari inputan browser
        $post = $this->input->post();

        //memeriksa inputan data
        // 1. melakukan cek apakah ada duplikasi data atau tidak
        // 2. melakukan cek apakah inputan bernilai kosong atau tidak
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'nama_setting',
            'Nama Setting',
            'required'
        );
        $this->form_validation->set_rules(
            'npwpd',
            'NPWPD',
            'required'
        );
        $this->form_validation->set_rules(
            'nama_wp',
            'Nama WP',
            'required'
        );
        $this->form_validation->set_rules(
            'link_curl',
            'Link CURL',
            'required'
        );
        $this->form_validation->set_message('required', 'Anda belum menginpukan %s');

        $syarat = array(
            array(
                "type"      => "string",
                "column"    => "tb_setting.nama_setting",
                "val"       => $post["nama_setting"]
            ),
            array(
                "type"      => null,
                "column"    => "tb_setting.deleted_at",
                "val"       => null
            ),
        );
        $is_unique = $this->data_model->is_unique("tb_setting", $syarat);

        if (($this->form_validation->run() == FALSE and empty($post["id"])) or ($is_unique["num_rows"] > 0 and $post["mode_simpan"] == "simpan")) {
            print_r(
                json_encode(
                    array(
                        "status" => false,
                        "msg" => (($is_unique["num_rows"] > 0) ? "<p>Nama setting yang diinput sudah ada dalam database.</p>" : validation_errors())
                    )
                )
            );
        } else {
            //jika mode simpan adalah simpan maka lakukan query insert, jika tidak lakukan query update
            if ($post["mode_simpan"] == "simpan") {
                //melakukan proses simpan data
                $data_setting = array(
                    "nama_setting"      => $post["nama_setting"],
                    "npwpd"             => $post["npwpd"],
                    "nama_wp"           => $post["nama_wp"],
                    "method"            => $post["method"],
                    "link_curl"         => $post["link_curl"],
                    "curlopt_header"    => $post["curlopt_header"],
                    "preset_curl"       => $post["preset_curl"],

                    "need_token"        => $post["need_token"],
                    "token_req_url"     => $post["token_req_url"],
                    "token_req_payload" => $post["token_req_payload"],
                    "token_req_method"  => $post["token_req_method"],
                );
                $simpan_data_setting = $this->data_model->simpan_data_setting($data_setting);
                if ($simpan_data_setting) {
                    //menyusun struktur data yang ingin direturn dan ditampilkan dibrowser
                    $return_data = array(
                        "status"    => true,
                        "msg"       => "Berhasil Menyimpan Data Setting."
                    );
                }
            } else {
                if ($is_unique["num_rows"] == 0) {
                    $data_setting = array(
                        "nama_setting"      => $post["nama_setting"],
                        "npwpd"             => $post["npwpd"],
                        "nama_wp"           => $post["nama_wp"],
                        "method"            => $post["method"],
                        "link_curl"         => $post["link_curl"],
                        "curlopt_header"    => $post["curlopt_header"],
                        "preset_curl"       => $post["preset_curl"],

                        "need_token"        => $post["need_token"],
                        "token_req_url"     => $post["token_req_url"],
                        "token_req_payload" => $post["token_req_payload"],
                        "token_req_method"  => $post["token_req_method"],

                        "updated_at"        => date("Y-m-d H:i:s"),
                    );
                    $simpan_data_setting = $this->data_model->update_data_setting($data_setting, $post["id_setting"]);

                    if ($simpan_data_setting) {
                        //menyusun struktur data yang ingin direturn dan ditampilkan dibrowser
                        $return_data = array(
                            "status"    => true,
                            "msg"       => "Berhasil Menyimpan Data Setting."
                        );
                    }
                } else {
                    $data_setting = array(
                        "npwpd"             => $post["npwpd"],
                        "nama_wp"           => $post["nama_wp"],
                        "method"            => $post["method"],
                        "link_curl"         => $post["link_curl"],
                        "curlopt_header"    => $post["curlopt_header"],
                        "preset_curl"       => $post["preset_curl"],

                        "need_token"        => $post["need_token"],
                        "token_req_url"     => $post["token_req_url"],
                        "token_req_payload" => $post["token_req_payload"],
                        "token_req_method"  => $post["token_req_method"],

                        "updated_at"        => date("Y-m-d H:i:s"),
                    );
                    $simpan_data_setting = $this->data_model->update_data_setting($data_setting, $post["id_setting"]);

                    if ($simpan_data_setting) {
                        //menyusun struktur data yang ingin direturn dan ditampilkan dibrowser
                        $return_data = array(
                            "status"    => true,
                            "msg"       => "Berhasil Menyimpan Data Setting. Tapi Nama Setting Tidak Diupdate Karena Duplikat Dengan Data Nama Setting Yang Sudah Ada."
                        );
                    }
                }
            }

            //menampilkan struktur data yang sudah diolah dalam bentuk json agar bisa dibaca dijavascript
            print_r(json_encode($return_data));
        }
    }

    public function hapus_setting()
    {
        header("Content-type:application/json");
        $post = $this->input->post();
        $data_setting = array(
            "updated_at" => date("Y-m-d H:i:s"),
            "deleted_at" => date("Y-m-d H:i:s"),
        );

        if ($post["id_setting"] != null or $post["id_setting"] != "") {
            $proses_hapus = $this->data_model->hapus_setting($data_setting, $post["id_setting"]);
            if ($proses_hapus) {
                $data = array(
                    "status" => true,
                    "msg"    => "Data Setting Berhasil Dihapus."
                );
            } else {
                $data = array(
                    "status" => false,
                    "msg"    => "Data Setting Gagal Dihapus."
                );
            }
        } else {
            $data = array(
                "status" => false,
                "msg"    => "Server Tidak Menerima Data Dengan Benar."
            );
        }

        print_r(json_encode($data));
    }
}

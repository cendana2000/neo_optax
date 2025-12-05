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



















    public function detail_bonbil_by_id()
    {
        header("Content-type:application/json");

        $data = array(
            "data"      => null,
            "msg"       => "Server Belum Menerima Data.",
            "status"    => true
        );

        $id_bonbil = $this->input->post("id_bonbil") ? $this->input->post("id_bonbil") : "";
        if ($id_bonbil != "") {
            $get_detail_bonbil_by_id = $this->data_model->get_detail_bonbil_by_id($id_bonbil);

            $data = array(
                "data"      => ($get_detail_bonbil_by_id["num_rows"] > 0) ? $get_detail_bonbil_by_id["data"][0] : $get_detail_bonbil_by_id["data"],
                "status"    => true,
                "msg"       => "OK."
            );
        }

        print_r(json_encode($data));
    }

    public function get_data_wp_etax()
    {
        header("Content-type:application/json");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pajak.malangkota.go.id/daftar_etax/home/caritempatusaha',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('return' => 'json'),
            CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=5nr22gmtcojbv7enq78jhc6fldsrosih'
            ),
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        print_r($response);
    }

    public function get_daftar_alasan_tolak()
    {
        $daftar_alasan_tolak = $this->data_model->get_daftar_alasan_tolak();

        $data = array(
            "data"      => ($daftar_alasan_tolak["num_rows"] > 0) ? $daftar_alasan_tolak["data"][0] : $daftar_alasan_tolak["data"],
            "status"    => true,
            "msg"       => "OK."
        );

        print_r(json_encode($data));
    }

    public function simpan_validasi()
    {
        header("Content-type:application/json");

        $mode_simpan    = $this->input->post("mode_simpan");
        $data           = $this->input->post('data');

        $id_bonbil      = $data["id_bonbil"];

        if ($data["is_valid"] == "2") {
            $id_alasan      = (int)((ctype_alpha($data["id_alasan"])) ? 0 : $data["id_alasan"]);

            $cek_id_alasan  = $this->data_model->cek_id_alasan($id_alasan);
            if ($cek_id_alasan["num_rows"] == 0) {
                $data_alasan = array(
                    "alasan" => $data["id_alasan"],
                );
                $simpan_alasan = $this->data_model->simpan_alasan($data_alasan);
                $data["id_alasan"] = $simpan_alasan["id"];
            }

            $no_undian = NULL;
            $data["npwpd"] = NULL;
            $data["lokasi_etax"] = NULL;
        } else {
            $data["id_alasan"] = 0;
            $no_undian = $this->generate_no_undian($data["current_created_at"]);
        }

        if ($mode_simpan == "resi") {
            $field_data = array(
                "no_undian"     => $no_undian,
                "npwpd"         => $data["npwpd"],
                "lokasi_etax"   => $data["lokasi_etax"],
                "no_resi"       => $data["no_resi"],
                "id_alasan"     => $data["id_alasan"],
                "is_valid"      => $data["is_valid"],
                "updated_at"    => date("Y-m-d H:i:s"),
                "jenis_etax"    => $data["jenis_etax"],
            );
        } else {
            $field_data = array(
                "no_undian"     => $no_undian,
                "npwpd"         => $data["npwpd"],
                "lokasi_etax"   => $data["lokasi_etax"],
                "tanggal_jam"   => $data["tanggal_jam"],
                "id_alasan"     => $data["id_alasan"],
                "is_valid"      => $data["is_valid"],
                "updated_at"    => date("Y-m-d H:i:s"),
                "jenis_etax"    => $data["jenis_etax"],
            );
        }

        $simpan_detail_bonbil = $this->data_model->simpan_detail_bonbil($id_bonbil, $field_data);
        if ($simpan_detail_bonbil["status"]) {
            $data = array(
                "status" => true,
                "msg"    => "Bonbil Berhasil Divalidasi."
            );
        } else {
            $data = array(
                "status" => false,
                "msg"    => "Bonbil Gagal Divalidasi."
            );
        }

        print_r(json_encode($data));
    }

    public function generate_no_undian($created_at)
    {
        $fullYear = date("Y"); // Mendapatkan tahun penuh (misalnya, 2023)
        $lastTwoDigitsOfYear = substr($fullYear, -2); // Mengambil dua digit terakhir
        $gsp_ke = (date("n") <= 7) ? 1 : 2;

        $month_created = date("n", strtotime($created_at));
        $get_last_row_no_undian = $this->data_model->get_last_row_no_undian($month_created);
        if ($get_last_row_no_undian["num_rows"] == 0) {
            $no_urut = 1;
            $no_undian = $lastTwoDigitsOfYear . $gsp_ke . sprintf("%02d", $month_created) . sprintf("%05d", $no_urut);
        } else {
            $no_undian_terakhir = $get_last_row_no_undian["result"][0]["no_undian"];
            $limaDigitBelakang = substr($no_undian_terakhir, -5);
            $no_urut = (int)$limaDigitBelakang + 1;
            $no_undian = $lastTwoDigitsOfYear . $gsp_ke . sprintf("%02d", $month_created) . sprintf("%05d", $no_urut);
        }

        return $no_undian;
    }

    public function hapus_bonbil()
    {
        header("Content-type:application/json");
        $post = $this->input->post();
        $data_bonbil = array(
            "updated_at" => date("Y-m-d H:i:s"),
            "deleted_at" => date("Y-m-d H:i:s"),
        );

        if ($post["id_bonbil"] != null or $post["id_bonbil"] != "") {
            $proses_hapus = $this->data_model->hapus_bonbil($data_bonbil, $post["id_bonbil"]);
            if ($proses_hapus) {
                $data = array(
                    "status" => true,
                    "msg"    => "Data Bonbil Berhasil Dihapus."
                );
            } else {
                $data = array(
                    "status" => false,
                    "msg"    => "Data Bonbil Gagal Dihapus."
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

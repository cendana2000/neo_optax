<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Main_Controller
{
    var $css_plugin = array();
    var $js_plugin = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('auth/auth_model', 'data_model');

        if (!$this->uri->segment(2) == "logout") {
            if ($this->session->userdata("user")) {
                redirect(base_url('admin'));
            }
        }
    }

    public function index()
    {
        redirect(base_url('admin'));
    }

    public function admin()
    {
        // MENAMBAHKAN CUSTOM CSS DAN JS BILA DIPERLUKAN TANPA MENGUBAH ARRAY YANG LAMA
        array_splice(
            $this->css_plugin,
            1,
            0,
            array(
                base_url('assets/plugin/fontawesome/css/all.css'),
                base_url('assets/vendor/css/pages/page-auth.css')
            )
        );

        array_splice(
            $this->js_plugin,
            0,
            0,
            array()
        );

        $data["css_plugin"]     = $this->css_plugin;
        $data["js_plugin"]      = $this->js_plugin;
        $data["page_title"]     = "API SYNCRONIZER - LOGIN";
        $data["main_content"]   = $this->load->view("auth/form_login", $data, true);
        $data["javascript"]     = $this->load->view("auth/javascript", $data, true);

        $data["side_bar"]       = "";
        $data["navbar"]         = "";
        $data["content"]        = $this->load->view("layout/content", $data, true);
        $this->load->view('auth/html', $data);
    }

    public function login()
    {
        $post = $this->input->post();
        $data = array(
            "username" => $post['username'],
            "password" => md5($post['password'])
        );

        $login = $this->data_model->login($data);

        $status = false;
        $msg = "";
        if ($login['num_rows'] > 0) {
            $status = true;
            $msg = "Berhasil Login.";
        } else {
            $msg = "Login Gagal";
        }


        if ($status) {
            $data_login = (count($login["data"]) > 0) ? $login["data"][0] : false;
            if ($data_login) {
                $this->session->set_userdata("user", $data_login);
            } else {
                $data = array(
                    "status" => false,
                    "msg"    => "Login Gagal",
                );
                $this->session->set_flashdata("info", $data);
            }
        } else {
            $data = array(
                "status" => $status,
                "msg"    => $msg,
            );
            $this->session->set_flashdata("info", $data);
        }
        redirect(base_url());
    }

    public function logout()
    {
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->sess_destroy();
        redirect(base_url());
    }
}

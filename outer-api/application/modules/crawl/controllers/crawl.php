<?php defined('BASEPATH') or exit('No direct script access allowed');

class Crawl extends Main_Controller
{
    var $css_plugin = array();
    var $js_plugin = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("crawl/crawl_model", "data_model");
    }

    public function index()
    {
        header("Content-type:application/json");
        $get       = $this->input->get();
        $npwpd      = $get["npwpd"];
        $start_date = $get["start_date"];
        $end_date   = $get["end_date"];

        #1 mengambil data setting
        $get_data_setting = $this->data_model->get_data_setting($npwpd);

        #2 cek apakah butuh token?
        $preset_curl    = $get_data_setting["data"][0]["preset_curl"];
        if ($preset_curl == "MAJOO") {
            $this->load->library("etax_crawler/majoo", "majoo");
            $data = $this->majoo->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "MOKA") {
            $this->load->library("etax_crawler/moka", "moka");
            $next_cursor = null;
            $data = $this->moka->get_data($get_data_setting, $start_date, $end_date, "");

            #jika halaman dari curl sebelumnya > 1 maka ambil data lagi!
            if (count($data) > 0) {
                $next_cursor = $data[0]["next_cursor"];

                while ($next_cursor != null) {
                    $ambil_lagi = $this->moka->get_data($get_data_setting, $start_date, $end_date, $next_cursor);
                    $data = array_merge($data, $ambil_lagi);

                    if (count($ambil_lagi) > 0) {
                        $next_cursor = $ambil_lagi[0]["next_cursor"];
                    }
                }
            }
        } elseif ($preset_curl == "OLSERA") {
            $this->load->library("etax_crawler/olsera", "olsera");
            $data = $this->olsera->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "OLSERAV2") {
            $this->load->library("etax_crawler/olserav2", "olserav2");
            $data = $this->olserav2->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "PARAGON") {
            $this->load->library("etax_crawler/paragon", "paragon");
            $data = $this->paragon->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "PAWOON") {
            $this->load->library("etax_crawler/pawoon", "pawoon");
            $data = $this->pawoon->get_data($get_data_setting, $start_date, $end_date, 1);

            #jika halaman dari curl sebelumnya > 1 maka ambil data lagi!
            if (count($data) > 0) {
                $total_page = $data[0]["total_page"];
                for ($i = 2; $i <= $total_page; $i++) {
                    $data = array_merge($data, $this->pawoon->get_data($get_data_setting, $start_date, $end_date, $i));
                }
            }
        } elseif ($preset_curl == "ESBPOS") {
            $this->load->library("etax_crawler/esbpos", "esbpos");
            $data = $this->esbpos->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "ESBPOSV2") {
            $this->load->library("etax_crawler/esbposv2", "esbposv2");
            $data = $this->esbposv2->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "NUTAPOS") {
            $this->load->library("etax_crawler/nutapos", "nutapos");
            $data = $this->nutapos->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "LARISPOS") {
            $this->load->library("etax_crawler/larispos", "larispos");
            $data = $this->larispos->get_data($get_data_setting, $start_date, $end_date, 1);

            #jika halaman dari curl sebelumnya > 1 maka ambil data lagi!
            if (count($data) > 0) {
                $total_page = $data[0]["total_page"];
                for ($i = 2; $i <= $total_page; $i++) {
                    $data = array_merge($data, $this->larispos->get_data($get_data_setting, $start_date, $end_date, $i));
                }
            }
        } elseif ($preset_curl == "LARISPOSV2") {
            $this->load->library("etax_crawler/larisposv2", "larisposv2");
            $data = $this->larisposv2->get_data($get_data_setting, $start_date, $end_date, 1);
        } elseif ($preset_curl == "LOYVERSE") {
            $this->load->library("etax_crawler/loyverse", "loyverse");
            $data = $this->loyverse->get_data($get_data_setting, $start_date, $end_date, 1);
        } elseif ($preset_curl == "KASIRPINTAR") {
            $this->load->library("etax_crawler/kasirpintar", "kasirpintar");
            $data = $this->kasirpintar->get_data($get_data_setting, $start_date, $end_date, 1);
        } elseif ($preset_curl == "GOBIZ") {
            $this->load->library("etax_crawler/gobiz", "gobiz");
            $merchandID = json_decode($get_data_setting["data"][0]["token_req_payload"])->merchant_id;
            $outlet     = json_decode($get_data_setting["data"][0]["token_req_payload"])->outlet;
            $data       = $this->gobiz->get_data($get_data_setting, $start_date, $end_date, 0, $merchandID, $outlet, "");

            $maximumData = 500;
            $total_halaman = ceil($data[0]["total_data"] / $maximumData);
            if ($total_halaman > 1) {
                $last_token = $data[0]["last_token"];
                for ($i = 1; $i <= $total_halaman; $i++) {
                    $from = ($i - 1) * $maximumData;
                    $data       = $this->gobiz->get_data($get_data_setting, $start_date, $end_date, $from, $merchandID, $outlet, $last_token);
                }
            }
        } elseif ($preset_curl == "LUNAPOS") {
            $this->load->library("etax_crawler/lunapos", "lunapos");
            $data = $this->lunapos->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "ISELLER") {
            $this->load->library("etax_crawler/iseller", "iseller");
            $data = $this->iseller->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "WOOGIGS") {
            $this->load->library("etax_crawler/woogigs", "woogigs");
            $data = $this->woogigs->get_data($get_data_setting, $start_date, $end_date);
        } elseif ($preset_curl == "OMEGAPOS") {
            $this->load->library("etax_crawler/omegapos", "omegapos");
            $data = $this->omegapos->get_data($get_data_setting, $start_date, $end_date);
        }

        $this->generate_log(json_encode($data), $npwpd, $preset_curl);
        print_r(json_encode($data));
    }

    public function generate_log($data, $npwpd, $preset_curl)
    {
        file_put_contents(FCPATH . "assets/data/log_access.log", "[" . date("Y-m-d H:i:s") . "] - [$npwpd] - [$preset_curl] - " . $data . PHP_EOL, FILE_APPEND);
    }

    public function logsyncoapireader()
    {
        $data_log = file_get_contents(FCPATH . "assets/data/log_access.log");
        $data_log_string = "";
        $exp_data_log = explode(PHP_EOL, $data_log);
        for ($i = count($exp_data_log); $i > (count($exp_data_log) - 10); $i--) {
            if (trim($exp_data_log[$i]) != "") {
                $data_log_string .= $exp_data_log[$i] . '<br>';
            }
        }

        // print_r($data_log_string);
        print_r(json_encode(array("status" => true, "log" => $data_log_string)));
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

class Paragon
{
    /**
     * Get an instance of CodeIgniter
     *
     * @access  protected
     * @return  void
     */
    protected function ci()
    {
        return get_instance();
    }

    public function get_data($get_data_setting, $start_date, $end_date)
    {
        #1 ambil token
        $token_req_url     =  $get_data_setting["data"][0]["token_req_url"];
        $token_req_method  =  $get_data_setting["data"][0]["token_req_method"];
        $token_req_payload =  $get_data_setting["data"][0]["token_req_payload"];

        #2 mengambil token
        $data_token        = json_decode($this->get_token($token_req_url, null, $token_req_method, $token_req_payload));
        $token             = $data_token->token;

        #3 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}"), array($start_date, $end_date), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array($token), $get_data_setting["data"][0]["curlopt_header"]);

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl;
        $data_response  = array();

        if (count($data_transaksi) > 0) {
            foreach ($data_transaksi as $key => $val) {
                if (strpos($val->penjualan_kode, "B-") !== 0) {
                    array_push($data_response, array(
                        "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->penjualan_tanggal)),
                        "penjualan_kode"                => $val->penjualan_kode,
                        "penjualan_total_item"          => $val->penjualan_total_item,
                        "penjualan_total_qty"           => $val->penjualan_total_qty,
                        "penjualan_sub_total"           => (float)str_replace(",", "", number_format($val->penjualan_sub_total, 2)),
                        "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($val->penjualan_total_nilai_pajak, 2)),
                        "penjualan_total_grand"         => $val->penjualan_total_grand,
                        "penjualan_nama_customer"       => $val->penjualan_nama_customer,
                        "penjualan_user_nama"           => $val->penjualan_user_nama,
                        "penjualan_jasa"                => $val->penjualan_jasa,
                    ));
                }
            }
        }

        return $data_response;
    }

    public function get_token($link, $header, $method, $post_field)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $post_field,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function do_crawl($link, $header, $method)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $link,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                $header
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

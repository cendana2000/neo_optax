<?php defined('BASEPATH') or exit('No direct script access allowed');

class Moka
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

    public function get_data($get_data_setting, $start_date, $end_date, $next_cursor)
    {
        #1 ambil token
        $token_req_url     =  $get_data_setting["data"][0]["token_req_url"];
        $token_req_method  =  $get_data_setting["data"][0]["token_req_method"];
        $token_req_payload =  $get_data_setting["data"][0]["token_req_payload"];

        #2 mengambil token
        $data_token        = json_decode($this->get_token($token_req_url, null, $token_req_method, $token_req_payload));
        $token             = $data_token->access_token;
        $outlet            = $data_token->outlets[0]->name;

        #3 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $start_date     = date("Y-m-01\T00:00:00\Z", strtotime($start_date));
        $end_date       = date("Y-m-t\T23:59:59\Z", strtotime($end_date));
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}", "{{next_cursor}}"), array($start_date, $end_date, $next_cursor), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array($token), $get_data_setting["data"][0]["curlopt_header"]);

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->orders;
        $data_response  = array();

        if (is_countable($data_transaksi)) {
            if (count($data_transaksi) > 0) {
                foreach ($data_transaksi as $key => $val) {
                    $total      = $val->total_collected;
                    $sub_total  = $total - ($total / 11);
                    $pajak      = $total - $sub_total;

                    array_push($data_response, array(
                        "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->created_at)),
                        "penjualan_kode"                => substr($val->uuid, -12),
                        "penjualan_total_item"          => count(explode(",", $val->item_name)),
                        "penjualan_total_qty"           => 0,
                        "penjualan_sub_total"           => (float)str_replace(",", "", number_format($sub_total, 2)),
                        "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($pajak, 2)),
                        "penjualan_total_grand"         => $total,
                        "penjualan_nama_customer"       => "",
                        "penjualan_user_nama"           => $outlet,
                        "penjualan_jasa"                => null,
                        "next_cursor"                   => $data_curl->next_cursor,
                        "curl_link"                     => $curl_link
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
            CURLOPT_HTTPHEADER => preg_split("/\r\n|\n|\r/", trim($header)),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

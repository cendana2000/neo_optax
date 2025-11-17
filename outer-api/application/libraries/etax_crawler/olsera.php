<?php defined('BASEPATH') or exit('No direct script access allowed');

class Olsera
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
        $token             = $data_token->access_token;

        #3 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $start_date     = date("Y-m-01", strtotime($start_date));
        $end_date       = date("Y-m-t", strtotime($start_date));

        // $start_date     = "2024-10-14";
        // $end_date       = "2024-10-14";

        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}"), array($start_date, $end_date), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array($token), $get_data_setting["data"][0]["curlopt_header"]);

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->data;
        $data_response  = array();

        if (is_countable($data_transaksi)) {
            if (count($data_transaksi) > 0) {
                foreach ($data_transaksi as $key => $val) {
                    // if ($key > 29) {
                    //     break;
                    // }

                    // Pengecekan panjang karakter order_no
                    if (strlen($val->order_no) > 32) {
                        continue; // Lewati iterasi ini jika order_no lebih dari 32 karakter
                    }

                    $total      = (float)str_replace(",", "", number_format($val->total_amount, 2));
                    $sub_total  = $total - ($total / 11);
                    $pajak      = $total - $sub_total;

                    array_push($data_response, array(
                        "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->order_time)),
                        "penjualan_kode"                => $val->order_no, //substr(, -12),
                        "penjualan_total_item"          => 1,
                        "penjualan_total_qty"           => (int)$val->total_qty,
                        "penjualan_sub_total"           => (float)str_replace(",", "", number_format($sub_total, 2)),
                        "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($pajak, 2)),
                        "penjualan_total_grand"         => $total,
                        "penjualan_nama_customer"       => $val->customer_id . "",
                        "penjualan_user_nama"           => $get_data_setting["data"][0]["nama_wp"],
                        "penjualan_jasa"                => (float)$val->service_charge_amount,
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

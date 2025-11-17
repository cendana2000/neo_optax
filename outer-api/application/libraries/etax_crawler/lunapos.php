<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lunapos
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
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}"), array($start_date, $end_date), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array($token), $get_data_setting["data"][0]["curlopt_header"]);
        $outlet_id      = $data_token->user_outlet_id;

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method, $start_date, $end_date, $outlet_id);

        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->data->records;
        $data_response  = array();

        if (is_countable($data_transaksi)) {
            if (count($data_transaksi) > 0) {
                foreach ($data_transaksi as $key => $val) {
                    // $total      = (float)str_replace(",", "", number_format($val->total, 2));
                    $discount   = (float)str_replace(",", "", number_format($val->discountAmount, 2));
                    $sub_total  = (float)str_replace(",", "", number_format($val->subtotal - $discount, 2));
                    $pajak      = (float)str_replace(",", "", number_format($sub_total / 10, 2));

                    array_push($data_response, array(
                        "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->transactionDate . " " . $val->transactionTime)),
                        "penjualan_kode"                => $val->transactionNumber, //substr(, -12),
                        "penjualan_total_item"          => $val->lineCount,
                        "penjualan_total_qty"           => (float)$val->lineTotalQty,
                        "penjualan_sub_total"           => $sub_total,
                        "penjualan_total_nilai_pajak"   => $pajak,
                        "penjualan_total_grand"         => $sub_total + $pajak,
                        "penjualan_nama_customer"       => $val->customer->displayName,
                        "penjualan_user_nama"           => $get_data_setting["data"][0]["nama_wp"],
                        "penjualan_jasa"                => 0,
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
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: ARRAffinity=0c161a19deaf7f7b913142dc0cb6daf844dff7d102ec78e5b72d4600ae9b0725'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function do_crawl($link, $header, $method, $start_date, $end_date, $outlet_id)
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
            CURLOPT_POSTFIELDS => '{
                "lowDate":"' . $start_date . '",
                "highDate":"' . $end_date . '",
                "transactionTypes":["sales_invoice","sales_credit_note"],
                "statuses":["open","closed"],
                "paymentStatuses":[""],
                "outletIds":[' . $outlet_id . '],
                "salesTypes":[],
                "take":10000,
                "skip":0
            }',
            CURLOPT_HTTPHEADER => array(
                $header,
                'Content-Type: application/json',
                'Cookie: ARRAffinity=f8d7fadf2909ff4e586acf5f4d58abc3ba7f56ba5654aec79a4f0995507cc6b6'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

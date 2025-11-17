<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pawoon
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

    public function get_data($get_data_setting, $start_date, $end_date, $page)
    {

        #1 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $start_date     = date("Y-m-01", strtotime($start_date));
        $end_date       = date("Y-m-t", strtotime($start_date));
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}", "{{page}}"), array($start_date, $end_date, $page), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array(""), $get_data_setting["data"][0]["curlopt_header"]);


        #2 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #3 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->report->data;
        $data_response  = array();


        if (is_countable($data_transaksi)) {
            if (count($data_transaksi) > 0) {
                foreach ($data_transaksi as $key => $val) {
                    array_push($data_response, array(
                        "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->transaction_date)),
                        "penjualan_kode"                => $val->receipt_code,
                        "penjualan_total_item"          => count($val->items),
                        "penjualan_total_qty"           => $val->summaries->sum_qty_product,
                        "penjualan_sub_total"           => $val->summaries->sum_product_price,
                        "penjualan_total_nilai_pajak"   => $val->taxes_and_services[0]->amount,
                        "penjualan_total_grand"         => $val->final_amount,
                        "penjualan_nama_customer"       => "",
                        "penjualan_user_nama"           => $val->outlet_name,
                        "penjualan_jasa"                => null,
                        "current_page"                  => $data_curl->report->current_page,
                        "total_page"                    => $data_curl->report->last_page,
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

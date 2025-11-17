<?php defined('BASEPATH') or exit('No direct script access allowed');

class Woogigs
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
        #3 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $start_date     = date("Y-m-01", strtotime($start_date));
        $end_date       = date("Y-m-t", strtotime($start_date));
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}"), array($start_date, $end_date), $curl_link);

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $method);


        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode(json_decode($curl_response));

        $data_transaksi = $data_curl->data;
        $data_response  = array();

        if ($data_curl->success) {
            foreach ($data_transaksi as $key => $val) {
                $total      = (float)str_replace(",", "", number_format($val->total, 2));
                $sub_total  = (float)str_replace(",", "", number_format($val->subtotal, 2));
                $pajak      = (float)str_replace(",", "", number_format($val->tax, 2));

                array_push($data_response, array(
                    "penjualan_tanggal"             => date("Y-m-d", strtotime($val->date_paid)),
                    "penjualan_kode"                => $val->receipt_code,
                    "penjualan_total_item"          => 0,
                    "penjualan_total_qty"           => (float)str_replace(",", "", number_format($val->total_qty, 2)),
                    "penjualan_sub_total"           => $sub_total,
                    "penjualan_total_nilai_pajak"   => $pajak,
                    "penjualan_total_grand"         => $total,
                    "penjualan_nama_customer"       => $val->customer_name,
                    "penjualan_user_nama"           => $val->business_name,
                    "penjualan_jasa"                => (float)str_replace(",", "", number_format($val->service_charge, 2)),
                ));
            }
        }

        return $data_response;
    }


    public function do_crawl($link, $method)
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
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

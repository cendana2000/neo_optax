<?php defined('BASEPATH') or exit('No direct script access allowed');

class Larispos
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
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}", "{{page}}"), array($start_date, $end_date, $page), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array(""), $get_data_setting["data"][0]["curlopt_header"]);

        #2 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #3 mengambil data dom
        #BILL
        preg_match_all('!<tr class="data-row.+?>.+?<td>.+?<b>(.+?)<\/b!is', $curl_response, $match);
        $bill = $match[1];

        #BRAND NAME
        preg_match_all('!<tr class="data-row.+?>.+?<td>.+?<b>.+?<\/b>.+?<\/td>.+?<td>(.+?)<\/td>!is', $curl_response, $match);
        $brand_name = $match[1];

        #TANGGAL PENJUALAN
        preg_match_all('!<tr class="data-row.+?>.+?<td>.+?<b>.+?<\/b>.+?<\/td>.+?<td>.+?<\/td>.+?<td>.+?<\/td>.+?<td>(.+?)<\/td>!is', $curl_response, $match);
        $tanggal_penjualan = $match[1];

        #GRAND TOTAL
        preg_match_all('!<tr class="data-row.+?>.+?<td>.+?<b>.+?<\/b>.+?<\/td>.+?<td>.+?<\/td>.+?<td>.+?<\/td>.+?<td>.+?<\/td>.+?<td>.+?<\/td>.+?<td>.+?<\/td>.+?<td><b>(.+?)<\/b><\/td>!is', $curl_response, $match);
        $grand_total = $match[1];

        #LAST PAGE
        preg_match_all('!title="Last page (.+?)"!is', $curl_response, $match);
        $last_page = $match[1];

        $data_transaksi = array("data" => array());
        foreach ($bill as $key => $val) {
            array_push($data_transaksi["data"], array(
                "billNum"       => $val,
                "salesOutDate"  => date("Y-m-d", strtotime(str_replace(" -", "", $tanggal_penjualan[$key]))),
                "total_item"    => 1,
                "total_qty"     => 1,
                "grandTotal"    => str_replace(",", "", $grand_total[$key]),
                "brandName"     => $brand_name[$key],
                "last_page"     => $last_page[$key],
            ));
        }
        $curl_response = json_encode($data_transaksi);

        #4 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->data;
        $data_response  = array();

        if (count($data_transaksi) > 0) {
            foreach ($data_transaksi as $key => $val) {
                $total      = (float)$val->grandTotal;
                $sub_total  = $total - ($total / 11);
                $pajak      = $total - $sub_total;

                array_push($data_response, array(
                    "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime($val->salesOutDate)),
                    "penjualan_kode"                => $val->billNum,
                    "penjualan_total_item"          => (int)$val->total_item,
                    "penjualan_total_qty"           => (int)$val->total_qty,
                    "penjualan_sub_total"           => (float)str_replace(",", "", number_format($sub_total, 2)),
                    "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($pajak, 2)),
                    "penjualan_total_grand"         => $total,
                    "penjualan_nama_customer"       => "",
                    "penjualan_user_nama"           => $val->brandName,
                    "penjualan_jasa"                => null,
                    "total_page"                    => $val->last_page
                ));
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

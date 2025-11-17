<?php defined('BASEPATH') or exit('No direct script access allowed');

class Omegapos
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
        $data_curl      = json_decode($curl_response);

        $data_transaksi = $data_curl->orderData;

        #3 mengambil data dom
        #BILL
        preg_match_all('!<tr>\s+<td>(.+?)<\/td>!is', $data_transaksi, $match);
        $bill = $match[1];

        #TANGGAL PENJUALAN
        preg_match_all('!<tr>\s+<td>.+?<\/td>\s+<td>(.+?)<\/td>!is', $data_transaksi, $match);
        $tanggal = $match[1];

        #QTY
        preg_match_all('!<tr>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td style=".+?">(\d+)<\/td>!is', $data_transaksi, $match);
        $qty = $match[1];

        #PAJAK
        preg_match_all('!<tr>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>Rp(.+?)<\/td>!is', $data_transaksi, $match);
        $pajak = $match[1];

        #SERVICE CHARGE
        preg_match_all('!<tr>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>.+?<\/td>\s+<td>Rp.+?<\/td>\s+<td>Rp(.+?)<\/td>!is', $data_transaksi, $match);
        $service_charge = $match[1];

        #4 merge bill number
        // buat struktur data array untuk melakukan merge data bonbil
        $dataBill = array();
        foreach ($bill as $key => $val) {
            if (!array_key_exists($val, $dataBill)) {
                $dataBill[$val] = array(
                    "tanggal"           => $tanggal[$key],
                    "total_item"        => 1,
                    "total_qty"         => (int)$qty[$key],
                    "pajak_total"       => (int)str_replace(array(".", ", 00", ",00"), "", $pajak[$key]),
                    "service_charge"    => (int)str_replace(array(".", ", 00", ",00"), "", $service_charge[$key]),
                );
            } else {
                $dataBill[$val] = array(
                    "tanggal"           => $tanggal[$key],
                    "total_item"        => $dataBill[$val]["total_item"] + 1,
                    "total_qty"         => $dataBill[$val]["total_qty"] + (int)$qty[$key],
                    "pajak_total"       => $dataBill[$val]["pajak_total"] + (int)str_replace(array(".", ", 00", ",00"), "", $pajak[$key]),
                    "service_charge"    => $dataBill[$val]["service_charge"] + (int)str_replace(array(".", ", 00", ",00"), "", $service_charge[$key])
                );
            }
        }

        $data_response = array();

        foreach ($dataBill as $key => $val) {
            $sub_total      = (float)($val["pajak_total"] * 10);
            $pajak          = (float)($val["pajak_total"]);
            $total          = (float)($pajak + $sub_total);
            $service_charge = (float)($val["service_charge"]);

            array_push($data_response, array(
                "penjualan_tanggal"             => date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $val["tanggal"]))),
                "penjualan_kode"                => $key,
                "penjualan_total_item"          => $val["total_item"],
                "penjualan_total_qty"           => $val["total_qty"],
                "penjualan_sub_total"           => $sub_total,
                "penjualan_total_nilai_pajak"   => $pajak,
                "penjualan_total_grand"         => $total,
                "penjualan_nama_customer"       => "",
                "penjualan_user_nama"           => "",
                "penjualan_jasa"                => $service_charge,
            ));
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

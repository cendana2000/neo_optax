<?php defined('BASEPATH') or exit('No direct script access allowed');

class EsbposV2
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
        #1 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $page           = 1;
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $curl_link      = str_replace(array("{{start_date}}", "{{end_date}}", "{{page}}"), array($start_date, $end_date, $page), $curl_link);
        $curlopt_header = str_replace(array("{{token}}"), array(""), $get_data_setting["data"][0]["curlopt_header"]);

        #2 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method);

        #3 mengambil data dom
        #BILL
        preg_match_all('!data-col-seq="2">(.*?)<\/td>!is', $curl_response, $match);
        $bill = $match[1];

        #TANGGAL PENJUALAN
        preg_match_all('!data-col-seq="4">(.*?)<\/td>!is', $curl_response, $match);
        $tanggal = $match[1];

        #WAKTU PENJUALAN
        preg_match_all('!data-col-seq="5">(.*?)<\/td>!is', $curl_response, $match);
        $waktu = $match[1];

        #GRAND TOTAL
        preg_match_all('!data-col-seq="40">(.*?)<\/td>!', $curl_response, $match);
        $grand_total = $match[1];

        #NAMA PEMBELI
        preg_match_all('!data-col-seq="43">(.*?)<\/td>!', $curl_response, $match);
        $nama_pembeli = $match[1];

        #BRAND NAME
        preg_match_all('!data-col-seq="8">(.*?)<\/td>!', $curl_response, $match);
        $brand_name = $match[1];

        $data_transaksi = array("data" => array());
        foreach ($bill as $key => $val) {
            array_push($data_transaksi["data"], array(
                "billNum"                   => $val,
                "salesOutDate"              => str_replace("/", "-", $tanggal[$key]) . " " . $waktu[$key],
                "total_item"                => 1,
                "total_qty"                 => 1,
                "grandTotal"                => str_replace(".", "", $grand_total[$key]),
                "brandName"                 => $brand_name[$key],
                "penjualan_nama_customer"   => $nama_pembeli[$key],
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
                    "penjualan_tanggal"             => date("Y-m-d", strtotime($val->salesOutDate)),
                    "penjualan_kode"                => $val->billNum,
                    "penjualan_total_item"          => (int)$val->total_item,
                    "penjualan_total_qty"           => (int)$val->total_qty,
                    "penjualan_sub_total"           => (float)str_replace(",", "", number_format($sub_total, 2)),
                    "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($pajak, 2)),
                    "penjualan_total_grand"         => $total,
                    "penjualan_nama_customer"       => $val->penjualan_nama_customer,
                    "penjualan_user_nama"           => $val->brandName,
                    "penjualan_jasa"                => null
                ));
            }
        }

        return $data_response;
    }

    public function format_tanggal_dari_bill($bill)
    {
        // String asli
        $stringAwal = $bill;

        // Memisahkan string berdasarkan delimiter "/"
        $pecahString = explode("/", $stringAwal);

        // Mengambil bagian yang diperlukan (240105)
        $kodeTransaksi = $pecahString[1];

        // Mengambil tahun, bulan, dan tanggal dari kode transaksi
        $tahun = "20" . substr($kodeTransaksi, 0, 2);
        $bulan = substr($kodeTransaksi, 2, 2);
        $tanggal = substr($kodeTransaksi, 4, 2);

        // Membentuk tanggal dalam format yang diinginkan
        $tanggalLengkap = $tahun . "-" . $bulan . "-" . $tanggal;
        return $tanggalLengkap;
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

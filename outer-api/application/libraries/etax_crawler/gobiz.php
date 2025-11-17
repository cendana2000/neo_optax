<?php defined('BASEPATH') or exit('No direct script access allowed');

class Gobiz
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

    public function get_data($get_data_setting, $start_date, $end_date, $from, $merchandID, $outlet, $last_token)
    {
        #1 ambil token
        $token_req_url     =  $get_data_setting["data"][0]["token_req_url"];
        $token_req_method  =  $get_data_setting["data"][0]["token_req_method"];
        $token_req_payload =  $get_data_setting["data"][0]["token_req_payload"];

        #2 mengambil token
        if ($last_token != "") {
            $token             = $last_token;
        } else {
            $get_token         = $this->get_token($token_req_url, null, $token_req_method, $token_req_payload);
            $data_token        = json_decode($get_token);
            $token             = $data_token->access_token;
            print_r($get_token);
        }

        print_r($token);
        die;

        #3 mengambil data parameter yang dibutuhkan untuk curl ke endpoint data transaksi
        $method         = $get_data_setting["data"][0]["method"];
        $curl_link      = $get_data_setting["data"][0]["link_curl"];
        $start_date     = date("Y-m-01", strtotime($start_date));
        $end_date       = date("Y-m-t", strtotime($start_date));
        $curlopt_header = str_replace(array("{{token}}"), array($token), $get_data_setting["data"][0]["curlopt_header"]);

        #4 mengambil data dari endpoint data transaksi
        $curl_response = $this->do_crawl($curl_link, $curlopt_header, $method, $from, $merchandID, $start_date, $end_date);

        #5 olah data dan sesuaikan kayak dipersada
        $data_curl      = json_decode($curl_response);
        $data_transaksi = $data_curl->hits;
        $data_response  = array();

        if (is_countable($data_transaksi)) {
            if (count($data_transaksi) > 0) {
                foreach ($data_transaksi as $key => $val) {
                    $total      = ($val->amount / 100);
                    $sub_total  = $total - ($total / 11);
                    $pajak      = $total - $sub_total;

                    // Buat objek DateTime dari string tanggal
                    $date = new DateTime($val->metadata->transaction->transaction_time);
                    // Ubah format tanggal menjadi Y-m-d
                    $penjualan_tanggal = $date->format('Y-m-d');

                    //menghitung total qty
                    $penjualan_total_item = 1;
                    $penjualan_total_qty  = 1;
                    if (is_countable($val->metadata->transaction->items)) {
                        $penjualan_total_item = count($val->metadata->transaction->items);
                        foreach ($val->metadata->transaction->items as $k => $v) {
                            $penjualan_total_qty += $v->quantity;
                        }
                    }

                    array_push($data_response, array(
                        "penjualan_tanggal"             => $penjualan_tanggal,
                        "penjualan_kode"                => $val->metadata->transaction->order_id,
                        "penjualan_total_item"          => $penjualan_total_item,
                        "penjualan_total_qty"           => $penjualan_total_qty,
                        "penjualan_sub_total"           => (float)str_replace(",", "", number_format($sub_total, 2)),
                        "penjualan_total_nilai_pajak"   => (float)str_replace(",", "", number_format($pajak, 2)),
                        "penjualan_total_grand"         => $total,
                        "penjualan_nama_customer"       => "",
                        "penjualan_user_nama"           => $outlet,
                        "penjualan_jasa"                => null,
                        "curl_link"                     => $curl_link,
                        "total_data"                    => $data_curl->total,
                        "last_token"                    => $token
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

    public function do_crawl($link, $header, $method, $from, $merchandID, $start_date, $end_date)
    {
        $curl = curl_init();
        eval('$header = array(' . $header . ');');

        // var_dump($header);
        // die;

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
                "from": ' . $from . ',
                "size": 500,
                "sort": {
                    "time": {
                        "order": "desc"
                    }
                },
                "included_categories": {
                    "incoming": [
                        "transaction_share",
                        "action"
                    ]
                },
                "query": [
                    {
                        "clauses": [
                            {
                                "op": "not",
                                "clauses": [
                                    {
                                        "clauses": [
                                            {
                                                "field": "metadata.source",
                                                "op": "in",
                                                "value": [
                                                    "GOSAVE_ONLINE",
                                                    "GoSave",
                                                    "GODEALS_ONLINE"
                                                ]
                                            },
                                            {
                                                "field": "metadata.gopay.source",
                                                "op": "in",
                                                "value": [
                                                    "GOSAVE_ONLINE",
                                                    "GoSave",
                                                    "GODEALS_ONLINE"
                                                ]
                                            }
                                        ],
                                        "op": "or"
                                    }
                                ]
                            },
                            {
                                "field": "metadata.transaction.status",
                                "op": "in",
                                "value": [
                                    "settlement",
                                    "capture",
                                    "refund",
                                    "partial_refund"
                                ]
                            },
                            {
                                "op": "or",
                                "clauses": [
                                    {
                                        "op": "or",
                                        "clauses": [
                                            {
                                                "field": "metadata.transaction.payment_type",
                                                "op": "in",
                                                "value": [
                                                    "qris",
                                                    "gopay",
                                                    "cash",
                                                    "offline_ovo",
                                                    "offline_telkomsel_cash",
                                                    "offline_credit_card",
                                                    "offline_debit_card",
                                                    "credit_card",
                                                    "grab_food",
                                                    "shopee_food",
                                                    "traveloka_eats"
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                "field": "metadata.transaction.transaction_time",
                                "op": "gte",
                                "value": "' . $start_date . 'T17:00:00.000Z"
                            },
                            {
                                "field": "metadata.transaction.transaction_time",
                                "op": "lte",
                                "value": "' . $end_date . 'T16:59:59.999Z"
                            },
                            {
                                "field": "metadata.transaction.merchant_id",
                                "op": "equal",
                                "value": "' . $merchandID . '"
                            }
                        ],
                        "op": "and"
                    }
                ]
            }',
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        var_dump($response);
        die;

        curl_close($curl);
        return $response;
    }
}

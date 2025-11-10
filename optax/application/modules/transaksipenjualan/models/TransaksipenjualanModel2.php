<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksipenjualanModel2 extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_penjualan',
                'primary' => 'penjualan_id',
                'fields' => array(
                    array('name' => 'penjualan_id'),
                    array('name' => 'penjualan_tanggal'),
                    array('name' => 'penjualan_kode'),
                    array('name' => 'penjualan_anggota_id'),
                    array('name' => 'penjualan_total_item'),
                    array('name' => 'penjualan_total_qty'),
                    array('name' => 'penjualan_total_harga'),
                    array('name' => 'penjualan_total_grand'),
                    array('name' => 'penjualan_total_bayar'),
                    array('name' => 'penjualan_total_bayar_tunai'),
                    array('name' => 'penjualan_total_bayar_voucher'),
                    array('name' => 'penjualan_total_bayar_voucher_khusus'),
                    array('name' => 'penjualan_total_bayar_voucher_lain'),
                    array('name' => 'penjualan_total_potongan'),
                    array('name' => 'penjualan_total_potongan_persen'),
                    array('name' => 'penjualan_total_kembalian'),
                    array('name' => 'penjualan_total_kredit'),
                    array('name' => 'penjualan_total_cicilan'),
                    array('name' => 'penjualan_total_cicilan_qty'),
                    array('name' => 'penjualan_total_jasa'),
                    array('name' => 'penjualan_total_jasa_nilai'),
                    array('name' => 'penjualan_total_retur'),
                    array('name' => 'penjualan_kredit_awal'),
                    array('name' => 'penjualan_jatuh_tempo'),
                    array('name' => 'penjualan_jenis_potongan'),
                    array('name' => 'penjualan_user_id'),
                    array('name' => 'penjualan_created'),
                    array('name' => 'penjualan_user_nama'),
                    array('name' => 'penjualan_keterangan'),
                    array('name' => 'penjualan_kasir'),
                    array('name' => 'penjualan_metode'),
                    array('name' => 'penjualan_jenis_barang'),
                    array('name' => 'pos_penjualan_customer_id'),
                    array('name' => 'penjualan_lock'),
                    array('name' => 'penjualan_bank'),
                    array('name' => 'penjualan_bank_ref'),
                    array('name' => 'penjualan_bank'),
                    array('name' => 'penjualan_meja_id'),
                    array('name' => 'detail_id',         'view' => true),
                    array('name' => 'meja_nama',         'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_pos_penjualan2',
                'mode' => array(
                    'table' => array(
                        'penjualan_id',
                        'penjualan_kode',
                        'penjualan_tanggal',
                        'customer_nama',
                        'customer_id',
                        'penjualan_total_harga',
                        'penjualan_total_potongan',
                        'penjualan_total_grand',
                        'penjualan_total_potongan_persen',
                        'pos_penjualan_customer_id',
                        'penjualan_bank',
                        'meja_nama',
                    ),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }

    public function gen_kode_penjualan($value = false, $trans)
    {
        $kode = $this->db->query('SELECT penjualan_tanggal, penjualan_kode FROM pos_penjualan order by penjualan_created desc limit 1')->result_array();
        if (isset($kode[0]['penjualan_kode'])) {
            if ($kode[0]['penjualan_tanggal'] < date('Y-m-d', strtotime($trans['penjualan_tanggal']))) {
                $last_kode = '001';
            } else {
                $last_kode = substr($kode[0]['penjualan_kode'], 1, 3);
                $last_kode = str_pad($last_kode + 1, 3, 0, STR_PAD_LEFT);
            }
        }
        return 'T' . $last_kode . $trans['penjualan_metode'];
    }
}

/* End of file TransaksipenjualanModel.php */
/* Location: ./application/modules/penjualan/models/TransaksipenjualanModel.php */
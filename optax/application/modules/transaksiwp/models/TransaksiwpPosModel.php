<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiwpPosModel extends Base_Model
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
                    array('name' => 'penjualan_status_aktif'),
                    // array('name' => 'detail_id',         'view' => true),
                )
            ),
            'view' => array(
                // 'name' => 'v_pos_penjualan2',
                'mode' => array(
                    'table' => array(
                        'penjualan_id',
                        'penjualan_created',
                        'penjualan_tanggal',
                        'penjualan_total_grand',
                        'penjualan_kode',
                        'penjualan_status_aktif',
                        'penjualan_lock',
                        'penjualan_total_retur',
                    ),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }

    //tambahan detailTransaksi
    function detailTransaksi($data)
    {
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pemda_id', $pemda_id);
        }

        $this->db->where('penjualan_id', $data['penjualan_id']);
        $this->db->where('penjualan_lock', null);
        return [
            'success' => true,
            'message' => 'Berhasil menampilkan data'
        ];
    }
}

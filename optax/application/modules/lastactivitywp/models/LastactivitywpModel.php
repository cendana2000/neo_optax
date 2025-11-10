<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LastactivitywpModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_pajak_penjualan_wp_last_activity_v5',
                'primary' => 'toko_id',
                'fields' => array(
                    array('name' => 'toko_id', 'view' => true),
                    array('name' => 'toko_kode', 'view' => true),
                    array('name' => 'toko_nama', 'view' => true),
                    array('name' => 'toko_wajibpajak_npwpd', 'view' => true),
                    array('name' => 'sum_total', 'view' => true),
                    array('name' => 'tanggal_last_transaksi', 'view' => true),
                    array('name' => 'status_active', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_pajak_penjualan_wp_last_activity_v5',
                'mode' => array(
                    'table' => array(
                        'toko_id',
                        'toko_kode',
                        'toko_nama',
                        'toko_wajibpajak_npwpd',
                        'tanggal_last_transaksi',
                        'status_active',
                    ),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */
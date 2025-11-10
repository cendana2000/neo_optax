<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiwpPosModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_penjualan_pooling',
                'primary' => 'penjualan_id',
                'fields' => array(
                    array('name' => 'penjualan_id'),
                    array('name' => 'penjualan_tanggal'),
                    array('name' => 'penjualan_kode'),
                    array('name' => 'penjualan_total_item'),
                    array('name' => 'penjualan_total_qty'),
                    array('name' => 'penjualan_sub_total'),
                    array('name' => 'penjualan_total_nilai_pajak'),
                    array('name' => 'penjualan_total_grand'),
                    array('name' => 'penjualan_nama_customer'),
                    array('name' => 'penjualan_user_nama'),
                    array('name' => 'penjualan_jasa'),
                    array('name' => 'penjualan_source'),
                )
            ),
            'view' => array(
                // 'name' => 'v_pos_penjualan2',
                'mode' => array(
                    'table' => array(
                        'penjualan_id',
                        'penjualan_total_item',
                        'penjualan_user_nama',
                        'penjualan_tanggal',
                        'penjualan_total_grand',
                        'penjualan_kode',
                        'penjualan_source',
                    ),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

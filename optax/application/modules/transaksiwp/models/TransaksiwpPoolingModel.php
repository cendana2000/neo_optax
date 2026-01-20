<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiwpPoolingModel extends Base_Model
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
                    array('name' => 'penjualan_jasa'),
                    array('name' => 'penjualan_diskon'),
                    array('name' => 'penjualan_total_grand'),
                    array('name' => 'penjualan_nama_customer'),
                    array('name' => 'penjualan_user_nama'),
                    array('name' => 'penjualan_source'),
                    array('name' => 'penjualan_deleted_at'),
                    array('name' => 'wajibpajak_id'),
                )
            ),
            'view' => array(
                'mode' => array(
                    'table' => array(
                        'penjualan_id',
                        'penjualan_tanggal',
                        'penjualan_kode',
                        'penjualan_total_item',
                        'penjualan_total_qty',
                        'penjualan_sub_total',
                        'penjualan_total_nilai_pajak',
                        'penjualan_jasa',
                        'penjualan_diskon',
                        'penjualan_total_grand',
                        'penjualan_nama_customer',
                        'penjualan_user_nama',
                        'penjualan_source',
                        'penjualan_deleted_at',
                        'wajibpajak_id',
                    ),
                )
            )
        );
        parent::__construct($model);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PoolingSchemaModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pajak_pooling_schema',
                'primary' => 'pajak_pooling_shcema_id',
                'fields' => array(
                    ['name' => 'pajak_pooling_shcema_id'],
                    ['name' => 'pajak_pooling_config_id'], // this field as parent on pajak_pooling_config_id
                    ['name' => 'pajak_pooling_shcema_table'],
                    ['name' => 'penjualan_tanggal'],
                    ['name' => 'penjualan_kode'],
                    ['name' => 'penjualan_total_item'],
                    ['name' => 'penjualan_total_qty'],
                    ['name' => 'penjualan_sub_total'],
                    ['name' => 'penjualan_total_nilai_pajak'],
                    ['name' => 'penjualan_total_grand'],
                    ['name' => 'penjualan_nama_customer'],
                    ['name' => 'penjualan_user_nama'],
                    ['name' => 'penjualan_jasa'],
                )
            ),
            'view' => array(
                'mode' => array(
                    'table' => array(
                        'pajak_pooling_shcema_id',
                        'pajak_pooling_config_id', // this field as parent on pajak_pooling_config_id
                        'pajak_pooling_shcema_table',
                        'penjualan_tanggal',
                        'penjualan_kode',
                        'penjualan_total_item',
                        'penjualan_total_qty',
                        'penjualan_sub_total',
                        'penjualan_total_nilai_pajak',
                        'penjualan_total_grand',
                        'penjualan_nama_customer',
                        'penjualan_user_nama',
                        'penjualan_jasa',
                    )
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file PegawaiModel.php */
/* Location: ./application/modules/Pegawai/models/PegawaiModel.php */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderpembeliandetailpembayaranModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_order_pembelian_pembayaran_detail',
                'primary' => 'order_detail_pembayaran_id',
                'fields' => array(
                    array('name' => 'order_detail_pembayaran_id'),
                    array('name' => 'order_detail_pembayaran_parent'),
                    array('name' => 'order_detail_pembayaran_tanggal'),
                    array('name' => 'order_detail_pembayaran_cara_bayar'),
                    array('name' => 'order_detail_pembayaran_akun'),
                    array('name' => 'order_detail_pembayaran_total'),
                )
            ),
            'view' => array(
                'name' => 'pos_order_pembelian_pembayaran_detail',
                'mode' => array(
                    'table' => array(
                        'pembelian_detail_id',
                        'order_detail_pembayaran_parent',
                        'order_detail_pembayaran_tanggal',
                        'order_detail_pembayaran_cara_bayar',
                        'order_detail_pembayaran_akun',
                        'order_detail_pembayaran_total',
                    )
                )
            )

        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file OrderpembelianModel.php */
/* Location: ./application/modules/pembelian/models/TransaksipembelianModel.php */
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembayarandetailpembayaranModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_pembayaran_pembayaran_detail',
                'primary' => 'pembayaran_detail_pembayaran_id',
                'fields' => array(
                    array('name' => 'pembayaran_detail_pembayaran_id'),
                    array('name' => 'pembayaran_detail_pembayaran_parent'),
                    array('name' => 'pembayaran_detail_pembayaran_tanggal'),
                    array('name' => 'pembayaran_detail_pembayaran_cara_bayar'),
                    array('name' => 'pembayaran_detail_pembayaran_total'),
                )
            ),
            'view' => array(
                'name' => 'pos_pembayaran_pembayaran_detail',
                'mode' => array(
                    'table' => array(
                        'pembelian_detail_id',
                        'pembayaran_detail_pembayaran_parent',
                        'pembayaran_detail_pembayaran_tanggal',
                        'pembayaran_detail_pembayaran_cara_bayar',
                        'pembayaran_detail_pembayaran_total',
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
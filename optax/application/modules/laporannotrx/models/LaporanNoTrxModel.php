<?php defined('BASEPATH') or exit('No direct script access allowed');

class LaporanNoTrxModel extends Base_Model
{
    public function __construct()
    {
        $model  = array(
            'table' => array(
                'name'      => 'pos_no_trx',
                'primary'   => 'pos_no_trx_id',
                'fields'    => array(
                    array('name' => 'pos_no_trx_id'),
                    array('name' => 'wajibpajak_id'),
                    array('name' => 'pos_no_trx_tanggal'),
                    array('name' => 'pos_no_trx_created_at'),
                    array('name' => 'pos_no_trx_updated_at'),
                )
            ),
            'view'  => array(
                'name'  => 'v_pos_no_trx',
                'mode'  => array(
                    'table' => array(
                        'pos_no_trx_id',
                        'wajibpajak_id',
                        'pos_no_trx_tanggal',
                        'pos_no_trx_created_at',
                        'pos_no_trx_updated_at',
                        'wajibpajak_npwpd',
                        'wajibpajak_nama',
                        'wajibpajak_alamat',
                        'kecamatan_id',
                        'kelurahan_id',
                        'kecamatan_nama',
                        'kelurahan_nama',
                    )
                )
            )
        );

        parent::__construct($model);
    }
}

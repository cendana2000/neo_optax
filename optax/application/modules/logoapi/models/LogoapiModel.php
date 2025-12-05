<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogoapiModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_log_oapi_v3',
                'primary' => 'toko_wajibpajak_id',
                'fields' => array(
                    array('name' => 'realisasi_id', 'view' => true),
                    array('name' => 'toko_wajibpajak_id', 'view' => true),
                    array('name' => 'realisasi_no', 'view' => true),
                    array('name' => 'realisasi_wajibpajak_npwpd', 'view' => true),
                    array('name' => 'toko_nama', 'view' => true),
                    array('name' => 'realisasi_sub_total', 'view' => true),
                    array('name' => 'realisasi_jasa', 'view' => true),
                    array('name' => 'realisasi_pajak', 'view' => true),
                    array('name' => 'realisasi_total', 'view' => true),
                    array('name' => 'realisasi_created_at', 'view' => true),
                    array('name' => 'toko_kode', 'view' => true),
                    array('name' => 'realisasi_tanggal', 'view' => true),
                    array('name' => 'wajibpajak_alamat', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_log_oapi_v3',
                'mode' => array(
                    'table' => array(
                        'realisasi_id',
                        'toko_wajibpajak_id',
                        'toko_kode',
                        'toko_nama',
                        'realisasi_wajibpajak_npwpd',
                        'realisasi_tanggal',
                        'realisasi_sub_total',
                        'realisasi_no',
                        'realisasi_jasa',
                        'realisasi_pajak',
                        'realisasi_total',
                        'realisasi_created_at',
                        'wajibpajak_alamat',
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
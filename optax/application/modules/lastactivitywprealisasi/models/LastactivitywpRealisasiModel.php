<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LastactivitywpRealisasiModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_realisasi_last_activity_v2',
                'primary' => 'wajibpajak_id',
                'fields' => array(
                    array('name' => 'wajibpajak_id', 'view' => true),
                    array('name' => 'wajibpajak_npwpd', 'view' => true),
                    array('name' => 'wajibpajak_nama', 'view' => true),
                    array('name' => 'sum_total_realisasi', 'view' => true),
                    array('name' => 'realisasi_tanggal', 'view' => true),
                    array('name' => 'status_active', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_realisasi_last_activity_v2',
                'mode' => array(
                    'table' => array(
                        'wajibpajak_id',
                        'wajibpajak_npwpd',
                        'wajibpajak_nama',
                        'realisasi_tanggal',
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
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RealisasipajakparentfiltercreatedModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pajak_realisasi',
                'primary' => 'realisasi_parent_npwpd',
                'fields' => array(
                    array('name' => 'realisasi_parent_npwpd', 'view' => true),
                    array('name' => 'realisasi_parent_nama', 'view' => true),
                    array('name' => 'realisasi_parent_jml_transaksi', 'view' => true),
                    array('name' => 'realisasi_parent_transaksi_terakhir', 'view' => true),
                    array('name' => 'realisasi_parent_total_pajak', 'view' => true),
                    array('name' => 'realisasi_parent_omzet', 'view' => true),
                    array('name' => 'realisasi_parent_tanggal_daftar', 'view' => true),
                    array('name' => 'realisasi_parent_jenis_pajak', 'view' => true),
                    array('name' => 'realisasi_parent_jenis_tarif', 'view' => true),
                    array('name' => 'realisasi_parent_tanggal', 'view' => true),
                    array('name' => 'realisasi_parent_wajibpajak_status', 'view' => true),
                    array('name' => 'realisasi_created_at', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_realisasi_parent_filter_with_created_v2',
                'mode' => array(
                    'table' => array(
                        'realisasi_created_at',
                        'realisasi_parent_jml_transaksi',
                        'realisasi_parent_omzet',
                        'realisasi_parent_total_pajak',
                        'realisasi_parent_npwpd',
                        'realisasi_parent_nama',
                        'realisasi_parent_transaksi_terakhir',
                        'realisasi_parent_tanggal_daftar',
                        'realisasi_parent_jenis_pajak',
                        'realisasi_parent_jenis_tarif',
                        'realisasi_parent_tanggal'
                    )
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file RealisasipajakparentfilterModel.php */
/* Location: ./application/modules/satuananggota/models/RealisasipajakparentfilterModel.php */
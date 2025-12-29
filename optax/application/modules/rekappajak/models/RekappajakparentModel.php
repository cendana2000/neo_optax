<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekappajakparentModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_rekap_pajak',
                'primary' => 'npwpd',
                'fields' => array(
                    array('name' => 'npwpd', 'view' => true),
                    array('name' => 'nama_wp', 'view' => true),
                    array('name' => 'jenis_nama', 'view' => true),
                    array('name' => 'sumber_data', 'view' => true),
                    array('name' => 'pemda_id', 'view' => true),
                    array('name' => 'tanggal_last_transaksi', 'view' => true),
                    array('name' => 'kecamatan_id', 'view' => true),
                    array('name' => 'kecamatan_nama', 'view' => true),
                    array('name' => 'jenis_device', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_rekap_pajak',
                'mode' => array(
                    'table' => array(
                        'npwpd',
                        'nama_wp',
                        'jenis_nama',
                        'sumber_data',
                        'pemda_id',
                        'tanggal_last_transaksi',
                        'kecamatan_id',
                        'kecamatan_nama',
                        'jenis_device',
                    ),
                )
            )
        );
        parent::__construct($model);
    }
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
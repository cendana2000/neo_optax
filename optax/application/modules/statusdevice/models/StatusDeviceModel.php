<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatusDeviceModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name'      => 'v_status_device',
                'primary'   => 'toko_id',
                'fields'    => [
                    ['name' => 'toko_id', 'view' => true],
                    ['name' => 'toko_kode', 'view' => true],
                    ['name' => 'toko_wajibpajak_npwpd', 'view' => true],
                    ['name' => 'toko_nama', 'view' => true],
                    ['name' => 'wajibpajak_alamat', 'view' => true],
                    ['name' => 'mobile_last_active', 'view' => true],
                    ['name' => 'web_last_active', 'view' => true],
                    ['name' => 'tanggal_last_transaksi', 'view' => true],
                    ['name' => 'status_active', 'view' => true],
                    ['name' => 'toko_is_oapi', 'view' => true],
                    ['name' => 'toko_desktop_ping_timestamp', 'view' => true],
                ]
            ),
            'view' => array(
                'name' => 'v_status_device',
                'mode' => array(
                    'datatable' => array(
                        'toko_id',
                        'toko_kode',
                        'toko_wajibpajak_npwpd',
                        'toko_nama',
                        'wajibpajak_alamat',
                        'mobile_last_active',
                        'web_last_active',
                        'tanggal_last_transaksi',
                        'status_active',
                        'toko_is_oapi',
                        'toko_desktop_ping_timestamp',
                    ),
                )
            )
        );

        parent::__construct($model);
    }
}


/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */
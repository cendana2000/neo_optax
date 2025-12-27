<?php defined('BASEPATH') or exit('No direct script access allowed');

class KecamatanModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name'      => 'conf_kecamatan',
                'primary'   => 'kecamatan_id',
                'fields'    => array(
                    array('name' => 'kecamatan_id'),
                    array('name' => 'kabkota_id'),
                    array('name' => 'provinsi_id'),
                    array('name' => 'create_uid'),
                    array('name' => 'write_uid'),
                    array('name' => 'kecamatan_kode'),
                    array('name' => 'kecamatan_nama'),
                    array('name' => 'kecamatan_created_at'),
                    array('name' => 'kecamatan_updated_at'),
                    array('name' => 'kecamatan_deleted_at'),
                )
            )
        );

        parent::__construct($model);
    }
}

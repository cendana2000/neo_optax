<?php defined('BASEPATH') or exit('No direct script access allowed');

class KelurahanModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name'      => 'conf_kelurahan',
                'primary'   => 'kelurahan_id',
                'fields'    => array(
                    array('name' => 'kelurahan_id'),
                    array('name' => 'kecamatan_id'),
                    array('name' => 'kabkota_id'),
                    array('name' => 'provinsi_id'),
                    array('name' => 'create_uid'),
                    array('name' => 'write_uid'),
                    array('name' => 'kelurahan_kode'),
                    array('name' => 'kelurahan_nama'),
                    array('name' => 'kelurahan_created_at'),
                    array('name' => 'kelurahan_updated_at'),
                    array('name' => 'kelurahan_deleted_at'),
                )
            )
        );

        parent::__construct($model);
    }
}

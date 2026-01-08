<?php defined('BASEPATH') or exit('No direct script access allowed');

class PemdaModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'conf_pemda',
                'primary' => 'pemda_id',
                'fields' => array(
                    array('name' => 'pemda_id'),
                    array('name' => 'pemda_nama'),
                    array('name' => 'pemda_kode'),
                    array('name' => 'pemda_alamat'),
                    array('name' => 'pemda_coord'),
                    array('name' => 'pemda_logo'),
                    array('name' => 'provinsi_id'),
                    array('name' => 'kabkota_id'),
                    array('name' => 'pemda_created_at'),
                    array('name' => 'pemda_deleted_at')
                )
            ),
            'view' => array(
                'name' => 'v_conf_pemda',
                'mode' => array(
                    'table' => array(
                        'pemda_id',
                        'pemda_nama',
                        'pemda_kode',
                        'pemda_alamat',
                        'pemda_coord',
                        'pemda_logo',
                        'provinsi_id',
                        'provinsi_nama',
                        'kabkota_id',
                        'kabkota_nama',
                        'pemda_created_at',
                        'pemda_deleted_at',
                    )
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */
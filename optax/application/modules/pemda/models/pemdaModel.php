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
                    array('name' => 'pemda_nama')
                )
            ),
            'view' => array(
                'mode' => array(
                    'table' => array(
                        'pemda_id',
                        'pemda_nama',
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
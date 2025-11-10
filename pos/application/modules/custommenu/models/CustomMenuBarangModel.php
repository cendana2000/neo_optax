<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomMenuBarangModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_custom_menu',
                'primary' => 'custom_menu_id',
                'fields' => array(
                    array('name' => 'custom_menu_id'),
                    array('name' => 'custom_menu_nama'),
                    array('name' => 'custom_menu_harga'),
                    array('name' => 'custom_menu_created_at'),
                    array('name' => 'custom_menu_deleted_at'),
                    array('name' => 'custom_menu_updated_at'),
                    array('name' => 'barang_id', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_pos_barang_custom',
                'mode' => array(
                    'table' => array(
                        'custom_menu_id',
                        'custom_menu_nama',
                        'custom_menu_harga',
                        'custom_menu_created_at',
                        'custom_menu_deleted_at',
                        'custom_menu_updated_at',
                        'barang_id',
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
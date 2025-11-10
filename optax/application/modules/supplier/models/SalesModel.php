<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pos_sales',
                'primary' => 'sales_id',
                'fields' => array(
                    array('name' => 'sales_id'),
                    array('name' => 'sales_supplier_id'),
                    array('name' => 'sales_nama'),
                    array('name' => 'sales_keterangan'),
                    array('name' => 'sales_telp'),
                    array('name' => 'sales_hp'),
                    array('name' => 'sales_order'),
                )
            ),
        );
        parent::__construct($model);
        //Do your magic here
    }
}

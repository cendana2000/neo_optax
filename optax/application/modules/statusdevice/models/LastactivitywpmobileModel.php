<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LastactivitywpmobileModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name'      => 'log_mobile',
                'primary'   => 'log_id',
                'fields'    => array(
                    array('name' => 'log_id'),
                    array('name' => 'log_tanggal'),
                    array('name' => 'log_device_id'),
                    array('name' => 'log_device_model'),
                    array('name' => 'log_last_active'),
                    array('name' => 'log_user_id'),
                    array('name' => 'log_user_code_store'),
                    array('name' => 'log_user_name'),
                    array('name' => 'log_created_at'),
                    array('name' => 'log_jam_0'),
                    array('name' => 'log_jam_1'),
                    array('name' => 'log_jam_2'),
                    array('name' => 'log_jam_3'),
                    array('name' => 'log_jam_4'),
                    array('name' => 'log_jam_5'),
                    array('name' => 'log_jam_6'),
                    array('name' => 'log_jam_7'),
                    array('name' => 'log_jam_8'),
                    array('name' => 'log_jam_9'),
                    array('name' => 'log_jam_10'),
                    array('name' => 'log_jam_11'),
                    array('name' => 'log_jam_12'),
                    array('name' => 'log_jam_13'),
                    array('name' => 'log_jam_14'),
                    array('name' => 'log_jam_15'),
                    array('name' => 'log_jam_16'),
                    array('name' => 'log_jam_17'),
                    array('name' => 'log_jam_18'),
                    array('name' => 'log_jam_19'),
                    array('name' => 'log_jam_20'),
                    array('name' => 'log_jam_21'),
                    array('name' => 'log_jam_22'),
                    array('name' => 'log_jam_23'),
                )
            ),
            'view'  => array(
                'mode'  => array(
                    'datatable' => array(
                        'log_id',
                        'log_tanggal',
                        'log_device_id',
                        'log_device_model',
                        'log_last_active',
                        'log_user_name',
                        'log_created_at',
                        'log_jam_0',
                        'log_jam_1',
                        'log_jam_2',
                        'log_jam_3',
                        'log_jam_4',
                        'log_jam_5',
                        'log_jam_6',
                        'log_jam_7',
                        'log_jam_8',
                        'log_jam_9',
                        'log_jam_10',
                        'log_jam_11',
                        'log_jam_12',
                        'log_jam_13',
                        'log_jam_14',
                        'log_jam_15',
                        'log_jam_16',
                        'log_jam_17',
                        'log_jam_18',
                        'log_jam_19',
                        'log_jam_20',
                        'log_jam_21',
                        'log_jam_22',
                        'log_jam_23',
                        'log_wajibpajak_nama',
                        'log_wajibpajak_npwpd',
                    )
                )
            )
        );
        parent::__construct($model);
    }
}

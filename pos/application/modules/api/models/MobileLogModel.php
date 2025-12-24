<?php defined('BASEPATH') or exit('No direct script access allowed');

class MobileLogModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'log_mobile',
                'primary' => 'log_id',
                'fields' => array(
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
                    array('name' => 'log_wajibpajak_nama'),
                    array('name' => 'log_wajibpajak_npwpd'),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

/* End of file MobileLoginModel.php */
/* Location: ./application/modules/api/models/MobileLoginModel.php */
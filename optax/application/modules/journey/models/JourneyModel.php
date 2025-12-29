<?php defined('BASEPATH') or exit('No direct script access allowed');

class JourneyModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name'      => 'journey_activity',
                'primary'   => 'journey_id',
                'fields'    => array(
                    array('name' => 'journey_id'),
                    array('name' => 'journey_trigger_action'),
                    array('name' => 'journey_identifikasi_masalah'),
                    array('name' => 'journey_penyelesaian'),
                    array('name' => 'journey_hasil'),
                    array('name' => 'journey_catatan'),
                    array('name' => 'wajibpajak_id'),
                    array('name' => 'journey_tgl_survey'),
                    array('name' => 'journey_attachment'),
                    array('name' => 'journey_status'),
                    array('name' => 'journey_pegawai_id'),
                )
            ),
            'view'  => array(
                'name'  => 'v_journey_activity',
                'mode'  => array(
                    'table' => array(
                        'journey_id',
                        'journey_trigger_action',
                        'journey_identifikasi_masalah',
                        'journey_penyelesaian',
                        'journey_hasil',
                        'journey_catatan',
                        'wajibpajak_id',
                        'journey_tgl_survey',
                        'journey_attachment',
                        'journey_status',
                        'journey_pegawai_id',
                        'wajibpajak_nama',
                        'wajibpajak_alamat',
                        'pegawai_nama'
                    )
                )
            )
        );

        parent::__construct($model);
    }
}

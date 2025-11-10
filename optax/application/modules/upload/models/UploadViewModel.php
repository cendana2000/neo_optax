<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UploadViewModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_realisasi_upload',
                'primary' => 'realisasi_wajibpajak_npwpd',
                'fields' => array(
                    array('name' => 'realisasi_wajibpajak_npwpd'),
                    array('name' => 'realisasi_masa_pajak'),
                    array('name' => 'realisasi_tanggal_upload_terakhir'),
                    array('name' => 'realisasi_total_sub_total'),
                    array('name' => 'realisasi_total_jasa'),
                    array('name' => 'realisasi_total_pajak'),
                    array('name' => 'realisasi_total_grand_total'),
                )
            ),
            'view' => array(
                'mode' => array(
                    'table' => array(
                        'realisasi_wajibpajak_npwpd',
                        'realisasi_masa_pajak',
                        'realisasi_tanggal_upload_terakhir',
                        'realisasi_total_sub_total',
                        'realisasi_total_jasa',
                        'realisasi_total_pajak',
                        'realisasi_total_grand_total',
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
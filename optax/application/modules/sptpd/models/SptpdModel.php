<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SptpdModel extends Base_Model
{
    function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pajak_sptpd',
                'primary' => 'sptpd_id',
                'fields' => array(
                    array('name' => 'sptpd_id'),
                    array('name' => 'sptpd_npwpd'),
                    array('name' => 'sptpd_bulan_pajak'),
                    array('name' => 'sptpd_tahun_pajak'),
                    array('name' => 'sptpd_nominal_omzet'),
                    array('name' => 'sptpd_nominal_pajak'),
                    array('name' => 'sptpd_etax_omzet'),
                    array('name' => 'sptpd_etax_pajak'),
                    array('name' => 'sptpd_nama_verifikator'),
                    array('name' => 'sptpd_tanggal_verifikasi'),
                    array('name' => 'sptpd_verifikator_id'),
                    array('name' => 'sptpd_status'),
                    array('name' => 'sptpd_nomor_sptpd'),
                    array('name' => 'sptpd_va_jatim'),
                    array('name' => 'sptpd_kode_billing'),
                    array('name' => 'sptpd_tanggal_bayar'),
                    array('name' => 'sptpd_nomor_sspd'),
                    array('name' => 'sptpd_tempat_pembayaran'),
                    array('name' => 'sptpd_status_pembayaran'),
                    array('name' => 'sptpd_created_at'),
                    array('name' => 'sptpd_deleted_at'),
                    array('name' => 'sptpd_updated_at'),
                )
                ),
                'view' => array(
                    'name' => 'v_pajak_sptpd',
                    'mode' => array(
                        'table' => array(
                            'sptpd_nominal_omzet',
                            'sptpd_bulan_tahun_pajak',
                            'sptpd_nominal_pajak',
                            'sptpd_status',
                            'sptpd_id',
                            'sptpd_npwpd',
                            'sptpd_status_pembayaran',
                            'sptpd_bulan_pajak',
                            'sptpd_tahun_pajak',
                        )
                    )
                )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

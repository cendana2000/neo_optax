<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VerifikasiSptpdModel extends Base_Model
{
    function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'pajak_sptpd',
                'primary' => 'sptpd_id',
                'fields' => array(
                    array('name' => 'sptpd_id', 'unique' => true),
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
                    array('name' => 'pegawai_nama', 'view' => true),
                    array('name' => 'wajibpajak_nama_penanggungjawab', 'view' => true),
                )
                ),
                'view' => array(
                    'name' => 'v_pajak_verifikasi_sptpd',
                    'mode' => array(
                        'table' => array(
                            'sptpd_id',
                            'sptpd_npwpd',
                            'sptpd_bulan_pajak',
                            'sptpd_tahun_pajak',
                            'sptpd_nominal_omzet',
                            'sptpd_nominal_pajak',
                            'sptpd_etax_omzet',
                            'sptpd_etax_pajak',
                            'sptpd_nama_verifikator',
                            'sptpd_tanggal_verifikasi',
                            'sptpd_verifikator_id',
                            'sptpd_status',
                            'sptpd_nomor_sptpd',
                            'sptpd_va_jatim',
                            'sptpd_kode_billing',
                            'sptpd_tanggal_bayar',
                            'sptpd_nomor_sspd',
                            'sptpd_tempat_pembayaran',
                            'sptpd_status_pembayaran',
                            'sptpd_created_at',
                            'sptpd_updated_at',
                            'sptpd_deleted_at',
                            'pegawai_nama',
                            'wajibpajak_nama_penanggungjawab'
                        )
                    )
                )
        );
        parent::__construct($model);
        //Do your magic here
    }
}

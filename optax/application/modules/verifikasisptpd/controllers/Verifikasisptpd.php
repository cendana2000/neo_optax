<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Hidehalo\Nanoid\Client;

class VerifikasiSptpd extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array(
            'VerifikasiSptpdModel'         => 'verifikasi_sptpd',
            'conf/NotificationModel'       => 'Notification',
        ));
    }

    public function loadVerifikasi()
    {
        $where = [
            'sptpd_status is NULL' => null,
        ];

        $data = $this->select_dt(varPost(), 'verifikasi_sptpd', 'table', false, $where);
        $this->response(
            $data
        );
    }

    public function loadVerifikasiSetuju()
    {
        $where = [
            "sptpd_status" => '1',
        ];

        $data = $this->select_dt(varPost(), 'verifikasi_sptpd', 'table', false, $where);
        $this->response(
            $data
        );
    }

    public function loadVerifikasiTolak()
    {
        $where = [
            'sptpd_status' => '0',
        ];

        $data = $this->select_dt(varPost(), 'verifikasi_sptpd', 'table', false, $where);
        $this->response(
            $data
        );
    }

    public function loadVerifikasiPembayaran()
    {
        $where = [
            'sptpd_status' => '1',
            'sptpd_status_pembayaran' => null,
        ];

        $data = $this->select_dt(varPost(), 'verifikasi_sptpd', 'table', false, $where);
        $this->response(
            $data
        );
    }

    public function loadVerifikasiAll()
    {
        $where = [];

        $data = $this->select_dt(varPost(), 'verifikasi_sptpd', 'table', false, $where);
        $this->response(
            $data
        );
    }

    public function store()
    {
        // $client = new Client();
        $data = varPost();
        $tahun_bulan = explode('-', $data['sptpd_bulan_tahun_pajak']);
        $data['sptpd_bulan_pajak'] = $tahun_bulan[1];
        $data['sptpd_tahun_pajak'] = $tahun_bulan[0];
        $data['sptpd_created_at'] = date('Y-m-d h:i:s');
        // $sptpd_id = $client->formattedId($this->verifikasi_sptpd->get_table(), 13);
        // $this->response($this->verifikasi_sptpd->insert($sptpd_id, $data));
        $this->response($this->verifikasi_sptpd->insert(substr(gen_uuid($this->verifikasi_sptpd->get_table()), 0, 13), $data));
    }

    public function detail()
    {
        $where['sptpd_id'] = varPost('sptpd_id');
        $data = $this->verifikasi_sptpd->read(varPost('sptpd_id'));
        // $data = $this->verifikasi_sptpd->read($where);
        // $data = $this->select_dt(varPost(), 'sptpd', 'table', true, $where);
        $this->response($data);
    }

    public function update()
    {
        $data = varPost();
        $data['sptpd_verifikator_id'] = $this->session->userdata()['pegawai_id'];
        $data['sptpd_nama_verifikator'] = $this->session->userdata()['pegawai_nama'];
        $data['sptpd_updated_at'] = date('Y-m-d h:i:s');
        $data['sptpd_tanggal_verifikasi'] = $data['sptpd_updated_at'];
        $update = $this->verifikasi_sptpd->update($data['sptpd_id'], $data);
        log_activity('Konfirmasi SPTPD ID : ' . $update['sptpd_id'] . ' Selesai');
        
        $ch = curl_init();

        $data_curl = array(
            "id_sptpd" => $update['record']['sptpd_id'],
            "npwpd" => $update['record']['sptpd_npwpd'],
            "bulan_pajak" => $update['record']['sptpd_bulan_pajak'],
            "tahun_pajak" => $update['record']['sptpd_tahun_pajak'],
            "nominal_omzet" => $update['record']['sptpd_nominal_omzet'],
            "nominal_pajak" => $update['record']['sptpd_nominal_pajak'],
            "etax_omzet" => $update['record']['sptpd_etax_omzet'],
            "etax_pajak" => $update['record']['sptpd_etax_pajak'],
            "nama_verifikator" => $update['record']['pegawai_nama'],
            "tanggal_verifikasi" => $update['record']['sptpd_tanggal_verifikasi'],
            "tanggal_pelaporan" => $update['record']['sptpd_created_at'],
            "status" => $update['record']['sptpd_status'],
        );

        $jsonData = json_encode($data_curl);

        curl_setopt($ch, CURLOPT_URL, "https://pajak.malangkota.go.id/persada_sptpd/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('persada_api:xH4_f+0G-nx45R')
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // print_r($response);
        curl_close($ch);

        $wp = $this->db->select('wajibpajak_id')->get_where('pajak_wajibpajak', [
            'wajibpajak_npwpd' => $update['record']['sptpd_npwpd']
        ])->row_array();

        if($data['sptpd_status'] == '1'){
            $this->Notification->sendNotif("Verifikasi Lapor SPTPD", "Permohonan SPTPD pada periode {$update['record']['sptpd_tahun_pajak']} - {$update['record']['sptpd_bulan_pajak']} Anda telah disetujui", 'VERIFIKASI', 'SPTPD', $wp['wajibpajak_id'], 'WP');
        }else{
            $this->Notification->sendNotif("Verifikasi Lapor SPTPD", "Permohonan SPTPD pada periode {$update['record']['sptpd_tahun_pajak']} - {$update['record']['sptpd_bulan_pajak']} Anda ditolak oleh verifikator", 'VERIFIKASI', 'SPTPD', $wp['wajibpajak_id'], 'WP');
        }


        $this->response(array(
            'success' => true,
            'message' => 'Data berhasil diverifikasi!',
            'data' => $response
        ));
    }
}

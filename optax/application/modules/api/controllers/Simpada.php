<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Simpada extends Base_Controller
{
    private $headers = null;
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Headers: *");

        $this->load->model(array(
            "verifikasisptpd/VerifikasiSptpdModel" => "sptpd"
        ));

        $this->basic_auth();
    }

    public function sptpd()
    {
        $data = varPost();
        $where = [];
        $where['sptpd_id'] = $data['id_sptpd'];
        $where['sptpd_npwpd'] = $data['npwpd'];
        $update_data = [
            'sptpd_nomor_sptpd' => $data['nomor_sptpd'],
            'sptpd_va_jatim' => $data['va_jatim'],
            'sptpd_kode_billing' => $data['kode_billing'],
            'sptpd_updated_at' => date('Y-m-d h:i:s'),
        ];
        $update;
        $sptpd;
        try {
            $sptpd = $this->sptpd->read($where);
            $update = $this->sptpd->update($where, $update_data);
            $wp = $this->db->select('wajibpajak_id')->get_where('pajak_wajibpajak', [
                'wajibpajak_npwpd' => $data['npwpd']
            ])->row_array();
            $this->Notification->sendNotif("Virtual Account SPTPD", "Nomor SPTPD dan VA anda sudah tersedia, mohon lakukan cetak dan lakukan pembayaran", 'VERIF-SIMPADA', 'SPTPD', $wp['wajibpajak_id'], 'WP');
        } catch (Exception $e) {
            $this->response(
                array(
                    'success' => false,
                    'message' => 'Data SPTPD tidak ditemukan/Invalid!'
                )
            );
        }

        $this->response(
            // array('Data' => $data)
            array(
                'success' => true,
                'data' => $update
            )
            // 'halo'
        );
    }

    public function sspd()
    {
        $data = varPost();
        $where = [];
        $where['sptpd_id'] = $data['id_sptpd'];
        $where['sptpd_npwpd'] = $data['npwpd'];
        $update_data = [
            'sptpd_nomor_sspd' => $data['nomor_sspd'],
            'sptpd_tanggal_bayar' => $data['tanggal_bayar'],
            'sptpd_updated_at' => date('Y-m-d h:i:s'),
        ];
        $update;
        $sptpd;
        try {
            $sptpd = $this->sptpd->read($where);
            $update = $this->sptpd->update($where, $update_data);
            $wp = $this->db->select('wajibpajak_id')->get_where('pajak_wajibpajak', [
                'wajibpajak_npwpd' => $data['npwpd']
            ])->row_array();
            $this->Notification->sendNotif("Status Pembayaran SSPD", "Pembayaran Anda sudah selesai dan berhasil", 'PEMBAYARAN-SSPD', 'SPTPD', $wp['wajibpajak_id'], 'WP');
        } catch (Exception $e) {
            $this->response(
                array(
                    'success' => false,
                    'message' => 'Data SPTPD tidak ditemukan/Invalid!'
                )
            );
        }

        $this->response(
            // array('Data' => $data)
            array(
                'success' => true,
                'data' => $update
            )
            // 'halo'
        );
    }

    public function basic_auth()
    {
        $headers = getallheaders();
        if (!array_key_exists('Authorization', $headers) && empty($headers['Authorization'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'not allowed',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $token = base64_decode(substr($headers['Authorization'], 6));
            $exploded_token = explode(':', $token);
            $username = $exploded_token[0];
            $password = $exploded_token[1];
            if ($username !== 'persada_api' && $password !== 'xH4_f+0G-nx45R') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'not allowed',
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
}

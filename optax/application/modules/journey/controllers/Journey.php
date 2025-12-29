<?php defined('BASEPATH') or exit('No direct script access allowed');

class Journey extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'JourneyModel' => 'Journey'
        ));
    }

    public function index()
    {
        $post = $this->input->post();

        if ($post['filter_sstatus'] != '') {
            if ($post['filter_status'] != 'all') {
                $where['journey_status=\'' . $post['filter_status'] . '\'']  = null;
            }
        }

        $this->response(
            $this->select_dt(varPost(), 'Journey', 'table', false, $where)
        );
    }

    public function read()
    {
        $data = varPost();
        $data = $this->db
            ->from('journey_activity')
            ->join('pajak_pegawai', 'pajak_pegawai.pegawai_id = journey_activity.journey_pegawai_id', 'left')
            ->where('journey_id', $data['id'])
            ->get()
            ->row();

        $this->response($data);
    }

    public function update()
    {
        $data       = varPost();

        $journey    = $this->Journey->read($data['journey_id']);

        if (!file_exists('./dokumen/journey')) {
            mkdir('./dokumen/journey', 0777, true);
        }

        $config['upload_path']      = "./dokumen/journey";
        $config['file_name']        = gen_uuid($this->Journey->get_table());
        $config['allowed_types']    = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size']         = 1000;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload('journey_attachment')) {
            throw new Exception($this->upload->display_errors('', ''));
        }

        unlink('./dokumen/journey' . $journey['journey_attachment']);
        $img                        = $this->upload->data();
        $data['journey_attachment'] = $img['file_name'];

        $data['journey_status']     = 'selesai';
        $data['journey_updated_at'] = date('Y-m-d H:i:s');
        $data['journey_pegawai_id'] = $this->session->userdata('user_pegawai_id');

        $operation                  = $this->Journey->update($data['journey_id'], $data);
        $this->response($operation);
    }

    public function check_inactive_transaction()
    {
        try {
            $tanggal    = date('Y-m-d');

            $where1     = "NOT EXISTS (SELECT 1 FROM pajak_realisasi WHERE pajak_realisasi.realisasi_wajibpajak_id=pajak_wajibpajak.wajibpajak_id AND pajak_realisasi.realisasi_tanggal='$tanggal')";
            $where2     = "NOT EXISTS (SELECT 1 FROM pos_penjualan WHERE pos_penjualan.wajibpajak_id=pajak_wajibpajak.wajibpajak_id AND pos_penjualan.penjualan_tanggal='$tanggal')";
            $where3     = "NOT EXISTS (SELECT 1 FROM pos_penjualan_pooling WHERE pos_penjualan_pooling.wajibpajak_id=pajak_wajibpajak.wajibpajak_id AND pos_penjualan_pooling.penjualan_tanggal='$tanggal')";
            $where4     = "NOT EXISTS (SELECT 1 FROM journey_activity WHERE journey_activity.wajibpajak_id=pajak_wajibpajak.wajibpajak_id AND journey_activity.journey_status='pending')";

            $wps        = $this->db
                ->from('pajak_wajibpajak')
                ->where('wajibpajak_status', '2')
                ->group_start()
                ->where($where1, NULL, FALSE)
                ->where($where2, NULL, FALSE)
                ->where($where3, NULL, FALSE)
                ->where($where4, NULL, FALSE)
                ->group_end()
                ->get()
                ->result();

            $time       = date('Y-m-d H:i:s');

            $records    = array();
            foreach ($wps as $wp) {
                $records[]  = array(
                    'journey_trigger_action'            => 'sistem - WP Offline',
                    'journey_identifikasi_masalah'      => '',
                    'journey_catatan'                   => null,
                    'wajibpajak_id'                     => $wp->wajibpajak_id,
                    'journey_penyelesaian'              => null,
                    'journey_tgl_survey'                => null,
                    'journey_attachment'                => null,
                    'journey_hasil'                     => null,
                    'journey_status'                    => 'pending',
                    'journey_pegawai_id'                => null,
                    'journey_created_at'                => $time
                );
            }

            if (!empty($records)) {
                $this->db->insert_batch('journey_activity', $records);
            }

            $datarow['success'] = true;
            $datarow['msg']     = 'success';
        } catch (Exception $e) {
            $datarow['success'] = false;
            $datarow['msg']     = $e->getMessage();
        } finally {
            $this->response($datarow);
        }
    }
}

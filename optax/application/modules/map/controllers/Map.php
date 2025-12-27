<?php defined('BASEPATH') or exit('No direct script access allowed');

class Map extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'kecamatan/KecamatanModel'      => 'kecamatan',
            'kelurahan/KelurahanModel'      => 'kelurahan',
            'wajibpajak/WajibPajakModel'    => 'wajib_pajak'
        ));
    }

    public function get_center_point()
    {
        $lat = null;
        $lng = null;
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $pemda = $this->db->get_where('conf_pemda', ['pemda_id' => $pemda_id])->row();
            if ($pemda) {
                if ($pemda->pemda_coord) {
                    $coord = trim($pemda->pemda_coord, '()');
                    [$lat, $lng] = explode(',', $coord);
                }
            }
        }

        $this->response([
            'lat'   => $lat ?? -7.9770,
            'lng'   => $lng ?? 112.6234
        ]);
    }

    public function kecamatan()
    {
        $filters = [
            'filters_static'    => array()
        ];

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $pemda = $this->db->get_where('conf_pemda', ['pemda_id' => $pemda_id])->row();
            if ($pemda) {
                $filters['filters_static']['provinsi_id']   = $pemda->provinsi_id;
                $filters['filters_static']['kabkota_id']    = $pemda->kabkota_id;
            }
        }

        $this->response($this->kecamatan->select($filters));
    }

    public function kelurahan()
    {
        $filters = [
            'filters_static'    => array(
                'kecamatan_id'  => varPost('kecamatan_id', null)
            )
        ];

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $pemda = $this->db->get_where('conf_pemda', ['pemda_id' => $pemda_id])->row();
            if ($pemda) {
                $filters['filters_static']['provinsi_id']   = $pemda->provinsi_id;
                $filters['filters_static']['kabkota_id']    = $pemda->kabkota_id;
            }
        }

        $this->response($this->kelurahan->select($filters));
    }

    public function get()
    {
        $filters = [
            'filters_static'    => array()
        ];

        if ($v = varPost('kecamatan_id', null)) {
            $filters['filters_static']['kecamatan_id']  = $v;
        }

        if ($v = varPost('kelurahan_id', null)) {
            $filters['filters_static']['kelurahan_id']  = $v;
        }

        $wp = $this->wajib_pajak->select($filters);
        $this->response($wp);
    }
}

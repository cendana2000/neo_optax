<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'wajibpajak/WajibpajakModel' => 'wajibpajak',
      'kecamatan/KecamatanModel' => 'kecamatan',
      'kelurahan/KelurahanModel' => 'kelurahan',
    ));
  }

  public function index()
  {
    $this->response(
      $this->select_dt(varPost(), 'wajibpajak', 'table', true)
    );
  }

  public function read($value = '')
  {
    $filter = [
      'wajibpajak_id' => $this->session->get_userdata()['wajibpajak_id'],
    ];
    $this->response($this->wajibpajak->read($filter));
  }

  public function update()
  {
    $data = varPost();

    if (!empty($_FILES['wajibpajak_image']['name'])) {

      $uploadPath = APPPATH . '../assets/media/berkasnpwpd/';

      if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
      }

      $config['upload_path']   = realpath($uploadPath);
      $config['allowed_types'] = 'jpg|jpeg|png';
      $config['max_size']      = 1024;
      $config['encrypt_name']  = true;

      $this->load->library('upload');
      $this->upload->initialize($config);

      if (!$this->upload->do_upload('wajibpajak_image')) {
        $this->response([
          'success' => false,
          'message' => $this->upload->display_errors('', '')
        ]);
        return;
      }

      $upload = $this->upload->data();
      $data['wajibpajak_berkas'] = $upload['file_name'];
    }

    $operation = $this->wajibpajak->update(
      $data['wajibpajak_id'],
      $data
    );

    log_activity('Ubah profil wajibpajak');
    $this->response($operation);
  }



  public function removeImage()
  {
    $data = varPost();

    $wp = $this->wajibpajak->read($data['id']);
    unlink(FCPATH . $wp['wajibpajak_berkas']);
    $wpupdate = $this->wajibpajak->update($data['id'], [
      'wajibpajak_berkas' => null
    ]);
    return $this->response($wpupdate);
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
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'wajibpajak/WajibpajakModel' => 'wajibpajak'
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
    $file = $_FILES['wajibpajak_image']['name'];
    $config['upload_path']  = './assets/berkasnpwp/images/';
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size'] = 1000;
    $config['file_name'] = uniqid('npwp_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

    $this->upload->initialize($config);

    if ($file) {
      if (!$this->upload->do_upload('wajibpajak_image')) {
        $status = "failed upload image";
        $msg = $this->upload->display_errors('', '');

        $data = varPost();
        $operation = $this->wajibpajak->update($data['wajibpajak_id'], $data);
      } else {
        $status = "success upload image";
        $dataupload = $this->upload->data();

        $data = varPost();
        $pathfile = $config['upload_path'] . $config['file_name'];
        $data['wajibpajak_berkas'] = ltrim($pathfile, '.');

        $operation = $this->wajibpajak->update($data['wajibpajak_id'], $data);
      }
    } else {
      $data = varPost();
      $operation = $this->wajibpajak->update($data['wajibpajak_id'], $data);
    }
    log_activity('Ubah profil wajibpajak');
    $this->response($operation);
  }

  public function removeImage(){
    $data = varPost();
    
    $wp = $this->wajibpajak->read($data['id']);
    unlink(FCPATH . $wp['wajibpajak_berkas']);
    $wpupdate = $this->wajibpajak->update($data['id'], [
      'wajibpajak_berkas' => null
    ]);
    return $this->response($wpupdate);
  }
}

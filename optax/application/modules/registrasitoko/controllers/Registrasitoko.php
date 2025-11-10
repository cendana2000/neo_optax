<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registrasitoko extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'wajibpajak/WajibpajakModel'  => 'wajibpajak',
      'registrasitoko/RegistrasitokoModel'    => 'toko',
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
      'wajibpajak_id' => $this->session->wajibpajak_id
    ];
    $toko = $this->toko->read($filter);
    if (!$toko) {
      $toko = $this->wajibpajak->read($filter);
    }

    $this->response($toko);
  }

  public function store($value = '')
  {
    $data = varPost();

    $file = $_FILES['logo_toko']['name'];
    $config['upload_path']  = './dokumen/toko/images/';
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size'] = 1000;
    $config['file_name'] = uniqid('toko_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

    if (!file_exists($config['upload_path'])) {
        mkdir('./dokumen/toko/images/', 0777, true);
    }

    $this->upload->initialize($config);

    if ($file) {
      if (!$this->upload->do_upload('logo_toko')) {
        $status = "failed upload image";
        $msg = $this->upload->display_errors('', '');
      } else {
        $status = "success upload image";
        $dataupload = $this->upload->data();

        $pathfile = $config['upload_path'] . $config['file_name'];
        $data['toko_logo'] = ltrim($pathfile, '.');
      }
    }

    $data['toko_nama'] = $data['wajibpajak_nama'];
    $data['toko_wajibpajak_npwpd'] = $data['wajibpajak_npwpd'];
    $data['toko_registered_at'] = date('Y-m-d H:i:s');
    $data['toko_status'] = 1;
    $toko = $this->toko->insert(gen_uuid($this->toko->get_table()), $data);
    log_activity('Mengajukan RegistrasiToko');
    $this->response($toko);
  }

  public function update(){
    $data = varPost();

    $file = $_FILES['logo_toko']['name'];
    $config['upload_path']  = './dokumen/toko/images/';
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size'] = 1000;
    $config['file_name'] = uniqid('srv_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

    if (!file_exists($config['upload_path'])) {
        mkdir('./dokumen/toko/images/', 0777, true);
    }

    $this->upload->initialize($config);

    if ($file) {
      if (!$this->upload->do_upload('logo_toko')) {
        $status = "failed upload image";
        $msg = $this->upload->display_errors('', '');

        $data = varPost();
        $operation = $this->toko->update($data['toko_id'], $data);
      } else {
        $status = "success upload image";
        $dataupload = $this->upload->data();

        $data = varPost();
        $pathfile = $config['upload_path'] . $config['file_name'];
        $data['toko_logo'] = ltrim($pathfile, '.');

        $operation = $this->toko->update($data['toko_id'], $data);
      }
    } else {
      $data = varPost();
      $operation = $this->toko->update($data['toko_id'], $data);
    }
    log_activity('Ubah Registrasitoko '.$operation['toko_nama']);
    $this->response($operation);
  }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Toko extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'wajibpajak/WajibpajakModel'  => 'wajibpajak',
      'toko/TokoModel'              => 'toko',
      'toko/TokoLastActivityModel'  => 'tokolastactivity',
    ));
  }

  public function index()
  {
    $where['(toko_status = \'2\' or toko_status = \'4\')'] = null;
    $this->response(
      $this->select_dt(varPost(), 'tokolastactivity', 'table', true, $where)
    );
  }

  public function read($value = '')
  {
    $this->response($this->tokolastactivity->read(varPost()));
  }

  public function closeToko($value = '')
  {
    $data = varPost();
    $id = $data['toko_id'];
    $opr = $this->tokolastactivity->read($id);
    if ($opr) {
      $opr_update = $this->tokolastactivity->update($id, [
        'toko_status' => '4'
      ]);
      return $this->response($opr_update);
    } else {
      return $this->response([
        'success' => false,
        'message' => 'toko not found!'
      ]);
    }
  }

  public function openToko($value = '')
  {
    $data = varPost();
    $id = $data['toko_id'];
    $opr = $this->tokolastactivity->read($id);
    if ($opr) {
      $opr_update = $this->tokolastactivity->update($id, [
        'toko_status' => '2'
      ]);
      return $this->response($opr_update);
    } else {
      return $this->response([
        'success' => false,
        'message' => 'toko not found!'
      ]);
    }
  }

  public function get_toko()
  {
    $data = ['toko_kode' => varPost('toko_kode')];
    $this->response($this->toko->read($data));
  }

  public function store($value = '')
  {
    $data = varPost();
    $data['toko_nama'] = $data['wajibpajak_nama'];
    $data['toko_wajibpajak_npwpd'] = $data['wajibpajak_npwpd'];
    $data['toko_registered_at'] = date('Y-m-d H:i:s');
    $data['toko_status'] = 1;
    $toko = $this->toko->insert(gen_uuid($this->toko->get_table()), $data);
    $this->response($toko);
  }

  public function pos_user()
  {
    $post = $this->input->post();

    if (empty($post['toko_kode'])) {
      return $this->response([
        'data' => [],
        'message' => 'Kode toko tidak ditemukan'
      ]);
    }

    $this->db->select('
        user_id,
        user_nama,
        user_telepon,
        user_email,
        user_status
    ');
    $this->db->from('pos_user');
    $this->db->where('user_code_store', $post['toko_kode']);
    $this->db->where('user_deleted_at IS NULL', null, false);

    $query = $this->db->get()->result_array();

    $this->response([
      'data' => $query
    ]);
  }


  /**
   * It recursively converts the multi dimension (deep) array to single dimension array as it was posted from an html form
   *
   * @return void
   * @author Mohsin Rasool
   * 
   **/

  private function http_build_query_for_curl($arrays, &$new = array(), $prefix = null)
  {
    if (is_object($arrays)) {
      $arrays = get_object_vars($arrays);
    }

    foreach ($arrays as $key => $value) {
      $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
      if (is_array($value) or is_object($value)) {
        $this->http_build_query_for_curl($value, $new, $k);
      } else {
        $new[$k] = $value;
      }
    }
  }
}

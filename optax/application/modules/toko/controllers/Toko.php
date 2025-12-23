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
    $data = varPost();
    $post = [];
    $this->http_build_query_for_curl($data, $post);
    // print_r('<pre>');print_r($data);print_r('</pre>');exit;
    // $ch = curl_init($_ENV['POS_URL'] . 'api/user/all/' . $data['toko_kode']);
    $ch = curl_init($_ENV['POS_URL'] . '?code_store=' . $data['toko_kode']);
    $request_headers = array(
      'Token:123',
      'CLient:PMU01',
    );
    // $data = null;
    /* curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  */
    curl_setopt_array($ch, [
      CURLOPT_HTTPHEADER => $request_headers,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query($data),
      CURLOPT_FAILONERROR => true,
      CURLOPT_FOLLOWLOCATION => false,
    ]);

    $result = curl_exec($ch);
    if (!$result) {
      # code...
      print_r('Error: ' . curl_error($ch));
    }

    curl_close($ch);

    // print_r('<pre>');print_r($result);print_r('</pre>');exit;
    $this->response(json_decode($result, true));
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{
  protected $db;
  protected $dbmp;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'user/UserModel' => 'User',
    ));
  }

  public function auth_header()
  {
    $this->db = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);
    print_r('here');
    print_r('<pre>');
    print_r($this->config->item('api_pos_token'));
    print_r('</pre>');
    exit;
    $auth = $this->input->request_headers(true);
    if ($this->config->item('api_pos_token') == $auth['Token'] && $this->config->item('api_pos_client') == $auth['Client']) {
      return true;
    } else {
      return false;
    }
  }

  public function all($val = '')
  {
    $this->db = $this->load->database(multidb_connect($_ENV['PREFIX_DBPOS'] . $val), true);

    $data = varPost();

    $where = [];
    $where["user_status='1'"] = null;
    $operation = $this->select_dt($data, 'User', 'datatable', true, $where);
    $this->response($operation);
  }

  public function get_data_user($user_id)
  {
    $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);
    $data = $this->dbmp->where(['pos_user_id' => $user_id])->get('pos_user')->row_array();

    if (!empty($data['pos_user_photo'])) {
      $data['pos_user_photo'] = base_url() . "dokumen/user/" . $data['pos_user_photo'];
    }

    $operation = [
      'success' => true,
      'data'    => $data,
    ];

    $this->response($operation);
  }

  public function update()
  {
    $data = varPost();
    $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);

    foreach ($data as $key => $value) {
      if (preg_match('/<[^>]*>/', $value)) {
        return $this->response([
          'message' => 'Input ' . $key . ' mengandung special character HTML! Input Value : ' . $value,
          'success' => false,
          'data' => $data
        ]);
      }
    }

    if (!file_exists("./dokumen/user")) {
      mkdir("./dokumen/user", 0777, true);
      mkdir("./dokumen/user/thumbs", 0777, true);
    }
    $filess = $_FILES['pos_user_photo']['name'];
    $config['upload_path'] = "./dokumen/user";
    $config['file_name'] = gen_uuid($this->User->get_table());
    $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';

    if ($filess) {

      $read = $this->dbmp->where(['pos_user_id' => $data['pos_user_id']])->get('pos_user')->row_array();
      $this->upload->initialize($config);
      $_FILES['upload_field_name']['name']        = $_FILES['user_foto']['name'];
      $_FILES['upload_field_name']['type']        = $_FILES['user_foto']['type'];
      $_FILES['upload_field_name']['tmp_name']    = $_FILES['user_foto']['tmp_name'];
      $_FILES['upload_field_name']['error']       = $_FILES['user_foto']['error'];
      $_FILES['upload_field_name']['size']        = $_FILES['user_foto']['size'];
      if (!$this->upload->do_upload('upload_field_name')) {
        $data['pos_user_photo'] = $this->upload->display_errors();
        return $this->response([
          'success' => false,
          'message' => strip_tags($data['user_foto']),
        ]);
      } else {
        unlink("./dokumen/user/" . $read['pos_user_photo']);
        unlink("./dokumen/user/thumbs/" . $read['pos_user_photo']);
        $img = $this->upload->data();
        $data['pos_user_photo'] = $img['file_name'];

        $file = $this->upload->data();
        $file_resize_name = $config['upload_path'] . '/' . $file['file_name'];
        $resize = array();
        $size   =  array(
          array('name' => 'thumbs/', 'width' => 'auto', 'height' => 80,  'quality' => '100%'),
        );
        foreach ($size as $r) {
          $resize = array(
            "image_library" => 'gd2',
            "width"         => $r['width'],
            "height"        => $r['height'],
            "quality"       => $r['quality'],
            "source_image"  => $file_resize_name,
            "new_image"     => $config['upload_path'] . '/' . $r['name'] . $file['file_name']
          );
          $this->image_lib->clear();
          $this->image_lib->initialize($resize);
          if (!$this->image_lib->resize()) {
            $result_foto  = array(
              'success' => false,
              'message' => $this->image_lib->display_errors()
            );
          }
        }
      }
    }
    $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);
    $operation = $this->dbmp->update('pos_user', $data, ['pos_user_id' => $data['pos_user_id']]);
    if ($operation['success'] && $operation['record']) {
      $this->session->set_userdata($operation['record']);
    }
    $this->response($operation);
  }

  public function changePassword()
  {
    $data = varPost();

    if ($data['pos_user_id']) {
      $user_id = $data['pos_user_id'];
      $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);

      $user = $this->dbmp->where('pos_user_id', $data['pos_user_id'])->get('pos_user')->row_array();
      $user_pass = $user['user_password'];
      $new_pass = $this->password($data['password_new']);
      $old_pass = $this->password($data['password_old']);

      if ($user_pass == $old_pass) {
        $update = $this->dbmp->update('pos_user', ['pos_user_password' => $new_pass], ['pos+user_id' => $user_id]);
        if ($update['success']) {
          $operation = [
            'success' => true,
            'message' => 'Successfully changed password !'
          ];
        } else {
          $operation = [
            'success' => false,
            'message' => 'Failed to change password !'
          ];
        }
      } else {
        $operation = [
          'success' => false,
          'message' => 'Wrong Old Password'
        ];
      }
    } else {
      $operation = [
        'success' => false,
        'message' => 'There\'s something wrong!'
      ];
    }
    $this->response($operation);
  }

  public function get_data_toko($toko_kode)
  {
    $filter = [
      'toko_kode' => $toko_kode
    ];

    $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);
    $toko = $this->dbmp->where($filter)->get('v_pajak_toko')->row_array();

    // base_url jika connect ke optax(localhost://8801)??
    if (!empty($toko['toko_logo'])) {
      $toko['toko_logo'] = $_ENV['PAJAK_URL'] . $toko['toko_logo'];
    }

    $this->response($toko);
  }
}

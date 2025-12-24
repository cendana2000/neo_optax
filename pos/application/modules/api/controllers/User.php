<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'user/UserModel' => 'User',
      'api/MobileLogModel' => 'Log'
    ));
  }

  public function auth_header()
  {
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
    $data = varPost();

    $where = [];
    $where["user_status='1'"] = null;
    $operation = $this->select_dt($data, 'User', 'datatable', true, $where);
    $this->response($operation);
  }

  public function get_data_user($user_id)
  {
    if ($wp_id = $this->session->userdata('wajibpajak_id')) {
      $this->db->where('wajibpajak_id', $wp_id);
    }
    $data = $this->db->where(['user_id' => $user_id])->get('pos_user')->row_array();

    $result = array(
      'pos_user_id'                   => $data['user_id'],
      'pos_user_name'                 => $data['user_nama'],
      'pos_user_email'                => $data['user_email'],
      'pos_user_password'             => $data['user_password'],
      'pos_user_password_argon2id'    => null,
      'pos_user_code_store'           => $data['user_code_store'],
      'pos_user_status'               => $data['user_status'],
      'pos_user_photo'                => $data['user_foto'],
      'pos_user_last_change_password' => $data['user_last_change_password'],
      'pos_user_role_access_id'       => $data['user_role_access_id'],
      'pos_user_jenis_parent_name'    => $data['user_jenis_parent_name'],
      'pos_user_created_at'           => $data['user_created_at'],
      'pos_user_updated_at'           => $data['user_updated_at'],
      'pos_user_address'              => $data['user_alamat'],
      'pos_user_phone'                => $data['user_telepon'],
    );

    if (!empty($data['user_foto'])) {
      $result['pos_user_photo'] = base_url() . "dokumen/user/" . $data['user_foto'];
    }

    $operation = [
      'success' => true,
      'data'    => $result,
    ];

    $this->response($operation);
  }

  public function update()
  {
    $data = varPost();

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

      if ($wp_id = $this->session->userdata('wajibpajak_id')) {
        $this->db->where('wajibpajak_id', $wp_id);
      }
      $read = $this->db->where(['pos_user_id' => $data['pos_user_id']])->get('pos_user')->row_array();
      $this->upload->initialize($config);
      $_FILES['upload_field_name']['name']        = $_FILES['pos_user_photo']['name'];
      $_FILES['upload_field_name']['type']        = $_FILES['pos_user_photo']['type'];
      $_FILES['upload_field_name']['tmp_name']    = $_FILES['pos_user_photo']['tmp_name'];
      $_FILES['upload_field_name']['error']       = $_FILES['pos_user_photo']['error'];
      $_FILES['upload_field_name']['size']        = $_FILES['pos_user_photo']['size'];
      if (!$this->upload->do_upload('upload_field_name')) {
        $data['pos_user_photo'] = $this->upload->display_errors();
        return $this->response([
          'success' => false,
          'message' => strip_tags($data['pos_user_photo']),
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
    if ($wp_id = $this->session->userdata('wajibpajak_id')) {
      $this->db->where('wajibpajak_id', $wp_id);
    }
    $operation = $this->db->update('pos_user', $data, ['pos_user_id' => $data['pos_user_id']]);

    if (!empty($operation['record'])) {
      $this->session->set_userdata($operation['record']);
    }
    return $this->response([
      'success' => true,
      'message' => 'Data profile berhasil diupdate',
    ]);
  }

  public function changePassword()
  {
    $data = varPost();

    if ($data['pos_user_id']) {
      $user_id = $data['pos_user_id'];

      if ($wp_id = $this->session->userdata('wajibpajak_id')) {
        $this->db->where('wajibpajak_id', $wp_id);
      }
      $user = $this->db->where('pos_user_id', $data['pos_user_id'])->get('pos_user')->row_array();
      $user_pass = $user['user_password'];
      $new_pass = $this->password($data['password_new']);
      $old_pass = $this->password($data['password_old']);

      if ($user_pass == $old_pass) {
        $update = $this->db->update('pos_user', ['pos_user_password' => $new_pass], ['pos+user_id' => $user_id]);
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

    $toko = $this->db->where($filter)->get('v_pajak_toko')->row_array();

    // base_url jika connect ke optax(localhost://8801)??
    if (!empty($toko['toko_logo'])) {
      $toko['toko_logo'] = $_ENV['PAJAK_URL'] . $toko['toko_logo'];
    }

    $this->response($toko);
  }

  public function ping_user()
  {
    try {
      $id       = varPost('pos_user_id');
      $today    = date('Y-m-d');

      if ($wp_id = $this->session->userdata('wajibpajak_id')) {
        $this->db->where('wajibpajak_id', $wp_id);
      }
      $user = $this->db->where('user_id', $id)->get('pos_user')->row();

      if (!$user) {
        throw new Exception('User Tidak Ditemukan');
      }

      if (!empty($user->pos_user_email)) {
        $wajibpajak = $this->db
          ->where('wajibpajak_email', $user->pos_user_email)
          ->get('pajak_wajibpajak')
          ->row();

        if ($wajibpajak) {
          $this->db->where('wajibpajak_email', $user->pos_user_email);
          $this->db->update(
            'pajak_wajibpajak',
            ['mobile_last_active' => date('Y-m-d H:i:s')]
          );
        }
      }

      $hour     = date('G');
      $record   = $this->db
        ->where('log_user_id', $id)
        ->get($this->Log->get_table())
        ->row();

      if ($record) {
        $isNewDay = ($record->log_tanggal != $today);
        $updateData = [
          'log_tanggal'      => $today,
          'log_device_id'    => $this->input->post('device_id'),
          'log_device_model' => $this->input->post('model'),
          'log_last_active'  => date('Y-m-d H:i:s'),
        ];

        if ($isNewDay) {
          for ($i = 0; $i < 24; $i++) {
            $updateData["log_jam_$i"] = 0;
          }
          $updateData["log_jam_$hour"] = 1;
        } else {
          $currentHourValue = $record->{"log_jam_$hour"} ?? 0;
          $updateData["log_jam_$hour"] = $currentHourValue + 1;
        }
        $this->db
          ->where('log_id', $record->log_id)
          ->update('log_mobile', $updateData);
      } else {
        $record = $this->db
          ->select('*')
          ->from('pos_user')
          ->join('pajak_toko', 'pajak_toko.toko_kode = pos_user.pos_user_code_store', 'left')
          ->join('pajak_wajibpajak', 'pajak_wajibpajak.wajibpajak_id = pajak_toko.toko_wajibpajak_id', 'left')
          ->where('pos_user_id', $id)
          ->get()
          ->row();

        $data                         = array();
        $data['log_tanggal']          = $today;
        $data['log_user_id']          = $id;
        $data['log_user_code_store']  = $user->pos_user_code_store;
        $data['log_user_name']        = $user->pos_user_name;
        $data['log_device_id']        = $this->input->post('device_id');
        $data['log_device_model']     = $this->input->post('model');
        $data['log_last_active']      = date('Y-m-d H:i:s');
        $data['log_created_at']       = date('Y-m-d H:i:s');
        $data["log_jam_$hour"]        = 1;
        $data['log_wajibpajak_nama']  = $record->wajibpajak_nama ?? '';
        $data['log_wajibpajak_npwpd'] = $record->wajibpajak_npwpd ?? '';
        $this->Log->insert(gen_uuid($this->Log->get_table()), $data);
      }

      $datarow['success'] = true;
      $datarow['message'] = 'Success';
    } catch (Throwable $th) {
      $datarow['success'] = false;
      $datarow['message'] = $th->getMessage();
    } finally {
      $this->response($datarow);
    }
  }
}

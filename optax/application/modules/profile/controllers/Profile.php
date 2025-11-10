<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends BASE_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"   => 'User',
            "pegawai/PegawaiModel" => 'pegawai',
            "wajibpajak/WajibpajakModel" => 'wajibpajak',
            "managementuser/RoleAccessModel" => 'roleaccess',
        ));
    }

    public function index()
    {
        $this->load->view('Table');
    }

    public function load()
    {
        if ($this->session->userdata('login_access') == "pemda") {
            $data = $this->pegawai->read($this->session->userdata('pegawai_id'));
            $role = $this->roleaccess->read($data['pegawai_role_access_id']);
            $operation = [
                'success' => true,
                'login_access' => 'pemda',
                'data'    => $data,
                'role' => $role,
            ];
        }else if($this->session->userdata('login_access') == "wajibpajak"){
            $data = $this->wajibpajak->read($this->session->userdata('wajibpajak_id'));
            $operation = [
                'success' => true,
                'login_access' => 'wajibpajak',
                'data' => $data,
            ];
        }
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        if ($this->session->userdata('login_access') == "pemda") {
            $operation = $this->pegawai->read($data['id']);
        }else if($this->session->userdata('login_access') == "wajibpajak"){
            $operation = $this->wajibpajak->read($data['id']);
        }
        $this->response($operation);
    }

    public function changePassword()
    {
        $data = varPost();
        if ($this->session->userdata('login_access') == "pemda") {
            if ($this->session->userdata('pegawai_id')) {
                $pegawai_id = $this->session->userdata('pegawai_id');
                $user = $this->pegawai->read($pegawai_id);
                $user_pass = $user['pegawai_password'];
                $new_pass = $this->password($data['password_new']);
                $old_pass = $this->password($data['password_old']);
                if($user_pass == $old_pass){
                    $update = $this->pegawai->update(['pegawai_id' => $pegawai_id], ['pegawai_password' => $new_pass, 'pegawai_last_change_password' => date('Y-m-d H:i:s')]);
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
                }else{
                    $operation = [
                        'success' => false,
                        'message' => 'Wrong Old Password'
                    ];
                }
            } else {
                $operation = [
                    'success' => false,
                    'message' => 'User not found, please re-login!'
                ];
            }
        }
        else if($this->session->userdata('login_access') == "wajibpajak"){
            if ($this->session->userdata('wajibpajak_id')) {
                $id = $this->session->userdata('wajibpajak_id');
                $user = $this->wajibpajak->read($id);
                $user_pass = $user['wajibpajak_password'];
                $new_pass = $this->password($data['password_new']);
                $old_pass = $this->password($data['password_old']);
                if($user_pass == $old_pass){
                    $update = $this->wajibpajak->update(['wajibpajak_id' => $id], ['wajibpajak_password' => $new_pass, 'wajibpajak_last_change_password' => date('Y-m-d H:i:s')]);
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
                }else{
                    $operation = [
                        'success' => false,
                        'message' => 'Wrong Old Password'
                    ];
                }
            } else {
                $operation = [
                    'success' => false,
                    'message' => 'User not found, please re-login!'
                ];
            }
        }
        $this->response($operation);
    }

    public function cekEmail()
    {
        $data = varPost();
        if($this->session->userdata('login_access') == "pemda"){
            $get = $this->pegawai->select([
                'fields' => ['pegawai_email', 'pegawai_id'],
                'filters_static' => [
                    'pegawai_email' => strtolower($data['email']),
                    'pegawai_deleted_at' => null,
                ],
                'limit' => 1
            ]);
            $operation = [
                'success' => true
            ];
            if ($get['total'] != 0) {
                $operation['success'] = false;
                $operation['id'] = $get['data'][0]['pegawai_id'];
            }
        }else if($this->session->userdata('login_access') == "wajibpajak"){
            $get = $this->wajibpajak->select([
                'fields' => ['wajibpajak_email', 'wajibpajak_id'],
                'filters_static' => [
                    'wajibpajak_email' => strtolower($data['email']),
                    'wajibpajak_deleted_at' => null,
                ],
                'limit' => 1
            ]);
            $operation = [
                'success' => true
            ];
            if ($get['total'] != 0) {
                $operation['success'] = false;
                $operation['id'] = $get['data'][0]['wajibpajak_id'];
            }
        }
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        if($this->session->userdata('login_access') == "pemda"){
            if (!file_exists("./dokumen/user")) {
                mkdir("./dokumen/user", 0777, true);
                mkdir("./dokumen/user/thumbs", 0777, true);
            }
            $filess = $_FILES['pegawai_foto']['name'];
            $config['upload_path'] = "./dokumen/user";
            $config['file_name'] = gen_uuid($this->pegawai->get_table());
            $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';
            if ($filess) {
                $read = $this->pegawai->read($data['pegawai_id']);
                $this->upload->initialize($config);
                $_FILES['upload_field_name']['name']        = $_FILES['pegawai_foto']['name'];
                $_FILES['upload_field_name']['type']        = $_FILES['pegawai_foto']['type'];
                $_FILES['upload_field_name']['tmp_name']    = $_FILES['pegawai_foto']['tmp_name'];
                $_FILES['upload_field_name']['error']       = $_FILES['pegawai_foto']['error'];
                $_FILES['upload_field_name']['size']        = $_FILES['pegawai_foto']['size'];
                if (!$this->upload->do_upload('upload_field_name')) {
                    $data['pegawai_foto'] = $this->upload->display_errors();
                } else {
                    unlink("./dokumen/user/" . $read['pegawai_foto']);
                    unlink("./dokumen/user/thumbs/" . $read['pegawai_foto']);
                    $img = $this->upload->data();
                    $data['pegawai_foto'] = $img['file_name'];
    
                    $file = $this->upload->data();
                    $file_resize_name = $config['upload_path'] . '/' . $file['file_name'];
                    $resize = array();
                    $size   =  array(
                        array('name' => 'thumbs/', 'width' => auto, 'height' => 80,  'quality' => '100%'),
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
            $operation = $this->pegawai->update($data['pegawai_id'], $data);
            if ($operation['success'] && $operation['record']) {
                $this->session->set_userdata($operation['record']);
            }
        }else if($this->session->userdata('login_access') == "wajibpajak"){
            if (!file_exists("./dokumen/user")) {
                mkdir("./dokumen/user", 0777, true);
                mkdir("./dokumen/user/thumbs", 0777, true);
            }
            $filess = $_FILES['wajibpajak_foto']['name'];
            $config['upload_path'] = "./dokumen/user";
            $config['file_name'] = gen_uuid($this->wajibpajak->get_table());
            $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';
            if ($filess) {
                $read = $this->wajibpajak->read($data['wajibpajak_id']);
                $this->upload->initialize($config);
                $_FILES['upload_field_name']['name']        = $_FILES['wajibpajak_foto']['name'];
                $_FILES['upload_field_name']['type']        = $_FILES['wajibpajak_foto']['type'];
                $_FILES['upload_field_name']['tmp_name']    = $_FILES['wajibpajak_foto']['tmp_name'];
                $_FILES['upload_field_name']['error']       = $_FILES['wajibpajak_foto']['error'];
                $_FILES['upload_field_name']['size']        = $_FILES['wajibpajak_foto']['size'];
                if (!$this->upload->do_upload('upload_field_name')) {
                    $data['wajibpajak_foto'] = $this->upload->display_errors();
                } else {
                    unlink("./dokumen/user/" . $read['wajibpajak_foto']);
                    unlink("./dokumen/user/thumbs/" . $read['wajibpajak_foto']);
                    $img = $this->upload->data();
                    $data['wajibpajak_foto'] = $img['file_name'];
    
                    $file = $this->upload->data();
                    $file_resize_name = $config['upload_path'] . '/' . $file['file_name'];
                    $resize = array();
                    $size   =  array(
                        array('name' => 'thumbs/', 'width' => auto, 'height' => 80,  'quality' => '100%'),
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
            $operation = $this->wajibpajak->update($data['wajibpajak_id'], $data);
            if ($operation['success'] && $operation['record']) {
                $this->session->set_userdata($operation['record']);
            }
        }
        $this->response($operation);
    }
}

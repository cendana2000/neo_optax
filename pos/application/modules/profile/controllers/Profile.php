<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends BASE_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"   => 'User',
            "managementuser/RoleAccessModel" => 'roleaccess',
        ));
    }

    public function index()
    {
        $this->load->view('Table');
    }

    public function load()
    {
        if ($this->session->userdata('user_id')) {
            $data = $this->User->read($this->session->userdata('user_id'));
            $role = $this->roleaccess->read($data['user_role_access_id']);
            $operation = [
                'success' => true,
                'data'    => $data,
                'role' => $role,
            ];
        }
        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->User->read($data['id']);
        $this->response($operation);
    }

    public function changePassword()
    {
        $data = varPost();

        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $user = $this->User->read($user_id);
            $user_pass = $user['user_password'];
            $new_pass = $this->password($data['password_new']);
            $old_pass = $this->password($data['password_old']);
            if($user_pass == $old_pass){
                $update = $this->User->update(['user_id' => $user_id], ['user_password' => $new_pass]);
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
        $this->response($operation);
    }

    public function cekEmail()
    {
        $data = varPost();

        $get = $this->User->select([
            'fields' => ['user_email', 'user_id'],
            'filters_static' => [
                'user_email' => strtolower($data['email']),
                'user_deleted_at' => null,
            ],
            'limit' => 1
        ]);
        $operation = [
            'success' => true
        ];
        if ($get['total'] != 0) {
            $operation['success'] = false;
            $operation['id'] = $get['data'][0]['user_id'];
        }
        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        if (!file_exists("./dokumen/user")) {
            mkdir("./dokumen/user", 0777, true);
            mkdir("./dokumen/user/thumbs", 0777, true);
        }
        $filess = $_FILES['user_foto']['name'];
        $config['upload_path'] = "./dokumen/user";
        $config['file_name'] = gen_uuid($this->User->get_table());
        $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';
        if ($filess) {
            $read = $this->User->read($data['user_id']);
            $this->upload->initialize($config);
            $_FILES['upload_field_name']['name']        = $_FILES['user_foto']['name'];
            $_FILES['upload_field_name']['type']        = $_FILES['user_foto']['type'];
            $_FILES['upload_field_name']['tmp_name']    = $_FILES['user_foto']['tmp_name'];
            $_FILES['upload_field_name']['error']       = $_FILES['user_foto']['error'];
            $_FILES['upload_field_name']['size']        = $_FILES['user_foto']['size'];
            if (!$this->upload->do_upload('upload_field_name')) {
                $data['user_foto'] = $this->upload->display_errors();
            } else {
                unlink("./dokumen/user/" . $read['user_foto']);
                unlink("./dokumen/user/thumbs/" . $read['user_foto']);
                $img = $this->upload->data();
                $data['user_foto'] = $img['file_name'];

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
        $operation = $this->User->update($data['user_id'], $data);
        if ($operation['success'] && $operation['record']) {
            $this->session->set_userdata($operation['record']);
        }
        $this->response($operation);
    }
}

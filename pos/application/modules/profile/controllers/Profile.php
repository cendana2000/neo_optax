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
            if ($user_pass == $old_pass) {
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
            } else {
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
        $user_id = $data['user_id'];
        if (!file_exists("./dokumen/user")) {
            mkdir("./dokumen/user", 0777, true);
            mkdir("./dokumen/user/thumbs", 0777, true);
        }

        $filess = $_FILES['user_foto']['name'] ?? null;

        $config['upload_path']   = "./dokumen/user";
        $config['file_name']     = gen_uuid($this->User->get_table());
        $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';

        if ($filess) {
            $read = $this->User->read($user_id);

            $_FILES['upload_field_name'] = [
                'name'     => $_FILES['user_foto']['name'],
                'type'     => $_FILES['user_foto']['type'],
                'tmp_name' => $_FILES['user_foto']['tmp_name'],
                'error'    => $_FILES['user_foto']['error'],
                'size'     => $_FILES['user_foto']['size']
            ];

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('upload_field_name')) {
                $upload_error = $this->upload->display_errors();
            } else {

                $img = $this->upload->data();
                $new_file = $img['file_name'];
                $data['user_foto'] = $new_file;
                if (!empty($read['user_foto'])) {
                    @unlink("./dokumen/user/" . $read['user_foto']);
                    @unlink("./dokumen/user/thumbs/" . $read['user_foto']);
                }

                $file_path = $config['upload_path'] . '/' . $new_file;

                $size = [
                    ['name' => 'thumbs/', 'width' => 200, 'height' => 200, 'quality' => '90%'],
                ];

                foreach ($size as $r) {
                    $resize = [
                        "image_library" => 'gd2',
                        "source_image"  => $file_path,
                        "new_image"     => $config['upload_path'] . '/' . $r['name'] . $new_file,
                        "width"         => $r['width'],
                        "height"        => $r['height'],
                        "maintain_ratio" => TRUE,
                        "quality"       => $r['quality']
                    ];
                    $this->image_lib->clear();
                    $this->image_lib->initialize($resize);
                    $this->image_lib->resize();
                }
                $this->db->where('pos_user_id', $user_id);
                $this->db->update('pos_user', [
                    'pos_user_photo' => $new_file
                ]);
            }
        }

        $operation = $this->User->update($user_id, $data);

        if ($operation['success'] && $operation['record']) {
            $this->session->set_userdata($operation['record']);
        }

        $this->response($operation);
    }
}

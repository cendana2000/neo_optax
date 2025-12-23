<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'UserModel' => 'User',
            'UserSuperModel' => 'UserSuper',
            'managementuser/RoleAccessModel' => 'roleaccess',
            'project/ProjectModel' => 'Project',
            'UserProjectModel'  => 'UserProject',
        ));
    }

    public function loadTable()
    {
        $data = varPost();

        $where = [];
        $where["user_status='1'"] = null;
        $operation = $this->select_dt($data, 'User', 'datatable', true, $where);

        $this->response($operation);
    }

    public function combobox_role()
    {
        $where = array(
            'role_access_deleted_at' => null
        );
        $operation = $this->roleaccess->select(array(
            // 'filters_static' => $where,
            'sort_static' => 'role_access_nama ASC'
        ));

        $this->response($operation);
    }
    public function combobox_project()
    {
        $data = varPost();
        $where = array(
            'project_deleted_at' => null
        );
        if (!check_superadmin()) {
            $where['project_id'] = $this->session->userdata('user_project_id');
        }
        if (isset($data['user_id']) && $data['user_id']) {
            $get_projects = $this->UserProject->select([
                'fields'    => ['user_project_project_id'],
                'filters_static'    => [
                    'user_project_user_id' => $data['user_id']
                ]
            ]);
            if ($get_projects['data']) {
                $project_ids = array_to_string(array_column($get_projects['data'], 'user_project_project_id'));
                $where['project_id not in (' . $project_ids . ')'] = null;
            }
        }
        // $where['(NOW() BETWEEN project_start_date and project_end_date)'] = null;
        $operation = $this->Project->select(array(
            'filters_static' => $where,
            'sort_static' => 'project_code ASC'
        ));

        $this->response($operation);
    }
    public function store()
    {
        $data = varPost();

        if ($wp_id = $this->session->userdata('wajibpajak_id')) {
            $this->db->where('wajibpajak_id', $wp_id);
        }
        $arPegawai = $this->db->get_where('pos_pegawai', ['pegawai_id' => $data['user_nama']])->row_array();

        $data['user_nama'] = $arPegawai['pegawai_nama'];
        $data['user_telepon'] = $arPegawai['pegawai_hp'];
        $data['user_alamat'] = $arPegawai['pegawai_alamat'];

        $data['user_status']            = 1;
        $data['user_is_registered']     = 1;
        if ($this->session->userdata('hak_akses_is_super') != 1) {
            $data['user_project_id'] = $this->session->userdata('user_project_id');
        }
        $data['user_created_at']        = date("Y-m-d H:i:s");
        $data['user_updated_at']        = date("Y-m-d H:i:s");
        $data['user_last_change_password'] = date('Y-m-d H:i:s');
        $data['user_password'] = $this->password($data['user_password']);

        // $data['user_password'] = $this->password($this->config->item('password_default'));

        if (!file_exists("./dokumen/user")) {
            mkdir("./dokumen/user", 0777, true);
            mkdir("./dokumen/user/thumbs", 0777, true);
        }
        $filess = $_FILES['user_foto']['name'];
        $config['upload_path'] = "./dokumen/user";
        $config['file_name'] = gen_uuid($this->User->get_table());

        if ($filess) {
            $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';

            $this->upload->initialize($config);
            $_FILES['upload_field_name']['name']        = $_FILES['user_foto']['name'];
            $_FILES['upload_field_name']['type']        = $_FILES['user_foto']['type'];
            $_FILES['upload_field_name']['tmp_name']    = $_FILES['user_foto']['tmp_name'];
            $_FILES['upload_field_name']['error']       = $_FILES['user_foto']['error'];
            $_FILES['upload_field_name']['size']        = $_FILES['user_foto']['size'];
            if (!$this->upload->do_upload('upload_field_name')) {
                $data['user_foto'] = $this->upload->display_errors();
            } else {
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
        $operation = $this->User->insert(gen_uuid($this->User->get_table()), $data);

        $this->response($operation);
    }

    public function update()
    {
        $data = varPost();
        $getNamaPegawai = $this->User->getPegawaiNamaByPegawaiID($data['user_nama']);
        if (count($getNamaPegawai) > 0) {
            $data['user_nama'] = $getNamaPegawai[0]->pegawai_nama;
            $data['user_password'] = $this->password($data['user_password']);
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
            $this->response($operation);
        }
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->User->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $data['user_status'] = 0;
        $operation = $this->User->update($data['id'], $data);
        $this->response($operation);
    }

    public function status()
    {
        $data = varPost();
        $read = $this->User->read($data['id']);
        if ($read['user_status'] == 1) {
            $data['user_status'] = 0;
            $operation = $this->User->update($data['id'], $data);
        } else {
            $data['user_status'] = 1;
            $operation = $this->User->update($data['id'], $data);
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
                'user_deleted_at' => null
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

    public function resetPassword()
    {
        $data = varPost();

        if ($data['user_id']) {
            $this->User->update(['user_id' => $data['user_id']], [
                'user_password' => $this->password($this->config->item('password_default')),
                'user_last_change_password' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->response(['success' => true]);
    }

    public function loadTableProject()
    {
        $data = varPost();

        $where = [
            'user_project_user_id'  => $data['user_id']
        ];
        if (!check_superadmin()) {
            $where['user_project_project_id'] = $this->session->userdata('user_project_id');
        }
        $operation = $this->select_dt($data, 'UserProject', 'datatable', true, $where);

        $this->response($operation);
    }

    public function saveProject()
    {
        $data = varPost();
        $operation = $this->UserProject->insert(gen_uuid(), $data);
        $this->response($operation);
    }

    public function deleteProject()
    {
        $data = varPost();
        $operation = $this->UserProject->delete($data['id']);
        $this->response($operation);
    }
}

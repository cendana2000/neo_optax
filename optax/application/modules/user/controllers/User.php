<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'UserModel'                         => 'User',
            'UserSuperModel'                    => 'UserSuper',
            'managementuser/RoleAccessModel'    => 'roleaccess',
            'project/ProjectModel'              => 'Project',
            'UserProjectModel'                  => 'UserProject',
            'pegawai/PegawaiModel'              => 'pegawai',
            'user/UserPegawaiModel'             => 'userpegawai',
            'pemda/PemdaModel'                  => 'pemda',
        ));
    }

    public function index()
    {
        $data = varPost();
        $where = [];
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $where['pemda_id'] = $pemda_id;
        }
        $where["pegawai_status='1'"] = null;
        $where["pegawai_role_access_id <> '123'"] = null;
        $operation = $this->select_dt($data, 'userpegawai', 'datatable', true, $where);

        $this->response($operation);
    }

    public function combobox_role()
    {
        $where = array(
            'role_access_deleted_at' => null
        );
        $operation = $this->roleaccess->select(array(
            'filters_static' => $where,
            'sort_static' => 'role_access_nama ASC'
        ));

        $this->response($operation);
    }

    public function combobox_pemda()
    {
        $data = $this->db
            ->select([
                'pemda_id',
                'pemda_nama',
                'pemda_kode',
                'kabkota_id',
                'pemda_deleted_at'
            ])
            ->from('conf_pemda')
            ->where('pemda_deleted_at IS NULL', null, false)
            ->order_by('pemda_nama', 'ASC')
            ->get()
            ->result_array();

        $response = [
            'success' => true,
            'total'   => count($data),
            'data'    => $data
        ];

        $this->response($response);
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
        if (isset($data['pegawai_id']) && $data['pegawai_id']) {
            $get_projects = $this->pegawaiProject->select([
                'fields'    => ['user_project_project_id'],
                'filters_static'    => [
                    'user_project_pegawai_id' => $data['pegawai_id']
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

    private function email_exists($email, $exclude_id = null)
    {
        $this->db->where('pegawai_email', $email);
        if ($exclude_id) {
            $this->db->where('pegawai_id <>', $exclude_id);
        }
        return $this->db->get('pajak_pegawai')->num_rows() > 0;
    }

    private function is_role_pemda($role_id)
    {
        $role = $this->db
            ->select('role_access_kode')
            ->where('role_access_id', $role_id)
            ->get('pajak_role_access')
            ->row_array();

        return isset($role['role_access_kode']) && $role['role_access_kode'] === 'pemda';
    }


    public function store()
    {
        $post = varPost();

        if ($this->email_exists($post['pegawai_email'])) {
            $this->response([
                'success' => false,
                'message' => 'Email sudah digunakan'
            ]);
            return;
        }

        $id = gen_uuid($this->pegawai->get_table());
        $data['pegawai_id']                = $id;
        $data['pegawai_status']            = 1;
        $data['pegawai_nama']              = $post['pegawai_nama'];
        $data['pegawai_nip']               = $post['pegawai_nip'];
        $data['pegawai_email']             = $post['pegawai_email'];
        $data['pegawai_password']          = md5(md5($post['pegawai_password']));
        $data['pegawai_role_access_id']    = $post['pegawai_role_access_id'];
        $data['pegawai_hp']                = $post['pegawai_hp'];
        $data['pegawai_alamat']            = $post['pegawai_alamat'];
        $data['pegawai_last_change_password'] = date('Y-m-d H:i:s');
        $data['pegawai_created_at']        = date("Y-m-d H:i:s");
        $data['pegawai_created_by']        = $this->session->userdata('user_pegawai_id');
        $data['pemda_id']                  = $post['select_pemda'];

        $final_pemda_id = null;
        if ($this->is_role_pemda($post['pegawai_role_access_id'])) {
            if (empty($post['select_pemda'])) {
                $this->response([
                    'success' => false,
                    'message' => 'Pemda wajib dipilih untuk role Pemda'
                ]);
                return;
            }
            $final_pemda_id = (int) $post['select_pemda'];
        }

        if (!file_exists("./dokumen/user")) {
            mkdir("./dokumen/user", 0777, true);
            mkdir("./dokumen/user/thumbs", 0777, true);
        }
        $filess = $_FILES['pegawai_foto']['name'];
        $config['upload_path'] = "./dokumen/user";
        $config['file_name'] = gen_uuid($this->pegawai->get_table());

        if ($filess) {
            $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';

            $this->upload->initialize($config);
            $_FILES['upload_field_name']['name']        = $_FILES['pegawai_foto']['name'];
            $_FILES['upload_field_name']['type']        = $_FILES['pegawai_foto']['type'];
            $_FILES['upload_field_name']['tmp_name']    = $_FILES['pegawai_foto']['tmp_name'];
            $_FILES['upload_field_name']['error']       = $_FILES['pegawai_foto']['error'];
            $_FILES['upload_field_name']['size']        = $_FILES['pegawai_foto']['size'];
            if (!$this->upload->do_upload('upload_field_name')) {
                $data['pegawai_foto'] = $this->upload->display_errors();
            } else {
                $img = $this->upload->data();
                $data['pegawai_foto'] = $img['file_name'];

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
        $operation = $this->pegawai->insert($id, $data);
        if ($final_pemda_id !== null) {
            $this->db->where('pegawai_id', $id)
                ->update('pajak_pegawai', [
                    'pemda_id' => $final_pemda_id
                ]);
        } else {
            $this->db->where('pegawai_id', $id)
                ->update('pajak_pegawai', [
                    'pemda_id' => null
                ]);
        }
        $this->response($operation);
    }

    public function update()
    {
        $post = varPost();
        if ($this->email_exists($post['pegawai_email'], $post['pegawai_id'])) {
            $this->response([
                'success' => false,
                'message' => 'Email sudah digunakan oleh user lain'
            ]);
            return;
        }

        $data['pegawai_id']                = $post['pegawai_id'];
        $data['pegawai_nama']              = $post['pegawai_nama'];
        $data['pegawai_nip']               = $post['pegawai_nip'];
        $data['pegawai_email']             = $post['pegawai_email'];
        $data['pegawai_role_access_id']    = $post['pegawai_role_access_id'];
        $data['pegawai_hp']                = $post['pegawai_hp'];
        $data['pegawai_alamat']            = $post['pegawai_alamat'];
        $data['pegawai_last_change_password'] = date('Y-m-d H:i:s');
        $data['pegawai_created_at']        = date("Y-m-d H:i:s");
        $data['pegawai_created_by']        = $this->session->userdata('user_pegawai_id');
        $data['pemda_id']                  = $post['select_pemda'];
        if (!empty($post['pegawai_password'])) {
            $post['pegawai_password'] = md5(md5($post['pegawai_password']));
        } else {
            unset($post['pegawai_password']);
        }

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
        $operation = $this->pegawai->update($data['pegawai_id'], $data);
        $this->db->where('pegawai_id', $post['pegawai_id'])
            ->update('pajak_pegawai', [
                'pemda_id' => $post['select_pemda']
            ]);

        $this->response($operation);
    }

    public function read()
    {
        $data = varPost();
        $operation = $this->pegawai->read($data['id']);
        $this->response($operation);
    }

    public function delete()
    {
        $data = varPost();
        $data['pegawai_status'] = 0;
        $operation = $this->pegawai->update($data['id'], $data);
        $this->response($operation);
    }

    public function status()
    {
        $data = varPost();
        $read = $this->pegawai->read($data['id']);
        if ($read['pegawai_status'] == 1) {
            $data['pegawai_status'] = 0;
            $operation = $this->pegawai->update($data['id'], $data);
        } else {
            $data['pegawai_status'] = 1;
            $operation = $this->pegawai->update($data['id'], $data);
        }
        $this->response($operation);
    }

    public function cekEmail()
    {
        $data = varPost();

        $get = $this->pegawai->select([
            'fields' => ['pegawai_email', 'pegawai_id'],
            'filters_static' => [
                'pegawai_email' => $data['email'],
                'pegawai_deleted_at' => null
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
        $this->response($operation);
    }

    public function resetPassword()
    {
        $data = varPost();

        if ($data['pegawai_id']) {
            $this->pegawai->update(['pegawai_id' => $data['pegawai_id']], [
                'pegawai_password' => $this->password($this->config->item('password_default')),
                'pegawai_last_change_password' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->response(['success' => true]);
    }

    public function loadTableProject()
    {
        $data = varPost();

        $where = [
            'user_project_pegawai_id'  => $data['pegawai_id']
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
        $operation = $this->pegawaiProject->insert(gen_uuid(), $data);
        $this->response($operation);
    }

    public function deleteProject()
    {
        $data = varPost();
        $operation = $this->pegawaiProject->delete($data['id']);
        $this->response($operation);
    }
}

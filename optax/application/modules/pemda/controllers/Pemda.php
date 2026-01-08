<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pemda extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'pemda/pemdaModel' => 'pemda'
        ));
    }

    public function index()
    {
        $post = varPost();
        $where = [];

        if (isset($post["filter_status"]) && $post["filter_status"] != "") {
            if ($post["filter_status"] == "aktif") {
                $where['pemda_deleted_at IS NULL'] = null;
            } else if ($post["filter_status"] == "tidak_aktif") {
                $where['pemda_deleted_at IS NOT NULL'] = null;
            }
        }

        $result = $this->select_dt(
            $post,
            'pemda',
            'table',
            true,
            $where
        );
        return $this->response($result);
    }

    public function combobox_provinsi()
    {
        try {
            $query = $this->db->select(['cp.provinsi_id', 'cp.provinsi_nama'])
                ->where('cp.provinsi_deleted_at IS NULL', null, false)
                ->from('conf_provinsi cp')
                ->order_by('cp.provinsi_nama', 'ASC')
                ->get()
                ->result_array();

            $response = [
                'success' => true,
                'total'   => count($query),
                'data'    => $query
            ];

            return $this->response($response);
        } catch (Exception $e) {
            return $this->response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function combobox_kabkota()
    {
        $post = varPost();
        try {
            $provinsi_id = $post['provinsi_id'];
            if (!$provinsi_id) {
                return $this->response([
                    'success' => true,
                    'total'   => 0,
                    'data'    => [],
                    'message' => 'Pilih provinsi terlebih dahulu'
                ]);
            }

            $query =  $this->db->select(['ck.kabkota_id', 'ck.kabkota_nama'])
                ->where('ck.kabkota_deleted_at IS NULL', null, false)
                ->where('ck.provinsi_id', $provinsi_id)
                ->from('conf_kabkota ck')
                ->order_by('ck.kabkota_nama', 'ASC')
                ->get()->result_array();

            $response = [
                'success' => true,
                'total'   => count($query),
                'data'    => $query,
                'message' => count($query) > 0 ? '' : 'Tidak ada kabupaten/kota untuk provinsi ini'
            ];

            return $this->response($response);
        } catch (Exception $e) {
            return $this->response([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function select()
    {
        $where = varPost();
        $where['pemda_deleted_at IS NULL'] = null;

        $this->response(
            $this->pemda->select([
                'filters_static' => $where,
                'without_global_scope' => true
            ])
        );
    }


    public function set_pemda($id = null)
    {
        $this->session->set_userdata('pemda_id', $id);
        $operation = array('success' => false);

        $this->response($operation);
    }

    function read($value = '')
    {
        $this->response($this->pemda->read(varPost()));
    }

    public function save()
    {
        $post = varPost();

        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $data['pemda_kode'] = substr(str_shuffle($permitted_chars), 0, 5);
        $check = $this->db->get_where('conf_pemda', ['pemda_kode' => $data['pemda_kode']])->row();
        if ($check) {
            $data['pemda_kode'] = substr(str_shuffle($permitted_chars), 0, 5);
        }

        $data['pemda_nama']       = $post['pemda_nama'];
        $data['provinsi_id']      = $post['select_provinsi'];
        $data['kabkota_id']       = $post['select_kabkota'];
        $data['pemda_alamat']     = isset($post['pemda_alamat']) ? $post['pemda_alamat'] : null;
        $data['pemda_coord']      = $post['pemda_coord'];
        $data['pemda_created_at'] = date("Y-m-d H:i:s");
        if (!file_exists("./dokumen/pemda")) {
            mkdir("./dokumen/pemda", 0777, true);
            mkdir("./dokumen/pemda/thumbs", 0777, true);
        }

        $hasFile = isset($_FILES['pemda_logo']['name']) && !empty($_FILES['pemda_logo']['name']);

        if ($hasFile) {
            $config['upload_path'] = "./dokumen/pemda";
            $config['file_name'] = gen_uuid('conf_pemda');
            $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';
            $config['max_size'] = 2048;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('pemda_logo')) {
                $img = $this->upload->data();
                $data['pemda_logo'] = $img['file_name'];
                $this->load->library('image_lib');
                $file_resize_name = $config['upload_path'] . '/' . $img['file_name'];
                $sizes = array(
                    array('name' => 'thumbs/', 'width' => 'auto', 'height' => 80, 'quality' => '100%'),
                );

                foreach ($sizes as $size) {
                    $config_resize = array(
                        "image_library" => 'gd2',
                        "width"         => $size['width'],
                        "height"        => $size['height'],
                        "quality"       => $size['quality'],
                        "source_image"  => $file_resize_name,
                        "new_image"     => $config['upload_path'] . '/' . $size['name'] . $img['file_name']
                    );

                    $this->image_lib->initialize($config_resize);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                }
            } else {
                $data['pemda_logo'] = null;
            }
        } else {
            $data['pemda_logo'] = null;
        }

        if (isset($post['pemda_id']) && !empty($post['pemda_id']) && $post['pemda_id'] != 'null') {
            $this->db->where('pemda_id', $post['pemda_id']);
            $result = $this->db->update('conf_pemda', $data);

            if ($result) {
                $operation = [
                    'success' => true,
                    'message' => 'Data berhasil diupdate',
                    'id' => $post['pemda_id'],
                    'record' => null
                ];
            } else {
                $operation = [
                    'success' => false,
                    'message' => 'Gagal mengupdate data'
                ];
            }
        } else {
            if (isset($data['pemda_id'])) {
                unset($data['pemda_id']);
            }

            $result = $this->db->insert('conf_pemda', $data);

            if ($result) {
                $insert_id = $this->db->insert_id();
                $operation = [
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'id' => $insert_id,
                    'record' => null
                ];
            } else {
                $operation = [
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $this->db->error()['message']
                ];
            }
        }

        $this->response($operation);
    }

    public function update()
    {
        $post = varPost();

        if (empty($post['pemda_id'])) {
            return $this->response([
                'success' => false,
                'message' => 'ID Pemda tidak ditemukan'
            ]);
        }
        $old = $this->db
            ->get_where('conf_pemda', ['pemda_id' => $post['pemda_id']])
            ->row();

        if (!$old) {
            return $this->response([
                'success' => false,
                'message' => 'Data pemda tidak ditemukan'
            ]);
        }
        $data = [
            'pemda_nama'        => $post['pemda_nama'],
            'provinsi_id'       => $post['provinsi_id'],
            'kabkota_id'        => $post['kabkota_id'],
            'pemda_alamat'      => $post['pemda_alamat'] ?? null,
            'pemda_coord'       => $post['pemda_coord'],
            'pemda_updated_at'  => date('Y-m-d H:i:s'),
        ];
        if (isset($post['pemda_status'])) {
            if ($post['pemda_status'] === 'inactive') {
                $data['pemda_deleted_at'] = date('Y-m-d H:i:s');
            } else {
                $data['pemda_deleted_at'] = null;
            }
        }
        $path = './dokumen/pemda/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            mkdir($path . 'thumbs', 0777, true);
        }
        if (!empty($post['remove_logo']) && $post['remove_logo'] == 1) {
            if (!empty($old->pemda_logo)) {
                @unlink($path . $old->pemda_logo);
                @unlink($path . 'thumbs/' . $old->pemda_logo);
            }
            $data['pemda_logo'] = null;
        }
        if (!empty($_FILES['pemda_logo']['name'])) {
            if (!empty($old->pemda_logo)) {
                @unlink($path . $old->pemda_logo);
                @unlink($path . 'thumbs/' . $old->pemda_logo);
            }

            $config = [
                'upload_path'   => $path,
                'file_name'     => gen_uuid('conf_pemda'),
                'allowed_types' => 'jpg|jpeg|png|JPG|JPEG|PNG',
                'max_size'      => 2048
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('pemda_logo')) {
                $img = $this->upload->data();
                $data['pemda_logo'] = $img['file_name'];
                $this->load->library('image_lib');
                $resize = [
                    'image_library'  => 'gd2',
                    'source_image'   => $path . $img['file_name'],
                    'new_image'      => $path . 'thumbs/' . $img['file_name'],
                    'height'         => 80,
                    'maintain_ratio' => true,
                    'quality'        => '100%'
                ];

                $this->image_lib->initialize($resize);
                $this->image_lib->resize();
                $this->image_lib->clear();
            } else {
                return $this->response([
                    'success' => false,
                    'message' => strip_tags($this->upload->display_errors())
                ]);
            }
        }
        $this->db->where('pemda_id', $post['pemda_id']);
        $update = $this->db->update('conf_pemda', $data);

        if ($update) {
            $this->response([
                'success' => true,
                'message' => 'Data pemda berhasil diperbarui',
                'id'      => $post['pemda_id']
            ]);
        } else {
            $this->response([
                'success' => false,
                'message' => 'Gagal memperbarui data'
            ]);
        }
    }
}

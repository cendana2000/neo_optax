<?php defined('BASEPATH') or exit('No direct script access allowed');

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Main extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"   => 'User',
            "project/ProjectModel"   => 'Project',
            "user/UserProjectModel"   => 'UserProject',
        ));
    }

    public function index()
    {
        redirect('/');
    }

    public function index2()
    {
        print_r('<pre>');
        print_r($this->session->userdata());
        print_r('</pre>');
        exit;
    }

    public function getPage()
    {
        $wp_id = $this->session->userdata('wajibpajak_id');
        if (empty($wp_id)) {
            return $this->response([
                'islogin' => false,
                'message' => 'Session wajib pajak tidak valid'
            ]);
        }
        $menu = explode('-', varPost('menu'));
        if (count($menu) < 2) {
            return $this->response([
                'islogin' => true,
                'message' => 'Menu tidak valid'
            ]);
        }
        $view_path = strtolower($menu[0]) . '/' . $menu[1];
        $view = $this->load->view($view_path, null, true);
        $operation = [
            'view'     => base64_encode($view),
            'islogin'  => $this->User->islogin()
        ];

        $this->response($operation);
    }


    public function detailProject()
    {
        $data = varPost();
        $operation = $this->Project->read($data['id']);
        $this->response($operation);
    }

    public function loadProject()
    {
        $data = varPost();

        $operation = $this->UserProject->select([
            'fields' => ['user_project_project_id', 'project_code', 'project_description'],
            'filters_static' => [
                'user_project_user_id' => $data['id'],
                // '(NOW() BETWEEN project_start_date and project_end_date)' => null
            ],
            'sort_static' => 'project_code'
        ]);
        $this->response($operation);
    }

    public function changeProject()
    {
        $data = varPost();

        $user = $this->db->where(array(
            'user_email'    => strtolower($data['email']),
            'user_status'   => 1,
            'user_deleted_at' => null,
        ))->get('v_user')->row_array();
        if (!empty($user)) {
            $user['user_project_id'] = $data['project_id'];
            $user['login_status'] = true;
            $this->session->set_userdata($user);
            $this->response(array(
                'success' => true,
            ));
        } else {
            $this->response(array(
                'success' => false,
                'message' => 'User not found. Please check your email and password.'
            ));
        }
    }

    public function changeLog()
    {
        $operation = $this->db->query('SELECT * FROM sys_change_log 
        WHERE change_log_which_app = 1 
        ORDER BY change_log_change_date DESC
        LIMIT 3')->result_array();

        // $this->ChangeLog->select([
        //     'sort_static' => 'change_log_change_date DESC',
        //     'limit' => 3
        // ]);
        $this->response([
            'success' => true,
            'data' => $operation
        ]);
    }

    public function ping()
    {
        $email = $this->session->userdata('user_email');
        $userId = $this->session->userdata('user_id');
        $codeStore = $this->session->userdata('toko_kode');
        $userName = $this->session->userdata('user_role_access_nama') ?? $this->session->userdata('user_nama');

        $wpNama = $this->session->userdata('toko_nama') ?? '';
        $wpNpwpd = $this->session->userdata('npwpd') ?? '';

        if (!$email || !$userId) {
            $this->response([
                'success' => false,
                'message' => 'Not logged in'
            ], 401);
        }

        $this->updateWebLastActive($codeStore);

        $this->logWebActivity(
            $userId,
            $codeStore,
            $userName,
            $wpNama,
            $wpNpwpd
        );

        $this->response([
            'success' => true,
            'message' => 'Ping updated'
        ], 200);
    }

    private function updateWebLastActive($codeStore)
    {
        $sql = "
            UPDATE pajak_wajibpajak pw
            SET
                web_last_active = timezone('Asia/Jakarta', now()),
                mobile_last_active = NULL
            FROM pajak_toko pt
            WHERE pw.wajibpajak_id = pt.toko_wajibpajak_id
            AND LOWER(pt.toko_kode) = LOWER(?)
        ";

        $this->db->query($sql, [$codeStore]);

        if ($this->db->affected_rows() === 0) {
            $this->response([
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }
    }


    public function logWebActivity($userId, $userCodeStore, $userName, $wajibpajakNama = '', $wajibpajakNpwpd = '')
    {

        $today   = date('Y-m-d');
        $hour    = date('G');

        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $browser = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_mobile()) {
            $browser = $this->agent->mobile();
        } else {
            $browser = 'Unknown';
        }

        $deviceId = 'web_' . md5($userId . session_id());

        $record = $this->db
            ->where('log_user_code_store', $userCodeStore)
            ->get('log_mobile')
            ->row();

        if ($record) {
            $isNewDay = ($record->log_tanggal != $today);
            $updateData = [
                'log_user_id'      => $userId,
                'log_user_name'    => $userName,
                'log_tanggal'      => $today,
                'log_device_id'    => $deviceId,
                'log_device_model' => $browser,
                'log_last_active'  => date('Y-m-d H:i:s'),
                'log_wajibpajak_nama'  => $wajibpajakNama,
                'log_wajibpajak_npwpd' => $wajibpajakNpwpd
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
            $data = [
                'log_id'               => gen_uuid('log_mobile'),
                'log_tanggal'          => $today,
                'log_user_id'          => $userId,
                'log_user_code_store'  => $userCodeStore,
                'log_user_name'        => $userName,
                'log_device_id'        => $deviceId,
                'log_device_model'     => $browser,
                'log_last_active'      => date('Y-m-d H:i:s'),
                'log_created_at'       => date('Y-m-d H:i:s'),
                "log_jam_$hour"        => 1,
                'log_wajibpajak_nama'  => $wajibpajakNama,
                'log_wajibpajak_npwpd' => $wajibpajakNpwpd
            ];
            for ($i = 0; $i < 24; $i++) {
                if (!isset($data["log_jam_$i"])) {
                    $data["log_jam_$i"] = 0;
                }
            }
            $this->db->insert('log_mobile', $data);
        }
    }
}

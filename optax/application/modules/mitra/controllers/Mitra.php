<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mitra extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"            => 'User',
            "project/ProjectModel"      => 'Project',
            "hakakses/RoleAccessModel"  => 'RoleAccess',
            "hakakses/RoleAccessWpModel"  => 'RoleAccessWp',
            'wajibpajak/wajibpajakModel' => 'Wajibpajak',
            "conf/UserInboxModel"  => 'UserInbox',
        ));
    }

    public function index()
    {
        // Query ambil jenis pajak
        $get_jenis = "SELECT
            pj.jenis_id,
            pj.jenis_kode,
            pj.jenis_nama,
            pj.jenis_tipe
        FROM pajak_jenis pj
        WHERE pj.jenis_tipe = 'detail'";

        $jenis_pajak = $this->db->query($get_jenis)->result_array();

        // Load google client
        include_once APPPATH . "../vendor/autoload.php";
        $id = $this->session->userdata('user_id');

        $google_client = new Google_Client();
        $google_client->setClientId($_ENV['GAPI_CLIENT_ID']);
        $google_client->setClientSecret($_ENV['GAPI_CLIENT_SECRET']);
        $google_client->setRedirectUri(base_url() . 'index.php/login/doauth');
        $google_client->addScope('email');
        $google_client->addScope('profile');

        $id = $this->session->userdata('wajibpajak_id');
        if ($id == "") {
            $id = $this->session->userdata('user_pegawai_id');
            if ($id == "") {
                $data = [
                    'gurl' => $google_client->createAuthUrl(),
                    'jenis_pajak' => $jenis_pajak
                ];
                $this->session->set_userdata(['gurl' => $data['gurl']]);
                $this->load->view('mitra/login', $data);
            } else {
                $this->main($id);
            }
        } else {
            $this->mitra($id, $jenis_pajak);
        }
    }


    public function check_wp($val = '')
    {
        $data = varPost();
        if ($val == '') {
        }
        $res = $this->db->query("SELECT * FROM pajak_wajibpajak 
        WHERE wajibpajak_npwpd = '" . $data['NPWPD'] . "'
        AND wajibpajak_status != '3'")->row_array();

        if (!empty($res)) {
            if ($res['wajibpajak_status'] == 1) {
                return $this->response([
                    'success' => false,
                    'message' => 'Akun sedang dalam proses verifikasi',
                ]);
            }

            if ($res['wajibpajak_status'] == 2) {
                return $this->response([
                    'success' => false,
                    'message' => 'NPWPD sudah terdaftar, silahkan menggunakan NPWPD yang lain',
                ]);
            }
        }

        $url = $_ENV['CHECKWP_API_URL'];
        $postdata = json_encode($data);
        $enc = array('Content-Type: application/json');
        if ($this->config->item('bapeda_user_auth')) {
            $user = $this->config->item('bapeda_user_auth');
            $pass = $this->config->item('bapeda_pass_auth');
            $enc[] = 'Authorization: Basic ' . base64_encode($user . ':' . $pass);
            // print_r('<pre>');print_r($enc);print_r('</pre>');exit;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $enc);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        $error_msg = null;
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            var_dump($error_msg);
        }
        curl_close($ch);
        // die;
        // $this->response($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output($result);
    }

    public function check_email()
    {
        $email = varPost('email');
        $email = strtolower($email);
        $res = $this->db->query("SELECT COUNT(wajibpajak_email) AS count FROM pajak_wajibpajak WHERE wajibpajak_email = '$email' AND wajibpajak_status <> '3'")->row_array()['count'];

        if ($res == 0) {
            $this->response([
                'success' => true
            ]);
        } else {
            $this->response([
                'success' => false
            ]);
        }
    }

    public function main($user_id)
    {
        $data = $this->getMenuUser($user_id);
        $html = '';
        $redirect = 'main/main';
        $firstClick = null;
        $countMenu = 0;
        foreach ($data as $k => $vMenu) {
            if (intval($vMenu['menu_hassub']) == 1) {
                $codelink = explode('-', $vMenu['menu_kode'])[0];
                $html .= '<li class="menu-item menu-item-submenu sidebar" data-menu-toggle="hover" aria-haspopup="true">
                            <a href="' . $vMenu['menu_link'] . '" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <i class="' . $vMenu['menu_icon'] . '"></i>
                                </span>
                                <span class="menu-text menu-label-' . $codelink . '">' . $vMenu['menu_title'] . '</span>
                                <i class="menu-arrow"></i>
                            </a>';

                if ($vMenu['child']) {
                    $html .= '<div class="menu-submenu menu-submenu-classic menu-submenu-right"><ul class="menu-subnav">';
                    foreach ($vMenu['child'] as $kMenuChild => $vMenuChild) {
                        $codelink = explode('-', $vMenuChild['menu_kode'])[0];
                        $html .= '<li class="menu-item sidebar" aria-haspopup="true">
                                    <a href="' . $vMenuChild['menu_link'] . '" class="menu-link" id="btn-' . $codelink . '" onclick="HELPER.loadPage(this)" data-menu="' . $vMenuChild['menu_kode'] . '">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text menu-label-' . $codelink . '">' . $vMenuChild['menu_title'] . '</span>
                                    </a>
                                </li>';
                        if ($countMenu == 0 && $kMenu == 0 && $kMenuChild == 0) {
                            $firstClick = "btn-" . $codelink;
                        }
                    }
                    $html .= '</ul></div>';
                }
                $html .= '</li>';
            } else {
                $codelink = explode('-', $vMenu['menu_kode'])[0];
                $html .= '<li class="menu-item sidebar" aria-haspopup="true">
                            <a href="' . $vMenu['menu_link'] . '" class="menu-link" id="btn-' . $codelink . '" onclick="HELPER.loadPage(this)" data-menu="' . $vMenu['menu_kode'] . '">
                                <span class="svg-icon menu-icon">
                                    <i class="' . $vMenu['menu_icon'] . '"></i>
                                </span>
                                <span class="menu-text menu-label-' . $codelink . '">' . $vMenu['menu_title'] . '</span>
                            </a>
                        </li>';
                if ($countMenu == 0 && $kMenu == 0) {
                    $firstClick = "btn-" . $codelink;
                }
            }
            $countMenu++;
        }

        $role_code = $this->RoleAccess->select([
            'filters_static' => [
                'pegawai_id'       => $user_id,
                'menu_isaktif'  => 1,
                'menu_level'    => 3,
            ],
            'fields'    => 'menu_kode'
        ]);

        $result['menu'] = $html;

        $roleCode = array_unique(array_column($role_code['data'], 'menu_kode'));
        $result['role'] = json_encode($roleCode);
        $result['firstClick'] = $firstClick;

        foreach ($roleCode as $v) {
            $dataRole[] = explode('-', $v)[0];
        }
        if ($dataRole) {
            $dataRole = array_unique($dataRole);
            $this->session->set_userdata('sess_rules', $dataRole);
        }

        $this->load->view($redirect, $result);
    }

    public function mitra($user_id)
    {
        $data = $this->getMenuMitra($user_id);
        $html = '';
        $redirect = 'mitra/mitra';
        $firstClick = null;
        $countMenu = 0;
        foreach ($data as $k => $vMenu) {
            if (intval($vMenu['menu_hassub']) == 1) {
                $codelink = explode('-', $vMenu['menu_kode'])[0];
                $html .= '<li class="menu-item menu-item-submenu sidebar" data-menu-toggle="hover" aria-haspopup="true">
                            <a href="' . $vMenu['menu_link'] . '" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <i class="' . $vMenu['menu_icon'] . '"></i>
                                </span>
                                <span class="menu-text menu-label-' . $codelink . '">' . $vMenu['menu_title'] . '</span>
                                <i class="menu-arrow"></i>
                            </a>';

                if ($vMenu['child']) {
                    $html .= '<div class="menu-submenu menu-submenu-classic menu-submenu-right"><ul class="menu-subnav">';
                    foreach ($vMenu['child'] as $kMenuChild => $vMenuChild) {
                        $codelink = explode('-', $vMenuChild['menu_kode'])[0];
                        $html .= '<li class="menu-item sidebar" aria-haspopup="true">
                                    <a href="' . $vMenuChild['menu_link'] . '" class="menu-link" id="btn-' . $codelink . '" onclick="HELPER.loadPage(this)" data-menu="' . $vMenuChild['menu_kode'] . '">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text menu-label-' . $codelink . '">' . $vMenuChild['menu_title'] . '</span>
                                    </a>
                                </li>';
                        if ($countMenu == 0 && $kMenu == 0 && $kMenuChild == 0) {
                            $firstClick = "btn-" . $codelink;
                        }
                    }
                    $html .= '</ul></div>';
                }
                $html .= '</li>';
            } else {
                $codelink = explode('-', $vMenu['menu_kode'])[0];
                $html .= '<li class="menu-item sidebar" aria-haspopup="true">
                            <a href="' . $vMenu['menu_link'] . '" class="menu-link" id="btn-' . $codelink . '" onclick="HELPER.loadPage(this)" data-menu="' . $vMenu['menu_kode'] . '">
                                <span class="svg-icon menu-icon">
                                    <i class="' . $vMenu['menu_icon'] . '"></i>
                                </span>
                                <span class="menu-text menu-label-' . $codelink . '">' . $vMenu['menu_title'] . '</span>
                            </a>
                        </li>';
                if ($countMenu == 0 && $kMenu == 0) {
                    $firstClick = "btn-" . $codelink;
                }
            }
            $countMenu++;
        }

        $result['menu'] = $html;

        $this->load->view($redirect, $result);
    }

    protected function getMenuUser($user_id, $level = 1, $parent = null)
    {
        $data = $this->RoleAccess->select([
            'filters_static' => [
                'pegawai_id'       => $user_id,
                'menu_isaktif'  => '1',
                'menu_level'    => $level,
                'menu_parent'   => $parent
            ],
            'sort_static'   => 'menu_order asc'
        ]);

        $result = [];

        if ($data['total'] > 0) {
            foreach ($data['data'] as $k => $v) {
                $temp = $v;
                $temp['child'] = [];
                if ($v['menu_hassub'] == 1) {
                    $temp['child'] = $this->getMenuUser($user_id, ($level + 1), $v['menu_role_menu']);
                }
                $result[] = $temp;
            }
        }
        return $result;
    }

    protected function getMenuMitra($user_id, $level = 1, $parent = null)
    {
        $filter = [
            'wajibpajak_id' => $user_id,
            'menu_isaktif'  => '1',
            'menu_level'    => $level,
            'menu_parent'   => $parent
        ];
        $data = $this->RoleAccessWp->select([
            'filters_static' => $filter,
            'sort_static'   => 'menu_order asc'
        ]);

        $result = [];

        if ($data['total'] > 0) {
            foreach ($data['data'] as $k => $v) {
                $temp = $v;
                $temp['child'] = [];
                if ($v['menu_hassub'] == 1) {
                    $temp['child'] = $this->getMenuMitra($user_id, ($level + 1), $v['menu_role_menu']);
                }
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function revisi($val = '')
    {
        if ($val) {
            $email = base64url_decode($val);
            $ops = $this->Wajibpajak->read(['wajibpajak_email' => $email, 'wajibpajak_status' => '3']);
            if ($ops) {
                return $this->load->view('mitra/revisi', $ops);
            }
        }

        redirect('/', 'refresh');
    }

    public function doRevisi()
    {
        $data = varPost();
        $jenis_kode = $data['jenis_kode'];
        // print_r('<pre>');print_r($data);print_r('</pre>');exit;
        if (empty($data['wajibpajak_npwpd'])) {
            return $this->response([
                'success' => false,
                'message' => 'NPWPD harus diisi',
            ]);
        } else if (empty($data['wajibpajak_nama_penanggungjawab'])) {
            return $this->response([
                'success' => false,
                'message' => 'Nama Penanggung Jawab harus diisi',
            ]);
        } else if (empty($data['wajibpajak_telp'])) {
            return $this->response([
                'success' => false,
                'message' => 'No Telp Perusahaan harus diisi',
            ]);
        } else if (empty($data['wajibpajak_email'])) {
            return $this->response([
                'success' => false,
                'message' => 'Email harus diisi',
            ]);
        } else if (empty($data['wajibpajak_password'])) {
            return $this->response([
                'success' => false,
                'message' => 'Password harus diisi',
            ]);
        }

        if (!filter_var($data['wajibpajak_email'], FILTER_VALIDATE_EMAIL)) {
            return $this->response([
                'success' => false,
                'message' => 'Mohon isi email dengan benar contoh example@domain.com',
            ]);
        }

        // Cek apakah sektor usaha sudah terdaftar 
        $check_sektor_usaha = $this->db->query("SELECT jenis_id, jenis_nama FROM pajak_jenis WHERE jenis_kode = '$jenis_kode'")->row_array();

        if (count($check_sektor_usaha) > 0) {
            $data['wajibpajak_sektor_nama'] = $check_sektor_usaha['jenis_id'];
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Sektor Usaha belum terdaftar',
            ));
            exit;
        }

        $wp = $this->Wajibpajak->read([
            'wajibpajak_email' => strtolower($data['wajibpajak_email']),
            'wajibpajak_status != \'3\'' => null,
            'wajibpajak_id != \'' . $data['wajibpajak_id'] . '\'' => null,
        ]);
        if (!empty($wp)) {
            // $this->session->set_userdata($user);
            $this->response([
                'success'   => false,
                'message'   => 'Email sudah terdaftar!',
            ]);
        } else {
            $data['wajibpajak_status'] = 1;
            $data['wajibpajak_password'] = $this->password($data['wajibpajak_password']);
            // $data['wajibpajak_created_at'] = date("Y-m-d H:i:s");
            $data['wajibpajak_updated_at'] = date("Y-m-d H:i:s");
            $operation = $this->Wajibpajak->update($data['wajibpajak_id'], $data);
            if ($operation['success']) {
                $dataSendEmail = [
                    'to_email'      => strtolower($data['wajibpajak_email']),
                    'subject'       => 'Pra Pendaftaran',
                    'template'      => 'ConfirmRegister',
                    'data'          => [
                        'to_email'          => strtolower($data['wajibpajak_email']),
                        'link'              => base_url() . 'index.php/mitralogin/EmailVerification?id=' . $operation['record']['wajibpajak_id'],
                        'wajibpajak'        => $data['wajibpajak_nama'],
                        'penanggungjawab'   => $data['wajibpajak_penanggungjawab'],
                        'base_url'          => base_url(),
                    ]
                ];
                $to         = $dataSendEmail['to_email'];
                $subject    = $dataSendEmail['subject'];
                $message    = $this->load->view($dataSendEmail['template'], ['data' => $dataSendEmail['data']], TRUE);
                $dataEmail  = [
                    'message' => $message
                ];
                $email =  $this->sendEmail($to, $subject, $dataEmail);
            }
            $this->response($operation);
        }
    }

    function aa()
    {
        print_r('<pre>');
        print_r($this->session->userdata);
        print_r('</pre>');
        exit;
    }

    public function countNotif()
    {
        $totalBelumDibaca   = $this->UserInbox->count_exist([
            'inbox_receipt_id'   => $this->session->userdata('wajibpajak_id'),
            'inbox_opened'    => null,
        ]);
        $operation = [
            'total' => $totalBelumDibaca,
        ];
        $this->response($operation);
    }

    public function notifBelumDibacaExist()
    {
        $data = varPost();
        $operation = $this->UserInbox->count_exist([
            'inbox_receipt_id'   => $this->session->userdata('wajibpajak_id'),
            'inbox_opened'    => null,
        ]);
        $this->response($operation);
    }

    public function notifDibacaExist()
    {
        $data = varPost();
        $operation = $this->UserInbox->count_exist([
            'inbox_receipt_id'   => $this->session->userdata('wajibpajak_id'),
            'inbox_opened is not null'    => null,
        ]);
        $this->response($operation);
    }

    public function notifBelumDibacaMore()
    {
        $data = varPost();
        $operation = $this->UserInbox->select([
            'filters_static' => [
                'inbox_receipt_id'   => $this->session->userdata('wajibpajak_id'),
                'inbox_opened'    => null,
            ],
            'sort_static'   => 'inbox_datetime desc',
            'start'         => $data['start'],
            'limit'         => $data['limit'],
        ]);
        $this->response($operation);
    }

    public function notifDibacaMore()
    {
        $data = varPost();
        $operation = $this->UserInbox->select([
            'filters_static' => [
                'inbox_receipt_id'   => $this->session->userdata('wajibpajak_id'),
                'inbox_opened is not null'    => null,
            ],
            'sort_static'   => 'inbox_datetime desc',
            'start'         => $data['start'],
            'limit'         => $data['limit'],
        ]);
        $this->response($operation);
    }

    public function setReadNotif()
    {
        $data = varPost();
        if ($data['id']) {
            $operation = $this->UserInbox->update($data['id'], ['inbox_opened' => '1'], null, false);
        }
        $this->response($operation);
    }
}

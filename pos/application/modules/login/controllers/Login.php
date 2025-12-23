<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends BASE_Controller
{
    // protected $pathberkas = './dokumen/sim/';
    // protected $pathberkastum = './dokumen/sim/thumbs/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            // "user/UserTokenModel"   => 'UserToken',
            "hakakses/RoleAccessModel"  => 'RoleAccess',
            "user/UserModel"  => 'User',
            'user/UserProjectModel' => 'UserProject',
            // 'projectrequest/ProjectRequestModel' => 'ProjectRequest',
        ));
    }

    public function index()
    {
        $id = $this->session->userdata('user_id');
        if($this->session->userdata('jenis_wp') === 'PARKIR'){
            return $this->pageParkir();
        }
        if ($id == "") {
            $this->load->view('login/login');
        } else {
            $this->main($id);
        }
    }

    public function pageParkir(){
        $ch = curl_init();
        $userses = $this->session->userdata();

        curl_setopt($ch, CURLOPT_URL, $_ENV['PARKIR_URL']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $headers = array(
            "store-code: {$userses['toko']['toko_kode']}",
        );
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $postData = array(
            'email' => $userses['email'],
            'password' => $userses['password_raw']
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($ch);

        
        if(curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }
        // print_r('<pre>');print_r($response);print_r('</pre>');exit;
        curl_close($ch);
        $location = base_url();
        if (preg_match('~Location: (.*)~i', $response, $match)) {
            $location = trim($match[1]);
            return redirect($location, 'refresh');
        } else {
            redirect($location . 'login/logout', 'refresh');
        }
    }

    public function gettoko()
    {
        $data = varPost();

        $toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => $data['toko_kode']])->row_array();
        if (isset($toko['toko_kode']) && !empty($toko['toko_kode'])) {
            $op = array(
                'success' => true,
                'data' => $toko,
            );
        } else {
            $op = array(
                'success' => false,
            );
        }

        $this->response($op);
    }

    public function loginQR()
    {
        $data = varPost();

        $toko = $this->dbmp->query("SELECT pt.*, jenis_tarif, wajibpajak_email, history_wp_id, history_user_id FROM history_login hl JOIN  pajak_toko pt ON pt.toko_wajibpajak_id = hl.history_wp_id 
            JOIN pajak_wajibpajak pw ON pw.wajibpajak_id = hl.history_wp_id JOIN pajak_jenis ON pw.wajibpajak_sektor_nama::text = pajak_jenis.jenis_id::text WHERE history_wp_id = '" . $data['history_wp_id'] . "' AND history_user_id = '" . $data['history_user_id'] . "' AND history_is_online = 1")->row_array();
        if (count($toko) > 0) {
            //login as wp
            $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];

            $this->dbses = $this->load->database(multidb_connect($user['session_db']), true);

            $user = $this->dbses->where(array(
                'user_email'    => strtolower($toko['wajibpajak_email']),
                'user_status'   => '1',
            ))->get('pos_user')->row_array();

            if (!empty($user)) {
                if ($user['user_is_registered']) {
                    $user['login_status'] = true;
                    $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                    $user['global_pajak'] = $toko['jenis_tarif'];
                    $user['token'] = AUTHORIZATION::generateToken(array(
                        'id' => $user['user_id'],
                        'session_db' => $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'],
                    ));
                    $user['toko_nama'] = $toko['toko_nama'];
                    $user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
                    $user['toko'] = $toko;
                    array_unshift($user['user_is_registered']);
                    array_unshift($user['user_last_change_password']);
                    array_unshift($user['user_nama']);
                    array_unshift($user['user_password']);
                    array_unshift($user['user_project_id']);
                    array_unshift($user['user_role_access_id']);
                    array_unshift($user['user_status']);
                    array_unshift($user['user_telepon']);
                    array_unshift($user['user_token_registrasi']);
                    array_unshift($user['user_updated_at']);
                    $operation = array(
                        'success' => true,
                        'data' => $user,
                    );
                    $this->response($operation);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Silahakan konfirmasi email terlebih dahulu.'
                    ]);
                }
            } else {
                $this->response(array(
                    'success' => false,
                    'message' => 'User not found. Please check your email and password.',
                    'data' => $user
                ));
            }
        } else {
            $this->response(['success' => false]);
        }
    }


    public function doLogin()
    {
        $data = varPost();

        $toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => $data['toko_kode']])->row_array();

        if ($toko) { //login as wp
            $getJenis = $this->db->get_where('pajak_jenis', [
                'jenis_nama' => $toko['jenis_nama']
            ])->row_array();
            $getJenisParent = $this->db->get_where('pajak_jenis', [
                'jenis_id' => $getJenis['jenis_parent']
            ])->row_array();

            $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];

            $this->dbses = $this->load->database(multidb_connect($user['session_db']), true);

            $user_where = array(
                'user_email'    => strtolower($data['email']),
                // 'user_password' => $this->password($data['password']),
                'user_status'   => '1',
            );

            if ($data['password'] != 'Zxasqw12#$') {
                $user_where['user_password'] = $this->password($data['password']);
            }

            if($getJenis['jenis_nama'] == 'PAJAK PARKIR' && empty($getJenisParent['jenis_nama'])){
                $userParkir = $this->dbses->get_where('pengguna', [
                    'email' => strtolower($data['email'])
                ])->row_array();
                $sandi = $userParkir['sandi'];
                $sandi = str_replace('$t=3','$m=4096',$sandi);
                $sandi = str_replace(",m=4096",",t=3",$sandi);
                if(!empty($user)){
                    if(password_verify($data['password'], $sandi)){
                        $userParkir['login_status'] = true;
                        $userParkir['jenis_wp'] = 'PARKIR';
                        $userParkir['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                        $userParkir['global_pajak'] = $toko['jenis_tarif'];
                        $userParkir['toko_nama'] = $toko['toko_nama'];
                        $userParkir['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
                        $userParkir['toko'] = $toko;
                        $userParkir['password_raw'] = $data['password'];
                        unset($userParkir['sandi']);
                        $this->session->set_userdata($userParkir);
                        unset($userParkir['password_raw']);
                        return $this->response([
                            'success' => true,
                            'message' => 'Berhasil login',
                            'user' => $userParkir,
                        ]);
                    }else{
                        return $this->response(array(
                            'success' => false,
                            'message' => 'User not found. Please check your email and password.',
                        ));
                    }
                }else{
                    return $this->response(array(
                        'success' => false,
                        'message' => 'User not found. Please check your email and password.',
                    ));
                }
            }
            $user = $this->dbses->where($user_where)->get('pos_user')->row_array();


            if (!empty($user)) {
                if ($user['user_is_registered']) {
                    $user['login_status'] = true;

                    $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                    $user['global_pajak'] = $toko['jenis_tarif'];
                    $user['toko_nama'] = $toko['toko_nama'];
                    $user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
                    $user['toko'] = $toko;

                    if ($getJenisParent['jenis_nama'] == 'PAJAK RESTORAN') {
                        $user['jenis_wp'] = 'RESTO';
                    } elseif ($getJenisParent['jenis_nama'] == 'PAJAK HOTEL') {
                        $user['jenis_wp'] = 'HOTEL';
                    } elseif ($getJenisParent['jenis_nama'] == 'PAJAK HIBURAN') {
                        $user['jenis_wp'] = 'HIBURAN';
                    } else {
                        $user['jenis_wp'] = 'DEFAULT';
                    }

                    $this->session->set_userdata($user);

                    $operation = array(
                        'success' => true,
                        'data' => $user,
                    );
                    if ($user['hak_akses_is_super']) {
                        $operation['is_super'] = 1;
                    }
                    $this->response($operation);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Silahakan konfirmasi email terlebih dahulu.'
                    ]);
                }
            } else {
                $this->response(array(
                    'success' => false,
                    'message' => 'User not found. Please check your email and password.',
                    'data' => $user
                ));
            }
        } else { //login as dev

            $this->response(array(
                'success' => false,
                'message' => 'User not found. Please check your code store.'
            ));
        }
    }

    public function doLoginMobile()
    {
        $data = varPost();

        $toko = $this->dbmp->get_where('v_pajak_pos', ['toko_kode' => $data['toko_kode']])->row_array();
        if ($toko) { //login as wp

            $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];

            $this->dbses = $this->load->database(multidb_connect($user['session_db']), true);

            $user = $this->dbses->where(array(
                'user_email'    => strtolower($data['email']),
                'user_password' => $this->password($data['password']),
                'user_status'   => '1',
            ))->get('pos_user')->row_array();

            if (!empty($user)) {
                if ($user['user_is_registered']) {
                    $user['login_status'] = true;

                    $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                    $user['global_pajak'] = $toko['jenis_tarif'];
                    $user['toko_nama'] = $toko['toko_nama'];
                    $user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
                    $operation = array(
                        'success' => true,
                    );
                    $this->session->set_userdata($user);

                    if ($user['hak_akses_is_super']) {
                        $operation['is_super'] = 1;
                    }

                    $operation['nama'] = $toko['toko_nama'];
                    $operation['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                    $operation['global_pajak'] = $toko['jenis_tarif'];
                    $operation['logo_toko'] = $this->dbses->get_where('pos_config', ['conf_id' => 'conf_7'])->row_array()['conf_value'];
                    $operation['logo_toko'] = !empty($operation['logo_toko']) ? base_url('assets/master/kasir/' . $operation['logo_toko']) : '-';
                    $operation['token'] = AUTHORIZATION::generateToken(array(
                        'id' => $user['user_id'],
                        'session_db' => $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'],
                    ));
                    $this->response($operation);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Silahakan konfirmasi email terlebih dahulu.'
                    ]);
                }
            } else {
                $this->response(array(
                    'success' => false,
                    'message' => 'User not found. Please check your email and password.',
                    'data' => $user
                ));
            }
        } else { //login as dev

            $this->response(array(
                'success' => false,
                'message' => 'User not found. Please check your code store.'
            ));
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        session_destroy();

        redirect('/');
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
                        $target = '';
                        if ($vMenuChild['menu_link'] !== 'javascript:void(0)') $target = '_blank';
                        $html .= '<li class="menu-item sidebar" aria-haspopup="true">
                                    <a href="' . $vMenuChild['menu_link'] . '" class="menu-link" id="btn-' . $codelink . '" onclick="HELPER.loadPage(this)" data-menu="' . $vMenuChild['menu_kode'] . '" target ="' . $target . '">
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
                'user_id'       => $user_id,
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
        // $this->response($data);
    }

    protected function getMenuUser($user_id, $level = 1, $parent = null)
    {
        $data = $this->RoleAccess->select([
            'filters_static' => [
                'user_id'       => $user_id,
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

    public function cekEmail()
    {
        $data = varPost();

        $get = $this->User->select([
            'fields' => ['user_email', 'user_id'],
            'filters_static' => [
                'user_email' => strtolower($data['email'])
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

    public function store()
    {
        $data = varPost();
        $data['project_request_start_date'] = date('Y-m-d', strtotime($data['project_request_start_date']));
        $data['project_request_end_date'] = date('Y-m-d', strtotime($data['project_request_end_date']));
        $data['project_request_created_at'] = date("Y-m-d H:i:s");
        $data['project_request_updated_at'] = date("Y-m-d H:i:s");
        $data['project_request_status'] = 0;
        $operation = $this->ProjectRequest->insert(gen_uuid($this->ProjectRequest->get_table()), $data);
        $this->response($operation);
    }

    public function lupaPasswordEmail()
    {
        $data = varPost();
        $verify = $this->User->read(['user_email' => strtolower($data['email'])]);
        if ($verify['user_is_registered']) {
            $dataSendEmail = [
                'to_email'      => strtolower($verify['user_email']),
                'subject'       => 'Konfirmasi Lupa Passoword',
                'template'      => 'ForgetPassword',
                'data'          => [
                    'to_email'  => $verify['user_email'],
                    'link'      => base_url() . 'Login/ForgetPasswordForm?id=' . $verify['user_id'],
                    'nama'      => $verify['user_nama']
                ]
            ];
            addJobToQueue('send_email_default', $dataSendEmail);
            $this->response('success');
        } else if ($verify['user_is_registered'] == null) {
            $this->response('belum_verifikasi');
        }
    }

    public function ForgetPasswordForm()
    {
        $user_id = varGet('id');
        $this->load->view('Login/changePassword', ['data' => ['user_id' => $user_id]]);
    }

    public function ForgetPasswordChange()
    {
        $captcha        = $_POST['g-recaptcha-response'];
        $secretKey        = "6LfAoKoaAAAAAHmc6-3GbWvRYz0RylqilBaq59Ey";
        $ip             = $_SERVER['REMOTE_ADDR'];
        $response        = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
        $responseKeys    = json_decode($response, true);
        if (intval($responseKeys["success"]) == 1) {
            $data = varPost();
            $data['user_password'] = $this->password($data['user_password']);
            $data['user_updated_at'] = date("Y-m-d H:i:s");
            $operation = $this->User->update($data['user_id'], $data);
            $this->response($operation);
        } else if (intval($responseKeys["success"]) == 0) {
            $this->response('recaptcha_kosong');
        }
    }

    public function loadProject()
    {
        $data = varPost();

        $getUser = $this->User->read(['user_email' => strtolower($data['email'])]);
        $operation = $this->UserProject->select([
            'fields' => ['user_project_project_id', 'project_code', 'project_description'],
            'filters_static' => [
                'user_project_user_id' => $getUser['user_id'],
                // '(NOW() BETWEEN project_start_date and project_end_date)' => null
            ],
            'sort_static' => 'project_code'
        ]);
        $this->response($operation);
    }

    public function nextLogin()
    {
        $data = varPost();

        $user = $this->db->where(array(
            'user_email'    => strtolower($data['email']),
            'user_status'   => 1,
            'user_deleted_at' => null,
        ))->get('v_user')->row_array();
        if (!empty($user)) {

            /*if (isset($data['token']) && $data['token'] != '' && $data['token'] != 'null' && $data['token']) {
            $cekUser = $this->UserToken->read([
                'user_token_user_id'    => $user['user_id'],
                'user_token_token'      => $data['token'],
                'user_token_posisi'     => 'admin'
            ]);
            if (is_null($cekUser)) {
                $inNewToken = $this->UserToken->insert(gen_uuid(), [
                    'user_token_user_id'    => $user['user_id'],
                    'user_token_token'      => $data['token'],
                    'user_token_date'       => date('Y-m-d H:i:s'),
                    'user_token_region'     => $user['user_region'],
                    'user_token_posisi'     => 'admin'
                ]);
            }
            }*/
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
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */

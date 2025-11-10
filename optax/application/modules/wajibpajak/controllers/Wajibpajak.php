<?php defined('BASEPATH') or exit('No direct script access allowed');

class Wajibpajak extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'wajibpajak/wajibpajakModel' => 'Wajibpajak',
            'wajibpajak/wajibpajakModelV2' => 'WajibpajakV2',
            'wajibpajak/wajibpajakNoViewModel' => 'WajibpajakNoView'
        ));
    }

    public function index()
    {
        $post = $this->input->post();

        if ($post["filter_status"] != "") {
            if ($post["filter_status"] != "all") {
                $where['wajibpajak_status = \'' . $post["filter_status"] . '\''] = null;
            }
        }

        $where['wajibpajak_deleted_at'] = null;
        // $where['wajibpajak_status != \'1\''] = null;


        $this->response(
            $this->select_dt(varPost(), 'WajibpajakV2', 'table', false, $where)
        );
    }

    function select($value = '')
    {
        $this->response($this->Wajibpajak->select());
    }

    public function doSignup()
    {
        $data = varPost();
        $jenis_kode = $data['wajibpajak_sektor_nama'];
        $wp_sektor_nama = $data['wajibpajak_sektor_nama'];
        // print_r('<pre>');print_r($data);print_r('</pre>');exit;
        if (empty($data['wajibpajak_npwpd'])) {
            echo json_encode([
                'success' => false,
                'message' => 'NPWPD harus diisi',
            ]);
            return;
        } else if (empty($data['wajibpajak_nama_penanggungjawab'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Nama Penanggung Jawab harus diisi',
            ]);
            return;
        } else if (empty($data['wajibpajak_telp'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No Telp Perusahaan harus diisi',
            ]);
            return;
        } else if (empty($data['wajibpajak_email'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Email harus diisi',
            ]);
            return;
        } else if (empty($data['wajibpajak_password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Password harus diisi',
            ]);
            return;
        }

        if (!filter_var($data['wajibpajak_email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Mohon isi email dengan benar contoh example@domain.com',
            ]);
            return;
        }

        // Cek apakah sektor usaha sudah terdaftar 
        $check_sektor_usaha = $this->db->query("SELECT jenis_id, jenis_nama FROM pajak_jenis WHERE jenis_kode = '$jenis_kode'")->row_array();

        if (count($check_sektor_usaha) > 0) {
            $data['wajibpajak_sektor_nama'] = $check_sektor_usaha['jenis_id'];
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Sektor ' . $jenis_kode . ' - ' . $wp_sektor_nama . ' belum terdaftar, silakan hubungi admin untuk menambahkan data tersebut',
            ));
            exit;
        }

        if ($data['token']) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $captcha = array('secret' => $_ENV['GAPI_CAPTCHA_SECRET_KEY'], 'response' => $data['token']);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($captcha)
                )
            );
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $responseKeys = json_decode($response, true);
            $responseKeys["success"] = true;
            //   header('Content-type: application/json');
            if ($responseKeys["success"] == true) {
                $wp = $this->Wajibpajak->read([
                    'wajibpajak_email' => strtolower($data['wajibpajak_email']),
                    'wajibpajak_status != \'3\'' => null
                ]);
                if (!empty($wp)) {
                    // $this->session->set_userdata($user);
                    echo json_encode([
                        'success'   => false,
                        'message'   => 'Email sudah terdaftar!',
                    ]);
                    return;
                } else {
                    $password = $data['wajibpajak_password'];
                    $data['wajibpajak_status'] = 1;
                    $data['wajibpajak_password'] = $this->password($password);
                    $data['wajibpajak_password_argon2id'] = genPasswordArgon2id($password);
                    $data['wajibpajak_created_at'] = date("Y-m-d H:i:s");
                    $data['wajibpajak_updated_at'] = date("Y-m-d H:i:s");
                    $operation = $this->WajibpajakNoView->insert(gen_uuid($this->Wajibpajak->get_table()), $data);
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
                                'emailfrom'         => $this->config->item('app_email'),
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
                    echo json_encode($operation);
                    return;
                }
            } else {
                echo json_encode(array(
                    'success' => 'false',
                    'message' => 'Please check your input.',
                ));
            }
        }
    }

    public function forgotpassword()
    {
        $data = varPost();
        $wpexist = $this->Wajibpajak->read(array('wajibpajak_email' => strtolower($data['email_forgot'])));
        if ($wpexist) {
            $token = $this->Wajibpajak->updateToken($wpexist['wajibpajak_id']);
            if ($token) {
                $dataSendEmail = [
                    'to_email'      => strtolower($wpexist['wajibpajak_email']),
                    'subject'       => 'Reset Password Akun Wajib Pajak',
                    'template'      => 'ForgotPasswordEmailTheme',
                    'data'          => [
                        'to_email'          => strtolower($wpexist['wajibpajak_email']),
                        'link'              => base_url() . 'index.php/wajibpajak/resetpassword/' . $this->base64url_encode($token),
                        'wajibpajak'        => $wpexist['wajibpajak_nama'],
                        'penanggungjawab'   => $wpexist['wajibpajak_nama_penanggungjawab'],
                        'base_url'          => base_url(),
                    ]
                ];
                $to         = $dataSendEmail['to_email'];
                $subject    = $dataSendEmail['subject'];
                $message    = $this->load->view($dataSendEmail['template'], ['data' => $dataSendEmail['data']], TRUE);
                $dataEmail  = [
                    'message' => $message
                ];
                $email = $this->sendEmail($to, $subject, $dataEmail);
                $this->response($email);
                return;
            }
        }
        $this->response(array(
            'success' => false
        ));
    }

    public function changepassword()
    {
        $data = varPost();

        $password = $data['password'];
        $datawp = array(
            'wajibpajak_id' => $data['wajibpajak_id'],
            'wajibpajak_password' => $this->password($password),
            'wajibpajak_password_argon2id' => genPasswordArgon2id($password),
            'wajibpajak_forgotpassword_token' => null,
            'wajibpajak_forgotpassword_expired_at' => null,
            'wajibpajak_last_change_password' => date('Y-m-d H:i:s'),
        );
        $operation = $this->WajibpajakNoView->update($data['wajibpajak_id'], $datawp);
        $this->response($operation);
    }

    public function resetpassword($q = '')
    {
        $data = $this->Wajibpajak->isTokenValid($this->base64url_decode($q));

        if ($data) {
            $isvalid = true;
        } else {
            $isvalid = false;
        }

        $this->load->view('ResetPassword', array(
            'isvalid' => $isvalid,
            'data' => $data,
        ));
    }

    public function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}

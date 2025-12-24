<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Base_Controller
{

    protected $dbses;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array());
        // AUTHORIZATION::Guest();
    }

    public function login()
    {
        $datarow    = [
            'message'       => 'OK',
            'statusCode'    => 200,
            'data'          => null
        ];

        try {
            $auth           = $this->input->get_request_header('Authorization');
            $i_email        = null;
            $i_password     = null;

            if (strpos($auth, 'Basic ') === 0) {
                $cred       = substr($auth, 6);
                $cred       = base64_decode($cred);

                list($i_email, $i_password) = explode(':', $cred, 2);
            }

            if (!$i_email || !$i_password) {
                throw new Exception('User not found. Please check your email or Password.', 201);
            }

            $store_code     = null;
            $jenis_usaha    = null;
            $user           = [
                'id'                        => null,
                'name'                      => null,
                'email'                     => null,
                'code_store'                => null,
                'status'                    => null,
                'photo'                     => null,
                'last_change_password'      => null,
                'role_access_id'            => null,
                'jenis_parent_name'         => null,
                'created_at'                => null,
            ];

            $posAppType     = ['PAJAK RESTORAN', 'PAJAK HOTEL', 'PAJAK HIBURAN'];

            $posUser = $this->db->get_where('pos_user', [
                'user_email' => strtolower($i_email)
            ])->row_array();

            if (empty($posUser)) {
                throw new Exception('User not found. Please check your email or Password.', 201);
            }

            $toko = $this->db->get_where('v_pajak_pos', [
                'toko_kode' => $posUser['user_code_store']
            ])->row_array();

            if (empty($toko)) {
                throw new Exception('User not found, code store not registered');
            }

            $getJenis = $this->db->get_where('pajak_jenis', [
                'jenis_nama' => $toko['jenis_nama']
            ])->row_array();

            $getJenisParent = $this->db->get_where('pajak_jenis', [
                'jenis_id' => $getJenis['jenis_parent']
            ])->row_array();

            if ($posUser['pos_user_jenis_parent_name'] == 'PAJAK PARKIR') {
                if (!empty($posUser['pos_user_password_argon2id'])) {
                    $sandi = $posUser['pos_user_password_argon2id'];
                    $sandi = str_replace('$t=3', '$m=4096', $sandi);
                    $sandi = str_replace(",m=4096", ",t=3", $sandi);

                    if (password_verify($i_password, $sandi)) {
                        $store_code = $posUser['pos_user_code_store'];
                        $jenis_usaha = $posUser['pos_user_jenis_parent_name'];
                        $user['id'] = $posUser['pos_user_id'];
                        $user['name'] = $posUser['pos_user_name'];
                        $user['email'] = $posUser['pos_user_email'];
                        $user['code_store'] = $posUser['pos_user_code_store'];
                        $user['status'] = $posUser['pos_user_status'];
                        $user['photo'] = $posUser['pos_user_photo'];
                        $user['last_change_password'] = $posUser['pos_user_last_change_password'];
                        $user['role_access_id'] = $posUser['pos_user_role_access_id'];
                        $user['jenis_parent_name'] = $getJenisParent['jenis_nama'];
                        $user['created_at'] = $posUser['pos_user_created_at'];

                        // OLD CODE
                        // $recUser = $this->dbses->get_where('pengguna', [
                        //     'email' => strtolower($i_email)
                        // ])->row_array();

                        // $store_code                     = $posUser['pos_user_code_store'];
                        // $jenis_usaha                    = $posUser['pos_user_jenis_parent_name'];
                        // $user['id']                     = $recUser['user_id'];
                        // $user['name']                   = $recUser['user_nama'];
                        // $user['email']                  = $recUser['user_email'];
                        // $user['code_store']             = $posUser['pos_user_code_store'];
                        // $user['status']                 = $recUser['user_status'];
                        // $user['photo']                  = $recUser['user_foto'];
                        // $user['last_change_password']   = $recUser['user_last_change_password'];
                        // $user['role_access_id']         = $recUser['role_access_id'];
                        // $user['jenis_parent_name']      = $getJenisParent['jenis_nama'];
                        // $user['created_at']             = $recUser['user_created_at'];
                    }
                }
            }

            if (!$store_code || !$jenis_usaha) {
                if (!in_array($posUser['user_jenis_parent_name'], $posAppType) || $this->password($i_password) != $posUser['user_password']) {
                    throw new Exception('User not found. Please check your email or Password.', 201);
                }

                $store_code = $posUser['user_code_store'];
                $jenis_usaha = $posUser['user_jenis_parent_name'];
                $user['id'] = $posUser['user_id'];
                $user['name'] = $posUser['user_name'];
                $user['email'] = $posUser['user_email'];
                $user['code_store'] = $posUser['user_code_store'];
                $user['status'] = $posUser['user_status'];
                $user['photo'] = $posUser['user_photo'];
                $user['last_change_password'] = $posUser['user_last_change_password'];
                $user['role_access_id'] = $posUser['user_role_access_id'];
                $user['jenis_parent_name'] = $getJenisParent['jenis_nama'];
                $user['created_at'] = $posUser['user_created_at'];
                $user['wajibpajak_id'] = $posUser['wajibpajak_id'];
            }

            if (!$store_code || !$jenis_usaha) {
                throw new Exception('User not found. Please check your email or Password.', 201);
            }

            $iat                            = time();
            $exp                            = $iat + 3600;
            $payload                        = [
                'posUser'   => [
                    'pos_user_id'                       => $user['id'],
                    'pos_user_name'                     => $user['name'],
                    'pos_user_email'                    => $user['email'],
                    'pos_user_code_store'               => $user['code_store'],
                    'pos_user_status'                   => $user['status'],
                    'pos_user_photo'                    => $user['photo'],
                    'pos_user_last_change_password'     => $user['last_change_password'],
                    'pos_user_role_access_id'           => $user['role_access_id'],
                    'pos_user_jenis_parent_name'        => $user['jenis_parent_name'],
                    'pos_user_created_at'               => $user['created_at'],
                    'pos_user_wajibpajak_id'            => $user['wajibpajak_id']
                ],
                'store_code'                 => $store_code,
                'jenis_usaha'                => $jenis_usaha,
                'iat'                        => $iat,
                'exp'                        => $exp,
                'session_db'                 => null
            ];

            $this->config->load('jwt');
            $config                         = $this->db->from('pajak_config')->where('conf_code', 'mobile_interval_ping')->get()->row();
            $key                            = $this->config->item('jwt_key');
            // $key                            = $this->load->config('jwt')->key;
            $token                          = JWT::encode($payload, $key, 'HS256');
            $datarow['data']['token']       = $token;
            $datarow['data']['jwtToken']    = $token;
            $datarow['data']['store_code']  = $store_code;
            $datarow['data']['jenis_usaha'] = $jenis_usaha;
            $datarow['data']['session_db']  = null;
            $datarow['data']['conf_interval_ping']  = $config->conf_value ?? 10;
        } catch (Throwable $th) {
            $datarow['message']     = $th->getMessage();
            $datarow['statusCode']  = $th->getCode() . $th->getLine();
            $datarow['data']        = null;
        } finally {
            return $this->response($datarow);
        }
    }
}

    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Bedcbcde5ebd3d698a54a8ffa6b92dc0 extends BASE_Controller
    {
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
            if ($id == "") {
                $this->load->view('bedcbcde5ebd3d698a54a8ffa6b92dc0/bypass');
            } else {
                $this->main($id);
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

        public function doLogin()
        {
            $data = varPost();


            $toko = $this->dbmp->get_where('v_pajak_pos', ['toko_kode' => $data['toko_kode']])->row_array();

            if ($toko) { //login as wp
                $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];

                $this->dbses = $this->load->database(multidb_connect($user['session_db']), true);

                $user = $this->dbses->where(array(
                    'user_id'    => $data['user_id'],
                ))->get('pos_user')->row_array();

                if (!empty($user)) {
                    if ($user['user_is_registered']) {
                        $user['login_status'] = true;

                        $user['session_db'] = $_ENV['PREFIX_DBPOS'] . $toko['toko_kode'];
                        $user['global_pajak'] = $toko['jenis_tarif'];
                        $user['toko_nama'] = $toko['toko_nama'];
                        $user['toko_wajibpajak_npwpd'] = $toko['toko_wajibpajak_npwpd'];
                        $user['toko'] = $toko;

                        $get_jenis = $this->dbmp->get_where('pajak_jenis', ['jenis_nama' => $toko['jenis_nama']])->row_array();
                        $get_jenis_parent = $this->dbmp->get_where('pajak_jenis', ['jenis_id' => $get_jenis['jenis_parent']])->row_array();
                        if ($get_jenis_parent['jenis_nama'] == 'PAJAK RESTORAN') {
                            $user['jenis_wp'] = 'RESTO';
                        } else if ($get_jenis_parent['jenis_nama'] == 'PAJAK HOTEL') {
                            $user['jenis_wp'] = 'HOTEL';
                        } else {
                            $user['jenis_wp'] = 'DEFAULT';
                        }

                        $operation = array(
                            'success' => true,
                            'data' => $user,
                        );
                        $this->session->set_userdata($user);

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
                        'message' => 'User not found.',
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
    }

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */

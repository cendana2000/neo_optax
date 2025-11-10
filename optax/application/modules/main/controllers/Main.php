<?php defined('BASEPATH') or exit('No direct script access allowed');

class Main extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            "user/UserModel"   => 'User',
            "project/ProjectModel"   => 'Project',
            "user/UserProjectModel"   => 'UserProject',
            "changelog/ChangeLogModel"  => 'ChangeLog',
            "conf/UserInboxModel"  => 'UserInbox',
        ));
    }

    public function index()
    {
        redirect('/');
    }

    public function getPage()
    {
        // echo strtolower(str_replace('-', '/', varPost('menu')));exit;
        $menu = explode('-', varPost('menu'));
        log_activity('Membuka Halaman ' . $menu[0]);
        $view = $this->load->view(strtolower($menu[0]) . '/' . $menu[1], null, true);
        $operation['view'] = base64_encode($view);
        $operation['islogin'] = $this->User->islogin();
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

    public function getTokoStatus()
    {
        $op = $this->db->query('SELECT case when(sum(hl.history_is_online) > 0) then 1 else 0 end as history_is_online, pt.toko_nama, pt.toko_kode, pt.toko_logo FROM pajak_toko pt
        left join history_login hl on pt.toko_id = hl.history_wp_id
        WHERE toko_status = \'2\' AND hl.history_is_online = \'1\'
        group by toko_id')->result_array();
        // print_r('<pre>');print_r($this->db);print_r('</pre>');exit;
        $this->response($op);
    }

    public function getTokoUser()
    {
        $data = varPost();
        $this->dbpos = $this->load->database(multidb_connect($_ENV['PREFIX_DBPOS'] . $data['toko_kode']), true);
        $opuser = $this->dbpos->query('SELECT pu.user_id, pu.user_nama, pu.user_foto from pos_user pu')->result_array();

        // print_r('<pre>');print_r($opuser);print_r('</pre>');exit;

        $op = $this->db->query('SELECT NULLIF(hl.history_is_online, 0) as history_is_online, hl.history_wp_id, hl.history_nama_wp, hl.history_user_id from pajak_toko pt
        left join history_login hl on pt.toko_id = hl.history_wp_id
        where pt.toko_kode = \'' . $data['toko_kode'] . '\' AND hl.history_is_online = \'1\'')->result_array();

        foreach ($op as $key => $val) {
            $idx = array_search($val['history_user_id'], array_column($opuser, 'user_id'));
            $opuser[$idx]['history_is_online'] = $val['history_is_online'];
        }

        $this->response($opuser);
    }

    public function changeLog()
    {
        $operation = $this->ChangeLog->select([
            'filters_static' => [
                'change_log_which_app' => 2
            ],
            'sort_static' => 'change_log_change_date DESC',
            'limit' => 3,
        ]);
        $this->response($operation);
    }

    public function countNotif()
    {
        $totalBelumDibaca   = $this->UserInbox->count_exist([
            'inbox_receipt_id'   => $this->session->userdata('pegawai_id'),
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
            'inbox_receipt_id'   => $this->session->userdata('pegawai_id'),
            'inbox_opened'    => null,
        ]);
        $this->response($operation);
    }

    public function notifDibacaExist()
    {
        $data = varPost();
        $operation = $this->UserInbox->count_exist([
            'inbox_receipt_id'   => $this->session->userdata('pegawai_id'),
            'inbox_opened is not null'    => null,
        ]);
        $this->response($operation);
    }

    public function notifBelumDibacaMore()
    {
        $data = varPost();
        $operation = $this->UserInbox->select([
            'filters_static' => [
                'inbox_receipt_id'   => $this->session->userdata('pegawai_id'),
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
                'inbox_receipt_id'   => $this->session->userdata('pegawai_id'),
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

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

    public function index2(){
       print_r('<pre>');print_r($this->session->userdata());print_r('</pre>');exit;
    }

    public function getPage()
    {
        // echo strtolower(str_replace('-', '/', varPost('menu')));exit;
        $menu = explode('-',varPost('menu'));
        $view = $this->load->view(strtolower($menu[0]).'/'.$menu[1], null, true);
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

    public function changeProject(){
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
        $operation = $this->dbmp->query('SELECT * FROM sys_change_log 
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
}

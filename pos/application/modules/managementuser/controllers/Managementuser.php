<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManagementUser extends Base_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'RoleAccessModel' => 'roleaccess',
    ));
  }

  public function index()
  {
    $this->response($this->session->userdata);
  }

  public function loadTable()
  {
    $data = varPost();
    $where = [];
    $where['role_access_status'] = 1;

    $operation = $this->select_dt($data, 'roleaccess', 'table', true, $where);
    $this->response($operation);
  }

  public function read()
  {
    $read = $this->roleaccess->read(varPost());
    $this->response($read);
  }

  public function store()
  {
    $data = varPost();

    $newdata = array(
      'role_access_nama' => $data['role_access_nama'],
      'role_access_kode' => preg_replace('/\s+/', '', strtolower($data['role_access_nama'])),
      'role_access_status' => 1,
      'role_access_created_at' => date('Y-m-d H:i:s'),
      'role_access_created_by' => $this->session->userdata['user_id'],
    );

    $response = $this->roleaccess->insert(gen_uuid($this->roleaccess->get_table()), $newdata);

    $this->response($response);
  }

  public function update()
  {
    $data = varPost();
    $update = array(
      'role_access_nama' => $data['role_access_nama'],
      'role_access_updated_at' => date('Y-m-d H:i:s'),
      'role_access_updated_by' => $this->session->userdata['user_id'],
    );
    $response = $this->roleaccess->update(varPost('role_access_id', varExist($data, $this->roleaccess->get_primary(true))), $update);
    $this->response($response);
  }

  public function destroy()
  {
    $data = varPost();
    $operation = $this->roleaccess->delete(varPost('id', varExist($data, $this->roleaccess->get_primary(true))));
    $this->response($operation);
  }

  public function softDelete()
  {
    $data = varPost();
    $data['role_access_deleted_at'] = date('Y-m-d H:i:s');
    $data['role_access_deleted_by'] = $this->session->userdata['user_id'];
    $operation = $this->roleaccess->update(varPost('id', varExist($data, $this->roleaccess->get_primary(true))), $data);
    $this->response($operation);
  }

  public function get_menu()
  {
    $data = varPost();
    $role_id = $data['role_id'];
    $operation = $this->db->query("SELECT menu_id as id, 
    COALESCE(menu_parent, '#') as parent, 
    menu_title as text, menu_icon as icon, 
    pmr.menu_role_role_access as role_id
    FROM pos_menu 
    LEFT JOIN pos_menu_role pmr 
    on menu_id = pmr.menu_role_menu 
    and pmr.menu_role_role_access = '$role_id'
    ORDER BY menu_order asc")->result_array();

    foreach ($operation as $key => $value) {
      $statecon = (isset($operation[$key]['role_id']) && $operation[$key]['role_id'] != null) ? true : false;
      $operation[$key]['state'] = (object)[
        "selected" => $statecon,
        "opened" => $statecon,
      ];
    }
    $this->response(array("menu" => $operation));
  }

  public function get_menu_v2()
  {
    $data = varPost();
    $role_id = $data['role_id'];
    // $role_id = '123';
    $query = "SELECT *, (SELECT distinct pos_menu_role.menu_role_id FROM pos_menu_role 
    WHERE pos_menu_role.menu_role_menu = pos_menu.menu_id 
    AND pos_menu_role.menu_role_role_access = '" . $role_id . "'  AND pos_menu.menu_level = '3') AS menu_selected 
    FROM pos_menu 
    WHERE pos_menu.menu_isaktif = '1' or pos_menu.menu_isaktif = '2'
    ORDER BY pos_menu.menu_order";
    $menu['data'] = $this->db->query($query)->result_array();

    // print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;

    // print_r($menu['data']);
    // exit();
    $menu_list = array();
    foreach ($menu['data'] as $key => $value) {
      $parent = ($value['menu_parent'] == null) ? '#' : $value['menu_parent'];
      $state = false;
      // if ($init == 'tidak') {
      $state = (is_null($value['menu_selected']) ? false : true);
      // }
      array_push($menu_list, array(
        'id' => $value['menu_id'],
        'parent' => $parent,
        'text' => $value['menu_title'],
        // 'icon' => $value['menu_icon'],
        'state' => array(
          "selected" => $state,
          "opened" => false
        )
      ));
    }
    // print_r('<pre>');print_r($menu_list);print_r('</pre>');exit;
    $this->response(array('menu' => $menu_list));
  }

  public function get_menu_mobile()
  {
    $data = varPost();
    $role_id = $data['role_id'];
    $operation = $this->db->query("SELECT menu_id as id, 
    COALESCE(menu_parent, '#') as parent, 
    menu_title as text, menu_icon as icon, 
    pmr.menu_role_role_access as role_id
    FROM pos_menu_mobile 
    LEFT JOIN pos_menu_role_mobile pmr 
    on menu_id = pmr.menu_role_menu 
    and pmr.menu_role_role_access = '$role_id'
    ORDER BY menu_order asc")->result_array();

    foreach ($operation as $key => $value) {
      $statecon = (isset($operation[$key]['role_id']) && $operation[$key]['role_id'] != null) ? true : false;
      $operation[$key]['state'] = (object)[
        "selected" => $statecon,
        "opened" => $statecon,
      ];
    }
    $this->response(array("menu" => $operation));
  }

  public function store_menu_role()
  {
    $data = varPost();

    $opdel = $this->db->query("DELETE FROM pos_menu_role WHERE menu_role_role_access = '" . $data['role_access_id'] . "'");

    $message = "Role empty";
    if (isset($data['roles'])) {
      if (count($data['roles']) > 0) {
        $data_batch = array();
        foreach ($data['roles'] as $key => $val) {
          array_push($data_batch, array(
            "menu_role_id" => uniqid("", true),
            "menu_role_menu" => $val,
            "menu_role_role_access" => $data['role_access_id'],
          ));
        }
        // print_r($data_batch);die();
        $operation = $this->db->insert_batch('pos_menu_role', $data_batch);
      }
    }

    $this->response($operation);
  }

  public function store_menu_role_mobile()
  {
    $data = varPost();

    $opdel = $this->db->query("DELETE FROM pos_menu_role_mobile WHERE menu_role_role_access = '" . $data['role_access_id'] . "'");

    $message = "Role empty";
    if (isset($data['roles'])) {
      if (count($data['roles']) > 0) {
        $data_batch = array();
        foreach ($data['roles'] as $key => $val) {
          array_push($data_batch, array(
            "menu_role_id" => uniqid("", true),
            "menu_role_menu" => $val,
            "menu_role_role_access" => $data['role_access_id'],
          ));
        }
        $operation = $this->db->insert_batch('pos_menu_role_mobile', $data_batch);
      }
    }

    $this->response($operation);
  }

  public function getMenuMobile()
  {
    $data = varPost();
    if (!empty($data['mobileDb'])) {
      $this->db = $this->load->database(multidb_connect($data['mobileDb']), true);
      $user = $this->db->get_where('pos_user', ['user_email' => $data['email']])->row_array();
      $dataMenu = $this->db->get_where('v_sys_menu_role_mobile', ['pos_suser_id' => $user['pos_suser_id']])->result_array();

      $this->response([
        'success' => true,
        'message' => 'Success get data menu',
        'data' => $dataMenu,
      ]);
    } else {
      $this->response([
        'success' => false,
        'message' => 'Fail connect to your POS'
      ]);
    }
  }
}

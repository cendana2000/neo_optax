<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManagementUser extends Base_Controller {

  public function __construct()
  {
      parent::__construct();
      $this->load->model(array(
          'RoleAccessModel' => 'roleaccess',
      ));
  }

  public function index(){
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

  public function store(){
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

  public function update(){
    $data = varPost();
    $update = array(
      'role_access_nama' => $data['role_access_nama'],
      'role_access_updated_at' => date('Y-m-d H:i:s'),
      'role_access_updated_by' => $this->session->userdata['user_id'],
    );
    $response = $this->roleaccess->update(varPost('role_access_id', varExist($data, $this->roleaccess->get_primary(true))), $update);
    $this->response($response);
  }

  public function destroy(){
    $data = varPost();
    $operation = $this->roleaccess->delete(varPost('id', varExist($data, $this->roleaccess->get_primary(true))));
    $this->response($operation);
  }

  public function softDelete(){
    $data = varPost();
    $data['role_access_deleted_at'] = date('Y-m-d H:i:s');
    $data['role_access_deleted_by'] = $this->session->userdata['user_id'];
    $operation = $this->roleaccess->update(varPost('id', varExist($data, $this->roleaccess->get_primary(true))), $data);
    $this->response($operation);
  }

  public function get_menu(){
    $data = varPost();
    $role_id = $data['role_id'];
    // {"id":"a001","parent":"#","text":"Dashboard","icon":null,"state":{"selected":false,"opened":false}}
    $operation = $this->db->query("SELECT menu_id as id, 
    COALESCE(menu_parent, '#') as parent, 
    menu_title as text, menu_icon as icon, 
    pmr.menu_role_role_access as role_id
    FROM pajak_menu 
    LEFT JOIN pajak_menu_role pmr 
    ON menu_id = pmr.menu_role_menu 
    AND pmr.menu_role_role_access = '$role_id'
    ORDER BY menu_order ASC")->result_array();

    foreach($operation as $key => $value){
      $statecon = (isset($operation[$key]['role_id']) && $operation[$key]['role_id'] != null) ? true : false;
      $operation[$key]['state'] = (Object)[
        "selected" => $statecon,
        "opened" => $statecon,
      ];
    }
    $this->response(array("menu" => $operation));

  }

  public function store_menu_role(){
    $data = varPost();

    $opdel = $this->db->query("DELETE FROM pajak_menu_role WHERE menu_role_role_access = '".$data['role_access_id']."'");
    $message = "Role empty";
    if(isset($data['roles'])){
      if(count($data['roles']) > 0){
        $data_batch = array();
        foreach($data['roles'] as $key => $val){
          array_push($data_batch, array(
            "menu_role_id" => uniqid("", true),
            "menu_role_menu" => $val,
            "menu_role_role_access" => $data['role_access_id'],
          ));
        }
        // print_r($data_batch);die();
        $operation = $this->db->insert_batch('pajak_menu_role', $data_batch);
        $message = "Success save roles";
      }
    }

    $this->response(array(
      'message' => $message
    ));
  }

}
?>
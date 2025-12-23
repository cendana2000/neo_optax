<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManagementUserWp extends Base_Controller
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

  public function get_menu()
  {
    $data = varPost();
    $wajibpajak_id = $data['wajibpajak_id'];
    if ($data['switch_semua_wp'] == 'true') {
      $wajibpajak_id = 'default';
    }
    $operation = $this->db->query("SELECT menu_id as id, 
    COALESCE(menu_parent, '#') as parent, 
    menu_title as text, menu_icon as icon,
    pmr.menu_role_wp_wajibpajak_id as wajibpajak_id
    FROM pajak_menu_wp 
    LEFT JOIN pajak_menu_role_wp pmr 
      ON menu_id = pmr.menu_role_wp_menu 
      AND pmr.menu_role_wp_wajibpajak_id = '$wajibpajak_id'
    WHERE pajak_menu_wp.menu_isaktif = '1'
    ORDER BY menu_order ASC")->result_array();

    foreach ($operation as $key => $value) {
      $statecon = (isset($operation[$key]['wajibpajak_id']) && $operation[$key]['wajibpajak_id'] != null) ? true : false;
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

    // $data = [
    //   "wajibpajak_id" => '',
    //   "switch_semua_wp" => true,
    //   "roles" => ["18005664505613f870062170ff916620",
    //   "f63143cc466006cf36cfa827b822c442",
    //   "f3143cc466006cf36cfa827b822c1231",
    //   "f3143cc466006cf36cfa827b822c1232",
    //   "f3143cc466006cf36cfa827b822c1233",
    //   "f3143cc466006cf36cfa827b822c1234",
    //   "f63143cc466006cf36cfa827b822c321",
    //   "f3143cc466006cf36cfa827b82221231",
    //   "f3143cc466006cf36cfa827b82221232",
    //   "f3143cc466006cf36cfa827b82221233",
    //   "f3143cc466006cf36cfa827b82221234",
    //   "f63143cc466006cf36cfa827b822c322",
    //   "f3143cc466006cf36cfa827b8222rti1",
    //   "f3143cc466006cf36cfa827b8222rti2",
    //   "f3143cc466006cf36cfa827b8222rti3",
    //   "f3143cc466006cf36cfa827b8222rti4",
    //   "f63143cc466006cf36cfa827b822crti",
    //   "f63143cc466006cf36cfa827b8242121"]
    // ];

    if ($data['switch_semua_wp'] == 'true') {
      if ($pemda_id = $this->session->userdata('pemda_id')) {
        $this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pajak_menu_role_wp.menu_role_wp_wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')', NULL, FALSE);
      }

      $delwprole = $this->db->delete('pajak_menu_role_wp');
      $listwp = $this->db->select('*')
        ->where('wajibpajak_deleted_at is null AND wajibpajak_status = \'2\'');

      if ($pemda_id = $this->session->userdata('pemda_id')) {
        $this->db->where('pemda_id', $pemda_id);
      }

      $listwp = $listwp->get('pajak_wajibpajak')->result_array();
      $datarole = [];
      foreach ($listwp as $klwp => $vlwp) {
        foreach ($data['roles'] as $kr => $vr) {
          $itemrole = [
            "menu_role_wp_id" => gen_uuid('pajak_wajibpajak'),
            "menu_role_wp_menu" => $vr,
            "menu_role_wp_wajibpajak_id" => $vlwp['wajibpajak_id']
          ];
          array_push($datarole, $itemrole);
        }
      }

      foreach ($data['roles'] as $kr => $vr) {
        $itemrole = [
          "menu_role_wp_id" => 'default_' . $kr,
          "menu_role_wp_menu" => $vr,
          "menu_role_wp_wajibpajak_id" => 'default'
        ];
        array_push($datarole, $itemrole);
      }
      $this->db->insert_batch('pajak_menu_role_wp', $datarole);
    } else {

      $delwprole = $this->db->where('menu_role_wp_wajibpajak_id = \'' . $data['wajibpajak_id'] . '\'')->delete('pajak_menu_role_wp');
      $datarole = [];
      foreach ($data['roles'] as $kr => $vr) {
        $itemrole = [
          "menu_role_wp_id" => gen_uuid('pajak_wajibpajak'),
          "menu_role_wp_menu" => $vr,
          "menu_role_wp_wajibpajak_id" => $data['wajibpajak_id']
        ];
        array_push($datarole, $itemrole);
      }
      $this->db->insert_batch('pajak_menu_role_wp', $datarole);
    }

    $message = "Success save roles";

    $this->response(array(
      'status' => true,
      'message' => $message
    ));
  }

  public function select_wajibpajak($value = '')
  {
    $data = varPost();
    $where = '';
    if ($pemda_id = $this->session->userdata('pemda_id')) {
      $where .= 'AND pajak_wajibpajak.pemda_id=' . $this->db->escape($pemda_id);
    }

    $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
    $total = $this->db->query('SELECT count(wajibpajak_id) total FROM pajak_wajibpajak WHERE wajibpajak_deleted_at IS NULL ' . $where . ' AND (wajibpajak_status = \'2\' OR wajibpajak_status = \'5\') AND LOWER(concat(wajibpajak_npwpd, wajibpajak_nama))::text like \'%' . strtolower($data['q']) . '%\'')->result_array();

    $return = $this->db->query('SELECT wajibpajak_id as id, concat(wajibpajak_npwpd, \' - \', wajibpajak_nama) as text, wajibpajak_npwpd FROM pajak_wajibpajak WHERE wajibpajak_deleted_at IS NULL ' . $where . ' AND (wajibpajak_status = \'2\' OR wajibpajak_status = \'5\') AND LOWER(concat(wajibpajak_npwpd, wajibpajak_nama))::text like \'%' . strtolower($data['q']) . '%\' LIMIT ' . $data['page'] . $data['limit'])->result_array();
    $this->response(array('items' => $return, 'total_count' => $total[0]['total']));
  }
}

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

    if (empty($data['roles']) || !is_array($data['roles'])) {
      return $this->response([
        'status'  => false,
        'message' => 'Role tidak boleh kosong'
      ], 400);
    }

    $this->db->trans_start();

    if ($data['switch_semua_wp'] === 'true') {

      $pemda_id = $this->session->userdata('pemda_id');

      if (!$pemda_id) {
        $this->db->trans_rollback();
        return $this->response([
          'status'  => false,
          'message' => 'Pemda ID tidak ditemukan di session'
        ], 400);
      }
      $sub = $this->db->select('wajibpajak_id')
        ->from('pajak_wajibpajak')
        ->where('pemda_id', $pemda_id)
        ->get()
        ->result_array();

      $ids = array_column($sub, 'wajibpajak_id');

      if (!empty($ids)) {
        $this->db->where_in('menu_role_wp_wajibpajak_id', $ids)
          ->delete('pajak_menu_role_wp');
      }
      $this->db->where('menu_role_wp_wajibpajak_id', 'default')
        ->delete('pajak_menu_role_wp');
      $listwp = $this->db->select('wajibpajak_id')
        ->from('pajak_wajibpajak')
        ->where('wajibpajak_deleted_at IS NULL', null, false)
        ->where('wajibpajak_status', '2')
        ->where('pemda_id', $pemda_id)
        ->get()
        ->result_array();
      $datarole = [];
      foreach ($listwp as $wp) {
        foreach ($data['roles'] as $menu_id) {
          $datarole[] = [
            'menu_role_wp_id'             => gen_uuid('pajak_menu_role_wp'),
            'menu_role_wp_menu'           => $menu_id,
            'menu_role_wp_wajibpajak_id'  => $wp['wajibpajak_id']
          ];
        }
      }

      foreach ($data['roles'] as $menu_id) {
        $datarole[] = [
          'menu_role_wp_id'             => gen_uuid('pajak_menu_role_wp'),
          'menu_role_wp_menu'           => $menu_id,
          'menu_role_wp_wajibpajak_id'  => 'default'
        ];
      }

      if (!empty($datarole)) {
        $this->db->insert_batch('pajak_menu_role_wp', $datarole);
      }
    } else {
      if (empty($data['wajibpajak_id'])) {
        $this->db->trans_rollback();
        return $this->response([
          'status'  => false,
          'message' => 'Wajib pajak belum dipilih'
        ], 400);
      }

      $this->db->where('menu_role_wp_wajibpajak_id', $data['wajibpajak_id'])
        ->delete('pajak_menu_role_wp');

      $datarole = [];
      foreach ($data['roles'] as $menu_id) {
        $datarole[] = [
          'menu_role_wp_id'             => gen_uuid('pajak_menu_role_wp'),
          'menu_role_wp_menu'           => $menu_id,
          'menu_role_wp_wajibpajak_id'  => $data['wajibpajak_id']
        ];
      }

      if (!empty($datarole)) {
        $this->db->insert_batch('pajak_menu_role_wp', $datarole);
      }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      return $this->response([
        'status'  => false,
        'message' => 'Gagal menyimpan konfigurasi role'
      ], 500);
    }

    return $this->response([
      'status'  => true,
      'message' => 'Success save roles'
    ]);
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

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogwajibpajakModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_log',
				'primary' => 'log_id',
				'fields' => array(
					array('name' => 'log_id'),
					array('name' => 'log_tanggal'),
					array('name' => 'log_user_id'),
					array('name' => 'log_activity'),
					array('name' => 'wajibpajak_nama_penanggungjawab', 'view' => true),
					array('name' => 'wajibpajak_npwpd', 'view' => true),
					array('name' => 'wajibpajak_nama', 'view' => true),
				)
			),
			'view' => array(
        'name' => 'v_log_wajibpajak',
				'mode' => array(
					'table' => array(
            'log_id', 
            'log_tanggal',
            'wajibpajak_nama',
          ),
          'detailtable' => array(
            'log_id', 
            'log_tanggal',
            'wajibpajak_nama',
            'log_activity'
          ),
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

  public function save_log($action){
    $user = $this->session->userdata();
    if(!empty($user['wajibpajak_id'])){
      $arraction = [$action.'#'.date('Y-m-d H:i:s')];
      $data = [
        'log_tanggal' => date('Y-m-d'),
        'log_user_id' => $user['wajibpajak_id'],
        'log_activity' => '{'.implode($arraction, ',').'}',
      ];
      $exist = $this->read(array(
        'log_tanggal' => date('Y-m-d'),
        'log_user_id' => $user['wajibpajak_id']
      ));
      if(empty($exist)){
        $ops = $this->insert(gen_uuid(), $data);
      }else{
        $removekurawal = preg_replace('/[{"}]/i', '', $exist['log_activity']);
        if(!empty($removekurawal)){
          $newarractivity = explode(',', $removekurawal);
          array_push($newarractivity, $action.'#'.date('Y-m-d H:i:s'));
          $udata = [
            'log_activity' => '{'.implode($newarractivity, ',').'}',
          ];
          $ops = $this->update($exist['log_id'], $udata);
        }else{
          $udata = [
            'log_activity' => '{'.$action.'#'.date('Y-m-d H:i:s').'}',
          ];
          $ops = $this->update($exist['log_id'], $udata);
        }
      }
      return $ops;
    }else{
      return [
        'success' => false
      ];
    }
  }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logwajibpajak extends BASE_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
          'LogwajibpajakModel' => 'logwajibpajak',
        ));
    }

    public function index()
    {
        $this->response('log-wajibpajak page');
    }

    public function datatable(){
      $data = varPost();
      $ops = $this->select_dt($data, 'logwajibpajak', 'table', false);
      $this->response($ops);
    }

    public function detaildatatable(){
      $data = varPost();
      $ops = $this->logwajibpajak->read($data['log_id']);
      if(!empty($ops)){
        $activity = $ops['log_activity'];
        $removekurawal = preg_replace('/[{"}]/i', '', $activity);
        $arractivity = explode(',', $removekurawal);
        krsort($arractivity);
        $newarractivity = [];
        foreach($arractivity as $key => $val){
          $arrval = explode('#', $val);
          $newarractivity[] = [
            'log_message' => $arrval[0],
            'log_at' => $arrval[1]
          ];
        }
        $this->response([
          'success' => true,
          'data' => [
            'log' => [
              'log_tanggal' => $ops['log_tanggal'],
              'log_wajibpajak_nama' => $ops['wajibpajak_nama']
            ],
            'activity' => $newarractivity
          ]
        ]);
      }else{
        $this->response([
          'success' => true,
          'data' => [
            'log' => [],
            'activity' => []
          ]
        ]);
      }
    }
}

/* End of file Logwajibpajak.php */
/* Location: ./application/modules/logwajibpajak/controllers/Logwajibpajak.php */

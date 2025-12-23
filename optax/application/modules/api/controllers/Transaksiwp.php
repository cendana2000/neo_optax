<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksiwp extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksiwp/TransaksiwpModel' 			 		 => 'transaksiwp',
		));
    $this->Auth();
	}

  private function Auth(){
		$headers = $this->input->request_headers();
		if(!array_key_exists('Authorization', $headers) && empty($headers['Authorization'])){
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode(array(
				'success' => false,
				'message' => 'not allowed',
			), JSON_UNESCAPED_UNICODE));
			$this->output->_display();
			exit;
		}else{
      $token = null;

      if(!empty($headers['Authorization'])){
          if(preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){
              $token = $matches[1];
          }
      }

      if($token != $this->config->item('api_pajak_token')){
        $this->output->set_content_type('application/json');
				$this->output->set_output(json_encode(array(
					'success' => false,
					'message' => 'token invalid',
				), JSON_UNESCAPED_UNICODE));
				$this->output->_display();
				exit;
      }
		}
	}

  public function index()
	{
    $data = varPost();
		$codestore = $data['codestore'];
		$startdate = $data['startdate'];
		$enddate = $data['enddate'];
    $page = $data['page'];
    $limit = $data['show'];

    if(empty($codestore) || empty($startdate) || empty($enddate)){
      $empty_msg = [];
      if(empty($codestore)) array_push($empty_msg, 'codestore');
      if(empty($startdate)) array_push($empty_msg, 'startdate');
      if(empty($enddate)) array_push($empty_msg, 'enddate');
      return $this->response([
        'status' => false,
        'message' => 'required parameters: '. implode(', ', $empty_msg),
      ], 400);
    }

    $startdate = date('Y-m-d 00:00:00', strtotime($startdate));
    $enddate = date('Y-m-d 23:59:59', strtotime($enddate));

		// $where['log_penjualan_code_store'] = $codestore;
		// $where['log_penjualan_wp_penjualan_tanggal >= \''.$startdate.'\' AND log_penjualan_wp_penjualan_tanggal <= \''.$enddate.'\''] = null;

    // $select_data = [
    //   'filters_static' => $where
    // ];

    if(isset($page) && !isset($limit)) $limit = 15;
    if(isset($page) && $page >= 0){
      $select_data['limit'] = $limit;
      $select_data['start'] = $page;
    }

    $toko = $this->db->get_where('pajak_toko', [
      'toko_kode' => $codestore
    ])->row_array();

    if(empty($toko)){
      return $this->response([
        'success' => false,
        'message' => 'codestore '.$codestore.' tidak terdaftar.'
      ], 400);
    }

		$where['penjualan_tanggal >= \''.$startdate.'\' AND penjualan_tanggal <= \''.$enddate.'\''] = null;

    $opr = [
      'success' => true,
      'data' => $this->db->get_where('pos_penjualan', $where)->result_array()
    ];
		$get_total = $this->db
		->select("sum(penjualan_total_grand) as total_nominal_penjualan")
		->where($where)
		->get('pos_penjualan')
		->row();
		$opr['sumtotal'] = $get_total;
		$wp = $this->db
		->select('wajibpajak_nama_penanggungjawab, 
		wajibpajak_npwpd, 
		toko_kode,
		toko_nama')
		->get_where('v_pajak_toko', [
			'toko_kode' => $codestore,
		])->row();
		$opr['wajibpajak'] = $wp;
		$this->response(
			$opr
		);

    /*

		$opr = $this->transaksiwp->select($select_data);

		$get_total = $this->db
		->select("sum(log_penjualan_wp_total) as total_nominal_penjualan")
		->where($where)
		->get('v_pajak_penjualan_wp')
		->row();
		$opr['sumtotal'] = $get_total;
		$this->response(
			$opr
		);
    */
	}
}
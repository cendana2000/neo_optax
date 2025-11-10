<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AkunBalanceModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ak_akun_balance',
				'primary' => 'akun_balance_id',
				'fields' => array(
					array('name' => 'akun_balance_id'),
					array('name' => 'akun_balance_bulan'),
					array('name' => 'akun_balance_tahun'),
					array('name' => 'akun_balance_akun'),
					array('name' => 'akun_balance_balance'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array('akun_balance_id','akun_balance_bulan','akun_balance_tahun','akun_balance_akun','akun_balance_balance')
				)
			)
		);
		parent::__construct($model);		
	}
	
	public function add_balance($akun, $bulan, $tahun, $balance){
		$query = $this->db->query("SELECT add_balance_akun('".$akun."', ".$bulan.", ".$tahun.", '".$balance."');");
		$result = $query->result_array();
		return $result;
	}

	public function reduce_balance($akun, $bulan, $tahun, $balance){
		$query = $this->db->query("SELECT reduce_balance_akun('".$akun."', ".$bulan.", ".$tahun.", '".$balance."');");
		$result = $query->result_array();
		return $result;
	}

	public function empty_akun_balance($akun = '', $month = 0, $year = 0, $type = 'parent'){
		if($type == 'parent'){
			$query = $this->db->query("SELECT akun_id FROM akun WHERE akun_parent = '".$akun."';");
			$result = $query->result_array();
			if($result){
				foreach ($result as $record) {
					$query_det = $this->db->query("UPDATE akun_balance SET akun_balance_balance = 0 WHERE akun_balance_akun = '".$record['akun_id']."' AND akun_balance_bulan = '".$month."' AND akun_balance_tahun = '".$year."';");
					$this->empty_akun_balance($record['akun_id'], $month, $year, $type);
				}	
			}else{
				return "done";
			}
		}else{
			$query_det = $this->db->query("UPDATE akun_balance SET akun_balance_balance = 0 WHERE akun_balance_akun = '".$akun."';");
		}
	}
}

/* End of file agamaModel.php */
/* Location: .//X/rsmh/app/modules/agama/models/agamaModel.php */
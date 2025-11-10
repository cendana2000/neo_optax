<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AkunModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ak_akun',
				'primary' => 'akun_id',
				'fields' => array(
					array('name' => 'akun_id'),
					array('name' => 'akun_key'),
					array('name' => 'akun_kode'),
					array('name' => 'akun_nama'),
					array('name' => 'akun_parent'),
					array('name' => 'akun_tipe'),
					array('name' => 'akun_is_root'),
					array('name' => 'akun_lock'),
					array('name' => 'akun_active'),
					array('name' => 'akun_golongan'),
					array('name' => 'akun_is_pembayaran'),
					array('name' => 'akun_is_bank'),
					array('name' => 'akun_saldo'),
					array('name' => 'akun_saldo_debit'),
					array('name' => 'akun_saldo_kredit'),
					array('name' => 'akun_bank_jenis_id'),
					array('name' => 'akun_bank_rekening'),
					array('name' => 'akun_is_kas_bank'),
					array('name' => 'akun_unit'),
				)
			),
			'view' => array(
				'mode' => array(
					'datatable' => array('akun_id','akun_key')
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function data_tree($parent = '#', $company = null){
		if (isset($_GET['id'])) {
			$parent = $_GET['id'];
		}	
		$query = $this->db->query("SELECT akun_id as id, akun_parent as parent, akun_kode as kode, akun_nama as nama, akun_tipe as tipe, CONCAT(akun_kode, ' - ', akun_nama) as text, akun_tipe as children, akun_key FROM ak_akun WHERE akun_parent = '".$parent."' AND akun_active = 1 ORDER BY akun_kode ASC;");
		$result = $query->result_array();
		foreach ($result as &$record) {
			if($record['children'] == 'parent'){
				$record['children'] = true;
			}else{
				$record['children'] = false;
			}
		}
		return $result;
	}

	public function get_akun_by_number($num, $raw = false){
		$data = $this->read(array(
			'akun_kode' => $num,
			'akun_active' => 1
		));	
			// 'akun_company' => $this->company_model->company_detail('company_id'),

		if($data){
			if($raw){
				return $data;
			}else{
				return $data['akun_id'];
			}
		}else{
			return null;
		}
	}

	public function get_akun_by_key($key, $company = null){
		$data = $this->db->query('SELECT akun_id FROM ak_akun WHERE akun_key = "'.$key.'" ORDER BY akun_kode DESC LIMIT 1')->result_array();
		if($data){
			return $data[0]['akun_id'];
		}else{
			return null;
		}
	}

	public function get_parent($id){
		$query = $this->db->query("SELECT akun_nama, akun_parent FROM ak_akun WHERE akun_id = '".$id."';");
		$result = $query->result_array();
		$data = $result[0];

		if($data['akun_parent'] == "#"){
			return $data;
		}else{
			return $this->get_parent($data['akun_parent']);
		}
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */
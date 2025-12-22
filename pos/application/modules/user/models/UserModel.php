<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_user',
				'primary' => 'user_id',
				'fields' => array(
					array('name' => 'user_id', 'unique' => true),
					array('name' => 'user_role_access_id'),
					array('name' => 'user_name'),
					array('name' => 'user_address'),
					array('name' => 'user_phone'),
					array('name' => 'user_email'),
					array('name' => 'user_password'),
					array('name' => 'user_status'),
					array('name' => 'user_photo'),
					array('name' => 'user_last_change_password'),
					array('name' => 'user_created_at'),
					array('name' => 'user_updated_at'),
					array('name' => 'user_deleted_at'),
					array('name' => 'wajibpajak_id'),
					array('name' => 'user_code_store'),
					array('name' => 'user_jenis_parent_name'),
				)
			),
			'view' => array(
				'name' => 'pos_user',
				'mode' => array(
					'datatable' => array(
						'user_id',
						'user_name',
						'user_phone',
						'user_email',
						'user_status',
						'user_role_access_id',
						'user_address',
						'user_password',
						'user_photo',
						'user_last_change_password',
						'user_created_at',
						'user_updated_at',
						'user_deleted_at',
						'wajibpajak_id',
						'user_code_store',
						'user_jenis_parent_name',
					)
				)
			)
		);
		parent::__construct($model);
	}

	public function get_rule_access($ruleCode = null)
	{
		$rules = (array) $this->get_rules();
		if (is_array($rules) and array_key_exists($ruleCode, $rules)) {
			return isset($rules[$ruleCode]) ? $rules[$ruleCode] : $rules[crc32($ruleCode)];
		} else {
			return false;
		}
	}

	public function get_rules()
	{
		$rules = $this->session->userdata('sess_rules');

		if (is_array($rules)) {
			$_rules = array_flip($rules);
			foreach ($_rules as $key => $value) {
				$_rules[$key] = true;
			}
			$rules = $_rules;
		}
		if (is_object($rules)) {
			$rules = (array) $rules;
		}

		return $rules;
	}

	public function islogin()
	{
		if ($this->session->userdata('user_id') and $this->session->userdata('is_login')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getPegawaiNamaByPegawaiID($id = "")
	{
		$sql = "SELECT pegawai_nama FROM pos_pegawai WHERE pegawai_id='$id'";
		return $this->db->query($sql)->result();
	}
}

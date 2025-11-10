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
					array('name' => 'user_nama'),
					array('name' => 'user_alamat'),
					array('name' => 'user_telepon'),
					array('name' => 'user_email'),
					array('name' => 'user_password'),
					array('name' => 'user_status'),
					array('name' => 'user_foto'),
					array('name' => 'user_last_change_password'),
					array('name' => 'user_is_registered'),
					array('name' => 'user_token_registrasi'),
					array('name' => 'user_last_change_password'),
					array('name' => 'user_created_at'),
					array('name' => 'user_updated_at'),
					array('name' => 'user_deleted_at'),
					array('name' => 'role_access_kode', 'view' => true),
					array('name' => 'role_access_nama', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_user',
				'mode' => array(
					'datatable' => array(
						'user_id',
						'user_nama',
						'user_telepon',
						'user_email',
						'user_status',
						'user_role_access_id',
						'user_alamat',
						'user_password',
						'user_foto',
						'user_last_change_password',
						'user_is_registered',
						'user_token_registrasi',
						'user_created_at',
						'user_updated_at',
						'user_deleted_at',
						'role_access_kode',
						'role_access_nama',
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
		if ($this->session->userdata('user_id') and $this->session->userdata('login_status')) {
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

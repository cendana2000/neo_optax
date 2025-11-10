<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WajibpajakModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_wajibpajak',
				'primary' => 'wajibpajak_id',
				'fields' => array(
					array('name' => 'wajibpajak_id'),
					array('name' => 'wajibpajak_npwpd'),
					array('name' => 'wajibpajak_nama_penanggungjawab'),
					array('name' => 'wajibpajak_sektor_id'),
					array('name' => 'wajibpajak_sektor_nama'),
					array('name' => 'wajibpajak_nama'),
					array('name' => 'wajibpajak_alamat'),
					array('name' => 'wajibpajak_status'),
					array('name' => 'wajibpajak_wp_id'),
					array('name' => 'wajibpajak_email'),
					array('name' => 'wajibpajak_telp'),
					array('name' => 'wajibpajak_password'),
					array('name' => 'wajibpajak_last_change_password'),
					array('name' => 'wajibpajak_berkas'),
					array('name' => 'wajibpajak_forgotpassword_token'),
					array('name' => 'wajibpajak_forgotpassword_expired_at'),
					array('name' => 'wajibpajak_created_at'),
					array('name' => 'wajibpajak_created_by'),
					array('name' => 'wajibpajak_updated_at'),
					array('name' => 'wajibpajak_updated_by'),
					array('name' => 'wajibpajak_deleted_at'),
					array('name' => 'wajibpajak_deleted_by'),
					array('name' => 'wajibpajak_foto'),
					array('name' => 'jenis_nama', 'view' => true),
					array('name' => 'toko_kode', 'view' => true)
				)
			),
			'view' => array(
				'name' => 'v_pajak_wajib_pajak',
				'mode' => array(
					'table' => [
						'wajibpajak_id',
						'wajibpajak_npwpd',
						'toko_kode',
						'wajibpajak_nama',
						'jenis_nama',
						'wajibpajak_nama_penanggungjawab',
						'wajibpajak_updated_at',
						'wajibpajak_status',
						'wajibpajak_alamat',
						'wajibpajak_wp_id',
						'wajibpajak_email',
						'wajibpajak_telp',
						'wajibpajak_berkas',
						'wajibpajak_created_at',
						'wajibpajak_deleted_at',
						'wajibpajak_sektor_id',
						'wajibpajak_sektor_nama',
					]

				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function updateToken($wajibpajak_id)
	{
		$token = substr(sha1(rand()), 0, 30);
		$date = date('Y-m-d H:i:s');
		$dateexpire = date_format((new DateTime(date('Y-m-d H:i:s')))->modify('+1 hour'), 'Y-m-d H:i:s');

		$data = array(
			'wajibpajak_forgotpassword_token' => $token,
			'wajibpajak_forgotpassword_expired_at' => $dateexpire,
		);
		$op = $this->update($wajibpajak_id, $data);
		if ($op['success']) {
			// return $token . $op['id'];
			return $token;
		} else {
			return null;
		}
	}

	public function isTokenValid($token)
	{
		$tkn = substr($token, 0, 30);
		// $wajibpajak_id = substr($token, 30);

		// $q = $this->read(array('wajibpajak_id' => $wajibpajak_id, 'wajibpajak_forgotpassword_token' => $tkn));
		$q = $this->read(array('wajibpajak_forgotpassword_token' => $tkn));

		if ($this->db->affected_rows() > 0) {
			$created = $q['wajibpajak_forgotpassword_expired_at'];
			$createdTS = strtotime($created);
			$today = date('Y-m-d H:i:s');
			$todayTS = strtotime($today);

			if ($todayTS > $createdTS) {
				return false;
			}

			return $q;
		} else {
			return false;
		}
	}
}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */

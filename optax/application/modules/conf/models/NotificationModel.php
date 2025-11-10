<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class NotificationModel extends Base_Model
{
	public function __construct()
	{
		$this->load->model(array(
			'conf/UserInboxModel' => 'userinbox',
		));
	}

	public function sendNotif($title, $body, $jenis = 'DEFAULT', $featureType = 'DEFAULT', $sentToUser = null, $sentToApp = 'PEMDA')
	{
		if($sentToApp == 'PEMDA'){
			$whereusers = ['pegawai_status' => '1'];
			if(!empty($sentToUser)){
				$whereusers['pegawai_id'] = $sentToUser;
			}
			$users = $this->db->select('pegawai_id, pegawai_nama')->get_where('pajak_pegawai', $whereusers)->result_array();
		}else if($sentToApp == 'WP'){
			$whereusers = ['wajibpajak_status' => '2'];
			if(!empty($sentToUser)){
				$whereusers['wajibpajak_id'] = $sentToUser;
			}
			$users = $this->db->select('wajibpajak_id, wajibpajak_nama_penanggungjawab')->get_where('pajak_wajibpajak', $whereusers)->result_array();
		}

		foreach($users as $key => $val){
			if($sentToApp == 'PEMDA'){
				$userId = $val['pegawai_id'];
				$sender = $this->session->userdata('pegawai_id');
			}else if($sentToApp == 'WP'){
				$userId = $val['wajibpajak_id'];
				$sender = $this->session->userdata('wajibpajak_id');
			}
			$pemdatoken = $this->db->select('*')->get_where('conf_user_login', [
				'user_login_app' => $sentToApp,
				'user_login_datetime_logout is NULL' => null,
				'user_login_user_id' => $userId
			])->result_array();
			$tokens = [];
			foreach($pemdatoken as $tokenkey => $tokenval){
				$tokens[] = $tokenval['user_login_fcm'];
			}
			$result = $this->googleclient->sendNotification($tokens, $title, $body);
			$this->userinbox->insert(gen_uuid(), [
				'inbox_title' => $title,
				'inbox_message' => $body,
				'inbox_sender_id' => $sender,
				'inbox_receipt_id' => $userId,
				'inbox_datetime' => date('Y-m-d H:i:s'),
				'inbox_fcm_token' => json_encode($tokens),
				'inbox_status' => json_encode($result),
				'inbox_receipt_type' => $sentToApp,
				'inbox_feature_id' => null,
				'inbox_opened' => null,
				'inbox_jenis' => $jenis,
				'inbox_feature_type' => $featureType,
				'inbox_note' => null,
			]);
		}
		return [
			'success' => true,
			'message' => 'notification sent successfuly'
		];
	}
}

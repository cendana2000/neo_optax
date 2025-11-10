<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MobileNotificationModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'mobile_notification',
				'primary' => 'notif_id',
				'fields' => array(
					array('name' => 'notif_id'),
					array('name' => 'notif_sent_to'),
					array('name' => 'notif_datetime'),
					array('name' => 'notif_content'),
					array('name' => 'notif_metadata'),
					array('name' => 'notif_is_sent'),
					array('name' => 'notif_type'),
					array('name' => 'notif_is_read'),
					array('name' => 'notif_title'),

				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function sent($value='')
	{
		$devices = $this->db
			->where(['device_user_id'=>$value['target']])
			->or_where(['device_user_id'=>md5($value['target'])])
			->get('mobile_device')
			->row_array();

		$device = $this->db
			->where(['activate_kodeanggota'=>$devices['device_user_kode']])
			->get('mobile_activation')
			->row_array();
		$notif = [
			"title" => $value['title'],
			"body" => $value['message'],
			"click_action" => "FCM_PLUGIN_ACTIVITY",
		];
		if($value['type'] == 'message'){
			$notif['android_channel_id'] = 'notif_fcm';
		}

		$data = [
			"body" => $value['message'],
			"title" => $value['title'],
			"callback_action" => 'callback',
			"notif_type"=>$value['type'],
			"notif_id" => $value['notif_id']
		];
		if($value['type'] == 'message'){
			 $data['android_channel_id'] = 'notif_fcm';
		}
		$json_data =[
			"to" => $device['activate_fcmtoken'],
			"notification" => $notif,
			"data" => $data
		];
		
		$data = json_encode($json_data);
		$url = 'https://fcm.googleapis.com/fcm/send';
		$server_key = getenv('ek_fcm_serverkey');
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$server_key
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
		// return $result;
	}
}

/* End of file MobileNotificationModel.php */
/* Location: ./application/modules/api/models/MobileNotificationModel.php */
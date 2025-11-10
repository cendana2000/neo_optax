<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('gen_uuid')) {
	function gen_uuid($table_name = '')
	{
		$CI = &get_instance();
		$res_id = $CI->db->query('SELECT gen_random_uuid() as ID');
		$gen_id = $res_id->row_array();
		return md5($table_name . $gen_id['id']);
	}
}


if (!function_exists('gen_kode_unik')) {
	function gen_kode_unik()
	{
		$CI = &get_instance();
		$res_id = $CI->db->query('SELECT gen_random_uuid() as ID');
		$gen_id = $res_id->row_array();
		$gen_id_ar = explode('-', $gen_id['id']);
		return $gen_id_ar[0];
	}
}

if (!function_exists('text_truncate')) {
	function text_truncate($text, $length = 100)
	{
		if ($length >= \strlen($text)) {
			return $text;
		}
		return preg_replace(
			"/^(.{1,$length})(\s.*|$)/s",
			'\\1...',
			$text
		);
	}
}

if (!function_exists('array_to_string')) {
	function array_to_string($data = [])
	{
		$result = "'" . implode("', '", $data) . "'";
		return $result;
	}
}

if (!function_exists('group_by_array')) {
	function group_by_array($array, $key)
	{
		$return = array();
		foreach ($array as $val) {
			$return[$val[$key]][] = $val;
		}
		return $return;
	}
}

if (!function_exists('is_html')) {
	function is_html($string)
	{
		if ($string != strip_tags($string) && strstr($string, "not-checkbox") == false) {
			return true;
		} else {
			return false;
		}
	}

	function cleanResponse($data)
	{
		foreach ($data as $k => $v) {
			if (is_array($v) && count($v) > 0) {
				$data[$k] = cleanResponse($v);
			} elseif (is_string($v)) {
				if (is_html($v)) {
					$data[$k] = htmlspecialchars($v);
				}
			}
		}
		return $data;
	}
}

if (!function_exists('time_since')) {
	function time_since($datetime)
	{
		$interval = date_create('now')->diff($datetime);
		$suffix = ($interval->invert ? ' yang lalu' : '');
		if ($v = $interval->y >= 1) return pluralize($interval->y, 'tahun') . $suffix;
		if ($v = $interval->m >= 1) return pluralize($interval->m, 'bulan') . $suffix;
		if ($v = $interval->d >= 1) return pluralize($interval->d, 'hari') . $suffix;
		if ($v = $interval->h >= 1) return pluralize($interval->h, 'jam') . $suffix;
		if ($v = $interval->i >= 1) return pluralize($interval->i, 'menit') . $suffix;
		return pluralize($interval->s, 'detik') . $suffix;
	}
}

if (!function_exists('pluralize')) {
	function pluralize($count, $text)
	{
		// return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
		return $count . (($count == 1) ? (" $text") : (" ${text}"));
	}
}

if (!function_exists('view')) {
	function view($view, $data = null, $tf = null)
	{
		$CI = &get_instance();
		if (is_array($view)) {
			for ($i = 0; $i < count($view); $i++) {
				$CI->load->view($view[$i], $data[$i], $tf[$i]);
			}
		} else {
			$CI->load->view($view, $data, $tf);
		}
	}
}

if (!function_exists('getConfig')) {
	function getConfig($value = '')
	{
		$CI = &get_instance();
		return $CI->db->query("SELECT * FROM configuration WHERE conf_code = '" . $value . "' ")->result_array()[0];
	}
}

if (!function_exists('load_view')) {
	function load_view($view, $data = null, $tf = null)
	{
		$CI = &get_instance();
		return $CI->load->view($view, $data, $tf);
	}
}

if (!function_exists('model')) {
	function model($data, $initial = null, $tf = TRUE)
	{
		$CI = &get_instance();
		return $CI->load->model($data, $initial, $tf);
	}
}

if (!function_exists('load_model')) {
	function load_model($data, $initial = null, $tf = TRUE)
	{
		$CI = &get_instance();
		return $CI->load->model($data, $initial, $tf);
	}
}

if (!function_exists('phone')) {
	function phone($all = false, $country = '')
	{
		$CI = &get_instance();
		$country_id = ($country != '') ? $country : $CI->session->userdata('user_country_id');
		$query = $CI->db->select('*')->from('phone')->where(array('phone_country_id' => $country_id, 'phone_is_default' => 1))->get()->result_array();
		if ($all === true) {
			return $query[0];
		} else {
			return $query[0]['phone_number'];
		}
	}
}

if (!function_exists('convertK')) {
	function convertK($num)
	{
		$num = (int)$num;
		if ($num > 1000) {

			$x = round($num);
			$x_number_format = number_format($x);
			$x_array = explode(',', $x_number_format);
			$x_parts = array('k', 'm', 'b', 't');
			$x_count_parts = count($x_array) - 1;
			$x_display = $x;
			$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
			$x_display .= $x_parts[$x_count_parts - 1];

			return $x_display;
		}

		return $num;
	}
}

if (!function_exists('currency_display')) {
	function currency_display($value = 0, $prefix = '')
	{
		$value = intval($value);
		return $prefix . number_format($value, 0, 0, '.');
	}
}

if (!function_exists('privacy_suffix')) {
	function privacy_suffix($type = '', $country = '')
	{
		$CI = &get_instance();
		$country_id = ($country != '') ? $country : $CI->session->userdata('user_country_id');
		$query = $CI->db->select('*')->from('privacy_suffix')->where(array('privacy_suffix_country' => $country_id, 'privacy_suffix_type' => $type))->get()->result_array();

		return $query[0];
	}
}

if (!function_exists('reformatDate')) {
	function reformatDate($date, $from_format = '', $to_format = '')
	{
		if ($date != null && $date != '' && $date != '0000-00-00') {
			$from 	= ($from_format == '' || $from_format == null) ? 'Y-m-d' : $from_format;
			$to 	= ($to_format == '' || $to_format == null) ? 'd-m-Y' : $to_format;
			$date_aux = date_create_from_format($from, $date);
			return date_format($date_aux, $to);
		}
	}
}

if (!function_exists('reformatDateTime')) {
	function reformatDateTime($date, $to_format = 'Y-m-d\TH:i:s\Z')
	{
		if ($date != null && $date != '' && $date != '0000-00-00') {
			$dt = new DateTime($date);
			return $dt->format($to_format);
		}
	}
}

if (!function_exists('findWhere')) {
	function findWhere($_array, $_matching)
	{
		$return = array();
		foreach ($_array as $item) {
			$is_match = true;
			foreach ($_matching as $key => $value) {

				if (is_object($item)) {
					if (!isset($item->$key)) {
						$is_match = false;
						break;
					}
				} else {
					if (!isset($item[$key])) {
						$is_match = false;
						break;
					}
				}

				if (is_object($item)) {
					if ($item->$key != $value) {
						$is_match = false;
						break;
					}
				} else {
					if ($item[$key] != $value) {
						$is_match = false;
						break;
					}
				}
			}

			if ($is_match) {
				array_push($return, $item);
			}
		}
		return $return;
	}
}

if (!function_exists('findRead')) {
	function findRead($_array, $_matching)
	{
		foreach ($_array as $item) {
			$is_match = true;
			foreach ($_matching as $key => $value) {

				if (is_object($item)) {
					if (!isset($item->$key)) {
						$is_match = false;
						break;
					}
				} else {
					if (!isset($item[$key])) {
						$is_match = false;
						break;
					}
				}

				if (is_object($item)) {
					if ($item->$key != $value) {
						$is_match = false;
						break;
					}
				} else {
					if ($item[$key] != $value) {
						$is_match = false;
						break;
					}
				}
			}

			if ($is_match) {
				return $item;
			}
		}
		return false;
	}
}

/*if (! function_exists('time_since')) {
	function time_since( $datetime , $lang='id')
    {
    	$config_lang = [
    		'y' => $lang=='id' ? 'Tahun' : 'Year',
    		'm' => $lang=='id' ? 'Bulan' : 'Month',
    		'd' => $lang=='id' ? 'Hari'  : 'Day',
    		'h' => $lang=='id' ? 'Jam'   : 'Hour',
    		'i' => $lang=='id' ? 'Menit' : 'Minute',
    		's' => $lang=='id' ? 'Detik' : 'Second',
    		'a' => $lang=='id' ? 'Lalu'  : 'Ago',
    	];
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? ' '.$config_lang['a'] : '' );
        if ( $v = $interval->y >= 1 ) return pluralize( $interval->y, $config_lang['y'], $lang ) . $suffix;
        if ( $v = $interval->m >= 1 ) return pluralize( $interval->m, $config_lang['m'], $lang ) . $suffix;
        if ( $v = $interval->d >= 1 ) return pluralize( $interval->d, $config_lang['d'], $lang ) . $suffix;
        if ( $v = $interval->h >= 1 ) return pluralize( $interval->h, $config_lang['h'], $lang ) . $suffix;
        if ( $v = $interval->i >= 1 ) return pluralize( $interval->i, $config_lang['i'], $lang ) . $suffix;
        return pluralize( $interval->s, $config_lang['a'] ) . $suffix;
    }
    function pluralize( $count, $text, $lang='id' )
    {
    	$plus = $lang=='id' ? '' : 's';
        return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}".$plus ) );
    }
}*/


if (!function_exists('addRound')) {
	function addRound($value, $count = null, $type = null)
	{
		if ($value != null || $value != "") {
			$a = ($count == null) ? 2 : $count;
			$b = ($type == null) ? PHP_ROUND_HALF_UP : $type;
			return round($value, $a, $b);
		} else {
			return false;
		}
	}
}

if (!function_exists('nullConverter')) {
	function nullConverter($val, $xval = null)
	{
		$retval = $val;
		if ($val === null || $val === '') {
			$retval = ($xval != null) ? $xval : '-';
		}
		return $retval;
	}
}

if (!function_exists('parseFloat')) {
	function parseFloat($value)
	{
		return floatval(preg_replace('#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,3}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.\\4'", $value));
	}
}

if (!function_exists('protect_email')) {
	function protect_email($value)
	{
		$email = '';
		$email_exp = explode('@', $value);
		$email_front = str_pad(substr($email_exp[0], 0, 2), strlen($email_exp[0]), "*", STR_PAD_RIGHT);
		$email_exp_end = explode('.', $email_exp[1]);
		$email_end = str_pad(substr($email_exp_end[0], 0, 2), strlen($email_exp_end[0]), "*", STR_PAD_RIGHT);
		$email = $email_front . '@' . $email_end . '.' . $email_exp_end[1];
		return $email;
	}
}


if (!function_exists('phpChgDate')) {
	function phpChgDate($tgl)
	{
		if ($tgl != null && $tgl != "" && $tgl != "0000-00-00") {
			$spliting = explode("-", $tgl);
			$bha = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
			$tanggal = $spliting[2];
			$tahun = $spliting[0];
			$ba = $spliting[1];
			$bh = "";
			foreach ($bha as $key => $value) {
				if ($ba == $key || $ba == "0" . $key) {
					$bh = $value;
				}
			}
			return $tanggal . " " . $bh . " " . $tahun;
		}
	}
}

if (!function_exists('namaHari')) {
	function namaHari($day = '')
	{
		$days = array(
			'Monday' 	=> 'Senin',
			'Tuesday' 	=> 'Selasa',
			'Wednesday' => 'Rabu',
			'Thursday' 	=> 'Kamis',
			'Friday' 	=> 'Jumat',
			'Saturday' 	=> 'Sabtu',
			'Sunday' 	=> 'Minggu'
		);
		return $days[$day];
	}
}

if (!function_exists('logToText')) {
	function logToText($toText = null)
	{
		$CI = &get_instance();
		$data = json_encode($toText);
		$date = date('d-m-Y');
		$file_path = "./log_activity/log_$date.txt";
		if (file_exists($file_path)) {
			write_file($file_path, $data . "\r\n", 'a');
		} else {
			write_file($file_path, $data . "\r\n", 'a');
		}
	}
}

if (!function_exists('logging')) {
	function ilog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}

		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'insert',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function ulog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'update',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function dlog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'delete',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function lilog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'login',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function lolog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'logout',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function iflog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'info',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function relog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'read',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function slog($msg)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> 'set',
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function custom_log($msg, $tipe)
	{
		ob_start();
		system('ipconfig /all');
		$mycom = ob_get_contents();
		ob_clean();

		$findme = "Physical";
		$pmac 	= strpos($mycom, $findme);
		$_HASIL	= substr($mycom, ($pmac + 36), 17);
		$CI = &get_instance();
		if ($CI->agent->is_browser()) {
			$agent = $CI->agent->browser() . ' ' . $CI->agent->version();
		} else if ($CI->agent->is_robot()) {
			$agent = $CI->agent->robot();
		} else if ($CI->agent->is_mobile()) {
			$agent = $CI->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}
		$data = array(
			'act_id' 			=> gen_uuid('activity'),
			'act_tipe' 			=> $tipe,
			'act_ip'			=> $CI->input->ip_address(),
			'act_date' 			=> get_now(),
			'act_data' 			=> $msg,
			'act_user_id'  		=> $CI->session->userdata('user_id'),
			'act_user_nama'  	=> $CI->session->userdata('user_nama'),
			'act_user_tipe'		=> '',
			'act_user_agent'	=> $agent,
			'act_adress'		=> $_HASIL,
		);
		$a = $CI->db->insert('activity', $data);
		logToText($data);
	}

	function parse_text($data, $hidden = null, $hidall = false, $hidallShow = null)
	{
		if ($hidden != null) {
			if (is_array($hidden)) {
				foreach ($hidden as $key => $value) {
					unset($data[$value]);
				}
			} else {
				unset($data[$hidden]);
			}
		}

		if ($hidall) {
			foreach ($data as $key => $value) {
				if ($key != $hidallShow) {
					unset($data[$key]);
				}
			}
		}

		$_data = "<ol>";
		foreach ($data as $key => $value) {
			// $_data .= "[".$key.":".$value."] ";
			$_data .= "<li>" . ucfirst(str_replace('_', ' ', $key)) . ":" . $value . "</li>";
		}
		$_data .= "</ol>";
		return $_data;
	}

	function check_superadmin()
	{
		$CI = &get_instance();
		$super = '76142822611d0c5adf101e32052c2909';
		$result = false;
		// $hak_akses = $CI->session->userdata('hak_akses_kode');
		// $hak_akses = $CI->session->userdata('user_region');
		$hak_akses = $CI->session->userdata('hak_akses_semua_region');
		if ($hak_akses == "1" || $hak_akses == 1) {
			$result = true;
		}

		return $result;
	}


	if (!function_exists('getAksesKendaraan')) {
		function getAksesKendaraan($hak_akses_id, $query_builder = false)
		{
			$response = array();
			$CI = &get_instance();

			$hak_akses = $CI->db->where('hak_akses_id', $hak_akses_id)->get('hak_akses')->row_array();

			if ($hak_akses) {
				if ($hak_akses['hak_akses_kode'] == "SUP") {
					$hak_akses['hak_akses_kendaraan_status'] = '0;1;2';
				}
				$akses_kendaraan = explode(';', $hak_akses['hak_akses_kendaraan_status']);
				if ($query_builder) {
					$query = '';
					foreach ($akses_kendaraan as $key => $value) {
						$query .= "'" . $value . "',";
					}

					$query = rtrim($query, ',');
					$response = $query;
				} else {
					$response = $akses_kendaraan;
				}
			}

			return $response;
		}
	}


	if (!function_exists('getAdminNotifikasi')) {
		function getAdminNotifikasi($prefix, $region, $array, $fcm = false)
		{

			$CI = &get_instance();
			$string = "";
			// $region = '0b43fbe329bb8f0f9b93d6b01e258ca9';
			// $prefix = 'ServiceSchedule';
			// $array = true;
			$where = [
				'menu_kode' => $prefix . "-Notif",
				'user_status' => 1,
				'(user_region = "' . $region . '" OR user_region = "ALL")' => null,
			];

			$hak_akses = $CI->db->where($where)->get('v_menu_role_notif')->result_array();

			$user_id = array_column($hak_akses, 'user_id');

			if ($fcm) {
				$query = '';
				foreach ($user_id as $key => $value) {
					$query  .= "'" . $value . "',";
				}
				$query = rtrim($query, ',');


				$token = $CI->db->select('user_token_token')->where_in('user_token_user_id', $user_id)->get('user_token')->result_array();

				if ($array) {
					$result = array_column($token, 'user_token_token');

					return $result;
				} else {

					$result = array_column($token, 'user_token_token');

					$string = implode(',', $result);

					return $string;
				}
			} else {
				if ($array) {
					$result = array_column($hak_akses, 'user_email');

					return $result;
				} else {

					$result = array_column($hak_akses, 'user_email');

					$string = implode(',', $result);

					return $string;
				}
			}


			// $user = $this->db->select('user_email')->where(array(
			//     'user_region' => $region,
			//     'user_tipe' => 'Admin',
			//     'user_status' => 1,
			//     // 'user_kategori' => 'Web Admin' 
			// ))->get('v_user')->result_array();


		}
	}


	if (!function_exists('transaksiKartuBbm')) {
		function transaksiKartuBbm($id_kartu = '', $nominal, $tipe, $keterangan = null, $id_transaksi = null)
		{
			$response = [];
			if ($id_kartu != '') {

				$dataHistory = [
					'kartu_bbm_history_id' => gen_uuid('kartu_bbm_history'),
					'kartu_bbm_history_id_transaksi' => $id_transaksi,
					'kartu_bbm_history_kartu_id' => $id_kartu,
					'kartu_bbm_history_tanggal' => date('Y-m-d'),
					'kartu_bbm_history_tambah' => null,
					'kartu_bbm_history_kurang' => null,
					'kartu_bbm_history_keterangan' => $keterangan,
					'kartu_bbm_history_insert_at' => date('Y-m-d H:i:s'),
					'kartu_bbm_history_status' => 1,
				];



				if ($tipe == 'tambah') {
					$dataHistory['kartu_bbm_history_tambah'] = $nominal;
				} else if ($tipe == 'kurang') {
					$dataHistory['kartu_bbm_history_kurang'] = $nominal;
				}

				$CI = &get_instance();

				$CI->db->trans_start();

				$kartu = $CI->db->where([
					'kartu_bbm_id' => $id_kartu,
					'kartu_bbm_status' => 1,
				])->get('kartu_bbm')->row_array();

				if (count($kartu) > 0) {
					if ($nominal > 0) {
						$saldoAkhir = $kartu['kartu_bbm_saldo'];
						$saldoAwal = $saldoAkhir; // sebelum ada pengurangan atau penambahan
						if ($tipe == 'tambah') {
							$saldoAkhir += $nominal;
						} else if ($tipe == 'kurang') {
							$saldoAkhir -= $nominal;
						}

						$dataHistory['kartu_bbm_history_saldo_awal'] = $saldoAwal;
						$dataHistory['kartu_bbm_history_saldo_akhir'] = $saldoAkhir;

						$CI->db->where([
							'kartu_bbm_id' => $id_kartu,
							'kartu_bbm_status' => 1,
						])->update('kartu_bbm', [
							'kartu_bbm_saldo' => $saldoAkhir,
						]);

						$CI->db->insert('kartu_bbm_history', $dataHistory);
					}
				} else {
					$dataHistory['kartu_bbm_history_saldo_awal'] = 0;
					$dataHistory['kartu_bbm_history_saldo_akhir'] = 0;
					$CI->db->insert('kartu_bbm_history', $dataHistory);
				}


				$CI->db->trans_complete();
				if ($CI->db->trans_status() === FALSE) {
					$response = [
						'success' => false,
						'msg' => 'Transaksi Gagal!',
						'err_code' => 1,
						'id_kartu' => $id_kartu
					];

					return $response;
				} else {
					$response = [
						'success' => true,
						'msg' => 'Transaksi Berhasil!',
						'id_kartu' => $id_kartu
					];

					return $response;
				}
			} else {
				$response = [
					'success' => false,
					'msg' => 'ID Kartu kosong!',
					'err_code' => 0,
					'id_kartu' => $id_kartu
				];
				return $response;
			}
		}
	}
}

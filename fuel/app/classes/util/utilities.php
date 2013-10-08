<?php

use Fuel\Core\Cookie;
use Fuel\Core\Config;
class Util_Utilities {

	public static function format_simple_date($date) {
		return date('Y年m月d日', $date);
	}

	public static function format_date($date) {

		$w = array('日', '月', '火', '水', '木', '金', '土');
		$result = date('Y年m月d日', $date) . '(' . $w[date('w', $date)] . ')';

		return $result;
	}

	public static function format_date_hour($date) {

		$w = array('日', '月', '火', '水', '木', '金', '土');
		$result = date('Y年m月d日', $date) . '(' . $w[date('w', $date)] . ') ' . date('H:i', $date);

		return $result;
	}

	public static function format_date_hour_string($date) {

		if ($date==null) return "";
		$w = array('日', '月', '火', '水', '木', '金', '土');
		$result = date('Y年m月d日', strtotime($date)) . '(' . $w[date('w', strtotime($date))] . ') ' . date('H:i', strtotime($date));

		return $result;
	}

	public static function format_date_string($date) {

		if ($date==null) return "";
		$w = array('日', '月', '火', '水', '木', '金', '土');
		$result = date('Y年m月d日', strtotime($date)) . '(' . $w[date('w', strtotime($date))] . ') ';

		return $result;
	}

	public static function format_day_string($date) {

		if ($date==null) return "";
		$w = array('日', '月', '火', '水', '木', '金', '土');
		$result = date('m/d', strtotime($date)) . '(' . $w[date('w', strtotime($date))] . ') ';

		return $result;
	}

	public static function format_date_hour_en($date) {

		if ($date==null) return "";
		$result = date('Y-m-d H:i',  strtotime($date));

		return $result;
	}

	public static function format_full_date_en($date) {

		if ($date==null) return "";
		$result = date('Y-m-d H:s',  strtotime($date));

		return $result;
	}

	public static function format_date_en($date) {

		if ($date==null) return "";
		$result = date('Y-m-d',  strtotime($date));

		return $result;
	}

	public static function string_escape($value) {

		if (!isset($value))
			return $value;

		$str_value = \DB::escape($value);
		$str_value = substr($str_value, 1);
		$str_value = substr($str_value, 0, -1);

		return $str_value;
	}

	public static function redis_gen_key(){
		return time().''.rand(1500,2500).''.rand(1000,2000);
	}
	
	public static function parse_object_array($obj) {

		$result = array();
		foreach ($obj as $i => $item) {
			$result[$i] = $item->to_array();
		}
		return $result;
	}

	public static function search($array, $key, $value) {
		$results = array();

		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value)
				$results[] = $array;

			foreach ($array as $subarray)
				$results = array_merge($results, Utilities::search($subarray, $key, $value));
		}

		return $results;
		//return array_filter($array, array(new FilterByValue($key,$value),"isFilter"));
	}

	public static function search_multi($array, $key1, $value1,$key2, $value2) {
		$results = array();

		if (is_array($array)) {
			if (isset($array[$key1]) && $array[$key1] == $value1 && isset($array[$key2]) && $array[$key2] == $value2)
				$results[] = $array;

			foreach ($array as $subarray)
				$results = array_merge($results, Utilities::search_multi($subarray, $key1, $value1,$key2, $value2));
		}

		return $results;
		//return array_filter($array, array(new FilterByValue($key,$value),"isFilter"));
	}

	//send mail
	public static function send_mail($to, $subject, $body, $is_html_body = false, $cc = array()) {

		$mail = \Email\Email::forge();

		$from = $mail->get_config('from.email');
		$admin_name = $mail->get_config('from.name');
		$mail->from($from, $admin_name);

		$mail->to($to, '');
		if (count($cc) > 0)
			$mail->cc($cc);
		$mail->subject($subject);
		if ($is_html_body) {
			$mail->html_body($body);
		} else {
			$mail->body($body);
		}

		//try {
			$mail->send();
			return true;
		/*} catch (\Fuel\Core\FuelException $e) {
			\Log::error($e->getTraceAsString());
			return false;
		}*/
	}

	public static function format_phone($phone) {

		if (!isset($phone))
			return "";
		$phone = preg_replace("/[^0-9]/", "", $phone);

		if (strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
		elseif (strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
		else
			return $phone;
	}

	public static function check_image_event($company_id, $image_file) {
		$result = false;

		try {
			if (is_null($image_file) or is_null($company_id) or $image_file == '')
				return $result;
			$path_file = DOCROOT . \Config::get('photo_info_path') . $company_id . '/' . $image_file;
			if (file_exists($path_file) === TRUE) {
				$result = true;
			}
		} catch (Exception $e) {
			Log::error($e->getTraceAsString());
		}
		return $result;
	}

	public static function show_text($text) {

		if (!isset($text) or is_null($text))
			return "&nbsp;";
		$order = array("\r\n", "\n", "\r");
		$replace = '<br />';
		$result = str_replace($order, $replace, $text);
		return $result;
	}

	public static function get_url_query_string($arr_para) {
		$query_string = '';
		if (count($arr_para) > 0) {
			$query_string .= '?';
			foreach ($arr_para as $key => $value) {
				$query_string .= $key . '=' . $value . '&';
			}
		}

		return $query_string;
	}

	public static function get_name_by_key($arr, $key) {
		$name ="";
		if (array_key_exists($key, $arr)) {
			$name = $arr[$key];
		}

		return $name;
	}

	/*
	 * clean input data
	*/
	public static function cleanData($data) {
		$data = \Fuel\Core\Security::xss_clean($data);
		$data = \Fuel\Core\Security::htmlentities($data);
		return $data;
	}

	/*
	 * Get day between two dates
	*/
	public static function getDiffDate($date1, $date2){
		$date2 = strtotime($date2);
		$date1 = strtotime($date1);
		$datediff = $date2 - $date1;
		return floor($datediff/(60*60*24));
	}

	/*
	 * Check number
	*/
	public static function checkIsNumber($s){
		if(preg_match('/^[0-9]+$/',$s))
		{
			return TRUE;
		}
		return FALSE;
	}

	//sort by subkey
	public static function sksort(&$array, $subkey="id", $sort_descending=false) {
		$temp_array = $array;

		$sort = array();
		foreach($temp_array as $index => &$value){
			$sort[$index] = $value[$subkey];
		}

		if($sort_descending){
			arsort($sort);
		}else{
			asort($sort);
		}
		$keys = array_keys($sort);
		$newValue = array();
		foreach ($keys as $index) {
			$newValue[$index] = $array[$index];
		}
		$array = $newValue;
	}

	public static function checkUserRemember(){
		try{
			$remember = \Cookie::get(VINCA_REMEMBER_COOKIE,'');
			$arr = explode('_', \Crypt::decode($remember));
			if(count($arr)==3){
				list($login_session_id, $user_agent) = explode(':', $arr[1]);
				if($user_agent !== md5(\Input::user_agent())){
					return false;
				}
				$user = \Model_User_Student::is_exist(array('login_session_id' => $login_session_id,'id' => $arr[2], 'delete_date' => '19000101000000'));
				if ($user and count($user) > 0) {
					\Session::set(VINCA_SESSION_STUDENT, $user[0]);
					//save log
					\Model_User_Log::update_user_log($user[0]['id']);
					return $user[0];
				}
			}
		} catch(Exception $e) {
			return false;
		}
		return false;
	}

	public static function formatUniByInitial($list, $group_key, $id, $name) {
		$rs = array();
		if (!is_array($list) or count($list) == 0) {
			return $rs;
		}
		$temp_key = $list[0][$group_key];
		$job = array();
		foreach ($list as $item) {
			if ($temp_key == $item[$group_key]) {
				$job[$item[$id]] = $item[$name];
			} else {
				$rs[$temp_key] = $job;
				$temp_key = $item[$group_key];
				$job = array();
				$job[$item[$id]] = $item[$name];
			}
		}
		$rs[$temp_key] = $job;

		return $rs;
	}

	public static function faved($id) {
		$str = Cookie::get(FAVORITE_COOKIE_USER, '');
		if ($str == '') {
			return false;
		}
		else {
			$arr = unserialize($str);		
			$keys = array_keys($arr);
			for($i=0;$i<count($keys);$i++){
				if($arr[$keys[$i]] == $id){
					return true;
				}
			}			
			return false;
		}
	}
	
	public static function removeFav($id) {
		$str = Cookie::get(FAVORITE_COOKIE_USER, '');
		if ($str == '') {
			return false;
		}
		else {
			$arr = unserialize($str);
			$keys = array_keys($arr);
			$j=-1;
			for($i=0;$i<count($keys);$i++){
				if($arr[$keys[$i]] == $id){
					$j = $keys[$i];
					break;
				}
			}
			if($j >= 0){
				unset($arr[$j]);				
				Cookie::set(FAVORITE_COOKIE_USER, serialize($arr), FAVORITE_COOKIE_TIME);
				return true;
			}
			return false;
		}
	}
	
	public static function addFav($id) {
		$str = Cookie::get(FAVORITE_COOKIE_USER, '');
		if ($str == '') {
			Cookie::set(FAVORITE_COOKIE_USER, serialize(array($id)), FAVORITE_COOKIE_TIME);
		}
		else {
			$arr = unserialize($str);
			if (!in_array($id, $arr)) {
				array_unshift($arr, $id);
				Cookie::set(FAVORITE_COOKIE_USER, serialize($arr), FAVORITE_COOKIE_TIME);
			}
		}
	}
	
	public static function resetCookieView($newvalue, $name_cookie, $time_cookie) {
		Cookie::delete($name_cookie);
		Cookie::set($name_cookie, $newvalue, $time_cookie);
	}
	
	public static function setCookieView($id, $name_cookie, $time_cookie) {
		$str = Cookie::get($name_cookie, '');
		if ($str == '') {
			Cookie::set($name_cookie, serialize(array($id=>time())), $time_cookie);
		}
		else {
			$arr = unserialize($str);
			$ids = array_keys($arr);
			if (!in_array($id, $ids)) {
				$arr = array($id=>time()) + $arr;
				Cookie::set($name_cookie, serialize($arr), $time_cookie);
			}
		}
	}

	public static function getCookieView($name_cookie) {
		return Cookie::get($name_cookie, '');
	}
}

class FilterByValue {

	private $key;
	private $value;

	function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}

	function isFilter($var) {
		return (is_array($var) && $var[$this->key] == $this->value);
	}

}

?>

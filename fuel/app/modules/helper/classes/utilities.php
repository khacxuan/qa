<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Helper;

class Utilities {

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

	public static function string_escape($value) {

		if (!isset($value))
			return $value;

		$str_value = \DB::escape($value);
		$str_value = substr($str_value, 1);
		$str_value = substr($str_value, 0, -1);

		return $str_value;
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

	//send mail 
	public static function send_mail($to, $from, $subject, $body, $admin_name = null, $cc = array(), $is_html_body = false) {

		$mail = \Email\Email::forge();

		//$mail->from($from, $admin_name);
		$mail->to($to, '');
		if (count($cc) > 0)
			$mail->cc($cc);
		$mail->subject($subject);
		if ($is_html_body) {
			$mail->html_body($body);
		} else {
			$mail->body($body);
		}

		try {
			$mail->send();
			return true;
		} catch (\Fuel\Core\FuelException $e) {
			\Log::error($e->getTraceAsString());
			return false;
		}
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

	public static function sendmail_contact($user) {
		$body = \Fuel\Core\View::forge('mail/mail_contact_user', $user->to_array());
		Utilities::send_mail(\Config::get('smart_admin_mail'), "", \Fuel\Core\Config::get('msg_contact_mail_subject'), $body);
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

<?php

use Fuel\Core\Validation;

class Util_Validation {
	/*
	 * 全角カタカナ
	 *  */

	public static function _validation_chkKatakana($param) {

		// 空白を削除
		$param = str_replace("　", "", $param);
		$param = str_replace(" ", "", $param);

		mb_regex_encoding("UTF-8");
		Validation::active()->set_message('chkKatakana', \Config::get('myvalidation_katakana'));
		if (!preg_match("/^[ァ-ヶー]+$/u", $param)) {
			// 全角カタカナ以外が含まれている
			return false;
		} else {
			return true;
		}
	}

	/*
	 * 全角ひらがな
	 * */

	public static function _validation_chkHiragana($param) {

		// 空白を削除
		$param = str_replace("　", "", $param);
		$param = str_replace(" ", "", $param);

		mb_regex_encoding("UTF-8");
		Validation::active()->set_message('chkHiragana', \Config::get('myvalidation_hiragana'));
		if (!preg_match("/^[ぁ-ゞ]*$/u", $param)) {
			// 全角カタカナ以外が含まれている
			return false;
		} else {
			return true;
		}
	}

	public static function _validation_chkRomaji($param) {
		mb_regex_encoding("UTF-8");
		Validation::active()->set_message('chkRomaji', \Config::get('myvalidation_romaji'));
		if (preg_match("/^[\w\d\s.,-]*$/", $param)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * valid_date
	 * @param $str
	 * @param $format
	 */
	public static function _validation_chkDate($str, $format = 'yyyy/mm/dd') {
		switch ($format) {
			case 'dd/mm/yyyy' :
				if (preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/|-](0?[1-9]|1[012])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[2], $match[1], $match[3])) {
					return TRUE;
				}
				break;
			case 'mm/dd/yyyy' :
				if (preg_match("/^(0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[1], $match[2], $match[3])) {
					return TRUE;
				}
				break;
			default :
				// 'yyyy/mm/dd'
				if (preg_match("/^(19\d\d|2\d\d\d)[\/|-](0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])$/", $str, $match) && checkdate($match[2], $match[3], $match[1])) {
					return TRUE;
				}
				break;
		}

		Validation::active()->set_message('chkDate', \Config::get('myvalidation_date'));

		return FALSE;
	}

	public static function _validation_chkDateOfEmpty($str, $format = 'yyyy/mm/dd') {
		if (empty($str)) return TRUE;
		switch ($format) {
			case 'dd/mm/yyyy' :
				if (preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/|-](0?[1-9]|1[012])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[2], $match[1], $match[3])) {
					return TRUE;
				}
				break;
			case 'mm/dd/yyyy' :
				if (preg_match("/^(0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[1], $match[2], $match[3])) {
					return TRUE;
				}
				break;
			default :
				// 'yyyy/mm/dd'
				if (preg_match("/^(19\d\d|2\d\d\d)[\/|-](0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])$/", $str, $match) && checkdate($match[2], $match[3], $match[1])) {
					return TRUE;
				}
				break;
		}

		Validation::active()->set_message('chkDateOfEmpty', \Config::get('myvalidation_date'));

		return FALSE;
	}

	public static function _validation_chkWithOtherDate($str_from, $str_to, $field_name) {
		$from = new \DateTime($str_from);
		$to = new \DateTime($str_to);
		if ($from > $to) {
			return true;
		}

		Validation::active()->set_message('chkWithOtherDate', \Config::get('valid_with_other_date'));

		return FALSE;
	}

	public static function _validation_chkWithOtherLessDate($str_from, $str_to, $field_name) {
		$from = new \DateTime($str_from);
		$to = new \DateTime($str_to);
		if ($from < $to) {
			return true;
		}

		Validation::active()->set_message('chkWithOtherLessDate', \Config::get('valid_with_other_date_less'));

		return FALSE;
	}

	public static function _validation_chkFullDate($str, $format = 'yyyy/mm/dd H:i') {
		switch ($format) {
			case 'dd/mm/yyyy':
				if (preg_match("/^(0?[1-9]|[12][0-9]|3[01])[\/|-](0?[1-9]|1[012])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[2], $match[1], $match[3])) {
					return TRUE;
				}
				break;
			case 'mm/dd/yyyy':
				if (preg_match("/^(0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])[\/|-](19\d\d|2\d\d\d)$/", $str, $match) && checkdate($match[1], $match[2], $match[3])) {
					return TRUE;
				}
				break;
			default: // 'yyyy/mm/dd'
				$match = array();
				$pos = strpos($str, " ");
				if ($pos > 0) {
					if (preg_match("/^(19\d\d|2\d\d\d)[\/|-](0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])[\s](0?[0-9]|[1][0-9]|2[03])[:](0?[0-9]|[1-5][0-9])([:](0?[0-9]|[1-5][0-9]))?$/", $str, $match) && checkdate($match[2], $match[3], $match[1])) {
						return TRUE;
					}
				} else {
					if (preg_match("/^(19\d\d|2\d\d\d)[\/|-](0?[1-9]|1[012])[\/|-](0?[1-9]|[12][0-9]|3[01])$/", $str, $match) && checkdate($match[2], $match[3], $match[1])) {
						return TRUE;
					}
				}
				break;
		}

		Validation::active()->set_message('chkFullDate', \Config::get('valid_date'));

		return FALSE;
	}

	public static function _validation_chkValidPostcode($param) {
		if (preg_match("/^(\d\d\d)[-](\d\d\d\d)*$/", $param)) {
			return true;
		} else {
			return false;
		}

		Validation::active()->set_message('chkValidPostcode', \Config::get('myvalidation_postcode'));

		return FALSE;
	}

	public static function _validation_chktel($param) {
		$pattern = "/^[0-9]+[0-9]*$/";
		$param = str_replace("-", '', $param);
		if (preg_match($pattern, $param)) {
			return true;
		}
		Validation::active()->set_message('chktel', \Config::get('myvalidation_tel'));
		return FALSE;
	}

	/**
	 *
	 */
	public static function _validation_unique($val) {

		$result = Model_User_User::checkUserExists($val);
		Validation::active()->set_message('unique', \Config::get('myvalidation_unique'));

		return !(count($result) > 0);
	}

	/**
	 *
	 */
	public static function _validation_uniqueemail($val) {
		//get login session
		$user = Session::get(SESSION_QA_USER, null);
		if ($user == null) {//not login
			Validation::active()->set_message('unique', \Config::get('myvalidation_unique'));
			return FALSE;
		}
		$result = Model_User_User::checkEmailExists($val, $user['_id']);
		if ($result === TRUE) {
			return TRUE;
		}
		Validation::active()->set_message('unique', \Config::get('myvalidation_unique'));

		return !(count($result) > 0);
	}

	public static function _validation_exist($val, $options) {
		list($table, $field) = explode('.', $options);

		$result = \DB::select("LOWER (\"$field\")")->where($field, '=', \Str::lower($val))->from($table)->execute();

		Validation::active()->set_message('exist', \Config::get('myvalidation_exist'));

		return $result->count() > 0;
	}

	/**
	 * valid_time H:i
	 * @param $str
	 */
	public static function _validation_chktime($str) {

		if (preg_match("/^([01][0-9]|2[0-3]):([0-5][0-9])$/", $str, $matches)) {
			return TRUE;
		}

		Validation::active()->set_message('chktime', \Config::get('myvalidation_time'));

		return FALSE;
	}

	public static function _validation_chkWithOtherTime($str_from, $str_to, $field_name) {

		$date_org = date('Y-m-d',mktime(0, 0, 0, 1, 1, 1900));
		$from=date('Y-m-d H:i:s',  strtotime($date_org." ". $str_from.":00"));
		$to=date('Y-m-d H:i:s',  strtotime($date_org." ". $str_to.":00"));

		if ($from > $to) {
			return true;
		}

		Validation::active()->set_message('chkWithOtherTime', \Config::get('valid_with_other_time'));

		return FALSE;
	}

}

<?php
use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;
class Model_Admin_User {

	/*
	 * return Array()
	 */
	public static function checkUserExists($username = '', $password = '', $checkpass = FALSE){
		$condition = array();
		$condition['username'] = $username;
		if ($checkpass == TRUE) {
			$condition['password'] = Crypt::encode($password);
			//$flag_social = Config::get('flag_social');
			//$condition['flag'] = $flag_social['none'];
		}
		$mongodb = Mongo_Db::instance();
		$mongodb->where($condition);
		$users = $mongodb->get('admin');
		return $users;
	}
}
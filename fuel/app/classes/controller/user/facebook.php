<?php
use Fuel\Core\Config;
require_once APPPATH . 'vendor/facebook/facebook.php';
define('FACEBOOK_PERMISSIONS', 'user_birthday,email,publish_stream');

class Controller_User_Facebook extends Controller {

	private $_facebook = NULL;

	public function action_index() {
		$config = \Config::load('facebook', true);
		$this -> _facebook = new Facebook($config);
		$userId = $this -> _facebook -> getUser();


		//facebookがログインしている
		if ($userId) {
			$userInfo = $this -> _facebook -> api('/' . $userId);
			$user = $this->firstRegister($userInfo);
			$url = Input::get('url','');
			if (empty($url)) {
				Response::redirect('');
			} else {
				$re_url = base64_decode($url);
				Response::redirect($re_url);
			}
		} else {
			$err = Input::get('error_code');
			if (!empty($err)) {
				Response::redirect('');
			}

			$url = Uri::current();

			$arr_para = Input::get();
			if (count($arr_para) > 0) {
				$url .= '?';
				foreach ($arr_para as $key => $value) {
					$url .= $key . '=' . $value . '&';
				}
			}

			$params = array('scope' => FACEBOOK_PERMISSIONS,'redirect_uri'=>$url);
			$loginUrl = $this -> _facebook -> getLoginUrl($params);
			Response::redirect($loginUrl);
		}
	}

	/**
	 * 招待を受ける
	 */
	private function firstRegister($userInfo) {
		//ログインしているかの確認
		$user = Session::get(SESSION_QA_USER);
		if (((!isset($user)))) {
		} else {
			Response::redirect('user/list');
		}
		$flag_social = Config::get('flag_social');
		$users = Model_User_User::is_exist(array('id' => $userInfo['id'], 'flag' => $flag_social['facebook']));

		$access_token = $this -> _facebook -> getAccessToken();
		//ユーザーが存在しない
		if (empty($users) or count($users) <= 0) {
			//日本語の名前を取得する
			$fql = 'SELECT name from user where uid = ' . $userInfo['id'];
			$ret_obj = $this -> _facebook->api(array(
												'method' => 'fql.query',
												'query' => $fql,
											));
			$time = time();

			$userfb = array(
				'username' => $userInfo['username'],
				'email' => $userInfo['email'],
				'flag' => $flag_social['facebook'],
				'id' => $userInfo['id'],
				'name' => (isset($ret_obj[0]['name']) ? $ret_obj[0]['name'] : ''),
				'token' => $access_token,
				'password' => '',
				'created_at' => $time,
				'updated_at' => $time,
			);
			//Facebook連携データはDBにInsert
			$user = Model_User_User::insertUserFB($userfb);

			if ($user == FALSE) {
				Response::redirect('user/login');
			}
			$users = Model_User_User::is_exist(array('_id' => $user, 'flag' => $flag_social['facebook']));
		} else {
			$entry = Model_User_User::updateFB(array('_id' => $users[0]['_id']), array('token' => $access_token));
			if ($entry == FALSE) {
				Response::redirect('user/login');
			}
		}
		Session::set(SESSION_QA_USER, $users[0]);
	}


}

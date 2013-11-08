<?php
use Fuel\Core\Config;
use Fuel\Core\Input;
use Fuel\Core\Response;

class Controller_User_Github extends Controller {

	private $_github = NULL;

	public function action_index() {
		$config = \Config::load('github', true);
		$access_token = Input::get('access_token', '');
		$code = Input::get('code', '');
		$user = null;
		if(empty($code)) {
			Session::set('state', hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']));
			$params = array(
					'client_id' => $config['client_id'],
					'scope' => 'user',
					'state' => Session::get('state')
			);
			Response::redirect('https://github.com/login/oauth/authorize?'.http_build_query($params));
		}
		else if (!empty($code)){
			$state = Input::get('state', '');
			if(empty($state) || Session::get('state') != $state) {
				Response::redirect('user/login');
			}

			// Exchange the auth code for a token
			$token = $this->apiRequest('https://github.com/login/oauth/access_token', array(
					'client_id' => $config['client_id'],
					'client_secret' => $config['client_secret'],
					'state' => Session::get('state'),
					'code' => $code
			));
			$user = $this->apiRequest('https://api.github.com/user', FALSE, $token->access_token);
			Session::delete('state');
			$user_profile = Session::get('profilepage', null);
			if ($user_profile == null) {//not from profile page
				$this->firstRegister($user);
			} else {
				Session::delete('profilepage');
				$this->changeProfile($user);
			}
		}
		else {
			Response::redirect('user/login');
		}
	}

	/**
	 * 招待を受ける
	 */
	private function firstRegister($userInfo) {
		$user = Session::get(SESSION_QA_USER);
		if (!isset($user)) {
		} else {
			Response::redirect('user/list');
		}
		$flag_social = Config::get('flag_social');
		$users = Model_User_User::is_exist(array('id_github' => ''.$userInfo->id));

		if (empty($users) or count($users) <= 0) {

			$time = time();
			$usergh = array(
				//'username' => $userInfo->login,
				'email' => $userInfo->email,
				'id_facebook' => '',
				'token_facebook' => '',
				'id_github' => ''.$userInfo->id,
				'token_github' => '',
				'id_twitter' => '',
				'token_twitter' => '',
				'name' => isset($userInfo->name) ? $userInfo->name : '',
				'password' => '',
				'created_at' => $time,
				'updated_at' => $time,
			);

			$user = Model_User_User::insertUserFB($usergh);

			if ($user == FALSE) {
				Response::redirect('user/login');
			}
			$users = Model_User_User::is_exist(array('_id' => $user));
		}
		else if(count($users) > 0 and isset($users[0]['banned'])){
			if ($users[0]['banned'] == 1) {
				Response::redirect('user/login?error=1');
			}
		}
		Session::set(SESSION_QA_USER, $users[0]);
		Response::redirect('user/list');
	}

	private function changeProfile($userInfo) {

		$users = Model_User_User::is_exist(array('id_github' => ''.$userInfo->id));

		if (empty($users) or count($users) <= 0) {

			$time = time();

			$usergh = array(
					'id_github' => ''.$userInfo->id,
					'token_github' => '',
					'updated_at' => $time,
			);

			//get login session
			$usersession = Session::get(SESSION_QA_USER, null);
			if ($usersession == null) {//not login
				Response::redirect('user/login');
			}
			if (empty($usersession['email'])) {
				$usergh['email'] = $userInfo->email;
			}
			$user = Model_User_User::updateUserSocial($usersession['_id'], $usergh);
			if ($user == FALSE) {
				Response::redirect('user/login');
			}
			Response::redirect('user/profile');
		} else {
			Response::redirect('user/profile?error=1&type=Github');
		}
	}

	function apiRequest($url, $post = FALSE, $session = '',$headers = array()) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		if($post) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		$headers[] = 'Accept: application/json';

		if(!empty($session)) {
			$headers[] = 'Authorization: Bearer ' . $session;
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		return json_decode($response);
	}
}

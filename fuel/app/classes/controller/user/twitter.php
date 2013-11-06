<?php
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Uri;
require_once APPPATH . 'vendor/twitter/codebird.php';

class Controller_User_Twitter extends Controller {

	private $_twitter = NULL;

	public function action_index() {
		$config = \Config::load('twitter', true);
		\Codebird\Codebird::setConsumerKey($config['consumer_key'], $config['consumer_secret']);
		$this -> _twitter = \Codebird\Codebird::getInstance();
		$oauth_token = Session::get('oauth_token', '');
		$oauth_verifier = Input::get('oauth_verifier', '');
		$oauth_verify = Session::get('oauth_verify', '');
		$userId = null;
		if (empty($oauth_token)) {
			// get the request token
			$reply = $this -> _twitter -> oauth_requestToken(array(
					'oauth_callback' => Uri::create('user/twitter')
			));

			// store the token
			$this -> _twitter -> setToken($reply->oauth_token, $reply->oauth_token_secret);
			Session::set('oauth_token', $reply->oauth_token);
			Session::set('oauth_token_secret', $reply->oauth_token_secret);
			Session::set('oauth_verify', true);

			// redirect to auth website
			$auth_url = $this -> _twitter -> oauth_authorize();
			Response::redirect($auth_url);

		} elseif (!empty($oauth_verifier) && !empty($oauth_verify)) {
			// verify the token
			$this -> _twitter -> setToken(Session::get('oauth_token'), Session::get('oauth_token_secret'));
			Session::delete('oauth_verify');

			// get the access token
			$userId = $this -> _twitter -> oauth_accessToken(array(
					'oauth_verifier' => $oauth_verifier
			));

			// store the token (which is different from the request token!)
			Session::set('oauth_token', $userId->oauth_token);
			Session::set('oauth_token_secret', $userId->oauth_token_secret);
		}
		else {
			Session::destroy();
			Response::redirect('user/login');
		}
		if ($userId == null && $userId->httpstatus != 200) {
			Session::destroy();
			Response::redirect('user/login');
		}

		$this -> _twitter -> setToken(Session::get('oauth_token'), Session::get('oauth_token_secret'));

		$this->firstRegister($userId);
	}

	private function firstRegister($userInfo) {

		$user = Session::get(SESSION_QA_USER);
		if (!isset($user)) {
		} else {
			Session::destroy();
			Response::redirect('user/list');
		}
		$flag_social = Config::get('flag_social');

		$users = Model_User_User::is_exist(array('id' => $userInfo->user_id, 'flag' => $flag_social['twitter']));
		if (empty($users) or count($users) <= 0) {
			$userT = $this -> _twitter -> account_verifyCredentials();
			Session::destroy();
			$time = time();
			$usertwitter = array(
				'username' => $userT->screen_name,
				'email' => '',
				'flag' => $flag_social['twitter'],
				'id' => $userT->id_str,
				'name' => isset($userT->name) ? $userT->name : '',
				'token' => '',
				'password' => '',
				'created_at' => $time,
				'updated_at' => $time,
			);

			$user = Model_User_User::insertUserFB($usertwitter);

			if ($user == FALSE) {
				Response::redirect('user/login');
			}
			$users = Model_User_User::is_exist(array('_id' => $user, 'flag' => $flag_social['twitter']));
		}
		else if(count($users) > 0 and isset($users[0]['banned'])){
			if ($users[0]['banned'] == 1) {
				Session::destroy();
				Response::redirect('user/login?error=1');
			}
		}
		Session::set(SESSION_QA_USER, $users[0]);
		Response::redirect('user/list');
	}


}

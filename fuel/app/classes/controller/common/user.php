<?php
class Controller_Common_User extends Controller_Template {

	public $template = 'template/user';
	protected $user_id = -1;

	public function before() {
		parent::before();
		
		//get parameter
		$uri_string = Uri::string();
		$arr_para = Input::get();
		if (count($arr_para) > 0) {
			$uri_string .= '?';
			foreach ($arr_para as $key => $value) {
				$uri_string .= $key . '=' . $value . '&';
			}
		}
		//redirect uri
		$uri = base64_encode($uri_string);
		
		//get login session
		$user = Session::get(SESSION_QA_USER, null);
		if ($user == null) {//not login
			$uri_str = Uri::string();
			if (!empty($uri_str) and strpos($uri_str, 'user/login') === false and strpos($uri_str, 'user/register') === false) {
				Response::redirect('user/login?url=' . $uri);
			}
		} else {//
			$this -> user_id = $user;
		}
	}
}
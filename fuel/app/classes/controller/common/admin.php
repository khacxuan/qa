<?php
class Controller_Common_Admin extends Controller_Template {

	public $template = 'template/admin';
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
		$user = Session::get(SESSION_QA_ADMIN, null);
		if ($user == null) {//not login
			$uri_str = Uri::string();
			if (!empty($uri_str) and strpos($uri_str, 'admin/login') === false and strpos($uri_str, 'admin/register') === false) {
				Response::redirect('admin/login?url=' . $uri);
			}
		} else {//
			$this -> user_id = $user;
		}
	}
}
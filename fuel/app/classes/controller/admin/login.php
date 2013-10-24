<?php
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Validation;
use Fuel\Core\Session;
class Controller_Admin_Login extends Controller_Common_Admin {
	public function action_index() {
		$url_redirect = Input::param('url', '');
		$user = Session::get(SESSION_QA_ADMIN, null);
		if(isset($user)){
			Response::redirect('admin/list');
		}
		$data = array();
		$data['username'] = Input::post('username', '');
		$data['password'] = Input::post('password', '');

		$confirm = Input::post('confirm', '');
		$this->template->js_file = array('jquery.validate.min.js', 'localization/messages_jp.js');
		if (Input::method() == 'POST') {
			if ($confirm != '') {
				$val = Validation::forge();
				$val->add_callable('Util_Validation');
				$val->add_field('username', 'Username', 'required|trim');
				$val->add_field('password', 'Password', 'required|trim');
				if ($val->run()) {
					$mongo_user = Model_Admin_User::checkUserExists($data['username'], $data['password'], TRUE);
					if (count($mongo_user) > 0) {
						Session::set(SESSION_QA_ADMIN, $mongo_user[0]);
						//redirect URL
						if ($url_redirect != '') {
							Response::redirect(base64_decode($url_redirect));
						}
						Response::redirect('admin/list');
					}
					else {
						$data['err'] = Config::get('err_not_login');
					}
				}
				else {
					$data['error'] = $val->error();
				}
			}
		}
		$this->template->content = View::forge('admin/login/index', $data);
	}

	public function action_success() {
	}
}
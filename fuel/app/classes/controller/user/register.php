<?php
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Validation;
use Fuel\Core\Config;
class Controller_User_Register extends Controller_Common_User {
	public function action_index() {
		$data = array();
		$data['email'] = Input::post('email', '');
		$data['password'] = Input::post('password', '');
		$data['name'] = Input::post('name', '');
		$data['confirmpassword'] = Input::post('confirmpassword', '');

		$confirm = Input::post('confirm', '');
		$this->template->js_file = array('jquery.validate.min.js', 'localization/messages_jp.js');
		$this->template->script_file = 'user/register/jsfile';
		if (Input::method() == 'POST') {
			if ($confirm != '') {
				$val = Validation::forge();
				$val->add_callable('Util_Validation');
				$val->add_field('email', 'Email', 'required|trim|valid_email|unique');
				$val->add_field('name', 'Name', 'required|trim');
				$val->add_field('password', 'Password', 'required|trim|min_length[6]');
				$val->add_field('confirmpassword', 'Confirm Password', 'required|trim|min_length[6]|match_field[password]');
				if ($val->run()) {
					$mongo_user = Model_User_User::insertUser($data);
					if ($mongo_user == TRUE) {
						Response::redirect('user/register/success');
					}
					else {
						$data['err'] = Config::get('err_not_register');
					}
				}
				else {
					$data['error'] = $val->error();
				}
			}
		}
		$this->template->content = View::forge('user/register/index', $data);
	}

	public function action_success() {
		$this->template->content = View::forge('user/register/success');
	}
}
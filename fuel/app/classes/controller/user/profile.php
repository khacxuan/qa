<?php
use Fuel\Core\Model;
use Fuel\Core\Response;
class Controller_User_Profile extends Controller_Common_User {

	public function action_index() {
		$data = array();
		$data['id'] = $this -> user_id['_id'];
		/*Check user existed*/
		$check = Model_User_User::is_exist(array('_id' => $data['id']));
		if (empty($check)) {
			Response::redirect('user/login');
		}
		$data['name'] = Input::post('name', $check[0]['name']);
		$data['email'] = Input::post('email', $check[0]['email']);
		$data['password'] = Input::post('password', '');
		$data['confirmpassword'] = Input::post('confirmpassword', '');

		$confirm = Input::post('confirm', '');
		$this->template->js_file = array('jquery.validate.min.js', 'localization/messages_jp.js');
		$this->template->script_file = 'user/profile/jsfile';
		if (Input::method() == 'POST') {
			if ($confirm != '') {
				$val = Validation::forge();
				$val->add_callable('Util_Validation');
				$val->add_field('name', 'Name', 'required|trim|unique');
				$val->add_field('email', 'Email', 'required|trim|unique');
				$val->add_field('password', 'Password', 'trim|min_length[6]');
				$val->add_field('confirmpassword', 'Confirm Password', 'trim|min_length[6]|match_field[password]');
				if ($val->run()) {
					$mongo_user = Model_User_User::updateUser($data);
					if ($mongo_user == TRUE) {
						$data['msg'] = Config::get('msg_edit_sucess');
						/*Update variable user_id*/
						$check = Model_User_User::is_exist(array('_id' => $data['id']));
						Session::set(SESSION_QA_USER, $check[0]);
					}
					else {
						$data['msg'] = Config::get('msg_err');
					}
				}
				else {
					$data['error'] = $val->error();
				}
			}
		}
		$this->template->content = View::forge('user/profile/index', $data);
	}

}
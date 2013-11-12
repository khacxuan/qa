<?php
use Fuel\Core\Model;
use Fuel\Core\Response;
class Controller_User_Profile extends Controller_Common_User {

	public function action_index() {
		$data = array();
		/*Check error to social*/
		$error = Input::get('error', null);
		if ($error == 1) {
			$typesocial = Input::get('type', '');
			$mes = Config::get('msg_err_social');
			$data['msg'] = str_replace(':social', $typesocial, $mes);
		}
		elseif($error == 2) {
			$data['msg'] = Config::get('msg_err_delete');
		}
		/*End check*/


		$data['id'] = $this -> user_id['_id'];
		/*Check user existed*/
		$check = Model_User_User::is_exist(array('_id' => $data['id']));
		if (empty($check)) {
			Response::redirect('user/login');
		}
		$data['name'] = Input::post('name', (isset($check[0]['name'])?$check[0]['name']:''));
		$data['email'] = Input::post('email', (isset($check[0]['email'])?$check[0]['email']:''));
		$data['social']['registered'] = array();
		$data['social']['unregistered'] = array();
		$arr_social = array('facebook', 'twitter', 'github');
		foreach ($arr_social as $v) {
			if (isset($check[0]['id_'.$v]) && $check[0]['id_'.$v] != '') {
				$data['social']['registered'][$v] = '';
			}
			else {
				$data['social']['unregistered'][$v] = '';
			}
		}
		$tags = '';
		if (isset($check[0]['tag_ids']) && count($check[0]['tag_ids']) > 0) {
			$arr_tag_name = Model_User_User::getTags($check[0]['tag_ids']);
			if (count($arr_tag_name) > 0) {
				$tags = '';
				foreach ($arr_tag_name as $k=>$v) {
					$tags .= ','.$v['name'];
				}
				$tags = substr($tags, 1);
			}
		}

		$data['tags'] = Input::post('tags', $tags);
		$data['password'] = Input::post('password', '');
		$data['confirmpassword'] = Input::post('confirmpassword', '');

		$confirm = Input::post('confirm', '');
		$this->template->css_file = array('jquery-ui.min.css', 'jquery.tagsinput.css');
		$this->template->js_file = array('jquery.validate.min.js', 'localization/messages_jp.js', 'jquery.tagsinput.min.js');
		$this->template->script_file = 'user/profile/jsfile';
		if (Input::method() == 'POST') {
			if ($confirm != '') {
				$val = Validation::forge();
				$val->add_callable('Util_Validation');
				$val->add_field('name', 'Name', 'required|trim');
				$val->add_field('email', 'Email', 'required|trim|uniqueemail');
				$val->add_field('password', 'Password', 'trim|min_length[6]');
				$val->add_field('confirmpassword', 'Confirm Password', 'trim|min_length[6]|match_field[password]');
				if ($val->run()) {
					$mongo_user = Model_User_User::updateUser($data);
					if ($mongo_user == TRUE) {
						$data['msg'] = Config::get('msg_edit_sucess');
						/*Update variable user_id*/
						$check = Model_User_User::is_exist(array('_id' => $data['id']), array('email', 'name'));
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

	public function action_social($type = '') {
		Session::set('profilepage', 1);
		Session::delete('oauth_token');
		Session::delete('oauth_token_secret');
		Session::delete('oauth_verify');
		Session::delete('oauth_token');
		switch ($type) {
			case 'facebook' : Response::redirect('user/facebook');break;
			case 'github' : Response::redirect('user/github');break;
			case 'twitter' : Response::redirect('user/twitter');break;
			default: Response::redirect('user/profile');break;
		}
	}

	public function action_deletesocial($type = '') {
		$arr_social = array('facebook', 'twitter', 'github');
		if (in_array($type, $arr_social)) {
			$data['id_'.$type] = '';
			$data['token_'.$type] = '';
			$check = Model_User_User::updateUserSocial($this -> user_id['_id'], $data);
			if ($check == FALSE) {
				Response::redirect('user/profile?error=2');
			}
			Response::redirect('user/profile');
		}
		else {
			Response::redirect('user/profile?error=2');
		}
	}
}
<?php
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Validation;
use Fuel\Core\Session;
class Controller_User_Userdetail extends Controller_Common_User {
	public function action_index($id = 0) {
		if ($id == 0) {
			Response::redirect('user/register');
		}
		$user = $this->user_id;
		$data['countQA'] = Model_User_User::getCountQuestionAndAnswer($id);
		$data['followed'] = Model_User_User::checkFollowed($user['_id'], $id);
		$data['userdetail'] = Model_User_User::getUserById($id);
		if (empty($data['userdetail'])) {
			Response::redirect('user/register');
		}

		$this->template->content = View::forge('user/userdetail/index', $data);
	}
}
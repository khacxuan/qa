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

		$arr = Model_User_User::getCountQuestionAndAnswer($id);
		$data['ques'] = $arr['ques'];
		$num_ans = $arr['ans'];
		$data['tags'] = $arr['tags'];
		$data['ans'] = array();
		if (count($num_ans) > 0) {
			foreach ($num_ans as $k => $v) {
				foreach ($v['answers'] as $k_a => $v_a) {
					if ((string)$v_a['by'] == $id) {
						$data['ans'][] = array('id' => (string)$v['_id'], 'content' => $v_a['content']);
					}
				}
			}
		}
		$data['followed'] = Model_User_User::checkFollowed($user['_id'], $id);
		$data['userdetail'] = Model_User_User::getUserById($id);
		if (empty($data['userdetail'])) {
			Response::redirect('user/register');
		}

		$this->template->content = View::forge('user/userdetail/index', $data);
	}
}
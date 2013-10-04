<?php

use Fuel\Core\Input;
class Controller_Ajax_User extends Controller {
	public function action_checkExist() {
		if (Input::method() == 'POST') {
			$username = Input::post('username', '');
			if (!empty($username)) {
				$result = Model_User_User::checkUserExists($username);
				if (count($result) > 0) {
					return json_encode(false);
				}
			}
		}
		return json_encode(true);
	}

	public function action_get_tags(){
		$tags = Model_User_Question::getAllTags();
		$ret = array();
		foreach ($tags as $tag){
			$ret[] = $tag['name'];
		}
		return json_encode($ret);
	}

	public function action_followUser() {
		if (Input::method() == 'POST') {
			$user = Session::get(SESSION_QA_USER);
			$id = $user['_id'];
			$userFollow = Input::post('id', '');
			if (!empty($userFollow)) {
				if (count(Model_User_User::getUserById($userFollow)) > 0) {
					$result = Model_User_User::updateFollow($id, $userFollow);
					if ($result == TRUE) {
						return json_encode(true);
					}
				}
			}
		}
		return json_encode(false);
	}
	
	public function action_add_reply() {
		try {
			if (Input::method() == 'POST') {
				//validate
				$content = Input::post('content');
				if(!empty($content)){
					$user = Session::get(SESSION_QA_USER);
					$question_id = Input::post('question_id');
					$content = Input::post('content');
					$date = time();
					if(!isset($question_id)){
						$question_id = '-1';
					}
					$reply = array(
						'by' => new MongoId($user['_id']),
		    			'content' => $content,
		    			'date' => $date
					);
					Model_User_Detail::add_reply($question_id, $reply);
					$params['reply'] = array_merge($reply,array("username" => $user['username'])) ;
					$view = View::forge('user/detail/item', $params);
					$list = $view->render();
					$data['new_reply'] = $list;
					$data['err_msg'] = '';
				}else{
					$data['err_msg'] =Config::get('msg_err_not_input_reply');
				}
			}else{
				Response::redirect(Uri::create('user/list'));
			}
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
			Log::error($e->getTraceAsString());
		}
		return json_encode($data);
	}
}
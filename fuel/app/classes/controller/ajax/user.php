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

	public function action_unFollowUser() {
		if (Input::method() == 'POST') {
			$user = Session::get(SESSION_QA_USER);
			$id = $user['_id'];
			$userFollow = Input::post('id', '');
			if (!empty($userFollow)) {
				if (count(Model_User_User::getUserById($userFollow)) > 0) {
					$result = Model_User_User::removeFollow($id, $userFollow);
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
					$better_flag = Input::post('better_flag');
					$date = time();
					$show_button = false;
					if(!isset($question_id)){
						$question_id = '-1';
					}
					$reply = array(
						'by' => new MongoId($user['_id']),
							'content' => $content,
							'date' => $date
					);
					$msg = Model_User_Detail::add_reply($question_id, $reply);
					$info = Model_User_Detail::get_info(new MongoId($user['_id']), $question_id);
					if($info['retval'][0]['questioner'] != 0 && $better_flag != 1){
						$show_button = true;
					}
					$params['reply'] = array_merge($reply,array("username" => $user['username']));
					$params['show_button'] = $show_button;
					$params['count_better'] = $info['retval'][0]['count_better'];
					$params['total_answer'] = $info['retval'][0]['total_answer'];
					
					$view = View::forge('user/detail/item', $params);
					$list = $view->render();
					$data['new_reply'] = $list;
					$data['date'] = $date;
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

	public function action_bookmark() {
		try {
			if (Input::method() == 'POST') {
				$user = Session::get(SESSION_QA_USER);
				$question_id = Input::post('question_id');
				if(!isset($question_id)){
					$question_id = '-1';
				}
				$flag = Model_User_Detail::bookmark($question_id, $user['_id']);
				$data['flag'] = $flag;
				$data['err_msg'] = '';
			}
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
			Log::error($e->getTraceAsString());
		}
		return json_encode($data);
	}
	
	public function action_sendmail() {
		try {
			if (Input::method() == 'POST') {
				$mail_to = Input::post('email');
				if($mail_to != ""){
					$data['reply'] = Input::post('reply');
					$subject = 'New reply';
					$body = View::forge('mail/template_send_new_reply', $data);
					\Helper\Utilities::send_mail($mail_to, "", $subject, $body,null,array(),true);
				}
			}
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
			Log::error($e->getTraceAsString());
		}
	}
	
	public function action_set_better() {
		try {
			if (Input::method() == 'POST') {
				$user = Session::get(SESSION_QA_USER);
				$question_id = Input::post('question_id');
				$index = Input::post('index');
				$comment = Input::post('comment');
				if(!isset($question_id)){
					$question_id = '-1';
				}
				if(!isset($index)){
					$index = '-1';
				}
				$flag = Model_User_Detail::set_better($question_id, $user['_id'], $index, trim($comment));
				$data['err_msg'] = '';
				$data['success'] = $flag;
			}
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
			Log::error($e->getTraceAsString());
		}
		return json_encode($data);
	}
	
	
}
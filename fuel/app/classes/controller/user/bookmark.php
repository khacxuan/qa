<?php

class Controller_User_bookmark extends Controller_Common_User {
	
	public function action_index() {
		$user = Session::get(SESSION_QA_USER);
		$data['questions'] = Model_User_Bookmark::get_bookmark(strval($user['_id']));
		$this->template->content = View::forge('user/bookmark/index', $data);
	}
	
	public function action_remove($question_id="") {
		try {
			$user = Session::get(SESSION_QA_USER);
			Model_User_bookmark::remove_bookmark($question_id, $user['_id']);
			Session::set_flash('msg_remove_bookmark_sucess', Config::get('msg_remove_bookmark_sucess'));
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
			Log::error($e->getTraceAsString());
		}
		Response::redirect('user/bookmark');
	}
}
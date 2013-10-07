<?php

class Controller_User_Detail extends Controller_Common_User {
	
	public function action_index($question_id) {
		Asset::add_path('assets/ckeditor/', 'ckeditor');
		$user = Session::get(SESSION_QA_USER);
		$question_id = isset($question_id) ? $question_id : '-1' ;
		
		$replies = Model_User_Detail::get_reply($question_id, strval($user['_id']));
		if(isset($replies['errmsg']) || count($replies['retval']) <= 0){
			Response::redirect(Uri::create('user/list'));
		}
		$data['replies'] = $replies;
		$this->template->content = View::forge('user/detail/index', $data);
		$this->template->script_file = 'user/detail/script';
	}
}
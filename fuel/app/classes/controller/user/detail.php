<?php

class Controller_User_Detail extends Controller_Common_User {
	
	public function action_index($question_id) {
		Asset::add_path('assets/ckeditor/', 'ckeditor'); 
		$question_id = isset($question_id) ? $question_id : '-1' ;
		
		//update views
		Model_User_Detail::increase_views($question_id);
		
		$replies = Model_User_Detail::get_reply($question_id, strval($this -> user_id['_id']));
		if(isset($replies['errmsg']) || count($replies['retval']) <= 0){
			Response::redirect(Uri::create('user/list'));
		}
		$data['replies'] = $replies;
		$data['login_id'] = strval($this -> user_id['_id']);
		$this->template->content = View::forge('user/detail/index', $data);
		$this->template->script_file = 'user/detail/script'; 
	}
}
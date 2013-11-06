<?php

class Controller_User_Detail extends Controller_Common_User {
	
	public function action_index($question_id) {
		Asset::add_path('assets/ckeditor/', 'ckeditor'); 
		$question_id = isset($question_id) ? $question_id : '-1' ;
		$limit_same_qa = Config::get('limit_same_qa');
		
		//update views
		Model_User_Detail::increase_views($question_id);
		
		$replies = Model_User_Detail::get_reply($question_id, strval($this -> user_id['_id']));
		if(isset($replies['errmsg']) || count($replies['retval']) <= 0){
			Response::redirect(Uri::create('user/list'));
		}
		$data['replies'] = $replies;
		$data['login_id'] = strval($this -> user_id['_id']);
		//$same = Model_User_Detail::get_same_question($question_id, $limit_same_qa); var_dump($same);
		$data['same_qa'] = Model_User_Detail::get_same_question($question_id, $limit_same_qa);
		$this->template->content = View::forge('user/detail/index', $data);
		$this->template->script_file = 'user/detail/script'; 
	}
}
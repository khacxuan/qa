<?php

class Controller_User_Detail extends Controller_Common_User {
	
	public function action_index($question_id) {
		Asset::add_path('assets/ckeditor/', 'ckeditor'); 
		$question_id = isset($question_id) ? $question_id : '-1' ;
		$limit_same_qa = Config::get('limit_same_qa');
		
		//update views
		Model_User_Detail::increase_views($question_id);
		
		$answer = Model_User_Detail::get_reply($question_id, strval($this -> user_id['_id']));
		if(isset($answer['errmsg']) || count($answer['retval']) <= 0){
			Response::redirect(Uri::create('user/list'));
		}
		$data['question'] = $answer['retval'][0]['question'];
		$data['bookmark'] = $answer['retval'][0]['bookmark'];
		$data['tags'] = $answer['retval'][0]['tag'];
		$better_flag = $answer['retval'][0]['better_flag'];
		$reply = $answer['retval'][0]['replies'];
		if($better_flag == 1){
			$this -> fsort($reply, "better_flag");
		}
		$data['reply'] = $reply;
		$data['better_flag'] = $better_flag;
		$data['login_id'] = strval($this -> user_id['_id']);
		$data['same_qa'] = Model_User_Detail::get_same_question($question_id, $limit_same_qa);
		
		$this->template->content = View::forge('user/detail/index', $data);
		$this->template->script_file = 'user/detail/script'; 
	}
	
	private function fsort (&$array, $key) {
	    $sorter=array();
	    $ret=array();
	    reset($array);
	    foreach ($array as $ii => $va) {
	        $sorter[$ii]=$va[$key];
	    }
	    arsort($sorter);
	    foreach ($sorter as $ii => $va) {
	        $ret[$ii]=$array[$ii];
	    }
	    $array=$ret;
	}
	
	
}
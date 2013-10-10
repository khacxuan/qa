<?php
class Controller_User_Followings extends Controller_Common_User {
		
	public function action_index() {
		$data['followings'] = Model_User_Followings::get_followings($this -> user_id['_id']); 
		$this->template->content = View::forge('user/followings/index', $data);
	}
	
}
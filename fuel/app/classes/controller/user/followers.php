<?php
class Controller_User_Followers extends Controller_Common_User {
		
	public function action_index() {
		$data['followers'] = Model_User_Followers::get_followers($this -> user_id['_id']); 
		$this->template->content = View::forge('user/followers/index', $data);
	}
	
}
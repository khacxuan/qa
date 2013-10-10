<?php
class Controller_User_Profile extends Controller_Common_User {
		
	public function action_index() {
		$this->template->content = View::forge('user/profile/index');
	}
	
}
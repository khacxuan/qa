<?php
class Controller_User_Top extends Controller_Common_User {
	public function action_index() {
		$data = array();
		$loginU = Session::get(SESSION_QA_USER);
		//var_dump($loginU);
				
		$data = array_merge($data, Model_User_Top::getTop($loginU['_id']));
		$this->template->content = View::forge('user/top/index', $data);
	}
}
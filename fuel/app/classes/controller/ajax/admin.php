<?php

use Fuel\Core\Input;
class Controller_Ajax_Admin extends Controller {
	private function checkLogin() {
		$isLogin = true;
		$data = array();
		$user = Session::get(SESSION_QA_ADMIN, null);
		if (!isset($user)) {
			$data = array('err_msg' => Config::get('err_not_login'));
		}
		return $data;
	}
	
	public function action_update_banned() {	
		$data = $this->checkLogin();
		if (!empty($data)) {
			return json_encode($data);
		}
		$data = array();
		try {
			$id = Input::post('id', 0);
			$flag = Input::post('flag', 0);
			Model_Admin_Listuser::ban($id, $flag);
		} catch (exception $e) {
			$data['err_msg'] = $e->getMessage();
		}
		
		return json_encode($data);
	}
}
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Admin_Logout extends Controller {

	public function action_index() {

		//delete session
		Session::delete(SESSION_QA_ADMIN);
		//redirect login
		Response::redirect('admin/login');
	}

}
?>

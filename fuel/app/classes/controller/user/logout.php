<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_User_Logout extends Controller {

	public function action_index() {

		//delete session
		Session::delete(SESSION_QA_USER);
		//redirect login
		Response::redirect('');
	}

}
?>

<?php
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Validation;
use Fuel\Core\Session;
class Controller_User_Listuser extends Controller_Common_User {
	public function action_index() {
		$user = $this->user_id;

		/*Check variable page_index*/
		$page_index = Input::param('page', 1);
		if (Util_Utilities::checkIsNumber($page_index) === FALSE) {
			$page_index = 1;
		}
		/*End check page_index*/

		/*pagination*/
		$data['total_record_page'] = Config::get('listuser_limit');
		$data['display_page'] = Config::get('listuser_displayed_pages');
		$offset = ($page_index - 1)*$data['total_record_page'];
		$list = Model_User_User::getAllUser($user['_id']);
		$list_limit = Model_User_User::getAllUser($user['_id'], $data['total_record_page'], $offset);
		$data['total_record'] = count($list);
		$data['from'] = $offset + 1;
		$data['to'] = $offset  + count($list_limit);
		$data['page_index'] = $page_index;
		$data['list'] = $list_limit;
		$data['total'] = count($list);
		$data['num'] = count($list_limit);
		/*end pagination*/

		$this->template->content = View::forge('user/listuser/index', $data);
	}
}
<?php
class Controller_User_List extends Controller_Common_User {

	public function action_index() {			
		$view = View::forge('user/list/index');
		$key = trim(Input::get('key')," 　\t\n\r\0\x0B");
		$answer = trim(Input::get('answer')," 　\t\n\r\0\x0B");
		$answer = is_numeric($answer)?$answer:-1;
		$data =  Model_User_List::getAllListQuestions($key,$answer);				
		$get = Input::get();
		unset($get['page']);
		$requestString = trim(http_build_query($get));
		$view->requestString = $requestString;
		if ($requestString != "") {
			$view->url_param = '?' . $requestString . '&page=';
		}
		//paging
		$page_record_total = \Config::get('qa_record_page_count', 1);
		$view->total_record_page = $page_record_total;
		$displayed_pages = \Config::get('qa_paging_displayed_pages',2);
		$view->display_page = $displayed_pages;
		$page_index = Fuel\Core\Input::get('page', 1);
		$page_index = !is_numeric($page_index) ? 1 : $page_index;
		$view->page_index = $page_index;
		$offset = $page_index * $page_record_total - $page_record_total;
		$Total = count(@$data ["retval"]);
		$view->total_record = $Total;		
		$page_from = $offset + 1;		
		$page_to = $page_index * $page_record_total;		
		if ($page_to > $Total) {
			$page_to = $Total;
		}
		$data =  Model_User_List::getAllListQuestoinsByCon($key,$answer,$page_record_total,$offset);	
		$view->page_from = $page_from;
		$view->page_to = $page_to;
		$view->students_count = $Total;
		$view->data = $data;		
		$this->template->js_file = array('jquery-ui-1.10.0.custom.min.js');		
		$this -> template -> content = $view;		
	}

}

?>

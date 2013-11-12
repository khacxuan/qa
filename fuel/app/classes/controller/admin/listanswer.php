<?php
class Controller_Admin_Listanswer extends Controller_Common_Admin {

	public function action_index() {
		$view = View::forge('admin/list/answer');
		$key = trim(Input::get('key')," 　\t\n\r\0\x0B");
		$answer = trim(Input::get('answer', -1)," 　\t\n\r\0\x0B");
		$data =  Model_Admin_List::getAllListQuestions($key,$answer);				
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
		$Total = count($data ["retval"]);
		$view->total_record = $Total;		
		$page_from = $offset + 1;		
		$page_to = $page_index * $page_record_total;		
		if ($page_to > $Total) {
			$page_to = $Total;
		}
		$data =  Model_Admin_List::getAllListQuestoinsByCon($key,$answer,$page_record_total,$offset);	
		$view->page_from = $page_from;
		$view->page_to = $page_to;
		$view->students_count = $Total;
		$view->data = $data;		
		$this->template->js_file = array('jquery-ui-1.10.0.custom.min.js');		
		$this -> template -> content = $view;			
	}
	
	public function action_delete($id){
		//(is_null ( $id ) or ! is_numeric ( $id )) and Response::redirect ( 'admin/list' );
		
		$page_index = Input::get ( 'page', 1 );
		$page_index = ! is_numeric ( $page_index ) ? 1 : $page_index;
		$key = Input::get ('key', null);
		$answer = Input::get ('answer', -1);
		//delete
		$ids = explode('_', $id);
		if(count($ids) == 3){
			$qaby = $ids[1];
			$qaid = $ids[2];
			Model_Admin_List::delete($qaby, $qaid);			
		}
		
		// Filter
		$url_param = "";
		if (isset ( $key ) and trim ( $key ) != '')
			$url_param .= $url_param == "" ? "?key=" . $key : "&key=" . $key;
			
		$url_param .= $url_param == "" ? "?answer=" . $answer : "&answer=" . $answer;
		$url_param .= $url_param == "" ? "?page=" . $page_index : "&page=" . $page_index;
		Response::redirect ( 'admin/listanswer' . $url_param );
	}
	
	public function action_delete_answer($id) {
		//(is_null ( $id ) or ! is_numeric ( $id )) and Response::redirect ( 'admin/list' );
	
		$page_index = Input::get ( 'page', 1 );
		$page_index = ! is_numeric ( $page_index ) ? 1 : $page_index;
		$key = Input::get ('key', null);
		$answer = Input::get ('answer', -1);
		//delete
		$ids = explode('_', $id);
		if(count($ids) == 4){
			$qaby = $ids[1];
			$qaid = $ids[2];
			$num = $ids[3];
			Model_Admin_List::deleteAnswer($qaby, $qaid, $num);			
		}
		
		// Filter
		$url_param = "";
		if (isset ( $key ) and trim ( $key ) != '')
			$url_param .= $url_param == "" ? "?key=" . $key : "&key=" . $key;
		
		$url_param .= $url_param == "" ? "?answer=" . $answer : "&answer=" . $answer;
		$url_param .= $url_param == "" ? "?page=" . $page_index : "&page=" . $page_index;
		Response::redirect ( 'admin/listanswer' . $url_param );
	}
}

?>

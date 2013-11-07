<?php
class Controller_User_Newqa extends Controller_Common_User {
	public function action_index() {
		Asset::add_path('assets/tag-it', 'tag-it');
		Asset::add_path('assets/ckeditor/', 'ckeditor');
		$data = array();
					
		//var_dump($obj->{'$id'});		
		//var_dump(new MongoId('524943f0adb9af2c25ba01da'));		
				
		if(Input::post()){
			$val=Validation::forge();
			$val->add_field('title','title','required|max_length[100]');
			$val->add_field('content','content','required');
			
			if($val->run()){
				$loginU = Session::get(SESSION_QA_USER);
				$data['questioner'] = $this->user_id['_id'];
				$data['question_title'] = Input::post('title');
				$data['question_content'] = Input::post('content');
				$taglist = Input::post('taglist');
				$data['tag_ids'] = '';
				if(!empty($taglist)){
					$data['tag_ids'] = Model_User_Question::getTagids(explode(',',$taglist));
				}	
				Model_User_Question::insertQuestion($data);				
				Response::redirect('user/list');
			}else{
				$data['error']=$val->error();
			}
		}		
		$this->template->content = View::forge('user/newqa/index', $data);
	}
}
<?php
use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;
class Model_User_Question {

	/*
	 * return Array()
	 */
	public static function getAllTags(){
		$mongodb = Mongo_Db::instance();
		$mongodb->select(array('name'));
		return $mongodb->get('tags');
	}
	
	public static function insertQuestion($data){
		$mongodb = Mongo_Db::instance();
		$time = time();
		$id = $mongodb->insert('qa', array(
				'question_title' => $data['question_title'],
				'question_content' => $data['question_content'],
				'questioner' => $data['questioner'],
				'tag_ids' => $data['tag_ids'],
				'created_at' => $time,
				'updated_at' => $time,
		));
	}

	
	public static function getTagids($tagNames) {		
		 $mongodb = Mongo_Db::instance();
		$mongodb->where_in('name', $tagNames);
		$tags = $mongodb->get('tags');
		$names = array();
		$retids = array();
		foreach ($tags as $tag){
			$retids[] = $tag['_id'];
			$names[] = $tag['name'];
		}
		
		$diffnames = array_diff($tagNames, $names);
		foreach ($diffnames as $name){
			$retids[] = $mongodb->insert('tags', array(
								'name' => $name));
		}
		
		return $retids;
	}
}
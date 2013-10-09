<?php

use Fuel\Core\Mongo_Db;

class Model_User_Bookmark {

	static public function get_bookmark($user_id) {
		$mongodb = \Mongo_Db::instance(); 
		$result = $mongodb->execute('function (){
				var questions = [];
				var bookmark = db.user.find({_id: ObjectId("'.$user_id.'")}, {bookmark: 1}).toArray();
				if(bookmark[0]["bookmark"]){
					questions = db.qa.find({_id:{$in: bookmark[0]["bookmark"]}}, {question_title: 1}).toArray();
				}
				return questions;
			}');		
		return $result;
	}
	
	static public function remove_bookmark($question_id, $userid) {
		$mongodb = \Mongo_Db::instance();
		$mongodb -> where(array('_id' =>  new MongoId($userid))) -> update('user',array('$pull' => array("bookmark" => new MongoId($question_id))), array(), true);
		
	}

}

?>
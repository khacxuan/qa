<?php

use Fuel\Core\Mongo_Db;

class Model_User_Detail {

	static public function get_reply($question_id, $user_id) {
		$mongodb = \Mongo_Db::instance(); 
		
		$result = $mongodb->execute('function (){
				var result = [];		
				db.qa.find({_id: ObjectId("'.$question_id.'")}).forEach(function(w){
					var username = db.user.find(
												{_id: w.questioner},
												{username: 1}
											   ).toArray();
					var objquestion = {
										_id: w._id, 
										question_title: w.question_title, 
										question_content: w.question_content, 
										username: username[0]["username"],
										created_at: w.created_at
									  } 
					var replies = [];
					if(w.answers){
						w.answers.forEach(function(item){
							var username = db.user.find(
														{_id: item.by},
														{username: 1}
													   ).toArray();
							replies.push({
								by: item.by,
								username: username[0]["username"],
								content: item.content,
								date: item.date
							})
						});
					}
					
					var tags = [];
					if(w.tag_ids){
						tags = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}
					
					var bookmark = db.user.find({_id: ObjectId("'.$user_id.'"), bookmark: ObjectId("'.$question_id.'")}).count();
								
					result.push({
						question: objquestion,
						replies: replies,
						tag: tags,
						bookmark: bookmark
					});
				});
				return result;
			}');		
		return $result;
	}
	
	static public function add_reply($question_id, $reply) {
		$mongodb = \Mongo_Db::instance();
		$mongodb -> where(array('_id' => new MongoId($question_id))) 
				 -> update('qa',array('$addToSet' => array("answers" => $reply)), array(), true);
	}

	static public function bookmark($question_id, $userid) {
		$mongodb = \Mongo_Db::instance();
		$flag = 0;
		$result = $mongodb -> get_where('user', array(
														'_id' => new MongoId($userid),
														'bookmark' => new MongoId($question_id)
													 )
									   );
		if(count($result) > 0){ //remove bookmark
			$mongodb -> where(array('_id' => new MongoId($userid))) 
				     -> update('user',array('$pull' => array("bookmark" => new MongoId($question_id))), array(), true);
			$flag = 0;
		}else{ //add bookmark
			$flag = $mongodb -> where(array('_id' => new MongoId($userid))) 
				     -> update('user',array('$addToSet' => array("bookmark" =>  new MongoId($question_id))), array(), true);
			$flag = 1;
		}
		return $flag;
	}
	
}

?>
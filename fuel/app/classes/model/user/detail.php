<?php

use Fuel\Core\Mongo_Db;

class Model_User_Detail {

	static public function get_reply($question_id) {
		$mongodb = \Mongo_Db::instance(); 
		//$question_id = new MongoId($question_id);
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
					var tempreply = w.answers;
					var replies = [];
					tempreply.forEach(function(item){
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
					
					var tags = [];
					if(w.tag_ids){
						tags = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}			
					result.push({
						question: objquestion,
						replies: replies,
						tag: tags
					});
				});
				return result;
			}');		
		return $result;
	}
	
	static public function add_reply($question_id, $reply) {
		$mongodb = \Mongo_Db::instance();
		$mongodb -> where(array('_id' => new MongoId($question_id))) 
				 -> update('qa',array('$push' => array("answers" => $reply)), array(), true);
	}
	
}

?>
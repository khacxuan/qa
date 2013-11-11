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
												{username: 1, email: 1, send_mail: 1}
											   ).toArray();
					var objquestion = {
										_id: w._id, 
										question_title: w.question_title, 
										question_content: w.question_content, 
										username: username[0]["username"],
										email: username[0]["email"],
										send_mail: username[0]["send_mail"],
										created_at: w.created_at,
										questioner: w.questioner
									  } 
					var replies = [];
					var better_flag = "";
					var questioner_reply = "";
					if(w.answers){
						
						w.answers.forEach(function(item){
							var username = db.user.find(
														{_id: item.by},
														{username: 1}
													   ).toArray();
					   
							var count_better = 0; 
							db.qa.find({answers: {$elemMatch: {by: item.by, better_flag: 1}}}).forEach(function(c){
								c.answers.forEach(function(item){
									if(item.better_flag == 1){
										count_better = count_better + 1;
									}
								});
							});
							
							if(item.better_flag == 1){
								better_flag = 1;
								questioner_reply = item.questioner_reply; 
							}
							
							replies.push({
								by: item.by,
								username: username[0]["username"],
								content: item.content,
								date: item.date,
								count_better: count_better,
								better_flag: item.better_flag,
								questioner_reply: item.questioner_reply
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
						bookmark: bookmark,
						better_flag: better_flag
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

	public static function get_info($by, $question_id){
		$mongodb = \Mongo_Db::instance();
		$result = $mongodb->execute('function (){
				var count_better = 0;
				var questioner = 0;
				var total_answer = 0;
				var result = [];
				db.qa.find({answers: {$elemMatch: {by: ObjectId("'.$by.'"), better_flag: 1}}}).forEach(function(c){
					c.answers.forEach(function(item){
						if(item.better_flag == 1){
							count_better = count_better + 1;
						}
					});
				});
				questioner = db.qa.find({_id : ObjectId("'.$question_id.'"), questioner : ObjectId("'.$by.'") }).count();
				total_answer = db.qa.find({_id : ObjectId("'.$question_id.'")},{_id: 0, answers: 1}).toArray();
				result.push({
					count_better: count_better,
					questioner: questioner,
					total_answer: total_answer[0]["answers"].length - 1
				});
				return result;
			}');		
		return $result;
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
	
	static public function increase_views($question_id) {
		$mongodb = \Mongo_Db::instance();
		$mongodb -> where(array('_id' => new MongoId($question_id))) 
				 -> update('qa',array('$inc' => array("views" => 1)), array(), true); 
	}
	
	static public function set_better($question_id, $loginid, $index, $comment) {
		$mongodb = \Mongo_Db::instance();
		return $mongodb -> where(
									array(
											'_id' => new MongoId($question_id), 
											'questioner' => new MongoId($loginid)
										 )
						   ) 
				 -> update('qa',array('$set' => array("answers.$index.better_flag" => 1,  "answers.$index.questioner_reply" => $comment)), array(), true);
	}

	static public function get_same_question($question_id, $limit) {
		$mongodb = \Mongo_Db::instance();
		$result = $mongodb->execute('function (){
				var qa = [];
				var tag = db.qa.find({_id: ObjectId("'.$question_id.'")}).toArray();
				if(tag[0].tag_ids){
					qa =  db.qa.find({tag_ids : {$elemMatch: {$in: tag[0].tag_ids}}, _id : {$ne : ObjectId("'.$question_id. '")}}, {_id: 1, question_title: 1}).sort({updated_at: -1}).limit('.$limit.').toArray();
				}
				return qa;
			}');
		return $result;
	}
	
}

?>
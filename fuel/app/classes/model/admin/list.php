<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_Admin_List {

	public static function getAllListQuestions($key = "",$answer=-1) {		
		if ($key != "") {
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);	
			if($answer ==-1){
				$con = '{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}';
			} else if($answer==0){
				$con = '{$and:[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}else{
				$con = '{$and:[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}
		} else {
			$con = "";
			if($answer ==-1){
				$con = "";
			} else if($answer==0){
				$con = '{answers:{ $exists: false}}';
			}else{
				$con = '{answers:{ $exists: true}}';
			}
		}

		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){					
				 return db.qa.find(' . $con .').toArray()	
			}');	
		
		return $result;	
	}
	

	public static function getAllListQuestoinsByCon($key = "",$answer=-1, $limit, $offset) {
		
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);
			if($answer ==-1){
				$con = '{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}';
			} else if($answer==0){
				$con = '{$and:[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}else{
				$con = '{$and:[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}
			
		} else {
			$con = "";
			if($answer ==-1){
				$con = "";
			} else if($answer==0){
				$con = '{answers:{ $exists: false}}';
			}else{
				$con = '{answers:{ $exists: true}}';
			}
		}

		$mdb = Mongo_DB::instance();		
		$result = $mdb->execute('function (){
				var a = [], users=[], uids = [];		
				db.qa.find(' . $con .').skip(' . $offset . ').limit(' . $limit . ').sort( { created_at: -1 } ).forEach(function(w){
					var b = [];
					if(w.tag_ids){
						b = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}			
					a.push({
						qa:w,
						tag:b
					});
				
					if(w.answers){
						n = w.answers.length;
						for(i=0;i<n;i++){
							uid = w.answers[i]["by"];
							struid = uid.toString();
							if(uids.indexOf(struid) < 0){
								uids.push(struid);
								users.push(db.user.findOne({_id:uid},{email: 1, name: 1, username: 1}));
							}
						}
					}
				});
				return [a,users];
			}');	
		
		return $result;
	}
	
	public static function delete($qaby, $qaid){
		$mdb = Mongo_DB::instance();
		$ret = $mdb->where(array('_id'=>new MongoId($qaid)))->delete('qa');
		
		$ret = $mdb->where(array('_id'=>new MongoId($qaby)))->get_one('user');
		if(empty($ret['number_of_deleted_questions'])){
			$mdb->where(array('_id'=>new MongoId($qaby)))->update('user', array('number_of_deleted_questions'=>1));
		}else{
			$count = $ret['number_of_deleted_questions'] + 1;
			$mdb->where(array('_id'=>new MongoId($qaby)))->update('user', array('number_of_deleted_questions'=>$count));
		}
	}
	
	public static function deleteAnswer($qaby, $qaid, $num){
		$mdb = Mongo_DB::instance();
		$ret = $mdb->where(array('_id'=>new MongoId($qaid)))->get_one('qa');
		$ret = $ret['answers'];
		unset($ret[$num]);
		
		$reindex = array();
		foreach ($ret as $item){
			$reindex[] = $item;
		}
		
		$mdb->where(array('_id'=>new MongoId($qaid)))->update('qa', array('answers'=>$reindex));
		
		$ret = $mdb->where(array('_id'=>new MongoId($qaby)))->get_one('user');
		if(empty($ret['number_of_deleted_answers'])){
			$mdb->where(array('_id'=>new MongoId($qaby)))->update('user', array('number_of_deleted_answers'=>1));
		}else{
			$count = $ret['number_of_deleted_answers'] + 1;
			$mdb->where(array('_id'=>new MongoId($qaby)))->update('user', array('number_of_deleted_answers'=>$count));
		}
	}
}
?>



<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_User_List {

	public static function getAllListQuestions($key = "",$answer=-1) {		
		if ($key != "") {
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);	
			if($answer ==-1){
				$con = '{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}';
			} else if($answer==0){
				$con = '{$and[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}else{
				$con = '{$and[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
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
				$con = '{$and[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
			}else{
				$con = '{$and[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}]}';
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
				var a = [];		
				db.qa.find(' . $con .').skip(' . $offset . ').limit(' . $limit . ').sort( { created_at: -1 } ).forEach(function(w){
					var b = [];
					if(w.tag_ids){
						b = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}	
					var c = db.user.count({bookmark:{$gt:w._id}});
					var d = db.qa.count({$and:[{_id: w._id},{answers:{$elemMatch:{better_flag:1}}}]});
					a.push({
						qa:w,
						tag:b,
						favorite:c,
						better_flag:d,
					});
				});
				return a;
			}');		
		return $result;
		
	}

}
?>



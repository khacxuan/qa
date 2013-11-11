<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_User_List {

	public static function getAllListQuestions($key = "", $answer = -1) {
		if ($key != "") {
			$key = str_replace(" ", "|", $key);
			$key = str_replace("　", "|", $key);
			if ($answer == -1) {
				$con = '{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}';
			} else if ($answer == 0) {
				$con = '{$and[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			} else {
				$con = '{$and[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			}
		} else {
			$con = "";
			if ($answer == -1) {
				$con = "";
			} else if ($answer == 0) {
				$con = '{answers:{ $exists: false}}';
			} else {
				$con = '{answers:{ $exists: true}}';
			}
		}

		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){					
				 return db.qa.find(' . $con . ').toArray()	
			}');

		return $result;
	}

	public static function getAllListQuestoinsByCon($key = "", $answer = -1, $limit, $offset) {
	
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ", "|", $key);
			$key = str_replace("　", "|", $key);
			if ($answer == -1) {
				$con = '{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}';
			} else if ($answer == 0) {
				$con = '{$and[{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			} else {
				$con = '{$and[{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			}
		} else {
			$con = "";
			if ($answer == -1) {
				$con = "";
			} else if ($answer == 0) {
				$con = '{answers:{ $exists: false}}';
			} else {
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
					var c = db.user.count({bookmark:w._id});
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

	public static function getAllListQuestoinsByConSortFollow($key = "", $answer = -1, $id_user = "", $limit, $offset) {
		
		$id_user = 'ObjectId("'.$id_user.'")';
		$follow_array = Model_User_List::getFollow($id_user);
		
		if(empty($follow_array)){
			
			return Model_User_List::getAllListQuestoinsByCon($key,$answer , $limit, $offset);
		}		
		$array_tmp;
		foreach ($follow_array as $val) {
			$temp = 'ObjectId("' . $val . '")';
			$array_tmp[] = $temp;
		}
		$follow_array = "[" . implode(',', $array_tmp) . "]";
		
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ", "|", $key);
			$key = str_replace("　", "|", $key);
			if ($answer == -1) {

				$con1 = '{$and[{questioner:{$in:' . $follow_array . '}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}';

				$con2 = '{$and[{questioner:{$nin:' . $follow_array . '}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}';
			} else if ($answer == 0) {
				$con1 = '{$and[{questioner:{$in:' . $follow_array . '}},{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
				$con2 = '{$and[{questioner:{$nin:' . $follow_array . '}},{answers:{ $exists: false}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			} else {
				$con1 = '{$and[{questioner:{$in:' . $follow_array . '}},{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
				$con2 = '{$and[{questioner:{$nin:' . $follow_array . '}},{answers:{ $exists: true}},{$or:[{question_content:{ $regex: "' . $key . '",$options: "im"}},{question_title:{ $regex: "' . $key . '",$options: "im"}}]}]}';
			}
		} else {
			$con1 = '{questioner:{$in:' . $follow_array . '}}';
			$con2 = '{questioner:{$nin:' . $follow_array . '}}';
			if ($answer == -1) {
				$con1 = '{questioner:{$in:' . $follow_array . '}}';
				$con2 = '{questioner:{$nin:' . $follow_array . '}}';
			} else if ($answer == 0) {
				$con1 = '{$and[{questioner:{$in:' . $follow_array . '}},{answers:{ $exists: false}}]}';
				$con2 = '{$and[{questioner:{$nin:' . $follow_array . '}},{answers:{ $exists: false}}]}';
			} else {
				$con1 = '{$and[{questioner:{$in:' . $follow_array . '}},{answers:{ $exists: true}}]}';
				$con2 = '{$and[{questioner:{$nin:' . $follow_array . '}},{answers:{ $exists: true}}]}';
			}
		}		
		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){	
			var offset = '.$offset.'
			var limit = '.$limit.';	
			var lm;	
			var a1 = [];
			var a2 = [];
			var a = [];
				db.qa.find(' . $con1 . ').sort( { created_at: -1 } ).forEach(function(w){
					var b = [];
					if(w.tag_ids){
						b = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}	
					var c = db.user.count({bookmark:w._id});
					var d = db.qa.count({$and:[{_id: w._id},{answers:{$elemMatch:{better_flag:1}}}]});				
					a1.push({
						qa:w,
						tag:b,
						favorite:c,
						better_flag:d,
					});
				});
				db.qa.find(' . $con2 . ').sort( { created_at: -1 } ).forEach(function(w){
					var b = [];
					if(w.tag_ids){
						b = db.tags.find({_id:{$in:w.tag_ids}}).toArray();
					}	
					var c = db.user.count({bookmark:w._id});
					var d = db.qa.count({$and:[{_id: w._id},{answers:{$elemMatch:{better_flag:1}}}]});				
					a2.push({
						qa:w,
						tag:b,
						favorite:c,
						better_flag:d,
					});
				});
				for(var i= 0; i<a2.length;i++){
					a1.push(a2[i]);
				}
				
				if(offset+limit<a1.length){
					lm = offset+limit;
				}else{
					lm = a1.length;
				}
				for(var i = offset; i< lm;i++ ){
					a.push(a1[i]);
				}
				return a;
			}');
		//echo "<pre>";var_dump($result);
		//die();
		return $result;
	}

	public static function getFollow($id_user) {
		$mdb = Mongo_DB::instance();
		$follow = $mdb->execute('function (){
			return db.user.find({_id:' . $id_user . '},{follow:1}).toArray();
			}');

		if (isset($follow['ok']) && $follow['ok'] == 1 && isset($follow ["retval"][0]["follow"])) {
			return $follow ["retval"][0]["follow"];
		} else {
			return;
		}
	}

}
?>



<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_User_Tag {

	public static function getAllListQuestionsByTag($tag_id = "") {
		if ($tag_id != "") {
			$con = '{tag_ids: ObjectId("' . $tag_id . '")}';
		} else {
			$con = "";
		}

		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){				
				 return db.qa.find(' . $con . ').toArray()	
			}');
		
		return $result;
	}

	public static function getAllListQuestoinsByConAndTag($tag_id = "", $limit, $offset) {

		if ($tag_id != "") {
			$con = '{tag_ids: ObjectId("' . $tag_id . '")}';
		} else {
			$con = "";
		}

		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){
				var a = [];		
				db.qa.find(' . $con . ').skip(' . $offset . ').limit(' . $limit . ').sort( { created_at: -1 } ).forEach(function(w){
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
	
	
	public static function getTagTotal($key = "") {
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);
			$con = '{name:{ $regex: "'.$key.'",$options: "im"}}';
		} else {
			$con = "";
		}
	
		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){
				return db.tags.find(' . $con .').count()
			}');
	
		if($result['ok'] == 1 && !empty($result['retval'])){
			return $result['retval'];
		}
		return 0;	
	}
	
	public static function getTagList($key = "", $limit, $offset) {
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);
			$con = '{name:{ $regex: "'.$key.'",$options: "im"}}';
		} else {
			$con = "";
		}
		
		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){
			var a = [];
			db.tags.find(' . $con .').skip(' . $offset . ').limit(' . $limit . ').forEach(function(t){	
				tid = t._id;			
				var c = db.qa.count({tag_ids:{$exists: true}, tag_ids:{$in: [tid]}});
				a.push({					
					tag:t,
					count_qa:c,
				});
			});
				
			return a;
		}');		
		if($result['ok'] == 1 && !empty($result['retval'])){
			return $result['retval'];
		}		
		
		return array();
	}
}
?>



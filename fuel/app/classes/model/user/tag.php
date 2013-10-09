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
					a.push({
						qa:w,
						tag:b
					});
				});
				return a;
			}');

		return $result;
	}

}
?>



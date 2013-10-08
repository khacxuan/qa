<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_User_List {

	public static function getAllListQuestions($key = "") {		
		if ($key != "") {
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);	
			$con = '{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}';
		} else {
			$con = "";
		}

		$mdb = Mongo_DB::instance();		
		$result = $mdb->execute('function (){					
				 return db.qa.find(' . $con .').toArray()	
			}');	
		
		return $result;
		
		
		
	}
	

	public static function getAllListQuestoinsByCon($key = "", $limit, $offset) {
		
		if ($key != "") {
			$key = preg_quote($key);
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);	
			$con = '{$or:[{question_content:{ $regex: "'.$key.'",$options: "im"}},{question_title:{ $regex: "'.$key.'",$options: "im"}}]}';
		} else {
			$con = "";
		}

		$mdb = Mongo_DB::instance();		
		$result = $mdb->execute('function (){
				var a = [];		
				db.qa.find(' . $con .').skip(' . $offset . ').limit(' . $limit . ').sort( { created_at: -1 } ).forEach(function(w){
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



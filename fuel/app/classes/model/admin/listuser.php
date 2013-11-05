<?php

use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;

class Model_Admin_Listuser {

	public static function getAllUsers($key = "") {	
		$con = "{}";
		if (!empty($key)) {
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);	
			$con = '{$or:[{username:{ $regex: "'.$key.'",$options: "im"}},{name:{ $regex: "'.$key.'",$options: "im"}}]}';
		}

		$mdb = Mongo_DB::instance();
		$result = $mdb->execute('function (){					
				 return db.user.find(' . $con .').toArray();
			}');	
		
		return $result;		
	}
	

	public static function getAllUsersByCon($key = "", $limit, $offset) {
		$con = "{}";
		if (!empty($key)) {
			$key = str_replace(" ","|" ,$key);
			$key = str_replace("　","|" ,$key);
			$con = '{$or:[{username:{ $regex: "'.$key.'",$options: "im"}},{name:{ $regex: "'.$key.'",$options: "im"}}]}';
		}

		$mdb = Mongo_DB::instance();		
		$result = $mdb->execute('function (){						
				return db.user.find(' . $con .', {username:1, name:1, email:1, banned:1, created_at:1}).skip(' . $offset . ').limit(' . $limit . ').sort( { created_at: -1 }).toArray();
			}');	
		
		return $result;
	}
	
	public static function ban($id, $val){
		$mdb = Mongo_DB::instance();
		$ret = $mdb->where(array('_id'=>new MongoId($id)))->update('user', array('banned'=>$val));
	}
}
?>



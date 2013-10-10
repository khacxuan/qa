<?php		

use Fuel\Core\Mongo_Db;

class Model_User_Followings {
	
	static public function get_followings($user_id) {
		$mongodb = \Mongo_Db::instance();
		$result = $mongodb->execute('function (){
				var result = [];
				var follow = db.user.find({_id: ObjectId("'.$user_id.'")}).toArray();
				if(follow.length > 0 && follow[0]["follow"]){
					follow[0]["follow"].forEach(function(id){
						var user = db.user.find({_id: id}).toArray();
						if(user.length > 0){
							result.push({
								_id: user[0]["_id"],
								username: user[0]["username"]
							});
						}
					});
				}
				return result;
			}');
		return $result;
	}
	
}

?>
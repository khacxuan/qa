<?php		

use Fuel\Core\Mongo_Db;

class Model_User_Followers {
	
	static public function get_followers($user_id) {
		$mongodb = \Mongo_Db::instance();
		$result = $mongodb->execute('function (){
				var result = [];
				db.user.find({follow: ObjectId("'.$user_id.'")}).forEach(function(w){
					var count = db.user.find({_id: ObjectId("'.$user_id.'"), follow: w._id }).count();
					result.push({
						_id: w._id,
						username: w.username,
						count_follow: count
					});
				});
				return result;
			}');
		return $result;
	}
	
}

?>
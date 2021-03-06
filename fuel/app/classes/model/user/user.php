<?php
use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;
class Model_User_User {

	/*
	 * return Array()
	 */
	public static function checkUserExists($email = '', $password = '', $checkpass = FALSE){
		$condition = array();
		$condition['email'] = $email;
		$mongodb = Mongo_Db::instance();
		if ($checkpass == TRUE) {
			$condition['password'] = Crypt::encode($password);
			$mongodb->select(array('email', 'name'));
			//$flag_social = Config::get('flag_social');
			//$condition['flag'] = $flag_social['none'];
		}
		$mongodb->where($condition);
		$users = $mongodb->get('user');
		return $users;
	}

	public static function checkEmailExists($email = '', $id = null){

		$condition = array();
		$condition['email'] = $email;

		$mongodb = Mongo_Db::instance();
		$mongodb->where($condition);
		$users = $mongodb->get('user');
		if (count($users) > 0) {
			if ((string)$users[0]['_id'] == (string)$id) {
				return TRUE;
			}
		}

		return $users;
	}

	/*
	 * return Boolean
	*/
	public static function insertUser($data = array()) {
		if (empty($data)){
			return FALSE;
		}

		if (!isset($data['email']) && !isset($data['name']) && !isset($data['password'])) {
			return FALSE;
		}

		if (empty($data['email']) && empty($data['name']) && empty($data['password'])) {
			return FALSE;
		}

		if (count(self::checkUserExists($data['email'])) > 0) {
			return FALSE;
		}
		$mongodb = Mongo_Db::instance();
		$time = time();
		$flag_social = Config::get('flag_social');
		$id = $mongodb->insert('user', array(
				//'username' => $data['username'],
				'email' => $data['email'],
				//'flag' => $flag_social['none'],
				'id_facebook' => '',
				'token_facebook' => '',
				'id_github' => '',
				'token_github' => '',
				'id_twitter' => '',
				'token_twitter' => '',
				'name' => $data['name'],
				'password' => Crypt::encode($data['password']),
				'created_at' => $time,
				'updated_at' => $time,
		));
		if ($id == false) {
			return FALSE;
		}
		return $id;
	}

	/*
	 * return Boolean
	*/
	public static function updateUser($data = array()) {

		if (empty($data)){
			return FALSE;
		}

		if (!isset($data['name']) && !isset($data['email'])) {
			return FALSE;
		}

		if (empty($data['name']) && empty($data['email'])) {
			return FALSE;
		}

		$time = time();
		$updatedata = array(
								'name' => $data['name'] ,
								'email' => $data['email'],
								'updated_at' => $time
							);

		if (!empty($data['password'])) {
			$updatedata['password'] = Crypt::encode($data['password']);
		}

		$mongodb = Mongo_Db::instance();
		$id = $mongodb->where(array('_id' => $data['id']))->update('user', $updatedata);
		if (!empty($data['tags'])) {
			$arr_tags['tag_ids'] = Model_User_Question::getTagids(explode(',',$data['tags']));
			$collection = $mongodb->get_collection('user');
			$collection->update(array('_id' => $data['id']),
					array('$set' => $arr_tags)
			);
		}
		if ($id == true) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * return Boolean
	*/
	public static function insertUserFB($data = array()) {
		if (empty($data)){
			return FALSE;
		}

		$mongodb = Mongo_Db::instance();
		$time = time();
		$id = $mongodb->insert('user', $data);
		if ($id == false) {
			return FALSE;
		}
		return $id;
	}

	/*
	 * return Boolean
	*/
	public static function updateUserSocial($id = 0, $data = array()) {
		if (empty($data)  && empty($id)){
			return FALSE;
		}

		$mongodb = Mongo_Db::instance();
		$time = time();
		$id = $mongodb->where(array('_id' => $id))->update('user', $data);
		if ($id == false) {
			return FALSE;
		}
		return TRUE;
	}

	/*
	 * return array
	 */
	public static function getAllUser($id, $limit = 0, $start = 0){
		$mongodb = Mongo_Db::instance();
		$mongodb->select(array('name'));
		$mongodb->where_ne('_id' ,$id);
		if (0 != $limit) {
			$mongodb->limit($limit)->offset($start);
		}
		$users = $mongodb->get('user');
		return $users;
	}

	/*
	 * return Array()
	*/
	public static function getUserById($id = 0){
		if ($id == 0) {
			return array();
		}
		$mongodb = Mongo_Db::instance();
		$mongodb->select(array('email', 'name'));
		$mongodb->where(array('_id' => new MongoId($id)));
		$users = $mongodb->get_one('user');
		return $users;
	}

	/*
	 * return Array()
	*/
	public static function is_exist($arr = '', $select = array()){
		$arr_re = array();
		if ($arr == '') {
			return $arr_re;
		}
		$mongodb = Mongo_Db::instance();
		if (empty($select)) {
			$mongodb->select();
		}
		else {
			$mongodb->select($select);
		}
		$mongodb->where($arr);
		$users = $mongodb->get('user');
		return $users;
	}

	/*
	 * return Array()
	*/
	public static function updateFB($where = '', $arr = ''){
		if ($arr == '' || $where == '') {
			return FALSE;
		}
		$mongodb = Mongo_Db::instance();
		$check = $mongodb->where($where)->update('user', $arr);
		if ($check == true) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * return Boolean
	*/
	public static function checkFollowed($id = 0, $idFollow = 0){
		if ($idFollow == 0) {
			return FALSE;
		}
		$mongodb = Mongo_Db::instance();
		$collection = $mongodb->get_collection('user');
		$re = $collection->find(array('_id' => $id, 'follow' => new MongoId($idFollow)));
		$re = iterator_to_array($re);
		//$mongodb->where(array('_id' => $id, 'follow' => new MongoId($idFollow)));
		//$re = $mongodb->get_one('user');
		if (count($re) > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * return Boolean
	*/
	public static function updateFollow($id = 0, $userFollow = 0) {
		if (empty($id) || empty($userFollow)){
			return FALSE;
		}
		$check = self::getUserById($userFollow);
		if (empty($check)) {
			return FALSE;
		}
		$mongodb = Mongo_Db::instance();
		$collection = $mongodb->get_collection('user');
		$re = $collection->update(array('_id' => $id),
								array('$addToSet' => array('follow' => new MongoId($userFollow)))
							);
		if ($re == true) {
			return TRUE;
		}
		return FALSE;
	}

	/*
	 * return Array()
	*/
	public static function getTags($arr = array()){
		$data = array();
		if (empty($arr)) {
			return $data;
		}
		$mongodb = Mongo_Db::instance();
		$mongodb->where_in('_id', $arr);
		$mongodb->select(array('name'));
		return $mongodb->get('tags');
	}

	/*
	 * return Boolean
	*/
	public static function removeFollow($id = 0, $userFollow = 0) {
		if (empty($id) || empty($userFollow)){
			return FALSE;
		}
		$check = self::getUserById($userFollow);
		if (empty($check)) {
			return FALSE;
		}
		$mongodb = Mongo_Db::instance();
		$collection = $mongodb->get_collection('user');
		$re = $collection->update(array('_id' => $id),
				array('$pull' => array('follow' => new MongoId($userFollow)))
		);
		if ($re == true) {
			return TRUE;
		}
		return FALSE;
	}

	public static function getCountQuestionAndAnswer($id = 0) {
		$arr = array();
		if (empty($id)){
			return $arr;
		}
		$check = self::getUserById($id);
		if (empty($check)) {
			return $arr;
		}

		$mongodb = Mongo_Db::instance();
		$count = 'function (id) {
			var objId = ObjectId(id);
			/*var fmap = function() {
					for(var i in this.answers){
						if(this.answers[i].by == sid) {emit(this.answers[i].by, this.answers[i].content)};
					}
				};
			var freduce = function(k, vals) {
					return vals;
				};
			var re = db.qa.mapReduce(
							fmap,
							freduce,
							{
								query: {answers: {$elemMatch: {by: objId}}},
								out: "eventCounts",
								scope: {sid: id}
							}
					);
			var ans_num = 0;
			if (re.ok == 1) {
				var collect = db.getCollection("eventCounts");
				var value = collect.findOne();
				if (value != null) {
					ans_num = value.value;
				}
			}*/

			var que_num = db.qa.find({"questioner": objId}, {question_content: 1}).toArray();
			var ans_num = db.qa.find({answers: {$elemMatch: {by: objId}}}).toArray();
			var tag = Array();
			var tag_num = db.qa.find({answers: {$elemMatch: {by: objId}}}).forEach(function(d){
					for(var i = 0; i < d.tag_ids.length; i++) {
						if (tag.indexOf(d.tag_ids[i].str)) {
							tag.push(d.tag_ids[i].str);
						}
					}
			});
			return {ans:ans_num,ques:que_num, tags: tag.length};
		}';
		$re = $mongodb->execute($count,array($id));

		if ($re['ok'] == 1 && !empty($re['retval'])) {
			$arr = $re['retval'];
		}
		return $arr;
	}
}
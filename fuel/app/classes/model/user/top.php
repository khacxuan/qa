<?php
use Fuel\Core\Mongo_Db;
use Fuel\Core\Crypt;
class Model_User_Top {

	public static function getTop($id){
		/* $mongodb = Mongo_Db::instance();
		$collection = $mongodb->get_collection('user');
		$re = $collection->find(array('_id' => $id)); */
		
		//var_dump(iterator_to_array($re));
		
		//var_dump($mongodb->where(array('_id'=> $id))->get_one('user'));
		$limit = 10;
		$mongodb = Mongo_Db::instance();
		$data = 'function (id) {
			fret = db.user.findOne({_id:id});			
			fids = [];
			retF = [];
			if(fret["follow"]){
				fids = fret["follow"];
				fids.forEach(function(v){
					retF.push({fid:db.user.findOne({_id:v}), qa:db.qa.find({questioner:v}).sort({updated_at:-1}).limit('.$limit.').toArray()});
				});
			}else{
				newqas = db.qa.find().sort({updated_at:-1}).limit('.$limit.').toArray();
				newqas.forEach(function(v){
					retF.push({fid:db.user.findOne({_id:v.questioner}), qa:[v]});				
				});				
			}
			
			retI = db.qa.find({_id:id, answers:{$exists: true}}).sort({"answers.date":-1}).limit('.$limit.').toArray();
			
			
			return {fret: retF, iret:retI};
		}';
		$re = $mongodb->execute($data, array($id));
		
		//var_dump($re['retval']['fret']);
		//var_dump($re['retval']['iret']);
				
		if ($re['ok'] == 1 && !empty($re['retval'])) {
			return array('fret'=>$re['retval']['fret'], 'iret'=>$re['retval']['iret']);
		}
		
		return array('fret'=>array(), 'iret'=>array());
	}
}
<?php 
defined ('FACEBOOK_APP') or die('restricted access'); 

class Model{
	
	public function Model(){}
	
	public static function &getInstance($modelname=""){
		$modelname = strtolower($modelname);
		static $instances;
		
		if(!is_array($instances) || !is_object($instances[$modelname])){
			
			if($modelname==""){
				$instance = new Model();
			}
			else{
				require_once(ROOT.'/models/'.$modelname.'.php');
				$classname = "Model_".$modelname;
				$instance = new $classname();
			}
			
			$instances[$modelname]=$instance;
			
		}
		
		return $instances[$modelname];
	}

	function toString(){
		return "class: Model";
	}
	
	//MODIFIED NEW
	
	function getAllWeek(){
		$db=Factory::getDatabase();
		
		$query = "SELECT * FROM ikea_current_week";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
		
	}
	
	function activateWeek($week){
		$db=Factory::getDatabase();
		$set_to_zero = 0;
		
		//making all the weeks to 0
		$query = "UPDATE ikea_current_week SET is_active='$set_to_zero'";
		$db->setQuery($query);
		$db->query();
		
		//now make the choosen one active
		$query = "UPDATE ikea_current_week SET is_active= 1 WHERE id='$week'";
		$db->setQuery($query);
		$db->query();
	}
	
	function getPrevActiveWeek(){
		$db=Factory::getDatabase();
		
		$query = "SELECT * FROM ikea_current_week WHERE is_active = 1";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result;
	}
	//for  view data
	function getIkeaUsers($id){
		$db=Factory::getDatabase();
		$query = "SELECT u.*,t.tag_name,p.image_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id='$id' AND u.photo_id = p.id AND t.id=u.tag_id";
		//$query = "SELECT * FROM ikea_users WHERE photo_id='$id'";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;		
	}
	//for random number generation
	function ikeaUsers($weekId){
		$db=Factory::getDatabase();
		$query = "SELECT u.*,t.tag_name, p.image_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id='$weekId' AND u.photo_id = p.id AND t.id=u.tag_id AND t.can_win = 1 and u.is_winner =0 order by u.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	}
	//NOT IN USE
	function getRandUser($weekId,$randNum){
		$db=Factory::getDatabase();
		$limit = 1;
		$query = "SELECT u.*,t.tag_name,p.image_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id=1 AND u.photo_id = p.id AND t.id=u.tag_id ORDER BY u.id LIMIT 8,1";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result;
	}
	
	function checkUserWon($fb_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_users WHERE fb_id = '$fb_id' AND is_winner = 1";
		$db->setQuery($query);
		$result = $db->loadObject();
		
			if(empty($result))
				return false;
			else 
				return true;
		//return $result;
	}
	/*
	function updateWinner($id){
		$db=Factory::getDatabase();
		$query = "UPDATE ikea_users SET is_winner = 1 WHERE id='$id'";
		$db->setQuery($query);
		$db->query();
	}
	*/
	function getWinners($weekId){
		$db=Factory::getDatabase();
		$query = "SELECT u.*,p.image_name,t.tag_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id='$weekId' AND u.photo_id = p.id AND t.id=u.tag_id AND u.is_winner =1 order by u.id;";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	}
	function getIkeaUsersForWeek($weekId){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_users WHERE photo_id='$weekId'";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
		
	}
	/* Modified Jansu */
	function get_photos_details(){
		$db=Factory::getDatabase();
		
		$query = "SELECT * FROM ikea_photos";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function update_photo_details($data){
		$db=Factory::getDatabase();
		$set_to_zero = 0;
		$query = "UPDATE ikea_photos SET is_active='$set_to_zero'";
		$db->setQuery($query);
		$db->query();
		$status = 1;
		$query = "UPDATE ikea_photos SET is_active = '$status' WHERE image_name = '$data'";
		$db->setQuery($query);
		$db->query();
	}
	
	function load_prev_photo(){
		$db=Factory::getDatabase();
		$is_active = 1;
		$query = "SELECT image_name FROM ikea_photos WHERE is_active='$is_active'";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function getWinnableTagsOfWeek($weekId){
		$db=Factory::getDatabase();
		$query = "SELECT t.id,t.tag_name FROM ikea_tags t, ikea_photos p WHERE t.can_win=1 AND p.week_id ='$weekId' AND t.photo_id = p.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getCountCandidatesOfTag($tagId){
		$db=Factory::getDatabase();
		$query = "select count(*) from ikea_users where tag_id='$tagId' AND fb_id not in (select fb_id from ikea_users where is_winner=1)";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getActiveWeekId(){
		$db=Factory::getDatabase();
		$query = "SELECT id FROM ikea_current_week WHERE is_active=1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	function getPrevWinners(){
		$db=Factory::getDatabase();
		$query = "SELECT u.*, t.tag_name, p.week_id FROM ikea_users u, ikea_tags t, ikea_photos p where is_winner=1 AND p.id = u.photo_id AND t.id = u.tag_id;";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	}

	function getAllWinnableItems(){
		$db=Factory::getDatabase();
		$query = "SELECT t.id,t.tag_name,p.week_id FROM ikea_tags t, ikea_photos p WHERE t.can_win=1 AND t.photo_id = p.id;";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getIkeaUsersWeekTagId($weekId, $tagId){
		$db=Factory::getDatabase();
		$query = "SELECT u.*,t.tag_name, p.image_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id='$weekId' AND t.id='$tagId' AND u.photo_id = p.id AND t.id=u.tag_id AND t.can_win = 1 and u.is_winner =0 order by u.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getRandomWinner($weekId,$tagId,$randNum){
		$db=Factory::getDatabase();
		$limit = 1;
		$query = "SELECT u.*,t.tag_name,p.image_name FROM ikea_users u,ikea_photos p, ikea_tags t WHERE p.week_id='$weekId' AND t.id='$tagId' AND u.photo_id = p.id AND t.id=u.tag_id ORDER BY u.id LIMIT $randNum,$limit";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result;
	}
	
	function confirmWinner($tableId){
		$db=Factory::getDatabase();
		$query = "UPDATE ikea_users SET is_winner =1 WHERE id='$tableId'";
		$db->setQuery($query);
		$db->query();
		return $query;
	}
}
?>
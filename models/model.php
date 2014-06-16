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
	
	//MODIFIED JANSU
	function get_active_photos(){
		//check if "preview" set, then user the preview week id instead of current week id.
		if(isset($_REQUEST['previewweek']))
			$query = "SELECT p.* FROM ikea_photos p WHERE p.week_id = " . $_REQUEST['previewweek'];
		else 	
			$query = "SELECT p.* FROM ikea_photos p, ikea_current_week w WHERE w.is_active=1 AND p.week_id = w.id";
			
		
		$db=Factory::getDatabase();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;

	}
	
	function isTodayAlreadyTag($fb_id,$photo_id=""){
		$db=Factory::getDatabase();
		$today = date("Y-m-d") . " 00:00:00";
		$query = "SELECT id FROM ikea_users WHERE tag_time > '$today' AND fb_id='$fb_id'";
		if(!empty($photo_id))
			$query .= " AND photo_id='$photo_id'";
		$db->setQuery($query); 
		$result = $db->loadResult();
		if(empty($result))
			return false;
			
		return true;
	}
	
	function getTags($active_photos,$userTags=""){
		$photo_ids = "";
		foreach($active_photos as $photo){
			$tagged = false;
			foreach($userTags as $tag){
				if($tag->photo_id == $photo->id)
					$tagged = true;
			}
			if(!$tagged)
				$photo_ids .= intval($photo->id).",";
		}
		
		//remove last comma
		if(!empty($photo_ids))
			$photo_ids = substr($photo_ids, 0, strlen($photo_ids)-1);
		else return "";
			
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_tags WHERE photo_id IN ($photo_ids)";
		$db->setQuery($query);  
		$result = $db->loadObjectList();
		return $result;
	}
	
	//get user tag for today!
	function getUserTags($fb_id){
		$db=Factory::getDatabase();
		$today = date("Y-m-d") . " 00:00:00";
		$today_end = date("Y-m-d") . " 23:59:59";
		$query = "SELECT t.*,u.photo_id FROM ikea_tags t, ikea_users u WHERE t.id=u.tag_id AND u.fb_id='$fb_id' AND u.tag_time < '$today_end' AND u.tag_time > '$today';";
		$db->setQuery($query);  
		$result = $db->loadObjectList();
		return $result;		
	}
	
	
	function get_active_photo_details($id)
	{
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_tags WHERE photo_id='$id'";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}	
	
	function getTag($tag_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_tags WHERE id = '$tag_id'";
		$db->setQuery($query);  
		$result = $db->loadObject();
		return $result;
	}
	
	function save_ikea_users($fb_id, $profile,$photo_id,$tag_id){
		$fb_email = $profile["email"];
		$fb_name = $profile["name"];
		$db=Factory::getDatabase();
		
		//check if user tag the photo first time
		$query = "SELECT id FROM ikea_users WHERE fb_id='$fb_id'";
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if(empty($result)){//first time
			$canvas_dir = Configuration::canvas_dir;
			$app_url = Configuration::app_url;
			$attachment = array('message' => '',
                'name' => Configuration::autoPost_name,
                'caption' => Configuration::autoPost_caption,
                'link' => "$app_url",
                'description' => '',
                'picture' => "$canvas_dir"."images/ikeaApp_ProfileGfx.jpg"
                );

			$facebook = Helper::getFacebook();
    		$result = $facebook->api('/me/feed/',
                                'post',
                                $attachment);
		}
		
		$query = "INSERT INTO ikea_users 
					(fb_id, fb_name, fb_email, photo_id, tag_id, tag_time) 
					VALUES
					('$fb_id','$fb_name','$fb_email','$photo_id','$tag_id',NOW())";
		$db->setQuery($query);
		$db->query();

		/*
		else{
			$query = "UPDATE ikea_users SET
						tag_id='$tag_id',
						tag_time=NOW()
						WHERE photo_id='$photo_id' AND fb_id='$fb_id'";
			$db->setQuery($query);
			$db->query();
		}
		*/
	}
	
	function getPhotos($week_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_photos WHERE week_id = '$week_id'";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getWinners(){
		$db=Factory::getDatabase();
		$query = "SELECT u.*,t.tag_name,t.tag_image_name FROM ikea_users u,ikea_tags t WHERE u.is_winner = '1' AND u.tag_id=t.id";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	function getStopContest(){
		$db=Factory::getDatabase();
		$query = "SELECT stop_contest FROM ikea_stop_contest";
		$db->setQuery($query);
		return $db->loadResult();
	}
			
	/*
	function check_photo_tag_id($photo_id,$tag_id){
		$db=Factory::getDatabase();
		
		$query = "SELECT id FROM ikea_tags WHERE id='$tag_id' AND photo_id='$photo_id'";
		$db->setQuery($query); 
		$result = $db->loadResult();
		return $result;
	}
	
	function check_tagged($fb_id,$photo_id){
		$db=Factory::getDatabase();
		
		$query ="SELECT fb_id FROM ikea_users WHERE fb_id='$fb_id' AND photo_id='$photo_id'";
		
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if(empty($result))
			return false;
		else 
			return true;
	}
	
	function check_first_time($fb_id,$week_id){
		$db=Factory::getDatabase();
		
		$query = "SELECT * FROM ikea_users WHERE fb_id='$fb_id' AND week_id='$week_id'";
		$db->setQuery($query);
		$result = $db->loadObject();
		print_r($result);exit();
		return $result;
	}
	
	function get_tagged_tag_details($tag_id){
		$db=Factory::getDatabase();
		
		$query = "SELECT * FROM ikea_tags WHERE id='$tag_id'";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	
	
	
	function getPrevTag($fb_id, $photo_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_users WHERE fb_id='$fb_id' AND photo_id='$photo_id' ";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result;
	}
	
	function getPrevTagDetails($tag_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_tags WHERE id='$tag_id'";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result;
	}
	
	function getPrevPhoto($photo_id){
		$db=Factory::getDatabase();
		$query = "SELECT * FROM ikea_photos WHERE id='$photo_id'";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result;
	}
	//END MODIFIED
	*/
	function recordLog($fb_id,$message) {
		$db=Factory::getDatabase();
		
		$query = "INSERT INTO log (fb_id,log_message,log_time) VALUES ('$fb_id'.'$message', NOW())";
		$db->setQuery($query);
		$db->query();
	}
	function getmain($app_id){
            include "connect_to_mysql.php";
            $db=Factory::getDatabase();
            $query="select * from appdetail where app_id=".$app_id;
            
        }
}
?>
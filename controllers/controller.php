<?php 
defined ('FACEBOOK_APP') or die('restricted access'); 

class Controller{
	
	public function Controller(){}
	
	public static function &getInstance($controllername=""){
		$controllername = strtolower($controllername);
		static $instances;
		if(!is_array($instances) || !is_object($instances[$controllername])){
			
			if($controllername==""){
				$instance = new Controller();
			}
			else{
				require_once(ROOT.'/controllers/'.$controllername.'.php');
				$classname = "Controller_".$controllername;
				$instance = new $classname();
			}
			
			$instances[$controllername]=$instance;
		}
		
		return $instances[$controllername];
	}
	
	function excute(){		
		$document = Factory::getDocument();
		
		$task = Request::getVar('task', 'defaultView');

		//validate user
		$this->$task();
	}	
	
	function defaultView(){	
		$week_id = Request::getVar("week_id");
		
		$model = Factory::getModel();
		$document = Factory::getDocument();
		$facebook = Helper::getFacebook();

		$fb_id = $facebook->getUser();
		//GETTING THE ACTIVE PHOTOS FIRST
		$active_photos = $model->get_active_photos();
		if(intval($week_id) >= intval($active_photos[0]->week_id) || empty($week_id)){
			$document->addRef("active_photos",$active_photos);
			if(empty($active_photos))
				die('No Active Photos');
				
			//check user already tag/not
			//$todayAlreadyTag = $model->isTodayAlreadyTag($fb_id);
			
			//get user tag for today
			$userTags = $model->getUserTags($fb_id);
			$document->addRef("userTags",$userTags);
			
			/*
			if(!$todayAlreadyTag){ //list all the available tag
				$tags = $model->getTags($active_photos);
				$document->addRef("tags",$tags);
				
			}
			*/
			$stopContest = $model->getStopContest();
			if(!$stopContest) {
				//list all the availabe tag that user have not tag today
				$tags = $model->getTags($active_photos,$userTags);
				$document->addRef("tags",$tags);
			}
			
			$this->viewLanding();
		}
		else
			$this->viewSpecificWeek();
		
		/*
		//CHECKING WHETHER THE USER HAS TAGGED THE PHOTO PASSING
		//$first_check = $model->check_first_time($fb_id,$active_photos[0]->id);
		$first_check = "";
		
		if(!empty($first_check)){
			//$document->addRef("first_check","$first_check");
			$tagged_details = $model->get_tagged_tag_details($first_check->tag_id);
			$document->addRef("tagged_details",$tagged_details);
		}
		else{
			//*Getting the active photo
			//$active_photo_info = $model->get_active_photo();
			//$document->addRef("active_photo_info",$active_photo_info);
			//Get the details of active photo
			$tags = $model->get_active_photo_details($active_photo_info->id);	

			$document->addRef("tags",$tags);
		}
				
		$this->viewLanding();
		*/
	}
	
	function submitTag(){
		$model = Factory::getModel();

		//$photo_id = Request::getVar('photo_id');
		$tag_id = Request::getVar('tag_id');
		$current_photo = Request::getVar('current_photo'); //this is photo count for javascript, not photo id!!

		/* GETTING USERS DETAILS */
		$facebook = Helper::getFacebook();
		$fb_id = $facebook->getUser();
		$profile = Helper::getProfile($facebook);
		
		$active_photos = $model->get_active_photos();
		$tagDetail = $model->getTag($tag_id);
		//only if the tag belong to the active photo is a VALID TAG!
		$validTag = false;
		foreach($active_photos as $photo){
			if($photo->id == $tagDetail->photo_id){
				$photo_id = $photo->id;
				$validTag = true; break; 
			}				
		}
		if(!$validTag)
			die("Invalid Tag");
		
		//check user already tag/not
		$document = Factory::getDocument();
		$stopContest = $model->getStopContest();
		$todayAlreadyTag = $model->isTodayAlreadyTag($fb_id,$photo_id);
		if($stopContest) {
			$document->addRef("message","Contest already closed");
		}
		else if($todayAlreadyTag) {
			$document->addRef("message","You already tag this photo today");
		}
		else {
			//save the user's tag
			$model->save_ikea_users($fb_id, $profile,$photo_id,$tag_id);
			
			$document->addRef("current_photo",$current_photo);
			$document->addRef("message","Successfully tagged");
		}
		$this->defaultView();
			
		/*
		//checking whether the tag belongs to the photo
		$check = $model->check_photo_tag_id($photo_id,$tag_id);
		//print_r($check);

		if(!empty($check)){
				
			//checking for the user has alredy tagged the photo
			$user_already_tagged = $model->check_tagged($fb_id,$photo_id);

			//saving the users and tags to the database
			if(!$user_already_tagged){
				$model->save_ikea_users($fb_id, $profile["email"],$photo_id,$tag_id);
				
				$this->defaultView();
			}
		}
		else {
			die("Invalid Tag");
		}
		*/
	}
	
	function viewLanding() {
		$model = Factory::getModel();
		$winners = $model->getWinners();
		$document = Factory::getDocument();
		$document->addRef("winners",$winners);
		$document->view = "landing";	
	}
	
	function viewSpecificWeek(){
		$week_id = Request::getVar("week_id","1");
		$facebook = Helper::getFacebook();
		$fb_id = $facebook->getUser();
		
		
		$model = Factory::getModel();	
		$document = Factory::getDocument();
		
		$winners = $model->getWinners();
		$document->addRef("winners",$winners);
		
		$photos = $model->getPhotos($week_id);
		$document->addRef("photos",$photos);
		
		$active_photos = $model->get_active_photos();
		$document->addRef("active_photos",$active_photos);
			
		//get all tag on the every week
		$userTags = $model->getUserTags($fb_id);
		$document->addRef("userTags",$userTags);
		
		$document->view="previous";
	}
	

	/* AJAX */
	function saveToAlbum(){
		$photo_name = Request::getVar("photo_name");
		
		$facebook = Helper::getFacebook();
		$facebook->setFileUploadSupport(true);
		$args = array('message' => 'I just tagged myself to my favourite IKEA product , hope I win it!');
		$FILE_PATH = "images/$photo_name";
		$args['image'] = '@' . realpath($FILE_PATH);
		//$args['link'] = Configuration::app_url;
		
		$data = $facebook->api('/me/photos', 'post', $args);
		
		if(empty($data) || empty($data["id"])){
			echo "ERROR Failed to Publish to Album."; 
			exit();
		}
		
		echo "Successfully saved to album.";
		$document = Factory::getDocument();
		$document->ajax = true;
		
	}
	
	function saveToComputer(){
		$photo_name = Request::getVar("photo_name");
		$directory = "images/";
		
		header("Content-Type: image/png");
		header("Content-Disposition: attachment; filename=$photo_name");
		ob_clean();
		readfile($directory.$photo_name);
		
		//header("Content-Disposition: attachment; filename=$filename");
		//header("Content-Type: application/force-download");
		//header("Content-Disposition: attachment;filename=\"$filename\"");
		//echo file_get_contents($directory.$file);
		
	}
	
	/*NOT USING START FROM HERE
	function afterPhotoSave()
	{
		$model = Factory::getModel();
		$document = Factory::getDocument();
		$facebook = Helper::getFacebook();

		$fb_id = $facebook->getUser();
		//GETTING THE ACTIVE PHOTO FIRST
		$active_photo_info = $model->get_active_photo();
		$document->addRef("active_photo",$active_photo_info);
		
		//CHECKING WHETHER THE USER HAS TAGGED THE PHOTO PASSING
		$first_check = $model->check_first_time($fb_id,$active_photo_info->id);
		
		if(!empty($first_check)){
		//$document->addRef("first_check","$first_check");
			$tagged_details = $model->get_tagged_tag_details($first_check->tag_id);
			$document->addRef("tagged_details",$tagged_details);
		}
		else{
		//*Getting the active photo
			$active_photo_info = $model->get_active_photo();
		//$document->addRef("active_photo_info",$active_photo_info);
		//Get the details of active photo
			$tags = $model->get_active_photo_details($active_photo_info->id);	

			$document->addRef("tags",$tags);
		}
		
		$savedMsg="Photo Saved!";
		$document->addRef("message",$savedMsg);
		
		$document->view = "landing";
	
	}
	
	
	
	function prevPhoto(){
		$model = Factory::getModel();
		$document = Factory::getDocument();
		$facebook = Helper::getFacebook();
		$fb_id = $facebook->getUser();
		$photoId = Request::getVar("photo_id");
		
		//FOR THE DARKENING THE NON ACTIVE TABS AND PRINT THE LINK ON THEM
		$active_photo_info = $model->get_active_photo();
		$document->addRef("active_photo",$active_photo_info);

		if($active_photo_info->id == $photoId){
			$this->defaultView();	
		}
		else{
			//GETTING THE DETAILS OF PREVIOUSLY TAGGED PHOTO SO THAT THE USER CAN SEE WHAT HE TAGGED IN THE PREVIOUS WEEK
			//GETTING THE TAG ID
			$prevTag = $model->getPrevTag($fb_id,$photoId);
			
			$prevTagDetails = $model->getPrevTagDetails($prevTag->tag_id);
			
			$prevPhoto = $model->getPrevPhoto($photoId);
			
			$document->addRef("prevPhoto",$prevPhoto);
			$document->addRef("prevTag",$prevTag);
			$document->addRef("prevTagDetails",$prevTagDetails);
			$document->view = "previous";
		}
	}
	*/

}
?>
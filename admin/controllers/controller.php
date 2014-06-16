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
		
		$facebook = Helper::getFacebook();
		$fb_id = $facebook->getUser();

		$admin = Helper::isAdmin($facebook);
		if(!$admin) die("no access");
		
		$task = Request::getVar('task', 'defaultView');
		
		//validate user
		$this->$task();
	}
	
	function defaultView(){
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		/*
		//GETTING ALL THE PHOTOS IN THE TABLE AND
		$photo_details = $model->get_photos_details();
		$document->addRef("photo_details",$photo_details);
		
		//GET THE PHOTO THAT WAS PREVIOUSLY CHOSEN IF ANY
		$prev_photo = $model->load_prev_photo();
		
		//ADDING REFERENCE TO THE DOCUMENT
		if(!empty($prev_photo)){
		$document->addRef("prev_photo",$prev_photo);
		}
		else{
		$prev_photo="No Image In Use";
		$document->addRef("prev_photo",$prev_photo);
		}
		*/
		//get the active week if any
		$prevActiveWeek = $model->getPrevActiveWeek();
		$document->addRef("prevActiveWeek",$prevActiveWeek);	
		
		//get all the week id stored for activating the week in the database for choosing purpose
		$getAllWeek = $model->getAllWeek();
		
		//get the active week
		$activeWeekId = $model->getActiveWeekId();
		
		//get all the winnable items for all the week
		$allWinnableItems = $model-> getAllWinnableItems();
		
		//get the previous winners
		$prevWinners = $model->getPrevWinners();
		
		$document->addRef("prevWinners",$prevWinners);
		$document->addRef("activeWeekId",$activeWeekId);
		$document->addRef("allWinnableItems",$allWinnableItems);
		$document->addRef("allWeeks",$getAllWeek);
		$document->view = "home";
	}
	
	function submitAction(){
		$week = Request::getVar('action_list');
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		$model->activateWeek($week);
		
		$this->defaultView();
		//$document->view = "home";
	}
	
	function viewData(){
		$weekId = Request::getVar('weekId');
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		$ikeaUsers = $model->getIkeaUsers($weekId);
		
		$document->addRef('ikeaUsers',$ikeaUsers);
		$document->addRef('weekId',$weekId);
		
		$this->defaultView();
		
	}
	
	function genRandWinners(){
		$weekId = Request::getVar('weekId');
		//echo $weekId;
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		//select all IDs of winnable items of this week
		
		$winnableTags= $model->getWinnableTagsOfWeek($weekId);
		
		//for each item, select the count of candidates, then choose one from the candidates
		foreach ($winnableTags as $tag){
			$count= $model->getCountCandidatesOfTag($tag->id);
			
			//if()
			
			$randNumber = rand(0,$count-1);
			
			$winner = $candidates[$randNumber];
			
			//update winner status to won
			$model->updateWinner($winner->id);
					
		}
		
		
		/*
		//$foundWinner = false;
		$numWinner = 0;
		
		//while($foundWinner == false){
		$ikeaUsers = $model->ikeaUsers($weekId);
		$document->addRef("posWinners",$ikeaUsers);
		//counting the ikea users to create rand numbers
		$countIkeaUsers = count($ikeaUsers); 
		//echo($countIkeaUsers);
		//exit();
		$randMax = $countIkeaUsers-1;
		
		if($countIkeaUsers >= 4){
			while($numWinner <= 3){
		
			//generating random number
			$randNumber = rand(0,$randMax);
	
		
			$userWonPrev = $model->checkUserWon($ikeaUsers[$randNumber]->fb_id);
			//if the userWonPrev is empty then update otherwise repeat
				if($userWonPrev == false){
					$model->updateWinner($ikeaUsers[$randNumber]->id);
					//$document->addRef('$numWinner',$winner);
					$numWinner++;
				}
			}
		
		
			$winners = $model->getWinners($weekId);	
	
			$document->addRef('winners',$winners);
			
			$this->defaultView();
		}
		else{
			$document->addRef("error","Not enough people have tag the winnable item");
			$document->addRef("posWinners",$ikeaUsers);
			$this->defaultView();	
		}
		*/
		
		
	}
	
	function exportData(){
		$weekId = Request::getVar('weekId');
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		
		$ikeaUsers = $model->getIkeaUsers($weekId);
		
		$document->addRef("ikea_users",$ikeaUsers);
		$document->addRef("weekId",$weekId);
			
		$document->template = 'template_csv_new.php';
		$document->view = "template_csv_new";
		//print_r($ikea_users);
		//exit();
		
	}
	
	function genRandomWinner(){
		$tagId = Request::getVar('tagId');
		$weekId = Request::getVar('weekId');
		
		$model = Factory::getModel();
		$document = Factory::getDocument();
		
		$ikeaUsersWeekTagId = $model->getIkeaUsersWeekTagId($weekId, $tagId);
		$countIkeaUsersWeekTagId = count($ikeaUsersWeekTagId); 
		
		$randMax = $countIkeaUsersWeekTagId-1;

		$randNum = rand(0,$randMax);
		
		$randomWinner = $model->getRandomWinner($weekId,$tagId,$randNum);
				
		$document->addRef("randWinner",$randomWinner);
		$document->addRef("randNum",$randNum);
		
		$this->defaultView();
	}
	
	function confirmWinner(){
		$tableId = Request::getVar('tableId');
		$document = Factory::getDocument();
		$model = Factory::getModel();
		
		$query = $model->confirmWinner($tableId);
		
		$document->addRef("query",$query);
		$this->defaultView();
	}
	
}
	
?>
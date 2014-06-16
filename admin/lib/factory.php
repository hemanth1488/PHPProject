<?php 
defined ('FACEBOOK_APP') or die('restricted access'); 

class Factory{
	
public static function & getModel($modelname=""){
		require_once(ROOT.'/models/model.php');
		$instance = Model::getInstance($modelname);
		return $instance;
		
	}
	

	public static function & getController($controllername=""){
		require_once(ROOT.'/controllers/controller.php');
		$instance = Controller::getInstance($controllername);	
		return $instance;
	}

	public static function & getDocument(){
		static $instance;
		if(!is_object($instance)){
			require_once('../lib/document.php');
			$instance = Document::getInstance();
		}
		
		return $instance;
		
	}
	
	public static function & getConfig(){
		static $instance;
		if(!is_object($instance)){
			require_once('../lib/configuration.php');
			$instance = new Configuration();
		}
		
		return $instance;	
	}

	
	public static function & getDatabase(){
		static $instance;
		if(!is_object($instance)){
			require_once('../lib/database.php');
			$instance = Database::getInstance();
		}
		
		return $instance;
	}

}
?>
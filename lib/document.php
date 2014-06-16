<?php 
defined ('FACEBOOK_APP') or die('restricted access');  

class Document{
	var $title;
	var $tab;
	var $view;
	var $redirect;
	var $ref;
	var $template;
	var $ajax;
	
	private function Document(){
		$this->title	= '';
		$this->tab 		= '';
		$this->view 	= '';
		$this->redirect	= '';
		$this->ref = array();
		$this->ajax = false;
		$this->template = 'template.php';
	} 
	
	public static function &getInstance(){
		static $instance;
		if(!is_object($instance)){
			$instance = new Document();
		}
		return $instance;
	}
	
	function addRef($key="", $val=""){
		if($key=="") return;
		$this->ref[$key] = $val;
	}
}
?>
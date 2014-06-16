<?php 
defined ('FACEBOOK_APP') or die('restricted access'); 

class Request{
	
	public static function getVar($var, $default='', $hash=''){
		if($hash=='post'){
			$value = $_POST[$var];
		}
		else if($hash=='get'){
			$value = $_GET[$var];
		}
		else{
			$value = $_REQUEST[$var];
		}
		
		if(empty($value)) $value=$default;
		
		return $value;
		
	} 
	
}
?>
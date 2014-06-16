<?php 
defined("FACEBOOK_APP") or die("restricted access");

require_once './lib/configuration.php';
require_once './lib/SimpleImage.php';
//require_once './sdk/facebook.php';
require_once './sdk_3.1.1/facebook.php';
require_once('./lib/factory.php'); //for record log

class Helper
{
	var $con;
	
	//
	// Common Functions
	//
	public function __construct() {
	$conf=new 	Configuration();
	}
	public static function getFacebook(){
			$conf=new Configuration();
		$appid = 1403012253280061;
		$appsecret = $conf->getapp_secret();
		
		// Create our Application instance 
		$facebook = new Facebook(array(
		  'appId'  => $appid,
		  'secret' => $appsecret,
		  'cookie' => true,
		  'domain' => $_SERVER["SERVER_NAME"]
		));
		
		//try get a valid session
		/*check session on SDK 2.2
		if (!$facebook->getSession()) Helper::requestPermission($facebook);	
		*/
		
		//check session on SDK 3.0
		$model = Factory::getModel();
		$fb_id = $facebook->getUser();
		
		if (!$facebook->getUser()) {
			//record log everytime invalid session
			//$model->recordLog("0","getUser return false or 0");
			Helper::requestPermission($facebook);	
		}
		else {
		 	try {
	        	// Proceed knowing you have a logged in user who's authenticated.
	        	$user_profile = $facebook->api('/me');
	      	} catch (FacebookApiException $e) {
	        	//you should use error_log($e); instead of printing the info on browser
	        	//d($e);  // d is a debug function defined at the end of this file
	        	//$model->recordLog($fb_id,$e);
	        	Helper::requestPermission($facebook);	
	      	}
		}
		return $facebook;
	}
	
	public static function isSessionExpiredException($facebook){
		$signedRequest = $facebook->getSignedRequest();
		$token = $signedRequest['oauth_token'];

		return empty($token);
		
	}
	
	public static function requestPermission($facebook){
		/* SDK 2.2
		$url = $facebook->getLoginUrl(array(
				'canvas' => 1,
				'fbconnect' => 0,
				'req_perms' => Configuration::req_perms,
				'next' => Configuration::app_url, 
				'cancel_url' => Configuration::fan_page_url,
				));
		*/
		//loginurl SDK 3.0
		$conf=new 	Configuration();
		$url = $facebook->getLoginUrl(array(
				'scope' => Configuration::req_perms,
				'redirect_uri' => $conf->getapp_url(), 
				));
		echo "<script type='text/javascript'>top.location.href = '$url';</script>";
		
		exit(); //prevent further execution of php code.
		
	}
	
	public static function connectDB(){
		//open connection
		$con = mysql_connect(Configuration::db_host, Configuration::db_username, Configuration::db_password);
		if (!$con)
		  	die('Could not connect: ' . mysql_error());
		mysql_select_db(Configuration::db_database,$con);
	}
	
	
	public static function escape($str){
		if (!$con) Helper::connectDB(); //must connect to db, in order to run escape
		if(get_magic_quotes_gpc()) $str=stripslashes($str);
		return mysql_real_escape_string ($str);
	}	
	
	public static function filterScript($str) {
		//look for < tag and remove it
		return str_replace("<", "", $str);
	}
	/*
	function checkSignature(){
		//ONLY check parameters presented in GET
		$params = Configuration::shared_key;
		ksort($_GET);
		foreach($_GET as $key => $val){
			if($key=='sig') continue;
			$params = $params.$key.$val;
		}
		//echo $params; echo "<br/>"; echo md5($params);echo "<br/>";
		return (md5($params)==$_GET['sig']);
		
	}
	
	function signResponse($output){
		//output string should be a plain string such as 1234 or helloworld
		//or a xml formated string, eg. <a b="c"></a>
		$key = Configuration::shared_key; 
		$sig = md5($key.$output);
		$response = '<response sig="'.$sig.'"><result>'.$output.'</result></response>';
		return $response;
	}
	*/
	
	//automatically post to user wall,please make sure stream_publish permission allowed
	public static function autoPostWall($facebook,$message,$picture_link,$link,$description,$name){
		$session = $facebook->getSession();
		$access_token = $session['access_token'];
		if ($session) {			
	    	try { 
			    $post_id = $facebook->api('/me/feed', 'post', array(
				    'access_token' => "$access_token",
				    'message'=> "$message", 
				    'picture' => "$picture_link", 
				    'link'=> "$link",
				    'description'=> "$description",
				    'name'=> "$name",
	    		) );
	    		return $post_id['id'];
	 		} catch (FacebookApiException $e) {
	        	return;//if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
	      	}
	    }
	     
	    return null;
	}
	
	public static function checkPageLiked($facebook) {
		try{
			$response = $facebook->api(array(
			      'method' => 'fql.query',
			      'query' => 'SELECT uid FROM page_fan WHERE page_id="'.Configuration::fan_page_id.'" AND uid=me()',
			    ));
			$like = $response[0]['uid'];	
			return $like;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	public static function getFbFriends($facebook) {
		try{
			$friends = $facebook->api(array(
			      'method' => 'fql.query',
			      'query' => 'SELECT uid2 FROM friend WHERE uid1 = me()',
			    ));	
			return $friends;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	//post id = userid_appid
	public static function getPostLike($facebook,$fb_post_id) {
		try {
			$user_like = $facebook->api(array(
		      'method' => 'fql.query',
		      'query' => 'SELECT user_id FROM like WHERE post_id = "'.$fb_post_id.'"',
		    ));
		    return $user_like;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	//post id = userid_appid
	public static function getPostComment($facebook,$fb_post_id) {
		try{
			$multiQuery = '{
	        		"query1": "SELECT fromid FROM comment WHERE post_id=\"'.$fb_post_id.'\"",
	         		"query2": "SELECT uid FROM user WHERE uid IN (SELECT fromid FROM #query1)"
	          		}';
			$user_comment = $facebook->api(array(
		      'method' => 'fql.multiquery',
		      'queries' => $multiQuery,
		    ));
		    return $user_comment[1]['fql_result_set'];
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	public static function isAdmin($facebook) {
		$admin_ids = Configuration::admin_id;
		$array = explode(",", $admin_ids);
		
		$fb_id = $facebook->getUser();
		
		foreach($array as $admin_id) {
			if($admin_id == $fb_id)
				return true;
		}
		return false;
	}
	
	public static function isTester($facebook) {
		$tester_ids = Configuration::tester_id;
		$array = explode(",", $tester_ids);
		
		$fb_id = $facebook->getUser();
		
		foreach($array as $tester_id) {
			if($tester_id == $fb_id)
				return true;
		}
		return false;
	}
	
	//modify as necessary , for now : full name and email
	public static function getProfile($facebook) {
		try{
			$response = $facebook->api(array(
			      'method' => 'fql.query',
			      'query' => 'SELECT name,email FROM user WHERE uid=me()',
			    ));
			$profile = $response[0];
			//$fb_name = $response[0]['name'];
			//$fb_email = $response[0]['email'];
			
			//$profile = (object) array("fb_name" => $fb_name);
			return $profile;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	public static function formatNewLine($str) {
		return str_replace("\n", "<br/>", $str);
	}
	
	public static function redirectTo($url){
		/*
		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			header( 'HTTP/1.1 303 See Other' );
			header( 'Location: ' . $url );
		}
		*/
		echo "<script>top.location.href='$url';</script>\n";
	}
	
	public static function sendMail($to,$from,$subject,$message,$header="") {
		$headers = "From: $from" . "\r\n" . $header;
		return mail($to, $subject, $message, $headers);
	}
}
?>
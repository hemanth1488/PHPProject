<?php 
defined("FACEBOOK_APP") or die("restricted access");

require_once '../lib/configuration.php';
require_once '../lib/SimpleImage.php';
require_once '../sdk/facebook.php';

class Helper
{
	var $con;
	
	//
	// Common Functions
	//
	
	function getFacebook(){
				
		$appid = Configuration::app_id;
		$appsecret = Configuration::app_secret;
		
		// Create our Application instance 
		$facebook = new Facebook(array(
		  'appId'  => $appid,
		  'secret' => $appsecret,
		  'cookie' => true,
		  'domain' => $_SERVER["SERVER_NAME"]
		));
		
		//try get a valid session
		//check session.
		if (!$facebook->getSession()) Helper::requestPermission($facebook);	
		return $facebook;
	}
	
	function isSessionExpiredException($facebook){
		$signedRequest = $facebook->getSignedRequest();
		$token = $signedRequest['oauth_token'];

		return empty($token);
		
	}
	
	function requestPermission($facebook){
		$url = $facebook->getLoginUrl(array(
				'canvas' => 1,
				'fbconnect' => 0,
				'req_perms' => Configuration::req_perms,
				'next' => ''
				));
		echo "<script type='text/javascript'>top.location.href = '$url';</script>";
		
		exit(); //prevent further execution of php code.
		
	}
	
	function connectDB(){
		//open connection
		$con = mysql_connect(Configuration::db_host, Configuration::db_username, Configuration::db_password);
		if (!$con)
		  	die('Could not connect: ' . mysql_error());
		mysql_select_db(Configuration::db_database,$con);
	}
	
	
	function escape($str){
		if (!$con) Helper::connectDB(); //must connect to db, in order to run escape
		if(get_magic_quotes_gpc()) $str=stripslashes($str);
		return mysql_real_escape_string ($str);
	}	
	
	function filterScript($str) {
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
	function autoPostWall($facebook,$message,$picture_link,$link,$description,$name){
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
	
	function checkPageLiked($facebook) {
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
	
	function getFbFriends($facebook) {
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
	function getPostLike($facebook,$fb_post_id) {
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
	function getPostComment($facebook,$fb_post_id) {
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
	
	function isAdmin($facebook) {
		$admin_ids = Configuration::admin_id;
		$array = explode(",", $admin_ids);
		
		$fb_id = $facebook->getUser();
		
		foreach($array as $admin_id) {
			if($admin_id == $fb_id)
				return true;
		}
		return false;
		/*
		try{
			$admin = $facebook->api(array(
		      'method' => 'fql.query',
		      'query' => 'SELECT uid FROM page_admin WHERE uid = me() AND page_id = "'.Configuration::fan_page_id.'"',
		    ));
		    if(empty($admin)) return false;
		    return true;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
		*/
	}
	
	//modify as necessary , for now : full name and email
	function getProfile($facebook) {
		try{
			$response = $facebook->api(array(
			      'method' => 'fql.query',
			      'query' => 'SELECT name FROM user WHERE uid=me()',
			    ));
			
			$fb_name = $response[0]['name'];
			
			$profile = (object) array("fb_name" => $fb_name);
			return $profile;
		}catch(FacebookApiException $e){
			if(Helper::isSessionExpiredException($facebook)) Helper::requestPermission($facebook);
		}
	}
	
	function formatNewLine($str) {
		return str_replace("\n", "<br/>", $str);
	}
	//
	// Application Specific Functions
	//
	
	function saveAmbassador($ambassador_fb_id,$ambassador_name) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		//check duplicate 
		$result = Helper::getAmbassador($ambassador_fb_id);
		if(!empty($result)) return "Ambassador already exist";
		
		$query = "INSERT INTO ambassador (ambassador_fb_id,ambassador_name) VALUES ('$ambassador_fb_id','$ambassador_name')";
		mysql_query($query);
	}
	
	function removeAmbassador($ambassador_fb_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "DELETE FROM post WHERE ambassador_fb_id='$ambassador_fb_id'";
		mysql_query($query);
		
		$query = "DELETE FROM ambassador WHERE ambassador_fb_id='$ambassador_fb_id'";
		mysql_query($query);		
	}
	
	function getAllAmbassador() {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT * FROM ambassador ORDER by id DESC";
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_object($result)){
			array_push($array, $row);
		}		
		mysql_free_result($result);
		return $array;
	}
	
	function setAmbassadorActive($ambassador_fb_ids) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		//set all ambassador to inactive
		$query = "UPDATE ambassador SET ambassador_active=FALSE";
		mysql_query($query);
		
		//set active ambassador
		$query = "UPDATE ambassador SET ambassador_active=TRUE WHERE ambassador_fb_id IN ($ambassador_fb_ids)";
		mysql_query($query);
	}
	
	function getAmbassador($ambassador_fb_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT * FROM ambassador WHERE ambassador_fb_id = '$ambassador_fb_id'";
		$result = mysql_query($query);
		$obj = mysql_fetch_object($result);
		return $obj;
	}
	
	//from active ambassador
	function getAllPost() {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT p.*,a.ambassador_fb_id,a.ambassador_name,a.ambassador_active FROM post p,ambassador a WHERE p.ambassador_fb_id = a.ambassador_fb_id AND a.ambassador_active=1 ORDER by p.post_time DESC;";
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_object($result)){
			array_push($array, $row);
		}		
		mysql_free_result($result);
		return $array;
	}
	
	function getPost($ambassador_fb_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT * FROM post WHERE ambassador_fb_id = '$ambassador_fb_id' ORDER by post_time DESC";
		$result = mysql_query($query);

		$array = array();
		while($row = mysql_fetch_object($result)){
			array_push($array, $row);
		}		
		mysql_free_result($result);
		return $array;
	}
	
	function getSpecificPost($post_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT p.*,a.ambassador_fb_id,a.ambassador_name,a.ambassador_active FROM post p,ambassador a WHERE p.ambassador_fb_id = a.ambassador_fb_id AND p.id= '$post_id'";
		$result = mysql_query($query);
		$obj = mysql_fetch_object($result);
		return $obj;
	}
	
	function checkUploadLimit($ambassador_fb_id) {
		$start_date = strtotime(Configuration::start_date . " 00:00:00");
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$dif = $today - $start_date;
		
		//check in which week
		$week = ceil($dif/(7*24*3600));
		
		//1week = 7*24*3600
		$start_week = $start_date + ( ($week-1) * (7*24*3600));
		$end_week = $start_date + ( $week * (7*24*3600));
		
		//convert to YYYY-mm-dd
		$start_week = date('Y-m-d', $start_week);
		$end_week = date('Y-m-d', $end_week);
		
		$query = "SELECT COUNT(*) FROM post WHERE ambassador_fb_id='$ambassador_fb_id' AND post_time > '$start_week' AND post_time < '$end_week'";
		$result  = mysql_query($query);
		$row = mysql_fetch_row($result);
		if(intval($row[0]) < 2) return true;
		return false;
	}
	
	//YOUTUBE ONLY!
	function extractVideoCode($video_link) {
		$video = explode("?v=",$video_link);
		//remove string other than code
		$video_code = explode("&",$video[1]);
		return $video_code[0];
	}
	
	function saveVideoUrl($ambassador_fb_id,$title,$video_link,$post_id="") {
		//check with DB
		if (!$con) Helper::connectDB();
		
		if(empty($post_id)) {
			$query = "INSERT INTO post (ambassador_fb_id,post_type,post_content,post_time,post_title) VALUES ('$ambassador_fb_id',
					'video','$video_link',NOW(),'$title')";
			mysql_query($query);
		}
		else {
			$query = "UPDATE post SET post_type='video', post_content='$video_link', post_title='$title' WHERE ambassador_fb_id='$ambassador_fb_id' AND id='$post_id'";
			mysql_query($query);
		}
	}
	
	function saveText($ambassador_fb_id,$title,$msg,$post_id="") {
		//check with DB
		if (!$con) Helper::connectDB();
		
		if(empty($post_id)) {
			$query = "INSERT INTO post (ambassador_fb_id,post_type,post_content,post_time,post_title) VALUES ('$ambassador_fb_id',
					'text','$msg',NOW(),'$title')";
			mysql_query($query);
		}
		else {
			$query = "UPDATE post SET post_type='text', post_content='$msg', post_title='$title' WHERE ambassador_fb_id='$ambassador_fb_id' AND id='$post_id'";
			mysql_query($query);
		}
	}
	
	function saveImage($ambassador_fb_id,$title,$post_id="") {
		$extension = strtolower(substr(strrchr($_FILES['img']['name'], '.'), 1));
		//allowed extension
		if (($extension!= "jpg") && ($extension != "jpeg") && ($extension!= "png")) 
			return "Unknown extension";
		
		//check file exist/not
		$now = time(); //added timestamp to avoid cache
		$image_name = $ambassador_fb_id . "_$now." . $extension;
		$dir_path = "./upload_images/" . $image_name; 
		
		$action = move_uploaded_file($_FILES['img']['tmp_name'], $dir_path);
		if (!$action) return 'Failed Uploading';
		
		
		//create thumbnail
		$img = new SimpleImage();
		$img->load($dir_path);
		//$img->resizeToWidth(50);
		//$img->resizeToHeight(50);
   		$img->resizeAndCrop(150,150);
   		//$img->resizeAndFill(180,190);
		$img->save('./thumbnails/'.$image_name);
		
		
		//save to db
		//check with DB
		if (!$con) Helper::connectDB();
		
		if(empty($post_id)) {
			$query = "INSERT INTO post (ambassador_fb_id,post_type,post_content,post_time,post_title) VALUES ('$ambassador_fb_id',
					'image','$image_name',NOW(),'$title')";
			mysql_query($query);
		}
		else {
			$query = "UPDATE post SET post_type='image', post_content='$image_name', post_title='$title' WHERE ambassador_fb_id='$ambassador_fb_id' AND id='$post_id'";
			mysql_query($query);
		}
	}
	
	function getActiveLink() {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT * FROM active_link";
		$result = mysql_query($query);
		$row = mysql_fetch_row($result);
		
		return $row[0];
	}
	
	function setActiveLink($active_link) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "UPDATE active_link SET active=$active_link";
		$result = mysql_query($query);
	}
	
	function saveComment($fb_id,$fb_name,$post_id,$content) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "INSERT INTO comments (fb_id,fb_name,post_id,comment_content,comment_time) VALUES ('$fb_id','$fb_name','$post_id','$content',NOW())";
		mysql_query($query);
		
		
		$result = '<li class="comment_content" style="color:white"><span class="comment_by">'.$fb_name.' says</span><span class="comment_by" style="float:right"></span><p>'.$content.'</p></li>';
		return $result;
	}
	
	function getComments($post_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		if(empty($post_id)) {}
		
		$query = "SELECT *,DATE_FORMAT(comment_time, '%d-%m-%Y %H:%i') as newdate FROM comments"; 
		if(!empty($post_id))
			$query .= " WHERE post_id = '$post_id'";
		$query .=" ORDER by comment_time DESC";
		
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_object($result)){
			array_push($array, $row);
		}		
		mysql_free_result($result);
		return $array;
	}
	
	function checkAmbassadorActive($post_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "SELECT a.ambassador_active FROM post p,ambassador a WHERE a.ambassador_fb_id=p.ambassador_fb_id AND p.id='$post_id'";
		$result = mysql_query($query);
		$row = mysql_fetch_row($result);
		if($row[0] == 1) return true;
		
		return false;
	}
	
	function deleteComment($comment_id) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "DELETE FROM comments WHERE id='$comment_id'";
		mysql_query($query);
	}
	
	function updateComment($comment_id,$content) {
		//check with DB
		if (!$con) Helper::connectDB();
		
		$query = "UPDATE comments SET comment_content='$content' WHERE id = '$comment_id'";
		mysql_query($query);
	}
	
	//save image from for tinymce
	function uploadImage() {
		$extension = strtolower(substr(strrchr($_FILES['img']['name'], '.'), 1));
		//allowed extension
		if (($extension!= "jpg") && ($extension != "jpeg") && ($extension!= "png")) 
			return "Unknown extension";
		
		//check file exist/not
		$now = time(); //added timestamp to avoid cache
		$image_name = $now ."_". $_FILES['img']['name'];
		$dir_path = "./upload_images/" . $image_name; 
		
		$action = move_uploaded_file($_FILES['img']['tmp_name'], $dir_path);
		if (!$action) return 'Failed Uploading';
		
		
		//create thumbnail
		$img = new SimpleImage();
		$img->load($dir_path);
		//$img->resizeToWidth(100);
		//$img->resizeToHeight(100);
   		//$img->resizeAndCrop(100,100);
   		$img->resizeAndFill(120,120);
		$img->save('./thumbnails/'.$image_name);
	}
	
	function sendMail($to,$from,$subject,$message,$header="") {
		$headers = "From: $from" . "\r\n" . $header;
		return mail($to, $subject, $message, $headers);
	}
	
}
?>
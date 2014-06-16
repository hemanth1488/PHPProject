<?php 
defined("FACEBOOK_APP") or die("restricted access");
session_start();
class Configuration{
	
	/*
	var $db_host = "localhost";
	var $db_database = "ikea";
	var $db_username = "root";
	var $db_password = "";
	*/
	public function __construct() {
	include "connect_to_mysql.php";
     $sql = mysql_query("SELECT * FROM appdetail where app_id=1403012253280061");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){
	$app_name=$row['app_name'];
	 $db_database = $row['app_name'];
	 $app_url = $row['app_url'];
	 $app_id =$row['app_id'];
	 $app_secret = $row['app_secret'];
	
	//facebook settings
	
	 $fan_page_id = $row['fan_page_id']; ////"239569229428097";
	 $fan_page_url= $row['fan_page_url']; ////"https://www.facebook.com/IKEAMalaysia?sk=app_142118839219863";
	}
  }
  }
	
	var $db_host = "localhost";
	
	var $db_username = "admin";
	var $db_password = "admin";
	
	public static function getapp_id(){
	return $app_id;
	}
	public static function getapp_secret(){
	return $app_secret;
	}
	public static function getapp_url()
	{return $app_url;
	}
	
	//const $app_name=$app_namea;
//const $app_url=$app_urla;
//const $app_id=$app_ida;
//const $app_secret=$app_secreta;
	const req_perms = "email, publish_stream";//publish_stream,offline_access,email";
	const canvas_dir = "https://apps.facebook.com/testikea/";
	
	//admin id
	const admin_id = "100002088804629,1326184708,647877250,544195053,665216253,582106125,100002146993967,668146027,788067785"; //separate by comma if there is more than 1 admin
	
	//tester id
	const tester_id = "100002088804629,1326184708,647877250,544195053,665216253,582106125,100002146993967"; //separate by comma if there is more than 1 tester
	
	const autoPost_name = "IKEA Malaysia Click, Tag & Win!";
	const autoPost_caption = "I just tagged myself to my favourite IKEA product, hope I win it!";
}
?>
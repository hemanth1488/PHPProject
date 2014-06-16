<?php 
session_start();
ini_set("display_errors", 1);
//error_reporting(E_ALL ^ E_NOTICE);

// Set flag that this is a parent file
define('FACEBOOK_APP', 1 );
define('ROOT', dirname(__file__));
define('BASE', ($_SERVER['HTTPS']=='on'?'https://' : 'http://' ). $_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], '/')+1));


$_SESSION["appid"]=1403012253280061;
require_once('./lib/factory.php');
require_once('./lib/request.php');
require_once('./lib/helper.php');



//required for IE in iframe FB environments if sessions are to work.
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

$controller = Factory::getController();
$document = Factory::getDocument();
$template = Request::getVar('template');

$facebook = Helper::getFacebook();
$fb_id = $facebook->getUser();

//check page liked?
//$like = Helper::checkPageLiked($facebook);
//if(empty($like)) {
	//$url = Configuration::fan_page_url;
	//echo "<script type='text/javascript'>top.location.href = '$url';</script>";
	//exit();
//}


//print_r($test);
//exit();
$controller->excute();

//display
if($document->redirect) Helper::redirectTo($document->redirect);
else if($document->ajax) {
	echo $document->ref['msg'];
	exit();
}
else{
		//normal with template
		include($document->template);
}
?>
<?php
//session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL ^ E_NOTICE);

// Set flag that this is a parent file
define('FACEBOOK_APP', 1 );
define('ROOT', dirname(__file__));
define('BASE', ($_SERVER['HTTPS']=='on'?'https://' : 'http://' ). $_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], '/')+1));


require_once('./lib/factory.php');
require_once('./lib/request.php');
require_once('./lib/helper.php');
require_once './lib/configuration.php';

//required for IE in iframe FB environments if sessions are to work.
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

//$facebook = Helper::getFacebook();
$appid = Configuration::app_id;
$appsecret = Configuration::app_secret;

// Create our Application instance 
$facebook = new Facebook(array(
  'appId'  => $appid,
  'secret' => $appsecret,
  'cookie' => true,
  'domain' => $_SERVER["SERVER_NAME"]
));

		
$signedRequest = $facebook->getSignedRequest();

if(!$signedRequest)	$signedRequest = $_SESSION['signedRequest'];
else				$_SESSION['signedRequest'] = $signedRequest;

if(!$signedRequest){
	?>
	<script type='text/javascript'>
		top.location.href ='<?php echo Configuration::fan_page_url . "?sk=app_" . Configuration::app_id?>';
	</script>
<?php 
}else if(!$signedRequest['page']['liked']){
?>
	<html><head>
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-26052138-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	</head><body style="margin:0; padding:0;"><img src="before_like_v2.jpg"/></body></html>
<?php
}else{
	?>
	<html><head>
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-26052138-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	</head><body style="margin:0; padding:0;"><a href="<?php echo Configuration::app_url?>index.php" target="_top"><img src="after_like_v2.jpg"/></a></body></html>
<?php 
}
?>
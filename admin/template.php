<?php
defined ('FACEBOOK_APP') or die('restricted access');
?>
<html>
<head>
	 <link href='css/styles.css' rel='stylesheet' type='text/css' media='all'/>
	 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/admin-js.js"></script>
	
	<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
	<script>
	function framesetsize(w,h){
	    var obj = new Object;
	    obj.width=w;
	    obj.height=h;
	    FB.Canvas.setSize(obj);
	}
	</script>
</head>
<body onload="framesetsize(720,$(document).height()+400)">
<div id="fb-root"></div>
<?php 

?>	

	<?php if($document->view) include(ROOT.'/views/'.$document->view.'.php'); ?>
	<div class="clear"></div>
</body>
</html>
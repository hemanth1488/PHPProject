<?php
defined ('FACEBOOK_APP') or die('restricted access'); 
$active_photos = $document->ref["active_photos"];
$week_id = $active_photos[0]->week_id;
$winners = $document->ref["winners"];
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
	<title>IKEA Click, Tag & Win Contest on Facebook</title>

	<meta http-equiv='content-type' content='text/html;charset=ISO-8859-1' />
	<meta name='description' content='-' />
	<meta name='keywords' content='-' />
	<meta name='robots' content='index,follow' />

    <link href='css/ikea_styles.css' rel='stylesheet' type='text/css' media='all'/>
	<link rel='shortcut icon' href='favicon.ico' />
	
	<!--[if lte IE 8]>
	<link href='css/IE8.css' rel='stylesheet' type='text/css' media='all'/>
	<![endif]-->
	<!--[if lte IE 7]>
	<link href='css/IE7.css' rel='stylesheet' type='text/css' media='all'/>
	<![endif]-->
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.alerts.js"></script>
	 <link href='css/jquery.alerts.css' rel='stylesheet' type='text/css' media='all'/>
	<script type="text/javascript" src="js/ikea_js.js"></script>
	<script type="text/javascript" >
	$(document).ready(function(){
		<?php if(!empty($document->ref["message"])){
			echo "showMessage();";	
		}
		?>
		
	});

	function redirect(url){
		window.open(url, '_blank'); 
		return false;
	}
	</script>
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/supersleight-custom.js"></script>
		<script>
			$(document).ready(function() {
				$('#home').supersleight({shim: 'images/x.gif'});
			});
		</script>
		<link href='css/IE6.css' rel='stylesheet' type='text/css' media='all'/>
	<![endif]-->
	
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
	
</head>

<body onload="" class="phase2">
<div id="fb-root"></div>		
		<div id="home">
		<div id="top_banner">
			<img id="click_logo" src="images/click_logo.png" onclick="showInstruction();"></img>
			<img id="ikea_logo" src="images/ikea_logo.png" onclick="redirect('<?php echo Configuration::fan_page_url;?>');"></img>
		</div>

		<div class="clear"></div>
		
		<div id="content">
		<?php if($document->view) include(ROOT.'/views/'.$document->view.'.php');?>
		</div>
	
		
		<div id="sboxes" style="background:url('images/yellow.png')">
			<div id="box1" style="background:url('images/blue.png');cursor:default;"><span class="s1">A NEW DEPARTMENT</span><br /> <span class="s1">EVERY WEEK:</span></div>
			
			<div id="box2" style="background:url('images/blue<?php if($week_id != 1) echo "_darker"?>.png');cursor:pointer;>"
			 onclick="<?php if($week_id == 1 || $week_id == 2 || $week_id == 3) echo "window.location='index.php?week_id=1'";?>">
			<span class="s1">WEEK 1:</span> <br /> <span class="s2">LIVING ROOM</span></div>
			
			<div id="box3" style="background:url('images/blue<?php if($week_id != 2) echo "_darker"?>.png');<?php if($week_id == 2 || $week_id == 3) echo "cursor:pointer;"; else echo "cursor:default";?>"
			onclick="<?php if($week_id == 2 || $week_id == 3) echo "window.location='index.php?week_id=2'";?>">
			<span class="s1">WEEK 2:</span> <br /> <span class="s2">WORKSPACES</span></div>
			
			<div id="box4" style="background:url('images/blue<?php if($week_id != 3) echo "_darker"?>.png');<?php if($week_id == 3) echo "cursor:pointer;"; else echo "cursor:default";?>"
			onclick="<?php if($week_id == 3) echo "window.location='index.php?week_id=3'";?>">
			<span class="s1">WEEK 3:</span> <br /> <span class="s2">BEDROOM &amp; CHILDREN'S IKEA</span></div>
			
			<div class="clear"></div>
		</div>	
		<div id="winner_bar">
			<div id="label"><span style="cursor:pointer" onclick="showWinnerList();">WINNERS:<p style="font-size:10px;margin: 0;">[Click here to view prizes]</p></span></div>
			<?php 
				$total_winner = count($winners);
				foreach($winners as $winner){
					$usersName = $winner->fb_name; //json_decode(file_get_contents("https://graph.facebook.com/$winner->fb_id"))->name;
					?>
					<div class="winner_box">
						<a href="<?php echo "https://www.facebook.com/profile.php?id=".$winner->fb_id;?>" target="_blank"><img src="https://graph.facebook.com/<?php echo $winner->fb_id;?>/picture" onmouseover="$(this).attr('title','<?php echo $usersName;?>');"/></a>
					</div>
					<?php 
				}
				for($i=0;$i<(12-$total_winner);$i++){
					?>
					<div class="winner_box">
						<img src="images/user.gif"/>
					</div>
					<?php 
				}
			?>
			<div class="clear"></div>
		</div>
		<div id="footer">
			<img src="images/fb_share_btn.jpg" onclick="postToWall()";/> &nbsp;
			<img src="images/fb_invite_btn.jpg" onclick="invite();"/> &nbsp;
			<img src="images/twitter_btn.png" onclick="shareTwitter();" style="margin-left:10px;"/> &nbsp;
			<span style="margin-left:140px;">&copy; Inter IKEA Systems B.V. 2011</span>
			<span style="margin-left:20px;"><a href="http://www.ikea.com/ms/en_MY/privacy_policy/privacy_policy.html"  target="_blank">Privacy Policy</a></span>
			<span style="margin-left:20px;"><a id="terms_cond" onclick="$('#termsCondOverlay').show();$('#termsCondPop').show();" href="#">Terms &amp; Conditions</a></span>
			<div class="clear"></div>
			<br/>
			<span>Best viewed in IE9, Chrome, Firefox 7.0.1</span>
		</div>	
		
		<div id="popupOverlay" style="display: none;" onclick="$('#popupOverlay').hide();$('.popUpBox, #termsPop').fadeOut(); return false;"></div>
		<div id="termsPop" class="popUpBox" style="display: none">
			<a onclick="$('#popupOverlay').hide();$(this).parent().fadeOut(); return false;" class="closePopUp" ><img src="images/close.png" alt="Close" title="Close" /></a>
			<div id="terms_content">
				
			</div>
			<div class="clear"></div>
		</div>	
		<div id="messagePop" class="popUpBox" style="display: none">
			<a onclick="$('#popupOverlay').hide();$(this).parent().fadeOut(); return false;" class="closePopUp" ><img src="images/close.png" alt="Close" title="Close" /></a>
			<div>
				<span id="msg"><?php echo $document->ref["message"]; ?></span>
			</div>
		</div>
		
		<div id="loadingOverlay" style="display: none;"></div>
		<div id="loadingPop" class="popUpBox" style="display: none">
			
			<img src="images/loader.gif"/>
		</div>
		
		<div id="termsCondOverlay" style="display: none;"></div>
		<div id="termsCondPop" style="display:none;overflow-y:scroll;max-height:450px;">
			<div style="overflow-x:none;">
			<img id="termsCondClose" src="images/close.png" onclick="$('#termsCondPop').hide();$('#termsCondOverlay').hide();"/>
			<div class="title"> <u><b>T &amp;C for IKEA Malaysia Click, Tag &amp; Win Contest</b></u></div>
			<div>
			<p>
			<u><b>Contest Mechanics</b></u>
			</p>
				<ul>
				<li><p>Each week for 3 weeks, IKEA Malaysia will showcase spreads within  the IKEA  Catalogue 2012 where fans can tag products featured for a chance to win them.</p></li>
				<li><p>The spreads featured will be Living Room, Workspaces and Bedroom/Children’s  IKEA. Each person can tag 1 product per spread per day and the same winner cannot win more than once throughout the Contest. The more tags you have, the higher your chances of winning!</p></li>
				<li><p>Contestants are required to provide accurate personal particulars and contact details to be eligible for winner notification and prize collection.</p></li> 
				<li><p>IKEA will not be liable for registrations that are deemed incomplete or falsified and winners can’t be notified. These inaccurate entries will be automatically removed and disqualified from the Contest without prior notice.</p></li>
				<li><p>Fans get a chance to win 4 different products from IKEA per week in this Contest. Winners are selected at random by the Contest application and will be announced every Thursday on IKEA Malaysia’s Facebook wall. </p></li>
				</ul>
			<u><b>Prize Collection</b></u>
			<ul>
				<li><p>Winners will be notified by email. If a winner cannot be contacted or does not respond within five (5) working days, the prize or prize notification will be invalid.  When that happens, the potential winner forfeits all rights to any prize. All decisions of IKEA Malaysia are final and binding in all respects.</p></li>
				<li><p>All prizes are to be collected from IKEA Malaysia's Merchandise and Pick Up department ground floor:</p></li>
			</ul>
				IKEA Malaysia Address:<br />
				No.2 Jalan PJU 7/2,<br /> 
				Mutiara Damansara,<br />
				47800 Petaling Jaya,<br />
				Selangor Darul Ehsan, Malaysia
			<ul>
				<li><p>Winners are to print out the email notifying them of their win, and bring them along with their IC to collect their prize. No other party can be authorized to collect on their behalf.</p></li>
				<li><p>Winners must collect their prizes within 2 weeks upon delivery of the winner notification email. If winners fail to collect their prize within that time frame, their prizes will be forfeited.</p></li>
			</ul>
			<u><b>Eligibility</b></u>
			<ul>
				<li><p>Contest is not applicable to employees of IKEA or related agencies.</p></li>
				<li><p>This Contest is only open to Malaysia residents aged 18 years and above.</p></li>
				<li><p>Contest is only held in Malaysia. </p></li>		
			</ul>
			<u><b>Rights Of IKEA</b></u>
			<ul>
				<li><p>IKEA reserves the right at any time to cancel and/or change the terms and conditions, prizes, pricing, packaging and plans at their discretion without any prior notice. IKEA shall not be held responsible for any claims at all incurred by the winner as a result of this cancellation or change.</p></li>
				<li><p>Neither IKEA nor its officers, directors, employees, agents, successors, or assigns shall be liable for any warranty, costs, damage, injury, or any other claims incurred as a result of the usage of a prize by any winner.</p></li>
				<li><p>The decisions of IKEA on all matters relating to the results are final and no appeals or correspondence will be entertained in any way.</p></li>
				<li><p>Contestants accept and agree that their personal information may be used for marketing purposes and to fulfill disclosure requirements related to the Contest.</p></li>
				<li><p>Prizes are not transferable to another party or exchangeable for cash, credit or in kind, and products won cannot be refunded or exchanged for something else.</p></li>
				<li><p>By entering the Contest, you conclusively are deemed to have agreed to be bound by the rules and terms and conditions of the Contest. You also accept and agree that this is an irrevocable condition of entry.</p></li>
				<li><p>Participants who do not comply with these Official Rules or attempt to interfere with this Contest in any way shall be disqualified.</p></li>
			</ul>
			<u><b>Duration</b></u>
			<ul>
				<li>6th October to 26th October 2011.</li>
			</ul>
			<u><b>Sponsor</b></u>
			<ul>
				<li>IKEA Malaysia</li>
			</ul>
			<br/>
			<br/>
			<br/>
			<br/>
			</div>
			</div>
		</div>
					
		<div id="instructionPop" style="display: none;">
			<img id="instruction_close" src="images/instruction_close.png" onclick="$('#instructionPop').fadeOut();$('#popupOverlay').hide();"/>
			<div class="title">Instructions / Contest Details:</div>
			<div style="float:left;font-size:18px;margin:20px 0 0 10px;">Contest dates: 6 Oct to 26 Oct 2011</div>
			<div class="clear"></div>
			<p style="font-weight:bold;width:620px;">Each week for 3 weeks, we will showcase pages within the IKEA Catalogue 2012 where fans can tag products featured for a chance to win them. 
			</p>
			<p>The different departments featured will be Living Room, Workspaces and Bedroom/Children's IKEA. Each IKEA Fan can tag one product per page per day for a chance to win. The more you tag, the more chances to win. There are 4 different products to win each week in this contest!
			</p>
			<p>Winners will be selected at random by the Contest application and announced every Thursday on IKEA Facebook wall. No queues, no hassles, all fun - just Click, Tag &amp; Win!
			</p>	
			<p>Ready to win? Follow these 3 easy steps:
			</p>
			<p>Step 1: Browse through the pages by clicking the &lt; left and right &gt; arrows at the sides of the screen

			</p>
			<p>Step 2: Mouse over to tag your name to the products you like
			</p>
			<p>Step 3: Win it! 
			</p>
			<p>* Please take a look at our <a href="#" style="color:#006699;text-decoration:underline;" onclick="$('#instructionPop').hide();$('#popupOverlay').hide();$('#termsCondOverlay').show();$('#termsCondPop').show();">Terms &amp; Conditions</a> here for contest details.
			</p>
		</div>
		
		<div id="winnerPop" style="display: none;">
			<img id="winner_close" src="images/winner_close.png" onclick="$('#winnerPop').fadeOut();$('#popupOverlay').hide();$('#tag_items').show();"/>
			<div class="title">WINNERS!</div>
			<div class="clear"></div>
			<p>Congratulations to all who won! An IKEA representative will be in touch with you shortly.
			</p>
			<div id="winner_list_container" style="text-align:center">
			<?php 
				if(empty($winners))
					echo "Currently No Winner";
				else {
					foreach($winners as $winner){
						if(intval($winner->photo_id) < 5 ) $winner->week_id = 1;
						else if(intval($winner->photo_id) < 9) $winner->week_id = 2;
						else $winner->week_id = 3;
						?>
						<div class="winner_list">
							<div class="user">
								<a href="<?php echo "https://www.facebook.com/profile.php?id=".$winner->fb_id;?>" target="_blank"><img src="https://graph.facebook.com/<?php echo $winner->fb_id;?>/picture"/></a>
							</div>
							<img class="item" src="images/<?php echo $winner->tag_image_name.'.jpg';?>"/>
							<div class="desc">
								<?php echo $winner->fb_name;?> | Week <?php echo $winner->week_id;?><br/><?php echo $winner->tag_name;?>
							</div>
						</div>
						<?php 
					}
				}
			?>
				<div class="clear"></div>
			</div>
		</div>
		
		<div id="ptfwloadingOverlay" style="display: none;"></div>
		<div id="ptfwmessagePop" class="popUpBox" style="display:none;">
			<a><img style="float:right"; id="closeFl" class ="closePopUpFl" src="images/close.png"/ onclick="$('#ptfwmessagePop').fadeOut();$('#ptfwloadingOverlay').hide();"/></a>
			<div id="ptfwFriendList" style="overflow-y:scroll;max-height:300px;">
				
			</div>
		</div>
		
		
		<div class="clear"></div>
		
		<script type="text/javascript" src="https://platform.twitter.com/widgets.js">
		</script>

 			<script src="https://connect.facebook.net/en_US/all.js"></script>
			<script>
			
			//global variable to store the currently logged user id
			 var userId;
			 var fuid;
			 
			 FB.init({
			     appId  : '<?php echo Configuration::app_id;?>',
			     status : true, // check login status
			     cookie : true, // enable cookies to allow the server to access the session
			     xfbml  : true, // parse XFBML
			     channelUrl  : 'https://www.yourdomain.com/channel.html', // Custom channel URL
			     oauth : true // enables OAuth 2.0
			   });

			  FB.getLoginStatus(function(response) {
				  if (response.authResponse) {
				    // logged in and connected user, someone you know
						userId = response.authResponse["userID"];
					
				  } else {
				    // no user session available, someone you dont know
					    alert("Unknown User");
				  }
				});
			  
				
				function scrollTo(y) {
					FB.Canvas.scrollTo(0,y);
				}
				
				function invite()
			    {
			       FB.ui({ method: 'apprequests', 
			    	   message: "I just joined the IKEA Click, Tag and Win contest. Browse and tag your favourite IKEA products for a chance to win too!",
			          filters: ['app_non_users']});
			    }
			    function postToWall()
			    {
			    	FB.ui({ method: 'feed', 
		            message: '',
		            link : '<?php echo Configuration::app_url;?>',
		            picture: '<?php echo Configuration::canvas_dir;?>images/ikeaApp_ProfileGfx.jpg',
		            name : 'IKEA Malaysia Click, Tag & Win!',
		            caption : 'I just tagged myself to my favourite IKEA product, hope I win it!',
		            description: ''
		            });
		    	}
		    	function postToFriendWall(){
		    		FB.ui({ method: 'send', 
			        message: '',
			        link : '<?php echo Configuration::app_url;?>',
			        picture: '<?php echo Configuration::canvas_dir;?>images/living_room.png',
			        name : 'Win an IKEA item!',
			        caption : '',
			        description: "Hello"
			            });
		    	}
		    	function showFriends(){	
			    	
		    		$('#ptfwloadingOverlay').show();
		    		$('#ptfwmessagePop').show();

				    if($('#ptfwFriendList div').length > 0){
					   // alert('hello');
				    }
				    else {
		    		//$("#ptfwmessagePop").append('<div id="frenlistdiv">');
					
		    		FB.api(
		    				{	method: 'fql.query',
			    				query: 'SELECT uid1 FROM friend WHERE uid2='+userId
		    				},function(response){ //f1
								for(i=0;i<response.length;i++){
									fuid = response[i].uid1;
										FB.api(//fb.api
												{method: 'fql.query',
												 query: 'SELECT uid,first_name,name,pic_square FROM user WHERE uid='+response[i].uid1
												},function(response){
														//htmlcontent = '<fb:multi-friend-selector condensed="true" selected_rows="0" style="width: 200px;" />';
														htmlcontent ='<div class="friendsItem">';
														htmlcontent +='<img onclick="msgToPost('+ response[0].uid +');$(\'#ptfwloadingOverlay\').hide();$(\'#ptfwmessagePop\').hide();" class="frenlist" src='+response[0].pic_square+' title='+response[0].first_name+' /><br/>';
														htmlcontent +=response[0].first_name;
														htmlcontent +='</div>';
														//htmlcontent +='<input class="checkbox" type="checkbox" name="frensToPost" value='+ response[0].uid + '/>';
														$("#ptfwFriendList").append(htmlcontent);
													}
										);//fb.api
									}	
			    			}//f1
		    			
		    			);
				    }
			    }	  
		    	
			   
				function msgToPost(id){
					FB.ui({ method: 'feed',
					 	message: '',
			            link : '<?php echo Configuration::app_url;?>',
			            picture: '<?php echo Configuration::canvas_dir;?>images/ikeaApp_ProfileGfx.jpg',
			            name : 'IKEA Click, Tag & Win!',
						to : id,
			            caption : 'I just tagged myself to my favourite IKEA product , hope I win it!',
			            description: ''
			            
				     },  function(response) {
					    	    if (response && response.post_id) {
					    	        alert('Posted Successfully.');
					    	      }
					    	    else{ 
					    	    $('.frenlist').remove();
					    	    }
					     }
				     );
				    // return false;
					
	              /*  FB.ui(
	                        {
	                        method:'fbml.dialog',
	                        display: 'dialog',
	                        fbml: '',
	                        width: '300px',
	                        height: '200px'
	                        },

	                    function(response) {
	                        //alert("working");
	                        fbDialogDismiss();
	                    }); */
	            }
			
		    	function shareTwitter(){
	                 var hrefLink = '<?php echo Configuration::app_url?>';
	                 
	                 var text = "I just tagged myself to my favourite product, hope I win it!";
	                 var url ="https://twitter.com/share?url="+encodeURIComponent(hrefLink)+"&text="+encodeURIComponent(text);
	                 window.showModalDialog(url, "", "dialogWidth:620px; dialogHeight:450px; center:yes");
	             }
		    	<?php if(! isset($_SESSION['hideInstruction'])){
					$_SESSION['hideInstruction'] = "1";
					?>
		    		showInstruction();
		    	<?php } ?>
			</script>
			<div class="clear"></div>
		</div>
</body>
</html>

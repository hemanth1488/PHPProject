<?php
defined ('FACEBOOK_APP') or die('restricted access'); 
//tags has all the g
$tags = $document->ref["tags"];
$userTags = $document->ref["userTags"];
$active_photos = $document->ref["active_photos"];
//PHOTO COUNT NOT PHOTO ID !!
$defaultDisplayID = $document->ref["current_photo"];
if(empty($defaultDisplayID)) $defaultDisplayID = "0";

if(!empty($document->ref["saved"]))
$photo_saved_msg = $document->ref["saved"];
?>
<script type="text/javascript">
	var weekID;
	var currentPhotoID = <?php echo $defaultDisplayID;?>;
	var maxPhoto = <?php echo count($active_photos);?>;
	var photoName;	
</script>

<div id="background_landing">
	<div id="arrow_left">
		<a href="#" onclick="prevPhoto();"><img src="images/arrow_left.png"/></a>
	</div>
	<div id="arrow_right">
		<a href="#" onclick="nextPhoto();"><img src="images/arrow_right.png"/></a>
	</div>
	<?php 
	$count = 0;
	foreach($active_photos as $photo){
		?>
		<div id="photo_<?php echo $count;?>" class="landing" style="background:url('images/<?php echo $photo->image_name;?>') no-repeat;<?php if($count == $defaultDisplayID) echo "display:block;"?>" onclick="hideMenu();">
			<script type="text/javascript"> weekID = <?php echo $photo->week_id;?>;</script>
			<?php 
		 	if(!empty($tags)){
		 		foreach($tags as $tag){
		 			if($tag->photo_id == $photo->id){
				 	?><!-- for the id the echo was missing if anything goes wrong remove it -->
				 		<div class="tag_items_rect" style="top:<?php echo $tag->rect_pos_top."px";?>;left:<?php echo $tag->rect_pos_left."px";?>;height:<?php echo $tag->rect_ht."px";?>; width:<?php echo $tag->rect_wid."px";?>;" onmouseover="$('#tag_<?php echo $tag->id;?>').show();$(this).attr('title','<?php echo $tag->tag_name?>');" onmouseout="$('#tag_<?php echo $tag->id;?>').hide();"></div>
					 	<div class="tag_items_trans" id="tag_<?php echo $tag->id;?>" style="top:<?php echo $tag->tag_pos_top."px";?>;left:<?php echo $tag->tag_pos_left."px";?>;display:none;" onmouseover="$(this).show();$(this).attr('title','<?php echo $tag->tag_name?>');" onmouseout="$(this).hide();" onclick="onClickTag('<?php echo $tag->id?>');"></div>
				 	<?php 
		 			}
			 	}
		 	}
		 	?>
		 	
		 	<?php if(!empty($userTags)) {
		 		foreach($userTags as $tag){?>
		 			
		 		<?php 
		 			if($tag->photo_id == $photo->id){
			 		?>
			 		<script type="text/javascript"> 
						var position_top = <?php echo $tag->tag_pos_top;?>;
			  			var position_left = <?php echo $tag->tag_pos_left;?>;
			  			var photo_id = <?php echo $tag->photo_id;?>;
						var photo_name=<?php echo "'"."$photo->image_name"."'";?>;
						arrtest.push(photo_id,position_top,position_left,photo_name);
						//testing
						
						//alert(photoName);			  		
				  		</script>
				  		<div id="tag_items" class="tag_items" style = "top:<?php echo $tag->tag_pos_top."px";?>; left:<?php echo $tag->tag_pos_left."px";?>; border: 3px solid white"; onclick="showMenu(this,'<?php echo $photo->image_name;?>');" onMouseOver="$('.tag_items').attr('title','<?php echo $tag->tag_name?>');">
				  			<img id="userPic" width="57px" height="57px" src="https://graph.facebook.com/<?php echo $fb_id;?>/picture"/>
				  		</div>  		
					<?php
		 			}
		 		}
	 		} ?>
	 		
		</div>
		<?php 
		$count++;
	}
	?>	
	<div id="share_options" style="position:absolute;display:none">
		<table id="tbl_1" cellpadding="1" cellspacing="0">
			<tr>
				<td id="share_option" class="so" style="border-bottom:solid 1px black;" onclick="postToWall()">Share</td>
			</tr>
			
			<tr>
				<td id="post_friend" class="so" style="border-bottom:solid 1px black;"  onclick="showFriends()">Post to friend's wall</td>
			</tr>
			<tr>
				<td id="post_twitter" class="so" style="border-bottom:solid 1px black;" onclick="shareTwitter()">Post to Twitter</td>
			</tr>
			<tr>
				<td id="save_to_album" class="so" style="border-bottom:solid 1px black;" onclick="saveToAlbum();">Save to FB photo album</td>
			</tr>
			<tr>
				<td id="save_to_comp" class="so" onclick="saveToComputer();">Save to my computer</td>
			</tr>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function (){

	<?php if(!empty($userTags)){?>
	showMenuAuto();
	<?php }?>
	/*
	alert(arrtest);
 	var temp = jQuery.inArray(currentPhotoID, arrtest);
	temp++;
	position_top = arrtest[temp];
	temp++;
	position_left = arrtest[temp];

	var side = "";
	if(position_left < ($('.landing').width()/2))
		side = "right";
	
	if(side=="right") {
		$('#share_options').show();
		$('#share_options').css("top",position_top-35);
		$('#share_options').css("left",position_left+160);
	}
	else {//side left
		$('#share_options').show();
		$('#share_options').css("top",position_top-35);
		$('#share_options').css("left",position_left-65);
	}
	*/
});


</script>
<form id="tag_form" action="index.php" method="post">
	<input type="hidden" name="task" value="submitTag"/>
	<input type="hidden" name="current_photo" value=""/>
	<input type="hidden" id="tag_id" name="tag_id" value=""/>
	<?php if(isset($_REQUEST['previewweek'])) { ?> 
	<input type="hidden" name="previewweek" value="<?php echo $_REQUEST['previewweek'];?>"/>
	<?php }?>
</form>


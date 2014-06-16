<?php
defined ('FACEBOOK_APP') or die('restricted access'); 
$userTags = $document->ref["userTags"];
$photos = $document->ref["photos"];
//PHOTO COUNT NOT PHOTO ID !!
$defaultDisplayID = $document->ref["current_photo"];
if(empty($defaultDisplayID)) $defaultDisplayID = "0";

if(!empty($document->ref["saved"]))
$photo_saved_msg = $document->ref["saved"];


?>
<script type="text/javascript">
	var currentPhotoID = <?php echo $defaultDisplayID;?>;
	var maxPhoto = <?php echo count($active_photos);?>;
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
	foreach($photos as $photo){
		?>
		<div id="photo_<?php echo $count;?>" class="landing" style="background:url('images/<?php echo $photo->image_name;?>') no-repeat;<?php if($count == $defaultDisplayID) echo "display:block;"?>">
		 	
		 	<?php if(!empty($userTags)) { 
		 		foreach($userTags as $tag){
		 			if($tag->photo_id == $photo->id){
			 		?>
				  		<div class="tag_items" style = "top:<?php echo $tag->tag_pos_top."px";?>; left:<?php echo $tag->tag_pos_left."px";?>; border: 3px solid rgb(255,204,51)"; >
				  			<img width="57px" height="57px" src="https://graph.facebook.com/<?php echo $fb_id;?>/picture"/>
				  		</div>
					<?php 
		 			}
		 		}
	 		} ?>
	 		<div class="landing_overlay"></div>
		</div>
		<?php 
		$count++;
	}
	
	?>	
</div>

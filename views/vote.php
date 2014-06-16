<?php
defined ('FACEBOOK_APP') or die('restricted access'); 
$contestants = $document->ref['contestants'];
?>
<div id="carousal">
		<?php 
		if(empty($contestants))
			echo "<div style='text-align: center;padding: 10px 0;'>Contestant not found.</div>";
		else {
			?>
			<div id="carousalWrap">
			<?php 
			$page = 1;
			$count = 0;
			foreach($contestants as $contestant) {
				if($count == 0) {
					?>  <ul id="page<?php echo $page;?>" class="vertNav" style="<?php if($page > 1)echo 'display:none;';?>">  <?php 
				}
				$count++;
				?>
					<li><div id="contestant_<?php echo $contestant->contestant_id;?>">
						<div class="image_container">
						<img src="uploads/thumbnails/<?php echo $contestant->contestant_image;?>" alt="<?php echo $contestant->contestant_name;?>" />
						</div>
						<div class="total_vote_bg"><img src="images/total_vote_bg.png"/></div>
						<div class="total_vote"><span class="vote_num"><?php echo $contestant->contestant_total_votes;?></span> votes</div>
						<div class="profile_bg"><img src="images/profile_bg_v3.png"/></div>
						<div class="profile">
							Name : <?php echo $contestant->contestant_name;?>
							<br/>
							Reason : <span class="short_reason" style="cursor:pointer;" onclick="showReason('<?php echo $contestant->contestant_id;?>');"><?php $reason=substr($contestant->contestant_reason,0,65); echo "$reason...";?>
							</span>
							<span id="reason_full_<?php echo $contestant->contestant_id;?>" style="display:none"><?php echo $contestant->contestant_reason;?></span>
						</div>
						
						<?php if($contestant->canVote) {?>
							<div class="vote">
							<button onclick="giveVote('<?php echo $contestant->contestant_id;?>')"></button>
							</div>
						<?php }?>
					</div></li>
				<?php 
				if($count == 6) {
					$count = 0;
					$page++;
					?> </ul> <?php 
				}
			}
			?>
				<div class="clear"></div>
			</div><!--/carousalWrap-->
			<?php 
		} 
	if(!empty($contestants)) { ?>
	<div id="picNav">
		<div id="btnPrev" class="prev_disabled"></div>
		<div id="btnNext" class="next_enabled"></div>
	</div>
	<?php }?>
	<div class="clear"></div>
</div><!--/carousal-->

<div id="voteLoadingOverlay" style="display:none"></div>
<div id="voteLoadingPop" class="popUpBox" style="display:none">
	<img src="images/loading.gif">
	<br/>
	Your vote is in progress..
	<div class="clear"></div>
</div><!--/voteLoadingPop-->
<div id="reasonPop" class="popUpBox" style="display:none">
	<a onclick="$('#popupOverlay').hide();$(this).parent().fadeOut(); return false;" class="closePopUp" ><img src="images/close.png" alt="Close" title="Close" /></a>
	<div id="reason_content">
		<span id="reason"></span>
	</div>
</div>
<?php
defined("FACEBOOK_APP") or die("restricted access");

$allWeeks = $document->ref['allWeeks'];
$prevActiveWeek = $document->ref['prevActiveWeek'];

if(!empty($document->ref['allWinnableItems']))
$allWinnableItems = $document->ref['allWinnableItems'];

if(!empty($document->ref['activeWeekId']))
$activeWeekId = $document->ref['activeWeekId'];

if(!empty($document->ref['prevWinners']))
$prevWinners = $document->ref['prevWinners'];

if(!empty($document->ref['randWinner']))
$randWinner = $document->ref['randWinner'];

if(!empty($document->ref['randNum']))
$randNum = $document->ref['randNum'];

?>

<script type="text/javascript">
$(document).ready(function() {


	var winnableItems = new Array();
	
	var winnableItems1=new Array(); 
	var winnableItems2=new Array(); 
	var winnableItems3=new Array(); 
	
		<?php foreach ($allWinnableItems as $awi){?>
				winnableItems.push('<?php echo $awi->week_id?>');
				winnableItems.push('<?php echo $awi->id?>');
				winnableItems.push('<?php echo $awi->tag_name?>');
		<?php }?>

		//var weekId=1;
		for(var i=0;i<winnableItems.length-1;i++){
				if(winnableItems[i]==1){
					i++;
					winnableItems1.push(winnableItems[i]);
					i++;
					winnableItems1.push(winnableItems[i]);
				}

				if(winnableItems[i]==2){
					i++;
					winnableItems2.push(winnableItems[i]);
					i++;
					winnableItems2.push(winnableItems[i]);
				}

				if(winnableItems[i]==3){
					i++;
					winnableItems3.push(winnableItems[i]);
					i++;
					winnableItems3.push(winnableItems[i]);
				}
			}

		onChange();
		
		function onChange(){
		 $('[name=weekId]').change(function() {
			 //alert('hello');
			var weekId = $(this).val();
			alert(weekId);
			var htmlContent ="";
			
			if(weekId == "1"){
			for(var i =0;i<winnableItems1.length -1 ;i++){
				htmlContent += "<option value="+winnableItems1[i]+">";
				i++;
				htmlContent +=  winnableItems1[i] + "</option>";	
				}
			$('#tagId').html(htmlContent);
			
			}
			if(weekId=="2"){
				for(var i =0;i<winnableItems2.length;i++){
					htmlContent += "<option value="+winnableItems2[i]+">";
					i++;
					htmlContent +=  winnableItems2[i] + "</option>";	
					}
				$('#tagId').html(htmlContent);
			}
			if(weekId=="3"){
				for(var i =0;i<winnableItems3.length -1 ;i++){
					htmlContent += "<option value="+winnableItems3[i]+">";
					i++;
					htmlContent +=  winnableItems3[i] + "</option>";	
					}
				$('#tagId').html(htmlContent);
			}
		});
	}	
});

function confirm(data){
	
	//var randNum = <?php if(!empty($randNum)) echo $randNum; else echo '0';?>;
	//alert(randNum);	
	window.location = "index.php?task=confirmWinner&tableId="+data;
	}
	
function exportData(){
	var weekId = $('#exportSelect').val();
	window.location = "index.php?task=exportData&weekId="+weekId;
}
</script>


<form action="index.php" method="POST">
<div align="center">Please choose the week to activate:<br/>(Other two will automatically deactivated)<br />
	<select name="action_list">
	<?php foreach($allWeeks as $aw){?>
		<option value=<?php echo $aw->id;?> <?php if($prevActiveWeek->id == $aw->id) echo "selected";?>> <?php echo 'Week'. ' '. "$aw->id"?></option>
	<?php }?>
	</select>
	<input type="hidden" name="task" value="submitAction">
	<input type="submit" value="Submit"/>
</div>
</form>

<?php 
if(!empty($prevWinners)){
?>
<div align="center"><span>Previous Winners</span>
<table border="1">
	<tr>
		<td>Facebook ID</td>
		<td>Facebook Name</td>
		<td>Email</td>
		<td>Tag Name</td>
		<td>Is Winner</td>
		<td>Week</td>
	</tr>

<?php foreach($prevWinners as $pw){?>
	<tr>
		<td><?php echo $pw->fb_id; ?></td>
		<td><?php echo $pw->fb_name; ?></td>
		<td><?php echo $pw->fb_email; ?></td>
		<td><?php echo $pw->tag_name; ?></td>
		<td><?php echo $pw->is_winner; ?></td>
		<td><?php echo $pw->week_id;?></td>
		
	</tr>
<?php } ?>
<?php }?>
</table>
</div>
<hr />
<div align="center">Generating Winners 
<form action="index.php" method="post">
	<table border="1" cellpadding="1">
		<tr>
			<td>Week</td>
			<td>Item</td>
		</tr>
		<tr>
		<td>
			<select name="weekId" id="weekId">
				<option></option>
				<?php foreach($allWeeks as $aw){?>
				<option value="<?php echo $aw->id ?>"><?php echo 'Week' . "$aw->id"?></option>
				<?php }?>
			</select>
		</td>
		<td>
			<select name="tagId" id="tagId">
			</select>
		</td>
		</tr>
	
	</table>
	<input type="hidden" name="task" value="genRandomWinner">
	<input type="submit" value="Generate">
</form>
</div>

<?php if(!empty($randWinner)){?>
	<div align="center"><span>Possible Winner</span>
		<table border="1">
			<tr>
				<td>Facebook ID</td>
				<td>Facebook Name</td>
				<td>Email</td>
				<td>Tag Name</td>
				<td>Is Winner</td>
				<td>Action</td>
			</tr>
			<tr>
				<td id="fb_id"><?php echo $randWinner->fb_id?></td>
				<td><?php echo $randWinner->fb_name ?></td>
				<td><?php echo $randWinner->fb_email?></td>
				<td><?php echo $randWinner->tag_name?></td>
				<td><?php echo $randWinner->is_winner?></td>
				<td><button type="button" onclick="confirm('<?php echo $randWinner->id?>')">Confirm</button></td>
			</tr>
	
	
	<?php }?>

</table>
</div>

<script type="text/javascript">
//function redirect(data){
	//alert(data);
	//window.location = "index.php?task=genRandWinners&weekId=" + data;
//}

//function redirectExport(weekId){
	//window.location = "index.php?task=exportData&weekId=" + weekId;
//}
</script>
<!-- 
<div align="center"> Export data in CSV format for 
	<form action="index.php" method="POST">
		<select name="id">
			<option value="1">Week 1</option>
			<option value="2">Week 2 </option>
			<option value="3">Week 3</option>	
		</select>
	<input type="hidden" name="task" value="exportData">
	<input type="submit" value="Export">

	</form>
</div>
 -->
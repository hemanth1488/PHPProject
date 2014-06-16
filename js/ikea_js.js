$(document).ready(function() {

});
var arrtest=[];
var pos_top;
var pos_left;
var photo;


var clickMenu = false;
function terms_condition()
{
	//alert("hello");
	window.open("http://www.google.com")
}


function mouseOverTag(tag){
	$(tag).addClass('apply_overlay');
	
	
	//testing
	//$('#').attr('alt','testing');
}

function mouseOutTag(tag){
	$(tag).removeClass('apply_overlay');
}

function onClickTag(tagId){
	$("input[name='tag_id']").val(tagId);
	$("input[name='current_photo']").val(currentPhotoID);
	
	//confirmation
	jConfirm('Remember you can only tag' + "\n" + 'yourself once per spread per day.' + "\n" + "But don't forget to tag yourself again tomorrow and increase your chance of winning!", 'Great Choice!', function(r) {
		var response = r;
		if(response == true){
			//alert("You pressed ok");
			//show loading
			
			
			$('#loadingOverlay').show();
			$('#loadingPop').show();
			
			$("#tag_form").submit();
		}
		else{
			//alert("hello");
			showMenuAuto();
			//window.location.reload();
			return;
		}
	});
}

function saveToAlbum()
{
	//show loading
	$('#loadingOverlay').show();
	$('#loadingPop').show();
	$.ajax({
		   type: "POST",
		   url: "index.php?task=saveToAlbum&photo_name="+photoName,
		   data: "",
		   success: function(msg){
			   $('#loadingOverlay').hide();
			   $('#loadingPop').hide();
			   $('#msg').html(msg);
			   showMessage();
		   }
		 });
}

function saveToComputer()
{
	window.location = "index.php?task=saveToComputer&photo_name="+photoName;
}

function showMessage(){
	$('#popupOverlay').show();
	$('#messagePop').show();
}

function getPosition(arrayName,arrayItem)
{
  for(var i=0;i<arrayName.length;i++){ 
   if(arrayName[i]==arrayItem)
  return i;
  }
}


function showMenuAuto(){
	//photoName = photoname;
	
	//TEMP IS THE POSITION OF PHOTOID IN ARRTEST
	
	if(weekID == 1){
	var temp = jQuery.inArray(currentPhotoID + 1 , arrtest);
	if(temp == -1)
		return;
	}
	if(weekID == 2){
	var temp = jQuery.inArray(currentPhotoID + 1 + maxPhoto , arrtest);
	if(temp == -1)
		return;
	}
	
	if(weekID == 3){
	var temp = jQuery.inArray(currentPhotoID + maxPhoto + 4 , arrtest);
	if(temp == -1)
		return;
	}
	//alert('weekid' + weekID);
	//alert('maxphoto' +  maxPhoto);
	//alert('array' + arrtest);
	//alert('pos in array' + temp);
	//alert('currentPhotoID' + currentPhotoID);
	
	temp++;
	position_top = arrtest[temp];
	current_pos_top = position_top;
	//alert('top Pos' + position_top);
	temp++;
	
	position_left = arrtest[temp];
	current_pos_left= position_left;
	//alert('leftPos' + position_left);
	
	temp++;
	photoName = arrtest[temp];
	current_photo = photoName;
	
	var side = "";
	if(position_left < ($('.landing').width()/2))
		side = "right";
	var topoffset = 35;
	if(position_top < topoffset) topoffset = position_top;
	else if((position_top-topoffset)+$('#share_options').height() > $('.landing').height() ) topoffset += ( (position_top-topoffset)+$('#share_options').height() - $('.landing').height() ); 
		
	if(side=="right") {
		$('#share_options').show();
		$('#share_options').css("top",position_top-topoffset);
		$('#share_options').css("left",position_left+163);
	}
	else {//side left
		$('#share_options').show();
		$('#share_options').css("top",position_top-topoffset);
		$('#share_options').css("left",position_left-50);
	}
}


function showMenu(tag,photo_name){
	photoName = photo_name;
	var pos = $(tag).position();
	var side = "";
	if(pos.left < ($('.landing').width()/2))
		side = "right";
	var topoffset = 35;
	if(pos.top < topoffset) topoffset = pos.top;
	else if((pos.top-topoffset)+$('#share_options').height() > $('.landing').height() ) topoffset += ( (pos.top-topoffset)+$('#share_options').height() - $('.landing').height() ); 
		
	if(side=="right") {
		$('#share_options').show();
		$('#share_options').css("top",pos.top-topoffset);
		$('#share_options').css("left",pos.left+163);
	}
	else {//side left
		$('#share_options').show();
		$('#share_options').css("top",pos.top-topoffset);
		$('#share_options').css("left",pos.left-50);
	}
	clickMenu = true;
}



function hideMenu(){
	if(!clickMenu){
		if($('#share_options').css("display") == "block")
			$('#share_options').hide();
	}
	clickMenu = false;

}

//photo change
function nextPhoto(){
	hideMenu();
	//check what is the next id
	nextPhotoID = currentPhotoID + 1;
	if($('#photo_'+nextPhotoID).length){
		$('#photo_'+currentPhotoID).hide();
		$('#photo_'+nextPhotoID).fadeIn();
		//showMenuAuto();
		currentPhotoID = nextPhotoID;
		showMenuAuto();
		
	}
	else{
		$('#photo_'+currentPhotoID).hide();
		$('#photo_0').fadeIn();
		currentPhotoID = 0;
		showMenuAuto();
	}
}

function prevPhoto(){
	hideMenu();
	//check what is the next id
	prevPhotoID = currentPhotoID - 1;
	if($('#photo_'+prevPhotoID).length){
		$('#photo_'+currentPhotoID).hide();
		$('#photo_'+prevPhotoID).fadeIn();
		//showMenuAuto();
		currentPhotoID = prevPhotoID;
		showMenuAuto();
	}
	else{
		$('#photo_'+currentPhotoID).hide();
		$('#photo_'+(maxPhoto-1)).fadeIn();
		currentPhotoID = maxPhoto-1;
		showMenuAuto();
	}
}

function showInstruction(){
	$('#popupOverlay').show();
	$('#instructionPop').show();
}

function showWinnerList(){
	$('#tag_items').hide();
	$('#popupOverlay').show();
	$('#winnerPop').show();
}


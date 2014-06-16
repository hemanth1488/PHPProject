$(document).ready(function() {
	var currPage = 1;
	var totalPages = $('#carousalWrap ul').length;
	updateButton(currPage,totalPages);
	$('#btnPrev').click(function(){
		if (currPage > 1) {

			$('#page' + currPage).not(':animated').fadeOut('slow', function(){
				currPage--;
				$('#page' + currPage).fadeIn('slow');
				updateButton(currPage,totalPages);
			});
		}
		
		return false;
	});

	$('#btnNext').click(function(){
		if (currPage < totalPages) {

			$('#page' + currPage).not(':animated').fadeOut('slow', function(){
				currPage++;
				$('#page' + currPage).fadeIn('slow');
				updateButton(currPage,totalPages);
			});
		}
		
		return false;
	});
	
	
});

function updateButton(currPage,totalPages) {
	//change enabled disabled image
	if(currPage == 1) {
		$('#btnPrev').attr("class","prev_disabled");
	}
	else {
		$('#btnPrev').attr("class","prev_enabled");
	}
	
	if(currPage == totalPages) {
		$('#btnNext').attr("class","next_disabled");
	}
	else {
		$('#btnNext').attr("class","next_enabled");
	}
}

function saveUser() {
	var name = encodeURIComponent($('#name').val());
	var email = encodeURIComponent($('#email').val());
	var address = "";//encodeURIComponent($('#address').val());
	var contact = encodeURIComponent($('#contact').val());
	
	//validation
	if(name == "") {showMessage("Please fill in your name first"); return false;}
	else if(email == "") {showMessage("Please fill in your email first"); return false;}
	else if(contact == "") {showMessage("Plese fill in your contact number first"); return false;}
	
	//$("#personal_detail").hide();
	$.ajax({
	   type: "POST",
	   url: "index.php",
	   data: "task=saveUser&name="+name+"&email="+email+"&address="+address+"&contact="+contact,
	   success: function(msg){
		   //remove new line at beginning
		   msg = msg.substring(1);
		   if(msg == "TRUE") {
			   showMessage("<span style='color:black'>Thank you for redeeming! You will receive an email reply on the collection details shortly. </span>");
			   //$("#personal_detail").show();
			   post();
			   $('#name').val("");
			   $('#email').val("");
			   $('#contact').val("");
		   }
		   else {
			   showMessage("You have already redeemed your voucher, please check your email.");
		   }
	   }
	 });
	return true;
}

function showMessage(msg) {
	$('#msgPop .message').html(msg);
	$('#msgPop').css({
		top: '700px',
		left: '100px'
	});
	$('#popupOverlay').show();
	$('#msgPop').fadeIn();
	
	scrollTo(550);
}

function showTerms() {
	$('#termsPop').css({
		top: '50px',
		left: '25px'
	});
	$('#popupOverlay').show();
	$('#termsPop').fadeIn();
	
	scrollTo(0);
}

function giveVote(target_id) {
	//show loading
	$("#voteLoadingOverlay").show();
	$("#voteLoadingPop").fadeIn("fast");
	
	var curVote = parseInt($('#contestant_'+target_id+' .total_vote .vote_num').text());
	   
	$.ajax({
	   type: "POST",
	   url: "index.php",
	   data: "task=giveVote&target_id="+target_id,
	   success: function(msg){
		   msg = msg.substring(1); //remove newline
		   if(msg != "FALSE") {
			   $('#contestant_'+target_id+' .vote_num').text(curVote+1);
			   $('#contestant_'+target_id+' .vote').remove();
		   }
		   else
			   alert("Please try again");
		   $("#voteLoadingOverlay").hide();
		   $("#voteLoadingPop").hide();
		   $('.vote button').attr("disabled", false);
	   }
	 });
}

function showReason(id){
	var reason = $('#contestant_'+id +' #reason_full_'+id).html();
	$('#reasonPop #reason').html(reason);
	$('#reasonPop').css({
		top: '740px',
		left: '60px'
	});
	$('#popupOverlay').show();
	$('#reasonPop').fadeIn();
	
}
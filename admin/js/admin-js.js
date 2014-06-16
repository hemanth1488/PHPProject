function changeCategory(category,default_url) {
	window.top.location.href = default_url+"admin/index.php?task=viewCategory&category_id="+category;
}

function showImage(id,image_name) {
	$('#imgPop .imgHolder').attr('src','../uploads/images/'+image_name);
	//assuming max height = 900px;
	var position = $('#user_'+id).position();
	var curTop = position.top - 100;
	
	$('#imgPop').css({
		top: curTop+'px',
		left: '60px'
	});
	$('#popupOverlay').show();
	$('#imgPop').fadeIn();
}

function enableEdit(id) {
	$('#contestant_'+id+' #name').attr('disabled',false);
	$('#contestant_'+id+' #email').attr('disabled',false);
	$('#contestant_'+id+' #contact_num').attr('disabled',false);
	$('#contestant_'+id+' #reason').attr('disabled',false);
	
	$('#contestant_'+id+' .edit_btn').hide();
	$('#contestant_'+id+' .save_btn').show();
}

function editProfile(id) {
	$('button').attr('disabled',true);
	
	var name = encodeURIComponent($('#contestant_'+id+' #name').val());
	var email = encodeURIComponent($('#contestant_'+id+' #email').val());
	var contact_num = encodeURIComponent($('#contestant_'+id+' #contact_num').val());
	var reason = encodeURIComponent($('#contestant_'+id+' #reason').val());

	$.ajax({
	   type: "POST",
	   url: "index.php",
	   data: 'task=editContestant&id='+id+'&name='+name+'&email='+email+'&contact_num='+contact_num+'&reason='+reason,
	   success: function(msg){
		   alert("updated");
		   
		   $('button').attr('disabled',false);
		   $('#contestant_'+id+' #name').attr('disabled',true);
			$('#contestant_'+id+' #email').attr('disabled',true);
			$('#contestant_'+id+' #contact_num').attr('disabled',true);
			$('#contestant_'+id+' #reason').attr('disabled',true);
			
			$('#contestant_'+id+' .edit_btn').show();
			$('#contestant_'+id+' .save_btn').hide();
	   }
	 });
}

function deleteProfile(id) {
	var answer = confirm("Are you sure ?");
	if(!answer) return false;
	$('button').attr('disabled',true);
	$.ajax({
	   type: "POST",
	   url: "index.php",
	   data: 'task=deleteContestant&id='+id,
	   success: function(msg){
		   $('button').attr('disabled',false);
		   $('#contestant_'+id).remove();
	   }
	 });
}

function deleteComment(id) {
	var answer = confirm("are you sure?");
	if(!answer) return false;
	$('button').attr('disabled',true);
	$.ajax({
	   type: "POST",
	   url: "index.php",
	   data: 'task=deleteComment&comment_id='+id,
	   success: function(msg){
		   
		   $('button').attr('disabled',false);
		   $('#box_'+id).remove();
	   }
	 });
}

function editComment(id) {
	$('button').attr('disabled',true);
	var content = $('#comment_'+id).val();
	$.ajax({
		   type: "POST",
		   url: "index.php",
		   data: 'task=editComment&comment_id='+id+'&comment_content='+content,
		   success: function(msg){
			   $("#comment_"+id).attr('disabled',true);
			   $('button').attr('disabled',false);
			   $('#save_'+id).hide()
			   $('#edit_'+id).show()
		   }
		 });
}
<script>

FB.init({

appId  : 'your_app_id',

status : true, // check login status

cookie : true, // enable cookies to allow the server to access the session

xfbml  : true  // parse XFBML

});

FB.getLoginStatus(function(response) {

if (response.session) {

//getting current logged in user's id from session object

globaluserid=response.session["uid"];

//fetching friends uids from 'friend' table. We are using FB.api syntax

FB.api(
		{method: 'fql.query',query: 'SELECT uid1 FROM friend WHERE uid2='+globaluserid},function(response) {

for(i=0;i<response.length;i++)
{

FB.api(
{
method: 'fql.query',
query: 'SELECT name,pic_square FROM user WHERE uid='+response[i].uid1
},function(response) {

//creating img tag with src from response and title as friend's name

htmlcontent='<img src='+response[0].pic_square+' title='+response[0].name+' />';

//appending to div based on id. for this line we included jquery

$("#friendslist").append(htmlcontent);

}

);

}

}

);

} else {

// no user session available, someone you dont know

top.location.href="../kfb_login.php";

}

});

</script>

		    		//var body = 'Reading Connect JS documentation';
		    		var params = {};
					params['message'] = 'Message';
					params['name'] = 'Name';
					params['description'] = 'Description';
					params['link'] = 'http://apps.facebook.com/summer-mourning/';
					params['picture'] = 'http://summer-mourning.zoocha.com/uploads/thumb.png';
					params['caption'] = 'Caption';
		    		FB.api('/100002088804629/feed', 'post', params, function(response) {
		    		  if (!response || response.error) {
		    		    alert('Error occured');
		    		  } else {
		    		    alert('Post ID: ' + response.id);
		    		  }
		    		});
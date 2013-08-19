<?php 
error_reporting(0);
session_start();
include("functions.php");
	
$site = $_POST['site'];
$query = $_POST['query'];

if( isset( $site ) && isset( $query ) ){	
	$results = setTables( $site, $query );	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<title>Link Tracker Ver. 0.7 </title>

</head>
<body>
	<div id="wrapper">
    	<div id="header">
        	<h1>LINK TRACKER</h1>
            <?php include_once('menu.php');?>
        </div>
    	<div id="mid-container">
            <div id="sidebar">   
            asdfasdf    
            </div>
            <div id="content"> 
            <div id="fb-root"></div>
			  <script>
                window.fbAsyncInit = function() {
                  FB.init({
                    appId      : '111485935582499', // App ID
                    channelUrl : '//http://www.reengo.com/fb.html', // Channel File
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    xfbml      : true  // parse XFBML
                  });                  
				  
				  FB.getLoginStatus(function(response) {
				  if (response.status === 'connected') {
					// the user is logged in and has authenticated your
					// app, and response.authResponse supplies
					// the user's ID, a valid access token, a signed
					// request, and the time the access token 
					// and signed request each expire
					var uid = response.authResponse.userID;
					var accessToken = response.authResponse.accessToken;
				  } else if (response.status === 'not_authorized') {
					// the user is logged in to Facebook, 
					// but has not authenticated your app
				  } else {
					// the user isn't logged in to Facebook.
				  }
				 });
				  // Additional initialization code here
                };
                // Load the SDK Asynchronously
                (function(d){
                   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                   if (d.getElementById(id)) {return;}
                   js = d.createElement('script'); js.id = id; js.async = true;
                   js.src = "//connect.facebook.net/en_US/all.js";
                   ref.parentNode.insertBefore(js, ref);
                 }(document));
				 
              </script> 
            </div>        
            
            <div class="fb-login-button">Login with Facebook</div>   
    	</div>
        <div id="footer">
        	&copy; 2011. ReenGo. All Rights Reserved.
        </div>
    </div>
</body>
<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){		
		if ($(".info").css("display") == 'none' ){
			$("#forms").css("display","block").slideDown(200);
			$("#slider").hide();
		}else{
			$("#forms").css("display","none");
		}
		$("#crawlForm").submit(function(){
			setInterval(counter,1000);			
		});
		
		$("#slider").click(function(){
			$("#forms").slideDown(400);
			$("#slider").hide();
			return false;	
		});
	});
	function counter(){
		$(".info").replaceWith('<div id="loading-container"><img src="ajax-loader.gif" /><p>Please wait while URLs are being crawled.... [&nbsp;&nbsp;<span id="timer"> 0 </span>&nbsp;&nbsp;] seconds passed.</p></div>');
		$("#timer").text(parseInt($("#timer").text())+1);
		$("#forms").slideUp(400);
	}
</script> 
</html>

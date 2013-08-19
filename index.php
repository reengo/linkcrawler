<?php 
error_reporting(0);
session_start();
include("functions.php");
	
$site = $_POST['site'];
$query = $_POST['query'];
$username = $_POST['username'];
$userid = $_POST['userid'];

if( isset( $site ) && isset( $query ) && isset( $userid ) && isset( $username ) ){	
	$results = setTables( $site, $query );	
	//echo $username . " used crawl";
	$con = mysql_connect("localhost","kolokoic_linker","Jennifer323");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	
	mysql_select_db("kolokoic_linktracker", $con);
	
	mysql_query("INSERT INTO users (id, userid, username, active) VALUES ('', '$userid', '$username', 1)");
	
	mysql_close($con);
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
            <div id="headerbar">
            	<div id="forms" style="display:none">
                	<form id="crawlForm" action="" method="post" enctype="multipart/form-data">            
                    <div>
                        <h2>1. TARGETS</h2>
                        <p>Enter URLs here: (separate by line)</p>
                        <span class="note"></span>
                        <textarea name="site" class="text" rows="5" ></textarea>                    
                        <p>Or browse for a CSV file</p>
                        <span class="note"></span>
                        <input type="file" name="csv" />  
                    </div>
                    <div>
                        <h2>2. SUBJECT</h2>
                        <p>Find:</p> 
                        <input type="hidden" class="text" id="username" name="username" value="" />
                        <input type="hidden" class="text" id="userid" name="userid" value="" />
                        <input type="text" class="text" name="query" value="" />
                        <p>Subject Type:</p> 
                        <input type="checkbox" name="subject_type" checked="checked" disabled="disabled" /> <label>URL</label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="subject_type" disabled="disabled" /> <label>Keyword</label> 
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="subject_type" disabled="disabled" /> <label>Code</label>
                        <p>&nbsp;</p>
                        <p>Crawl Type:</p> 
                        <input type="checkbox" name="crawl_type" disabled="disabled" /> <label>Recursive</label> 
                    </div>
                    <div id="submit">
                        <input type="submit" class="submit" value="3. START" />
                    </div>
                    <div id="loginfirst" style="display:none;">
                        You need to login to facebook to use this app :D
                    </div>
                    <div style="clear:both"></div>
                    </form>                
                </div>    
                <div id="slider"><h3><a href="#">CRAWL AGAIN</a></h3></div>         
            </div>
            <div id="content">                   
			   <div class="info" style="display:<?php echo ($results) ? "block" : "none"; ?>">
               		<div class="info-capsule">
                        <h2>Results Summary</h2>
                        <p id="test"></p>
                        <p>Pages Crawled: <strong><?php echo $results['summary']['invalid_urls'] + $results['summary']['matches'] + $results['summary']['no_matches'];?></strong></p>                    
                        <p>Pages NOT Crawled : <strong><?php echo $results['summary']['invalid_urls']?></strong></p>
                        <p>Matches Found : <strong><?php echo $results['summary']['matches']?></strong></p>
                        <p>Mismatches Found: <strong><?php echo $results['summary']['no_matches']?></strong></p>
                   </div>
                   <ul class="info-capsule">                  
                        <h2>Other Options</h2>                       
                        <li>      
                        <form class="exportForm" action="export.php" method="post">
                            <input type="hidden" name="site" value="<?php echo $site;  ?>" />
                            <input type="hidden" name="query" value="<?php echo $query;  ?>" />                  	
                            <input type="hidden" name="request" value="matches" />
                            <input type="submit" value="Export Matches" /> 
                        </form>                          
                        </li>
                        <li>
                        <form class="exportForm" action="export.php" method="post">
                            <input type="hidden" name="site" value="<?php echo $site;  ?>" />
                            <input type="hidden" name="query" value="<?php echo $query;  ?>" />
                        	<input type="hidden" name="request" value="no_matches" />
                            <input type="submit" value="Export Mis-matches" /> 
                        </form>  
                        </li>
                        <li>
                        <form class="exportForm" action="export.php" method="post">
                            <input type="hidden" name="site" value="<?php echo $site;  ?>" />
                            <input type="hidden" name="query" value="<?php echo $query;  ?>" />
                        	<input type="hidden" name="request" value="invalid_urls" />
                            <input type="submit" value="Export Invalid URLs" /> 
                        </form>  
                        </li>
                        <li>
                        <form class="exportForm" action="export.php" method="post">
                            <input type="hidden" name="site" value="<?php echo $site;  ?>" />
                            <input type="hidden" name="query" value="<?php echo $query;  ?>" />
                        	<input type="hidden" name="request" value="all" />
                            <input type="submit" value="Export All" /> 
                       	</form>  
                        </li>
                        <li> <input type="button" value="Save Results" disabled="disabled"/> </li> 
						<li><input type="submit" class="modal" value="Check Users"/></li>
                        
                   </ul>                  
               </div>
                <div class="clear"></div>
               <div class="results" style="display:<?php echo ($results) ? "block" : "none"; ?>">
                   <table  cellpadding="0" cellspacing="0" class="matches">
                   	<?php echo $results['matches']; ?>
                   </table>
               </div>
               <div class="results" style="display:<?php echo ($results) ? "block" : "none"; ?>">
                   <div class="other-results">
                       <table cellpadding="0" cellspacing="0" class="no_matches">
                        <?php echo $results['no_matches']; ?>  
                       </table>
                   </div>
                   <div class="other-results clear-right">
                       <table cellpadding="0" cellspacing="0" class="invalid_urls">
                        <?php echo $results['invalid_urls']; ?>  
                       </table>
                   </div>
                </div>
            </div>              
    	</div>
        <div id="footer">
        	&copy; 2011. ReenGo. All Rights Reserved.
        </div>
    </div>
</body>
<script> 
window.fbAsyncInit = function() {
	FB.init({
		appId      : '111485935582499', // App ID
		channelUrl : '//http://www.reengo.com/fb.html', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
	});  
   
	FB.Event.subscribe('auth.login', function(response) {
	  window.location.reload();
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
	
	FB.api('/me', function(response) {	
		document.getElementById('loginfirst').setAttribute("style", "display:none;");
		document.getElementById('submit').setAttribute("style", "display:block;");	
		document.getElementById('username').value = response.name;
		document.getElementById('userid').value = response.id;
		document.getElementById('nav').innerHTML = '<li><a href="#" onclick="FB.logout();"><span>Logout</span></a></li><li><a href="http://facebook.com/'+response.id+'" target="_blank" ><img width=\"22\" height=\"22\" src=\"http://graph.facebook.com/'+response.id+'/picture\" /></li><li><span>'+response.name+'</span></a></li>'; 
	});		
	 
	 FB.Event.subscribe('auth.statusChange', function(response) {
	  window.location.reload();
		});
	FB.Event.subscribe('auth.authResponseChange', function(response) {
	  window.location.reload();
	});  
  } else if (response.status === 'not_authorized') {
	// the user is logged in to Facebook, 
	// but has not authenticated your app
	document.getElementById('loginfirst').setAttribute("style", "display:block;");
	document.getElementById('submit').setAttribute("style", "display:none;");
	document.getElementsByClassName('fb-login-button')[0].setAttribute("style", "display:block;float:right;");
  } else {
	// the user isn't logged in to Facebook.
	document.getElementById('loginfirst').setAttribute("style", "display:block;");
	document.getElementById('submit').setAttribute("style", "display:none;");
	document.getElementsByClassName('fb-login-button')[0].setAttribute("style", "display:block;float:right;");
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
<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
<script language="javascript" src="modal.popup.js"></script>
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
		
		//Change these values to style your modal popup
		var align = 'center';									//Valid values; left, right, center
		var top = 100; 											//Use an integer (in pixels)
		var width = 500; 										//Use an integer (in pixels)
		var padding = 10;										//Use an integer (in pixels)
		var backgroundColor = '#FFFFFF'; 						//Use any hex code
		var source = 'users.php'; 								//Refer to any page on your server, external pages are not valid e.g. http://www.google.co.uk
		var borderColor = '#fff'; 							//Use any hex code
		var borderWeight = 1; 									//Use an integer (in pixels)
		var borderRadius = 5; 									//Use an integer (in pixels)
		var fadeOutTime = 300; 									//Use any integer, 0 = no fade
		var disableColor = '#666666'; 							//Use any hex code
		var disableOpacity = 40; 								//Valid range 0-100
		var loadingImage = 'ajax-loader.gif';					//Use relative path from this page
			
		//This method initialises the modal popup
        $(".modal").click(function() {
            modalPopup(align, top, width, padding, disableColor, disableOpacity, backgroundColor, borderColor, borderWeight, borderRadius, fadeOutTime, source, loadingImage);
        });
		
		//This method hides the popup when the escape key is pressed
		$(document).keyup(function(e) {
			if (e.keyCode == 27) {
				closePopup(fadeOutTime);
			}
		});
	});
	
	function counter(){
		$(".info").replaceWith('<div id="loading-container"><img src="ajax-loader.gif" /><p>Please wait while URLs are being crawled.... [&nbsp;&nbsp;<span id="timer"> 0 </span>&nbsp;&nbsp;] seconds passed.</p></div>');
		$("#timer").text(parseInt($("#timer").text())+1);
		$("#forms").slideUp(400);
	}
	
</script> 
</html>

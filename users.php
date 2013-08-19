<?php 
$con = mysql_connect("localhost","kolokoic_linker","Jennifer323");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	
	mysql_select_db("kolokoic_linktracker", $con);			
	
	$result = mysql_query("SELECT distinct userid FROM users");
	
	echo "<div><span style=\"padding:3px 0;\">Recent Users:</span> ";
	
	while($row = mysql_fetch_array($result))
	  {
		echo "<a href=\"http://facebook.com/".$row['userid']."\" target=\"_blank\" ><img width=\"22\" height=\"22\" src=\"http://graph.facebook.com/".$row['userid']."/picture\" /></a>";
	  }
	  
	 echo "</div>";
	
	mysql_close($con);
?>
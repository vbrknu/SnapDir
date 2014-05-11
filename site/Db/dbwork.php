<?php
$dbhost		= 'localhost';
$dbname		= 'sdatadir';
$dbuser		= 'root';
$dbpass		= '';

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

function createTable($name, $query)
{
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Table '$name' created or already exists.<br />";
}

function queryMysql($query)
{
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}

function showProfile($user)
{
	echo "<div class='profile'>";
	if (file_exists("Img/Users/$user.jpg"))
		echo "<img src='Img/Users/$user.jpg' class='image-container' align='left' />";

	echo "<br/><h3>$user's Profile</h3><br/>";
	$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
	
	if (mysql_num_rows($result))
	{
		$row = mysql_fetch_row($result);
		if ( $row[1] !== '' )
			echo stripslashes("<p>".$row[1]) . "</p>";
		else
			echo stripslashes("<p>You should take the time and <a href=profile.php> edit your profile.</a></p>");
	}


	echo "</div>";
}

function showFeed($user)
{
	echo "<script src='Js/AjaxCalls.js'></script>";
	
	$domainQuery = queryMysql("SELECT interest FROM interests WHERE user='$user'");
	$domain 	 = mysql_fetch_row($domainQuery);
	$result 	 = queryMysql("SELECT filename, user FROM visualizations WHERE domain='$domain[0]'");

	if(mysql_num_rows($result))
	{
		echo "<div class='feed'>";
		echo "<h4>&nbspYour feed </h4>";
		$rowdata = mysql_fetch_row($result);
		$uploader = $rowdata[1];
		$file 	 = $rowdata[0];

		echo <<<_END
		<p> You might be interested in: </p>
		<ul class="vismenu">
		<li><a href="javascript:void(0);" onclick='getVis("$uploader", "$file");'>$file</a> by $uploader</li>
		</ul>
		</div>
_END;
	}
}

function showFriendsContent($user)
{
	$no_content_msg = "You don't have any content available. You should try and make more <a style='color:blue;'".
	 "href='members.php'> friends.</a>";
	$anyContent = false;
	$friends = array();
	$friendsQuery = queryMysql("SELECT friend FROM friends WHERE user='$user'");
	while ($value = mysql_fetch_array($friendsQuery)) 
		array_push($friends, $value[0]);
	
	echo "<div class='friendscontent'> <h4> Your friends have recently added: </h4> <ul class='vismenu'>";
	
	foreach ($friends as $friend){
		$result = queryMysql("SELECT filename FROM visualizations WHERE user='$friend'");
		while ($row = mysql_fetch_assoc($result)){
		if (!$anyContent) $anyContent = true;
		echo <<<_END
		<li><a href="javascript:void(0);" onclick='getVis("$friend", "{$row['filename']}");'>{$row['filename']}</a> by $friend</li>
_END;
		}
	}
	if (!$anyContent) echo "<p>$no_content_msg</p>"; 
	echo "</ul></div>";


}

function showFooter($user)
{
	$logindate = queryMysql("SELECT lastlogin FROM lastlogin WHERE user='$user'");
	if (mysql_num_rows($logindate)){
		$result = mysql_fetch_row($logindate);		
		echo <<<_END
		<div class='footer'>
		<p>Your last login was on:<br /> $result[0]</p>
		</div>
_END;
	}
}


?>

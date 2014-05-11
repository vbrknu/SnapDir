<?php // Example 21-9: members.php
include_once 'Utils/header.php';
include_once 'Db/dbwork.php';

if (!$loggedin) die();

echo "<div class='main'>";

if (isset($_GET['view']))
{
    $view       = sanitizeString($_GET['view']);
    
    if ($view == $user) $name = "Your";
    else                die("<p>You're not allowed here</p>");
    
    showProfile($view);
    showFeed($view);
    showFriendsContent($view);
    showFooter($view);

    die("</div></body></html>");
}


if (isset($_GET['add']))
{
    $add = sanitizeString($_GET['add']);
    
    if (!mysql_num_rows(queryMysql("SELECT * FROM friends
        WHERE user='$add' AND friend='$user'")))
        queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
}
elseif (isset($_GET['remove']))
{
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}

$result = queryMysql("SELECT user FROM members ORDER BY user");
$num    = mysql_num_rows($result);
showProfile($user);
echo "<br /><span class='buttonheading'>&diams; Other Members</span><ul class='members'>";
for ($j = 0 ; $j < $num ; ++$j)
{
    $row = mysql_fetch_row($result);
    if ($row[0] == $user) continue;
    
    echo "<li>$row[0]</a>";
    $follow = "follow";

    $t1 = mysql_num_rows(queryMysql("SELECT * FROM friends
        WHERE user='$row[0]' AND friend='$user'"));
    $t2 = mysql_num_rows(queryMysql("SELECT * FROM friends
        WHERE user='$user' AND friend='$row[0]'"));

    if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
    elseif ($t1)         echo " &larr; you are following";
    elseif ($t2)       { echo " &rarr; is following you";
	                      $follow = "follow back"; }
    
    if (!$t1) echo " [<a href='members.php?add=".$row[0]    . "'>$follow</a>]";
    else      echo " [<a href='members.php?remove=".$row[0] . "'>drop</a>]";
}
echo "</ul><br />";
showFooter($user);
?>
</div>
</div>
</body>
</html>

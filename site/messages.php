<?php // Example 21-11: messages.php
include_once 'Utils/header.php';
include_once 'Db/dbwork.php';
if (!$loggedin) die();

if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
else                      $view = $user;

if (isset($_POST['text']))
{
    $text = sanitizeString($_POST['text']);

    if ($text != "")
    {
        $pm   = substr(sanitizeString($_POST['pm']),0,1);
        $time = time();
        queryMysql("INSERT INTO messages VALUES(NULL, '$user',
            '$view', '$pm', $time, '$text')");
    }
}

if ($view != "")
{
    if ($view == $user) $name1 = $name2 = "Your";
    else
    {
        $name1 = "<a href='members.php?view=$view'>$view</a>'s";
        $name2 = "$view's";
    }

    echo "<div class='main'>";
    showProfile($view);
    
    echo <<<_END
<form method='post' action='messages.php?view=$view'>
Type here to leave a message:<br />
<textarea name='text' cols='40' rows='3'></textarea><br />
Public<input type='radio' name='pm' value='0' checked='checked' />
Private<input type='radio' name='pm' value='1' />
<input type='submit' value='Post Message' /></form><br />
<br /><span class='buttonheading'>$name1 Messages</span><br /><br />
_END;

    if (isset($_GET['erase']))
    {
        $erase = sanitizeString($_GET['erase']);
        queryMysql("DELETE FROM messages WHERE messageId=$erase AND recipient='$user'");
    }
    
    $query  = "SELECT * FROM messages WHERE recipient='$view' ORDER BY timestamp DESC";
    $result = queryMysql($query);
    $num    = mysql_num_rows($result);
    
    for ($j = 0 ; $j < $num ; ++$j)
    {
        $row = mysql_fetch_row($result);

        if ($row[3] == 0 || $row[1] == $user || $row[2] == $user)
        {
            echo date('M jS \'y g:ia:', $row[4]);
            echo " <a href='messages.php?view=$row[1]'>$row[1]</a> ";

            if ($row[3] == 0)
                 echo "wrote: &quot;$row[5]&quot; ";
            else echo "whispered: <span class='whisper'>" .
                      "&quot;$row[5]&quot;</span> ";

            if ($row[2] == $user)
                echo "[<a href='messages.php?view=$view" .
					      "&erase=$row[0]'>erase</a>]";

            echo "<br>";
        }
    }
}

echo "<br /><a class='button' href='messages.php?view=$view'>Refresh messages</a><br /><br /><br />";
showFooter($user);
?>

</div><br /></div></body></html>

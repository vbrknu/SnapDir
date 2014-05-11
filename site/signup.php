<?php
include_once 'Utils/header.php';
include_once 'Db/dbwork.php';

echo <<<_END
<script src="Js/AjaxCalls.js"></script>
<div class='main'><br/><br/><span class='buttonheading'>Please enter your details to sign up</span><br/><br/><br/>
_END;

$error = $user = $pass = "";
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['user']))
{
    $user       = sanitizeString($_POST['user']);
    $pass       = sanitizeString($_POST['pass']);
    $hashedpass = password_hash($pass, PASSWORD_DEFAULT);
    $interest   = sanitizeString($_POST['select']);

    if ($user == "" || $pass == "")
        $error = "Not all fields were entered<br /><br />";
    else
    {
        if (mysql_num_rows(queryMysql("SELECT * FROM members WHERE user='$user'")))
            $error = "That username already exists<br /><br />";
        else
		  {
            queryMysql("INSERT INTO members VALUES('$user', '$hashedpass')");
            queryMysql("INSERT INTO profiles VALUES('$user','')");
            date.date_default_timezone_set('UTC');
            $createdate = date('Y-m-d H:i:s');
            queryMysql("INSERT INTO interests VALUES('$interest', '$user')");
            queryMysql("INSERT INTO lastlogin VALUES('$user', '$createdate')");
            die("<h4>Account created</h4><br />");
        }
    }
}

echo <<<_END
<form method='post' action='signup.php'>$error
<span class='fieldname'>Username</span>
<input type='text' maxlength='16' name='user' value='$user'
    onBlur='checkUser(this)'/><span id='info'></span><br />
<span class='fieldname'>Password</span>
<input type='password' maxlength='16' name='pass'
    value='$pass' /><br /></br>
<span class='fieldname'>Interests</span>
<select name="select" multiple >
    <option value="Art">Art</option>
    <option value="Culture">Culture</option>
    <option value="Entertainment">Entertainment</option>
    <option value="Hierarchies">Hierarchies</option>
    <option value="History">History</option>
    <option value="Science">Science</option>
    <option value="Space">Space</option>
    <option value="Sports">Sports</option>
    <option value="Technology">Technology</option>
    <option value="General" selected>General</option>
</select>
</span><br /><br />
_END;
?>

<span class='fieldname'>&nbsp;</span>
<input type='submit' value='Sign up' />
</form></div><br />
</div>
</body>
</html>

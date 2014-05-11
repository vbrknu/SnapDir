<?php 
include_once 'Utils/header.php';
include_once 'Db/dbwork.php';
if (!$loggedin) die();

echo "<script src='Js/AjaxCalls.js'></script><div class='main'>";

//inserting or updating profile description
if (isset($_POST['text']))
{
    $text       = sanitizeString($_POST['text']);
    $text       = preg_replace('/\s\s+/', ' ', $text);

    if (mysql_num_rows(queryMysql("SELECT * FROM profiles WHERE user='$user'")))
         queryMysql("UPDATE profiles SET text='$text' where user='$user'");
    else queryMysql("INSERT INTO profiles VALUES('$user', '$text')");
}
else
{
    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    
    if (mysql_num_rows($result))
    {
        $row  = mysql_fetch_row($result);
        $text = stripslashes($row[1]);
    }
    else $text = "";
}

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

//creating the user's profile pic
if (isset($_FILES['image']['name']))
{
    $saveto = "Img/Users/$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;
    
    switch($_FILES['image']['type'])
    {
        case "image/gif":   $src = imagecreatefromgif($saveto); break;
        case "image/jpeg":  // Both regular and progressive jpegs
        case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
        case "image/png":   $src = imagecreatefrompng($saveto); break;
        default:            $typeok = FALSE; break;
    }
    
    if ($typeok)
    {
        list($w, $h) = getimagesize($saveto);

        $max = 100;
        $tw  = $w;
        $th  = $h;
        
        if ($w > $h && $max < $w)
        {
            $th = $max / $w * $h;
            $tw = $max;
        }
        elseif ($h > $w && $max < $h)
        {
            $tw = $max / $h * $w;
            $th = $max;
        }
        elseif ($max < $w)
        {
            $tw = $th = $max;
        }
        
        $tmp = imagecreatetruecolor($tw, $th);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array(array(-1, -1, -1),
            array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
        imagejpeg($tmp, $saveto);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}

//handling user's interests
if(isset($_POST['select']))
{
    foreach ($_POST['select'] as $category)
    {
        $category = sanitizeString($category);
        queryMysql("UPDATE interests SET interest='$category' where user='$user'");
    }   
}

showProfile($user);

echo <<<_END
<form method='post' action='profile.php' enctype='multipart/form-data'><br />
<h4>Enter or edit your details and/or upload an image</h4>
<textarea name='text' cols='50' rows='3'>$text</textarea><br />
<br />
<span class='fieldname'>Interests:</span>
<select id="cat" name="select[]" onchange=checkSelect(this) >
    <option value="Art" > Art</option>
    <option value="Culture" > Culture</option>
    <option value="Entertainment" > Entertainment</option>
    <option value="History" > History</option>
    <option value="Science" > Science</option>
    <option value="Space" > Space</option>
    <option value="Sports" > Sports</option>
    <option value="Technology" > Technology</option>
    <option value="General" > General</option>
</select>
</span><br /><br />

Image: 
<input type='file' name='image' size='14' maxlength='32' />
<input type='submit' value='Save Profile' />
</form><br /><br />
_END;
showFooter($user);
echo "</div></div></body></html>";

?>




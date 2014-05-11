<?php
include_once '../Db/dbwork.php';


function checkCategory($cat) {
	$cat = sanitizeString($cat);
	if (mysql_num_rows(queryMysql("SELECT * FROM interests
        WHERE interest='$cat' AND user = '{$_POST['user']}'")))
		return TRUE;
	else
		return FALSE;
}

function getInterest(){
	if ($rquery = mysql_num_rows(queryMysql("SELECT interest FROM interests
		WHERE user = '{$_POST['user']}'")))
		$result = mysql_fetch_row($rquery);

	return $result[0];
}

function sanitizeString($var)
{
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	return mysql_real_escape_string($var);
}

if (isset($_POST['selected']))
{
	if (!checkCategory($_POST['selected'])){
		queryMysql("UPDATE interests SET interest='{$_POST['selected']}' where user='{$_POST['user']}'");
		$catg = $_POST['selected'];
		echo "<option value=\"$catg\" selected> $catg</option> ";
	}
}
	
?>
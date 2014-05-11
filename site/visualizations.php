<?php

include_once 'Utils/header.php';
include_once 'Db/dbwork.php';

echo "<script src='Js/AjaxCalls.js'></script>";
echo "<script src='Js/d3.min.js'></script>";
echo "<div class='main'>";
showProfile($user);

if (!$loggedin) die();

if (isset($_GET['deletevis']))
{
	$delete = sanitizeString($_GET['deletevis']);
	if (queryMysql("DELETE FROM visualizations WHERE filename='$delete' AND user='$user'"))
        echo "<br/><p> File was removed. Check <a href='visualizations.php?uservis=$uservis'> your visualizations</a> page to view the changes. </p>";
    else
        echo "<br/><p> Something went wrong while trying to delete your visualization</p>";
}

if (isset($_GET['uservis']))
{
    $uservis 	= sanitizeString($_GET['uservis']);
    $query 	 	= "SELECT filename FROM visualizations WHERE user='$user'";
    $execquery  = queryMysql($query);
    echo "<br/><span class='buttonheading'>Current visualizations</span>";
    echo "<ul class='vismenu'>";
    while( $value = mysql_fetch_array($execquery)){ 
    	echo  <<<_END
    	<li><a href="javascript:void(0);" onclick='getVis("$user", "$value[0]");'>$value[0]</a> 
    	<a href='visualizations.php?deletevis=$value[0]'> &#x2704;</a></li>
_END;
	}

    echo "</ul><br/><a id='newvis' href='visualizations.php?addvis=$uservis'>" .
         "&#10070; Add new visualization</a><br /><br />";
}



if (isset ($_GET['addvis']))
{
	echo <<<_END
<script src='Js/jquery-1.11.0.min.js'></script>
<form name="jsonFile" enctype="multipart/form-data" action="visualizations.php" method="post">
  <fieldset id='jsoninput'>
    <h4>Your json file details:</h4>
     <input type='file' name = 'jsonf' id='fileinput' >
     <input type='submit' value='Load'>
     <br />
     <div id='tcontainer'>
     <h4>The d3 template you want to use for your file:</h4>
     <input type="radio" id='d3ttree' name="template" value="tree" data-description="This is a fold-able regular vertical tree that will display your information" checked/>Tree <br />
     <input type="radio" id='d3tradial' name="template" value="radial tree" data-description="This is a tree that goes at 360 degrees, similar to an aerial overview of the data" />Radial Tree<br />
     <input type="radio" id='d3tdendo' name="template" value="dendogram" data-description="The dendogram is a tree diagram useful for showing taxonomic relationships" />Dendogram<br />
     </div>
     <div id='tdetails'>
     </div>
     <h4>Where does it fit:</h4>
  	 <input type="radio" name="domain" value="general" checked/>General <br />
     <input type="radio" name="domain" value="art"/>Art <br />
     <input type="radio" name="domain" value="culture"/>Culture <br />
     <input type="radio" name="domain" value="technology"/>Technology<br />
     <input type="radio" name="domain" value="entertainment"/>Entertainment<br />
     <input type="radio" name="domain" value="history"/>History<br />
     <input type="radio" name="domain" value="science"/>Science<br />
     <input type="radio" name="domain" value="sports"/>Sports<br />
     <input type="radio" name="domain" value="space"/>Space<br />
  </fieldset>
</form>

<script>
    var radioSel = $("input[name=template]:radio").get();

    $.each( radioSel, function(index, object) {
        var description = $(this).data('description');
        $(this).mouseover(function() {
            $( "#tdetails" ).slideDown( "fast", function() {
                $("#tdetails").addClass('infobox').text(description);
            });
        });
    });

    $("#tcontainer").mouseleave(function() {
            $("#tdetails").hide();
        });
    
</script>
_END;

}


if (isset($_FILES['jsonf']['name'])) {
	if ($_FILES['jsonf']['error'] > 0)
		echo "Error: " . $_FILES['jsonf']['error'] . "<br>";
	else {
		$savepath = "Vis/Users/$user/";
		$filename = basename($_FILES['jsonf']['name']);
		if (!file_exists($savepath)) 
			mkdir($savepath, 0775, true);
		$saveto   = $savepath . $filename;
		
		if (move_uploaded_file($_FILES['jsonf']['tmp_name'], $saveto))
		{
			$name = substr($filename, 0, (strlen ($filename)) - (strlen (strrchr($filename,'.'))));
			if (isset($_POST['domain'])) 	$domain 	= $_POST['domain'];
			if (isset($_POST['template']))	$template	= $_POST['template'];
			if (queryMysql("INSERT INTO visualizations VALUES('$name','$user', '$domain', '$template')"))
                echo "<p> File was added. Check <a href='visualizations.php?uservis=$uservis'> your visualizations</a> page to view it. </p>";
            else
                echo "<p> Your file couldn't be added. Go back to <a href='visualizations.php?uservis=$uservis'> your visualizations</a> page</p>";
		}
		else
		{
			echo "<p> Uploading failed. There was something wrong with your file. </p>";
		}
		
	}
}

showFooter($user);
echo "</div></body>";
?>
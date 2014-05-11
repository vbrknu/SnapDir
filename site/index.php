<?php 
include_once 'Utils/header.php';

echo "<br /><span class='main'>Welcome to SnapDir,";

if ($loggedin) echo " $user, you are logged in.";
else           echo ' please sign up and/or log in to join in.';

?>

</span><br /><br />
</div>
</body>
</html>

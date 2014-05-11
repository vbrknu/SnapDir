<?php 
include_once 'Utils/header.php';

if (isset($_SESSION['user']))
{
    destroySession();
    echo "<div class='main'>You have been logged out. Please " .
         "<a href='index.php'>click here</a> to refresh the screen.";
}
else echo "<div class='main'><br />" .
          "You cannot log out because you are not logged in";
?>

<br /><br />
</div>
</div>
</body>
</html>

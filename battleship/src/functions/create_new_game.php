<?php
include_once("../includehelper.php");


// Check the value of the "type" parameter from the GET request
if($_GET["type"]=="playervsplayer") {
    // Inform the user to use lobbies for player vs player
     echo "Use lobbies for playervsplayer";
}  else {
    // Invalid Request
    echo "Invalid Request";
}

?>
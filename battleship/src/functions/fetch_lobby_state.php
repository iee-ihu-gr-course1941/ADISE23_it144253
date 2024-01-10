<?php
include("../includehelper.php");

// Create a new stdClass object for the response
$res = new \stdClass();
// Retrieve the "code" parameter from the GET request
$code = $_GET["code"];
// Get lobby information based on the provided code
$lobby = DbUtils::getLobby($code);
// Populate response object with the lobby state
$res->state = $lobby[5];
// Encode the response object as JSON and echo it back to the player
echo json_encode($res);
?>
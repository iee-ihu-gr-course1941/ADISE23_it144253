<?php
include_once("../includehelper.php");
// Create a new stdClass object for the response
$res = new \stdClass();
// Retrieve the "code" parameter from the GET request
$code = $_GET["code"];
// Get lobby information based on the provided code
$lobby = DbUtils::getLobby($code);
// Check if the lobby exists
if($lobby) {
    // Check if the lobby is in the "waiting" state
    if($lobby[5] == "waiting") {
        // Populate response object with lobby information
        $res->id = $lobby[2];
        $res->playerId = $lobby[4];
        $res->success = true;
        echo json_encode($res);
        // Update lobby state to "occupied"
        DbUtils::setState($code, "occupied");
    } else {
        // Lobby is in the wrong state
        $res->success = false;
        $res->errormsg = "Lobby is in wrong state";
        echo json_encode($res);
    }
} else {
    // Lobby not found
    $res->success = false;
    $res->errormsg = "Lobby not found";
    echo json_encode($res);
}
?>
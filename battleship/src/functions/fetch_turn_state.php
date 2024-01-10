<?php
include_once("../includehelper.php");
// Create a new stdClass object for the response
$res = new \stdClass();
// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];
// Retrieve the game based on the provided $id
$game = DbUtils::getGame($id);
// Check if $playerId is provided
if($playerId) {
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided $playerId
        $player = $game->getPlayerFromId($playerId);
        // Check if the player exists
        if($player) {
            // Populate response object with turn information
            $res->yourTurn = $game->turn == $player->playerNum;
            $res->turn = $game->turn;
            $res->success = true;
            echo json_encode($res);
        } else {
            // Invalid Player Id
            $res->success = false;
            $res->errormsg = "Invalid Player Id.";
            echo json_encode($res);
        }
    } else {
        // Invalid Game Id
        $res->success = false;
        $res->errormsg = "Invalid Game Id.";
        echo json_encode($res);
    }
} else {
    // Player Id not specified
    $res->success = false;
    $res->errormsg = "Player Id Not Specified.";
    echo json_encode($res);
}
?>
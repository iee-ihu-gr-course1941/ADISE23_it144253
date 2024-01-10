<?php
include_once("../includehelper.php");

// Create a new stdClass object for the response
$res = new \stdClass();

// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];

// Get the game based on the provided id
$game = DbUtils::getGame($id);

// Check if player ID and game ID are provided
if($playerId  && $id) {
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided player ID
        $player = $game->getPlayerFromId($playerId);
        // Retrieve the enemy player
        $enemy = $game->getPlayer($player->playerNum == 1 ? 2 : 1);
        // Check if the player exists
        if($player) {
            // Calculate and retrieve statistics
            // Ships Sunk
            $res->shipsSunkPlayer = count($player->board->getSunkShips());
            $res->shipsSunkEnemy = count($enemy->board->getSunkShips());
            // Set success flag to true
            $res->success = true;
            // JSON-encode the response object and echo it
            echo json_encode($res);
        } else {
            // Invalid Player ID
            $res->success = false;
            $res->errormsg = "Invalid Player Id.";
            echo json_encode($res);
        }
    } else {
        // Invalid Game ID
        $res->success = false;
        $res->errormsg = "Invalid Game Id.";
        echo json_encode($res);
    }
} else {
    // Player ID or Game ID not specified
    $res->success = false;
    $res->errormsg = "Player Id or Game Id Not Specified.";
    echo json_encode($res);
}
?>
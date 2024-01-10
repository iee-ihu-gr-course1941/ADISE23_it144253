<?php
include_once("../includehelper.php");

// Create a new stdClass object for the response
$res = new \stdClass();

// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];

// Get the game based on the provided id
$game = DbUtils::getGame($id);
// Check if playerId is provided
if($playerId) {
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided playerId
        $player = $game->getPlayerFromId($playerId);
        // Retrieve the enemy player (opponent)
        $enemy = $game->getPlayer($player->playerNum == 1 ? 2 : 1);
        // Check if the player exists
        if($player) {
            // Check if the enemy has finished placing their ships
            $res->enemyFinished = $enemy->donePlacing;
            // If the enemy has not finished, provide the number of ships they still need to place
            if(!$res->enemyFinished) {
                $res->needToPlace = 5-  count($enemy->board->ships);
            }
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
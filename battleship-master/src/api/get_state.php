<?php
include_once("../easyinclude.php");
// Create a new stdClass object for the response
$res = new \stdClass();
// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];
// Retrieve the game based on the provided $id
$game = DbUtils::getGame($id);
// Check if both $id and $playerId are provided
if($id && $playerId) {
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided $playerId
        $player = $game->getPlayerFromId($playerId);
        // Check if the player exists
        if($player) {
            // Retrieve the game state
            $state = $game->state;
            // Check if the game state is 2 (indicating the end of the game)
            if($state == 2) {
                // Check if the player won the game
                $res->win = $game->winner->playerNum == $player->playerNum;
            }
            // Populate response object with game state information
            $res->state = $state;
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
    // Game Id or Player Id not specified
    $res->success = false;
    $res->errormsg = "Game Id or Player Id Not Specified.";
    echo json_encode($res);
}
?>
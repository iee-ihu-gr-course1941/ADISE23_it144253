<?php
include_once("../easyinclude.php");

// Create a new stdClass object for the response
$res = new \stdClass();

// Retrieve parameters from the GET request
$pos = $_GET["pos"];
$up = $_GET["up"];
$length = $_GET["length"];
$id = $_GET["id"];
$playerId = $_GET["playerId"];

// Check if game ID and player ID are provided
if($id && $playerId) {
    // Get the game based on the provided ID
    $game = DbUtils::getGame($id);
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided player ID
        $player = $game->getPlayerFromId($playerId);
        // Check if the player exists
        if($player) {
            // Check if the game state is 0 (placing ships phase)
            if($game->state == 0) {
                // Create a new ship based on the provided parameters
                $ship = new Ship(new Position($pos), intval($length), ($up==1?true:false));
                // Check if the ship can be placed on the player's board
                if($player->board->isPlaceable($ship)) {
                    // Place the ship on the player's board
                    $player->board->placeShip($ship);
                    // Set success flag to true
                    $res->success = true;
                    // Update the game in the database
                    DbUtils::updateGame($game, $id);
                    // JSON-encode the response object and echo it
                    echo json_encode($res);
                } else {
                    // Invalid Ship / Can't Place
                    $res->success = false;
                    $res->errormsg = "Invalid Ship / Can't Place.";
                    echo json_encode($res);
                }
            } else {
                // Already Done Placing
                $res->success = false;
                $res->errormsg = "Already Done Placing.";
                echo json_encode($res);
            }
        } else {
            // Invalid Player ID
            $res->success = false;
            $res->errormsg = "Invalid Player Id.";
            echo json_encode($res);
        }
    } else {
        // Invalid Game ID
        $res->success = false;
        $res->errormsg = "Invalid Game Id";
        echo json_encode($res);
    }
} else {
    // Game ID or Player ID not specified
    $res->success = false;
    $res->errormsg = "Game Id or Player Id not Specified";
    echo json_encode($res);
}
?>
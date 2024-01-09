<?php
include_once("../easyinclude.php");

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
        // Retrieve the enemy player (opponent)
        $enemy = $game->getPlayer($player->playerNum == 1 ? 2 : 1);
        // Check if the player exists
        if($player) {
            // Modify the enemy board to hide ship positions
            $board =  $enemy->board->board;
            foreach($board as $key1=>$row) {
                foreach($row as $key2=>$cell) {
                    if(!($cell == " " || $cell == "X" || $cell == "&#9785;")) {
                        $board[$key1][$key2] = " ";
                    }
                }
            }
            // Populate response object with the modified enemy board
            $res->board = $board;
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
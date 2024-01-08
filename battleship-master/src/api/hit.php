<?php
include_once("../easyinclude.php");
// Create a new stdClass object for the response
$res = new \stdClass();
// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];
$pos = $_GET["pos"];
// Create a Position object from the provided $pos
$pos = new Position($pos);
// Check if both $id and $playerId are provided
if($id && $playerId) {
    // Get the game based on the provided $id
    $game = DbUtils::getGame($id);
    // Check if the game exists
    if($game) {
        // Retrieve the player from the game based on the provided $playerId
        $player = $game->getPlayerFromId($playerId);
        // Retrieve the enemy player (opponent)
        $enemy = $game->getPlayer($player->playerNum == 1?2:1);
        // Check if the player exists
        if($player) {
            // Check if the game is in the correct state (state == 1 indicates the playing phase)
            if($game->state == 1) {
                // Check if it's the player's turn
                if($game->turn == $player->playerNum) {
                    // Check if a valid position is provided
                    if($pos) {
                        // Check if the position has not been hit before
                        if(!$player->board->isAlreadyHit($pos->x, $pos->y)) {
                            // Perform the hit operation and update the game
                            $game->hit($pos, $player);
                            $res->success = true;
                            DbUtils::updateGame($game, $id);
                            echo json_encode($res);  
                        } else {
                            // Position has already been hit
                            $res->success = false;
                            $res->errormsg = "Already Hit!";
                            echo json_encode($res);  
                        }
                    } else {
                        // Invalid Position
                        $res->success = false;
                        $res->errormsg = "Invalid Position!";
                        echo json_encode($res);  
                    }
                } else {
                    // Not the player's turn
                    $res->success = false;
                    $res->errormsg = "Wait your turn! ";
                    echo json_encode($res);
                }
            } else {
                // Wrong state to perform this action
                $res->success = false;
                $res->errormsg = "The game is in wrong state to do this.";
                echo json_encode($res);
            }
        } else {
            // Invalid Player Id
            $res->success = false;
            $res->errormsg = "Invalid Player Id.";
            echo json_encode($res);
        }
    } else {
        // Invalid Game Id
        $res->success = false;
        $res->errormsg = "Invalid Game Id";
        echo json_encode($res);
    }
} else {
    // Game Id or Player Id not specified
    $res->success = false;
    $res->errormsg = "Game Id or Player Id not Specified";
    echo json_encode($res);
}
?>
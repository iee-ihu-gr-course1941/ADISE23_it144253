<?php
include_once("../easyinclude.php");

// Create a new stdClass object for the response
$res = new \stdClass();

// Retrieve parameters from the GET request
$id = $_GET["id"];
$playerId = $_GET["playerId"];

// Check if both $id and $playerId are provided
if($id && $playerId) {
// Retrieve the game based on the provided $id
$game = DbUtils::getGame($id);

// Check if the game exists
if($game) {
    // Retrieve the player from the game based on the provided $playerId
    $player = $game->getPlayerFromId($playerId);

    // Check if the player exists
    if($player) {
        // Check if the game state is 0 (indicating the placement phase)
        if($game->state == 0) {
        // Server-side ship placement check
        if(count($player->board->ships) == 5) {
            $fivesleft = 1;
            $foursleft = 1;
            $threesleft =2;
            $twosleft = 1;
            // Check the number of ships of each length
            foreach($player->board->ships as $ship) {
                if($ship->length == 5) {
                    $fivesleft -= 1;
                } else if($ship->length == 4) {
                    $foursleft -= 1;
                }else if($ship->length == 3) {
                    $threesleft -= 1;
                }else if($ship->length == 2) {
                    $twosleft -= 1;
                }
            }
            // Check if the correct number of ships of each length is placed
            if($fivesleft == 0 && $foursleft == 0 && $threesleft == 0 && $twosleft == 0) {
                // Check if the player has not already done placing ships
                if($player->donePlacing == false) {
                // Mark the player as done placing ships
                    $game->donePlacing($player);
                    $player->donePlacing = true;
                    // Check the game state for further actions
                    if($game->state == 1) {
                        $res->waitforenemy = false;
                        $game->start();
                    } else {
                        $res->waitforenemy = true;
                    }
                    // Update the game in the database
                    DbUtils::updateGame($game, $id);
                    $res->success = true;
                    echo json_encode($res);
                } else {
                    // Player has already done placing ships
                    $res->success = false;
                    $res->errormsg = "Already done placing.";
                    echo json_encode($res);  
                }
            } else {
                // Invalid ship placement
                $res->success = false;
                $res->errormsg = "You placed invalid ships.";
                echo json_encode($res);
            }
            
        } else {
            // Incorrect number of ships
            $res->success = false;
            $res->errormsg = "Need to place 5 ships. Only ".count($player->board->ships)." found.";
            echo json_encode($res);
        }
        } else {
            // Game is not in the placement phase
            $res->success = false;
            $res->errormsg = "Already Done Placing.";
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
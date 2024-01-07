<?php
include_once("../easyinclude.php");

// Create a new stdClass object for the response
$res = new \stdClass();

// Function to generate a random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    return $randomString;
}

// Generate unique IDs and codes for the new lobby
$id = generateRandomString();
$playeridone = generateRandomString();
$playeridtwo = generateRandomString();
//used for deleting the lobby later
$secret = generateRandomString();
$code = sprintf("%06d", mt_rand(1, 999999));

// Create a new game and players
$game = new Game(new Player($playeridone), new Player($playeridtwo));

// Store the game in the database for easy access later
DbUtils::newGame($game, $id);

// Store the lobby in the database so that someone else can join
DbUtils::newLobby($code, $secret, $id, $playeridone, $playeridtwo);

// Populate the response object with useful information for the hoster's client
$res->id = $id;
$res->playeridone = $playeridone;
$res->secret = $secret;
$res->code = $code;

// Send the JSON-encoded response back to the client
echo json_encode($res);
?>
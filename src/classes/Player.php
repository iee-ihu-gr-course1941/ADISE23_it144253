<?php
class Player {
   // public $name;
   // Properties to store player information, including the game, board, player number, placement status, and turn status
    public $board;
    public $game;
    public $playerNum;
    public $donePlacing;
    public $id;
    public $turn;
function __construct($id )
{
    // Initialize player with default values
    $this->donePlacing = false;
    $this->turn = false;
    //$this->name = $name;
    //$this->playerNum = $playerNum;
    $this->id = $id;
}

public function placeShip(Ship $ship) {
    // Place a ship on the player's board
    $this->board->placeShip($ship);
}

public function onTurn() {
    // Set the turn status to true, indicating that it's the player's turn
    $this->turn = true;
}
}
?>
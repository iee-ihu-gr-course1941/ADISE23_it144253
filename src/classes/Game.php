<?php

class Game {
    public $player2;
    public $player1;
    public $board1;
    public $board2;
    public $state;
    public $turn;
    public $winner;
    function __construct(Player $player11, Player $player22) {
        // Initialize game with players, boards, and initial state
        $this->turn = 0;
        $player11->playerNum = 1;
        $this->player1 = $player11;
        $player22->playerNum = 2;
        $this->player2 = $player22;
        $this->board1 = new Board(10, 10);
        $this->board2 = new Board(10, 10);
        $this->state = 0;
        // Set player boards and game references
        $this->player1->board = $this->board1;
        $this->player2->board = $this->board2;
        $this->player1->game = $this;
        $this->player2->game = $this;
    }
    public function getPlayer($num) {
        // Get the player based on the provided player number
        if ($num == 1) {
            return $this->player1;
        }
        else {
            return $this->player2;
        }
    }
    public function getPlayerFromId($id) {
        // Get the player based on the provided player id
        if ($id == $this->player1->id) {
        
             
           return $this->player1;
        } else if ($id == $this->player2->id) {
            return $this->player2;
        } else {
            return NULL;
        } 
    }
    function start() {
        // Start the game by randomly choosing the starting player's turn
        if ($this->state == 1) {
            $this->turn = mt_rand(1, 2);
            $this->getPlayer($this->turn)->onTurn();
        }   
    }

    public function nextTurn() {
        // Move to the next turn, changing the active player
        $this->getPlayer($this->turn)->turn = false;
        $this->turn = ($this->turn == 1 ? 2 : 1);
        $this->getPlayer($this->turn)->onTurn();
    }
    public function hit(Position $pos, Player $player) {
        // Process a hit on the opponent's board
        array_push($player->board->pointsHit, $pos);
        $enemy = $this->getPlayer($player->playerNum == 1 ? 2 : 1);
        $ship = $enemy->board->checkShip($pos);
        //  var_dump($ship);
        //echo "<br>";
        if ($ship) {
            // Handle hit on a ship
            if (in_array($pos, $ship->pointsHit)) {
              //  echo "Player " . $player->playerNum . "<br>ALREADY HIT " . $pos->asString . "<br>";
                $this->nextTurn();
            }
            else {
                //echo "Player " . $player->playerNum . "<br>HIT " . $pos->asString . "<br>";
                $enemy->board->setPoint($pos, "X");
                array_push($ship->pointsHit, $pos);
                if ($ship->isAllPointsHit()) {
                    $ship->sunk = true;
                   // echo "SHIP SUNK! <br>";
                    if ($enemy->board->isAllShipsSunk()) {
//                        echo "Player " . $player->playerNum . " WON!!! ";

                        $this->state = 2;
                        $this->winner = $player;
                    }
                    else {
                        $this->nextTurn();
                    }
                }
                else {
                    $this->nextTurn();
                }

            }
        }
        else {
            // Handle a miss
            //echo "Player " . $player->playerNum . "<br>Missed " . $pos->asString . "<br>";
            $enemy->board->setPoint($pos, "*");
            $this->nextTurn();
        }

    }
    public function donePlacing(Player $player) {
        // Check if both players are done placing, then start the game
        //echo strval($player->playerNum);
        if ($player->playerNum == 1) {
            if ($this->player2->donePlacing) {
                $this->state = 1;
            }
        }
        else {
            if ($this->player1->donePlacing) {
                $this->state = 1;
            }
        }

        if ($this->state == 1) {
            $this->start();
        }

    }
}


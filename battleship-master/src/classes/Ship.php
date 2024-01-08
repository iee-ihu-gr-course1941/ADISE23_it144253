<?php
class Ship {
    // Properties to store ship details
    public $starting;
    public $length;
    public $up;
    public $sunk;
    public $letter;
    public $pointsHit;

    // Constructor to initialize ship properties
    function __construct($starting, $length1, $up1) {
        $this->starting = $starting;
        $this->length = $length1;
        $this->up = $up1;
        $this->sunk = FALSE;
        $this->pointsHit = [];

    }

    // Check if all points of the ship are hit
    public function isAllPointsHit() {
        return count($this->pointsHit) == count($this->getPoints());
    }

    // Get an array of points representing the ship
    public function getPoints() {
        $points = array();
        // Calculate points based on ship orientation (up or horizontal)
        if($this->up) {
            if((($this->starting->y)-$this->length+1) < 0 ){
                return NULL; // Invalid ship position
            }

            for ($i = 0; $i < $this->length; $i++) {
                array_push($points, new Position($this->starting->x,  $this->starting->y-$i));
            }

        } else {
            if($this->starting->x < 0 ){
                return NULL; // Invalid ship position
            }

            for ($i = 0; $i < $this->length; $i++) {
                array_push($points, new Position($this->starting->x+$i,  $this->starting->y));
            }

        }
        return $points;
    }
}
?>
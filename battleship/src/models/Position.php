<?php
class Position {
     // Properties to store the position in different representations
    public $asString;
    public $x;
    public $y;

    // Constructor with dynamic argument handling
    public function __construct()
    {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();

        if (method_exists($this, $function = '__construct'.$numberOfArguments)) {
            call_user_func_array(array($this, $function), $arguments);
        }
    }
    // Constructor for a position represented as a string (e.g., 'A1')
    public function __construct1($loc) {
        $this->asString = $loc;
       // echo $loc;
       // Extract x and y coordinates from the string
        if(strlen($loc) == 3) {
            $this->x = intval($loc[1].$loc[2])-1;
        } else {
            $this->x = intval($loc[1])-1;
        }
        
        $this->y = ord(strtolower($loc[0]))-97;
    }
    // Constructor for a position represented by x and y coordinates
    public function __construct2($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
        // Convert coordinates to a string representation (e.g., 'A1')
        $alphas = range('A', 'Z');
        $this->asString = $alphas[$y].($x+1);
    }
}
?>
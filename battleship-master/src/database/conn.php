<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Create a PDO (PHP Data Objects) instance for database connection
  $pdo = new PDO("mysql:host=$servername;dbname=battleship", $username, $password);

?>
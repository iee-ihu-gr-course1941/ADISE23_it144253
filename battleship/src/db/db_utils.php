<?php
include( "db_conn.php");

class DbUtils {
    // Method to create a new game entry in the database
    public static function newGame($game, $id) {
        global $pdo;
        $select = "INSERT INTO `battleship`.`games` (`game`, `idref`) VALUES ('".serialize($game)."','".$id."')";
        $statement = $pdo->prepare($select);
        $statement->execute();
    }

    // Method to retrieve a game from the database based on ID
    public static function getGame($id) {
        global $pdo;
        $select = "SELECT * FROM `battleship`.`games` WHERE `idref`=:id";
        $statement = $pdo->prepare($select);
        $statement->bindParam(":id", $id);
        $statement->execute();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            return unserialize($row['game']);
        }
    }

    // Method to update a game entry in the database
    public static function updateGame($game, $id) {
        global $pdo;
        $select = "UPDATE `battleship`.`games` SET `game`=:game WHERE `idref`=:id";
        $statement = $pdo->prepare($select);
            $serialize = serialize($game);
            $statement->bindParam(":game", $serialize);
        $statement->bindParam(":id", $id);
        $statement->execute();
    }

    // Method to create a new lobby entry in the database
    public static function newLobby($code, $secret, $gameid, $playeridone, $playeridtwo) {
        global $pdo;
        $select = "INSERT INTO `battleship`.`lobbies` (`code`, `gameid`,  `playeridone`, `playeridtwo`, `secret`, `state`) VALUES (:code,:gameid, :playeridone, :playeridtwo, :secret, 'waiting')";
        $statement = $pdo->prepare($select);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":gameid", $gameid);
        $statement->bindParam(":playeridone", $playeridone);
        $statement->bindParam(":playeridtwo", $playeridtwo);
        $statement->bindParam(":secret", $secret);
        $statement->execute();
    }

    // Method to delete a lobby entry from the database based on secret
    public static function deleteLobby($secret) {
        global $pdo;
        $select = "DELETE FROM `battleship`.`lobbies` WHERE `secret`=:secret";
        $statement = $pdo->prepare($select);
        $statement->bindParam(":secret", $secret);
        $statement->execute();
    }

    // Method to retrieve a lobby from the database based on code
    public static function getLobby($code) {
        global $pdo;
        $select = "SELECT * FROM `battleship`.`lobbies` WHERE `code`=:code";
        $statement = $pdo->prepare($select);
        $statement->bindParam(":code", $code);
        $statement->execute();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            return [$row['code'], $row['secret'], $row['gameid'], $row['playeridone'], $row['playeridtwo'], $row['state']];
        }
    }

    // Method to update the state of a lobby in the database
    public static function setState($code,$state) {
        global $pdo;
        $select = "UPDATE `battleship`.`lobbies` SET `state`=:state WHERE `code`=:code";
        $statement = $pdo->prepare($select);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":state", $state);
        $statement->execute();
    }
}
?>
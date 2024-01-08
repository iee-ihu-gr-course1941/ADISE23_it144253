-- DATABASE NAME: battleship
-- CREATE DATABASE battleship;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

CREATE TABLE `games` (
  `id` int(10) UNSIGNED NOT NULL,
  `game` mediumtext DEFAULT NULL,
  `idref` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `lobbies` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` mediumtext DEFAULT NULL,
  `gameid` mediumtext DEFAULT NULL,
  `playeridone` mediumtext DEFAULT NULL,
  `playeridtwo` mediumtext DEFAULT NULL,
  `secret` mediumtext DEFAULT NULL,
  `state` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `lobbies`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `games`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=394;


ALTER TABLE `lobbies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

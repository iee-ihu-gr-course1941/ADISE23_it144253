<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Metadata and Bootstrap CSS -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Battleship Game</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
       
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container flex justify-content-center">
                <a class="navbar-brand nav-title" href="human_player.php">&#x2694;Battleship Game&#x2694;</a>
            </div>
        </nav>
        <!-- Page content-->
        <div id="main" class="container">
            <div class="text-center">
                <h1 id="text-main"class="mt-5">Loading code..</h1>
                <p class="lead" id="text-secondary"></p>
                <button id="confirm" style="display: none;" type="button" class="btn btn-secondary"></button>
                <button id="cancel" style="display: none;" type="button" class="btn btn-secondary"></button>
            </div>
            <!-- Row for displaying player boards -->
            <div style="display: none;" class="row" id="rfow">
                <h4 class="col">Your fleet</h4>
                <h4 class="col">Enemy's fleet</h4>
            </div>
            <br>
            <!-- Row for the game grid -->
            <div class="row" id="row">
                
            </div>

        </div>


        <style>
            .nav-title {
                font-size: 40px !important;
            }

        </style>
        <!-- Bootstrap core JS-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Bootstrap CSS (again - duplicated) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <!-- Custom JavaScript files for the game logic -->
        <script src="Position.js"></script>
        <script src="Ship.js"></script>
        <script src="human_player.js"></script>
    </body>
</html>

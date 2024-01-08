<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Metadata and Bootstrap CSS -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Battleship</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
       
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">Battleship - Multiplayer</a>
            </div>
        </nav>
        <!-- Page content-->
        <div id="main" class="container">
            <div class="text-center">
                <h1 id="text-main"class="mt-5">Battleship Multiplayer</h1>
                <p class="lead" id="text-secondary"></p>
                <button id="create"  type="button" class="btn btn-primary">Create Game</button>
                <br>
                <button id="join"  type="button" class="btn btn-secondary">Join Game</button>
            </div>

        </div>
        

        <!-- Bootstrap JS and Custom Script -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
      
        <script>
        // JavaScript for handling user interactions and API requests
        // Function to send API requests and handle promises
        function sendRequest(to) {
            return new Promise((resolve, reject) => {
                fetch(to).then(b=>b.json().then((res) => {
                    resolve(res);
                }))
            })
        }

        var shouldpoll= false;
        // Create Game Button Click Event
        $("#create").click(() => {
            sendRequest("../api/new_lobby.php").then((data) => {
                
                sessionStorage.setItem("secret", data.secret);
                sessionStorage.setItem("code", data.code);
                sessionStorage.setItem("playerId", data.playeridone);
                sessionStorage.setItem("id", data.id);

                $("#create").hide();
                $("#join").hide();

                $("#text-main").html("Join code: "+sessionStorage.getItem("code"));
                $("#text-secondary").html("You will be redirected when someone joins!");
                shouldpoll = true;
            })
        })
        // Periodically poll for lobby state when a game is created
        setInterval(() => {
            if(shouldpoll) {
                sendRequest("../api/get_lobby_state.php?code="+sessionStorage.getItem("code")).then((stateData) => {
                    if(stateData.state == "occupied") {
                        window.location.href = "humanplay.php";
                    }
                })
            }
        }, 500)

        // Join Game Button Click Event
        $("#join").click(() => {
            code = prompt("Enter code:")
            if(code) {
                sendRequest("../api/join_lobby.php?code="+code).then((data) => {
                if(data.success) {
                    sessionStorage.setItem("playerId", data.playerId);
                    sessionStorage.setItem("id", data.id);
                    window.location.href = "humanplay.php";
                } else {
                        $("#text-secondary").html(data.errormsg);
                }
            })
                    }
        })
        </script>
    </body>
</html>

// Variable declarations for game state and board
var state = 0;
var board = undefined;
var shipsToPlace = 5;
var up = false;
var boardwidth = 10;
var boardheight = 10;
var placestate = 0;
var currentselect;
var isUp;
var shipStart;
var placing = true;
var gameId = sessionStorage.getItem('id');
var playerId = sessionStorage.getItem('playerId'); var state = 0;
var turn = true;
var waitingforopponent = false;
var serverResponded = true;
// Function to get DOM element by ID
function elem(id) {
    return document.getElementById(id);
}

// Function to send a request to the server
function sendRequest(to) {
    return new Promise((resolve, reject) => {
        fetch(to).then(b => b.json().then((res) => {
            resolve(res);
        }))
    })
}
// Function to create a table based on provided data
function createTable(tableData, enemy) {
    const alphabet = Array.from(Array(26)).map((e, i) => i + 65).map((x) => String.fromCharCode(x));
    var table = document.createElement('table');

    table.className = "table table-bordered"
    table.id = (enemy ? "enemyBoard" : "placeBoard");
    var row = {};
    var cell = {};
    var counter = 0;
    // Add row for column numbers
    row = table.insertRow(-1)
    blankcell = row.insertCell();
    blankcell.textContent = " "
    tableData[0].forEach(function () {
        counter++
        numcell = row.insertCell();
        numcell.textContent = counter;
    })
    counter = 0;
    var counterx = 0;
    // Add rows and cells with data
    tableData.forEach(function (rowData) {
        counterx = 0;
        row = table.insertRow(-1);
        alphacell = row.insertCell();
        alphacell.textContent = alphabet[counter]
        rowData.forEach(function (cellData) {

            cell = row.insertCell();
            cell.textContent = cellData;
            cell.className = (enemy ? "enemyCell" : "placeCell");
            cell.id = new Position(counterx, counter).asString + (enemy ? "e" : "")
            counterx++
        });
        counter++
    });
    // Append the table to the main element
    elem("main").appendChild(table);

}
// Function to add click event listeners to cells in the placement board
function tableListener() {
    var table = document.getElementById("placeBoard");
    if (table != null) {
        for (var i = 0; i < table.rows.length; i++) {
            for (var j = 0; j < table.rows[i].cells.length; j++)
                table.rows[i].cells[j].onclick = function () {
                    if (this.classList.contains("placeCell")) {
                        placeCellClick(new Position(this.id));
                    }
                };
        }
    }

}
// Function to add click event listeners to cells in the enemy board
function enemyListener() {
    var table = document.getElementById("enemyBoard");
    if (table != null) {
        for (var i = 0; i < table.rows.length; i++) {
            for (var j = 0; j < table.rows[i].cells.length; j++)
                table.rows[i].cells[j].onclick = function () {
                    if (this.classList.contains("enemyCell")) {
                        enemyCellClick(new Position(this.id.slice(0, -1)));
                    }
                };
        }
    }
}
// Function to color specific cells in the board
function colorCells(arr, enemy) {
    arr.forEach(function (cellLoc) {
        if (enemy) {
            var cell = elem(cellLoc + "e");
            cell.style.backgroundColor = "red";
        } else {
            var cell = elem(cellLoc)
            cell.style.backgroundColor = "lime";
        }
    })
}
// Function to get ship letter based on the ship number
function getShipLetter(num) {
    if (num == 5) {
        return "a";
    } else if (num == 4) {
        return "b";
    } else if (num == 3) {
        return "c";
    } else if (num == 2) {
        return "d";
    } else {
        return "e";
    }
}
// Function to get ship name based on the ship number
function getShipName(num) {
    if (num == 5) {
        return "Carrier";
    } else if (num == 4) {
        return "Battleship";
    } else if (num == 3) {
        return "Cruiser";
    } else if (num == 2) {
        return "Submarine";
    } else {
        return "Destroyer";
    }
}
// Function to get ship length based on the ship number
function getShipLength(num) {
    if (num == 5) {
        return 5;
    } else if (num == 4) {
        return 4;
    } else if (num == 3) {
        return 3;
    } else if (num == 2) {
        return 3;
    } else {
        return 2;
    }
}
// Function to check if a ship can be placed in a given position
function checkPlaceShip(ship) {
    isPlaceable = true;
    var cc = 0;
    ship.getPoints().forEach((point) => {
        if (point.x > boardwidth - 1 || point.x < 0 || point.y < 0 || point.y > boardheight - 1 || (elem(point.asString).innerHTML != " " && elem(point.asString).innerHTML != getShipLetter(shipsToPlace))) {
            isPlaceable = false
        }
        cc++
    })
    return isPlaceable;
}
// Function to handle the click event on the placement board cell
function placeCellClick(pos) {
    if (!placing) return
    if (waitingforopponent) return
    if (document.getElementById(pos.asString).innerHTML == " ") {
        if (placestate == 0 || placestate == 1) {
            elem("confirm").disabled = false
            elem("cancel").disabled = false
            if (currentselect) {
                elem(currentselect.asString).innerHTML = " ";
            }
            //check if click area is already taken
            if (elem(pos.asString).innerHTML == " ") {
                elem(pos.asString).innerHTML = getShipLetter(shipsToPlace);
                elem("confirm").innerHTML = "Down"
                elem("cancel").innerHTML = "Right"
                elem("confirm").style.display = ""
                elem("cancel").style.display = ""
                var downship = new Ship(pos, getShipLength(shipsToPlace), true);
                var rightship = new Ship(pos, getShipLength(shipsToPlace), false);
                if (!checkPlaceShip(downship)) {
                    elem("confirm").disabled = true
                }
                if (!checkPlaceShip(rightship)) {
                    elem("cancel").disabled = true
                }
                currentselect = pos;
                placestate = 1;
            }
        }
    }
}
// Function to handle the click event on the enemy board cell
function enemyCellClick(pos) {
    if (serverResponded) {
        if (state == 1) {
            if (turn) {
                if (elem(pos.asString + "e").innerHTML == " ") {
                    sendRequest(`../api/hit.php?id=${gameId}&playerId=${playerId}&pos=${pos.asString}`)
                        .then((hitData) => {
                            if (hitData.success) {
                                elem(pos.asString + "e").innerHTML = "⌛"
                                serverResponded = false;
                            } else {
                                // alert(hitData.errormsg)
                            }
                        }).catch(err)
                }
            }
        }
    }
}
// Function to update the enemy board based on the array provided
function updateEnemyBoard(arr) {
    arr.forEach(function (row, ir) {
        row.forEach(function (cell, ic) {
            theElem = elem(new Position(ic, ir).asString + "e");
            theElem.innerHTML = (cell == "X" ? "💥" : cell);
            if (cell == "X") theElem.style.backgroundColor = "lime";
        })
    })
}
// Function to update the board based on the array provided
function updateBoard(arr) {
    arr.forEach(function (row, ir) {
        row.forEach(function (cell, ic) {
            theElem = elem(new Position(ic, ir).asString)
            theElem.innerHTML = (cell == "X" ? "💥" : cell);
            if (cell == "X") theElem.style.backgroundColor = "red";
        })
    })
}
// Function to handle reconnecting
function reconnect() {
    elem("text-main").innerHTML = "Reconnect Feature priority: low"
}
// Function to handle errors
function err(e) {
    elem("text-main").innerHTML = "Error"
    console.log(e)
}
// Event listener for the "Confirm" button click
elem("confirm").onclick = function () {
    if (placestate == 1) {
        //up

        var ship = new Ship(currentselect, getShipLength(shipsToPlace), true)
        shipStart = ship;
        isUp = true;
        ship.getPoints().forEach((point) => {
            elem(point.asString).innerHTML = getShipLetter(shipsToPlace);
        })

        elem("cancel").disabled = false
        elem("confirm").disabled = false
        elem("confirm").innerHTML = "Confirm";
        elem("cancel").innerHTML = "Cancel";
        placestate = 2;
    } else if (placestate == 2) {
        elem("confirm").disabled = true;
        elem("cancel").disabled = true;
        sendRequest(`../api/place_ship.php?id=${gameId}&playerId=${playerId}&up=${isUp ? "1" : "0"}&pos=${shipStart.starting.asString}&length=${getShipLength(shipsToPlace)}`)
            .then((placeData) => {
                if (placeData.success) {
                    placestate = 0;
                    elem("confirm").disabled = false;
                    elem("cancel").disabled = false;
                    elem("confirm").style.display = "none";
                    elem("cancel").style.display = "none";
                    shipsToPlace -= 1;
                    currentselect = undefined;
                    if (shipsToPlace == 0) {
                        sendRequest(`../api/done_placing.php?id=${gameId}&playerId=${playerId}`)
                            .then((doneData) => {
                                if (doneData.success) {
                                    if (doneData.waitforenemy) {
                                        waitingforopponent = true;

                                        $("#text-main").html("Waiting for opponent to place ships..")
                                    } else {
                                        placing = false;
                                        elem("text-main").innerHTML = ""
                                        elem("text-secondary").innerHTML = ""

                                        state = 1;
                                        sendRequest("../api/get_enemy_board.php?id=" + gameId + "&playerId=" + playerId).then((enemyBoardData) => {
                                            if (enemyBoardData.success) {
                                                createTable(enemyBoardData.board, true);
                                                enemyListener()
                                                $("#placeBoard").show();
                                                $("#enemyBoard").appendTo("#row");
                                                $("#placeBoard").prependTo("#row");
                                                elem("placeBoard").className = "table table-bordered col-md-6"
                                                elem("enemyBoard").className = "table table-bordered col-md-6"
                                                elem("rfow").style.display = "";
                                            } else {
                                                alert(enemyBoardData.errormsg)
                                            }

                                        })
                                    }
                                } else {
                                    alert(doneData.errormsg);
                                }
                            }).catch(err)
                    } else {
                        elem("text-secondary").innerHTML = `Currently placing: ${getShipName(shipsToPlace)} (Length ${getShipLength(shipsToPlace)})<br>${shipsToPlace} more ships to go!<br><br>`;
                    }
                } else {
                    alert(placeData.errormsg);
                    /*          shipStart.getPoints().forEach((point) => {
                                           //consofle.log(point);
                             elem(point.asString).innerHTML = " ";  
                           })
                           shipsToPlace += 1;
                           elem("text-secondary").innerHTML = `Currently placing: ${getShipName(shipsToPlace)} (Length ${getShipLength(shipsToPlace)})<br>${shipsToPlace} more ships to go!<br><br>`;*/
                }
            }).catch(err)


    }
}
// Event listener for the "Cancel" button click
elem("cancel").onclick = function () {
    if (placestate == 1) {
        //right
        var ship = new Ship(currentselect, getShipLength(shipsToPlace), false)
        shipStart = ship;
        isUp = false;
        ship.getPoints().forEach((point) => {
            elem(point.asString).innerHTML = getShipLetter(shipsToPlace);
        })
        elem("cancel").disabled = false
        elem("confirm").disabled = false
        elem("confirm").innerHTML = "Confirm";
        elem("cancel").innerHTML = "Cancel";
        placestate = 2;
    } else if (placestate == 2) {
        placestate = 1;
        elem("cancel").style.display = "none"
        elem("confirm").style.display = "none"
        var ship = shipStart;
        ship.getPoints().forEach((point) => {
            elem(point.asString).innerHTML = " ";
        })
    }
}



//actual code
// Display a message while retrieving the game board
elem("text-main").innerHTML = "Retrieving game board.."
// Send a request to the server to get the player's board information
sendRequest("../api/get_board.php?id=" + gameId + "&playerId=" + playerId).then((boardInfo) => {
    if (boardInfo.success) {
        // Check the game state
        if (boardInfo.state == 0) {
            // Game is in ship placement phase
            shipsToPlace = boardInfo.shipsToPlace;
            if (shipsToPlace != 0) {
                // Player still needs to place ships
                elem("text-main").innerHTML = "Place your ships<br>"
                elem("text-secondary").innerHTML = `Currently placing: ${getShipName(shipsToPlace)} (Length ${getShipLength(shipsToPlace)})<br>${shipsToPlace} more ships to go!<br><br>`;
                board = boardInfo.board;
                boardwidth = board[0].length;
                boardheight = board.length;
                // Create the player's board table
                createTable(board, false);
                // Setup event listeners for the player's board
                tableListener()
            } else {
                // Waiting for the opponent to place ships
                waitingforopponent = true;
                $("#placeboard").hide();
                elem("text-main").innerHTML = "waiting for opponent to place ships..."
            }
        } else if (boardInfo.state == 1) {
            // Game has moved to the gameplay phase

            placing = false;
            elem("text-main").innerHTML = ""
            elem("text-secondary").innerHTML = ""

            state = 1;
            // Get and display the enemy's board
            sendRequest("../api/get_enemy_board.php?id=" + gameId + "&playerId=" + playerId).then((enemyBoardData) => {
                if (enemyBoardData.success) {
                    // Create the enemy's board table
                    createTable(enemyBoardData.board, true);
                    // Setup event listeners for the player's and enemy's boards
                    board = boardInfo.board;
                    boardwidth = board[0].length;
                    boardheight = board.length;

                    createTable(board, false);
                    tableListener()
                    enemyListener()
                    // Show both boards side by side
                    $("#placeBoard").show();
                    $("#enemyBoard").appendTo("#row");
                    $("#placeBoard").prependTo("#row");
                    elem("placeBoard").className = "table table-bordered col-md-6"
                    elem("enemyBoard").className = "table table-bordered col-md-6"
                    elem("rfow").style.display = "";

                }
            })



        }
    } else {
        // Display an error message if fetching the board fails
        elem("text-main").innerHTML = "Failed fetching board!<br><br>" + boardInfo.errormsg;
    }
}).catch(err)
//}
// Periodic interval to update the game state and UI
setInterval(() => {
    // Check if the game is in the gameplay phase (state == 1)
    if (state == 1) {
        // Request to get the current turn information
        sendRequest("../api/get_turn.php?id=" + gameId + "&playerId=" + playerId).then((turnData) => {
            if (turnData.success) {
                // Update the UI to indicate whether it's the player's turn or the opponent's turn
                turn = turnData.yourTurn;
                elem("text-main").innerHTML = turn ? "Your turn!" : "Opponent's turn.."
            }
        })
        // Request to get the enemy's board information
        sendRequest("../api/get_enemy_board.php?id=" + gameId + "&playerId=" + playerId).then((enemyBoardData) => {
            if (enemyBoardData.success) {
                // Update the UI with the latest enemy board state
                updateEnemyBoard(enemyBoardData.board)
                // Set a flag to indicate that the server has responded
                serverResponded = true;
            } else {
                // Display an alert if there is an error fetching enemy board data
                alert(enemyBoardData.errormsg)
            }
        })
        // Request to get the player's board information
        sendRequest("../api/get_board.php?id=" + gameId + "&playerId=" + playerId).then((boardData) => {
            if (boardData.success) {
                // Update the UI with the latest player board state
                updateBoard(boardData.board)
                // Set a flag to indicate that the server has responded
                serverResponded = true;
            } else {
                // Display an alert if there is an error fetching player board data
                alert(boardData.errormsg)
            }
        })
        // Request to get the current game state
        sendRequest("../api/get_state.php?id=" + gameId + "&playerId=" + playerId).then((stateData) => {
            if (stateData.success) {
                // Check if the game has ended (state == 2)
                if (stateData.state == 2) {
                    state = 2;
                    // Update UI to show the game result (win or lose)
                    if (stateData.win) {
                        //ez
                        elem("text-main").innerHTML = "You won! Congrats!"
                    } else {
                        //f'
                        elem("text-main").innerHTML = "You lost! Better luck next time-"
                    }
                }
            } else {
                // Display an alert if there is an error fetching game state data
                alert(boardData.errormsg)
            }
        })
        // Request to get the game statistics
        sendRequest("../api/stats.php?id=" + gameId + "&playerId=" + playerId).then((statsData) => {
            if (statsData.success) {
                // Update the UI with game statistics
                elem("text-secondary").innerHTML = `${statsData.shipsSunkPlayer} of your ships have been sunk.<br>You have sunk ${statsData.shipsSunkEnemy} opponent ships!`
                /*
                    // Additional functionality (commented out):
                    // Color cells on the boards based on hits
                    colorCells(statsData.pointsHitEnemy, false);
                    colorCells(statsData.pointsHitPlayer, true);
                    */
            }
        })
    }
    // Check if the player is waiting for the opponent to finish placing ships
    if (waitingforopponent) {
        // Request to check if the opponent has finished placing ships
        sendRequest("../api/opponent_finished.php?id=" + gameId + "&playerId=" + playerId).then((data) => {
            if (data.enemyFinished) {
                // Opponent has finished placing ships, update UI and switch to gameplay phase
                waitingforopponent = false;
                elem("text-main").innerHTML = ""
                elem("text-secondary").innerHTML = ""

                state = 1;
                // Request to get and display the enemy's board
                sendRequest("../api/get_enemy_board.php?id=" + gameId + "&playerId=" + playerId).then((enemyBoardData) => {
                    if (enemyBoardData.success) {
                        // Create the enemy's board table
                        createTable(enemyBoardData.board, true);
                        // Setup event listeners for the enemy's board
                        enemyListener()
                        // Show both boards side by side
                        $("#placeBoard").show();
                        $("#enemyBoard").appendTo("#row");
                        $("#placeBoard").prependTo("#row");
                        elem("placeBoard").className = "table table-bordered col-md-6"
                        elem("enemyBoard").className = "table table-bordered col-md-6"
                        elem("rfow").style.display = "";

                    }
                })


            } else {
                // Display a message indicating the number of ships the opponent still needs to place
                elem("text-secondary").innerHTML = `Opponent needs to place ${data.needToPlace} ships.`

            }
        })
    }
}, 1000)

// This JavaScript file defines a Ship class representing a battleship in the Battleship game.
class Ship {
    // Constructor to initialize a Ship object with its position, length, and orientation.
    constructor(pos, length, up) {
        this.length = length; // Length of the ship.
        this.up = up; // Orientation of the ship (true for vertical, false for horizontal).
        this.starting = up ? new Position(pos.x, pos.y + length - 1) : pos; // Starting position of the ship.
    }

    // Method to get an array of Position objects representing the points occupied by the ship.
    getPoints() {
        var points = []; // Array to store the ship's points.
        // Check ship orientation to determine how to generate points.
        if (this.up) {
            // For vertical orientation.
            if (((this.starting.y) - this.length + 1) < 0) {
                // Check if ship goes out of bounds.
                return null;
            }
            // Generate points for a vertically oriented ship.
            for (var i = 0; i < this.length; i++) {
                points[points.length] = new Position(this.starting.x, this.starting.y - i)
            }

        } else {
            // For horizontal orientation.
            if (this.starting.x < 0) {
                // Check if ship goes out of bounds.
                return null;
            }
            // Generate points for a horizontally oriented ship.
            for (i = 0; i < this.length; i++) {
                points[points.length] = new Position(this.starting.x + i, this.starting.y);
            }

        }
        return points;  // Return the array of ship's points.
    }
}
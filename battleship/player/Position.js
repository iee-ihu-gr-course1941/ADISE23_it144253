// This JavaScript file defines a Position class representing a 2D coordinate on the Battleship game board.
class Position {
    // Constructor to initialize a Position object.
    // It can be initialized with either Cartesian coordinates (x, y) or a string representation.
    constructor(x, y)
    // Check if y is provided to determine the type of initialization.
    {
        if (y || y == 0) {
            // If y is provided, assume Cartesian coordinates.
            this.x = x;
            this.y = y;
            // Map integer y to corresponding alphabetical representation.
            var alphas = Array.from(Array(26)).map((e, i) => i + 65).map((x) => String.fromCharCode(x));
            this.asString = alphas[y] + (x + 1);
        } else {
            // If y is not provided, assume string representation.
            this.asString = x;
            // Extract x and y coordinates from the string.
            if (x.charAt(2)) {
                this.x = parseInt(x.charAt(1) + x.charAt(2)) - 1;
            } else {
                this.x = parseInt(x.charAt(1)) - 1;
            }
            // Map lowercase letter to corresponding y coordinate.
            this.y = x.toLowerCase().charCodeAt(0) - 97;
        }
    }
}
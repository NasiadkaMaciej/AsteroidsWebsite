<?php

// File with no secrets filled

//Read and show data from server
$server = "";
$login = "";
$password = "";
$db = "";

define("SECRET", "");

$connection = new mysqli($server, $login, $password, $db);
if ($connection->connect_errno) {
    printf("Failed to connect to MySQL: ", $connection->connect_error);
    exit();
}
//Save data to server
if (isset($_POST["name"]) && isset($_POST["points"]) && isset($_POST["secret"])) {
    if (isset($_POST["secret"]) == SECRET) {
        $name = strtolower($_POST["name"]);
        $points = $_POST["points"];
        $query = "SELECT points from leaderboard WHERE name='$name'";
        $result = $connection->query($query);

        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO leaderboard (name, points) VALUES ('" . $name . "', '" . $points . "')";
        } else {
            $row = mysqli_fetch_array($reqsult);
            $lastScore = $row[0];
            if ($lastScore < $points) {
				$query = "UPDATE leaderboard SET points=" . $points . ", date=CURRENT_TIMESTAMP() WHERE name='$name'";
            }
        }

        $result = $connection->query($query);
    }
}
?>
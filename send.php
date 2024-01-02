<?php
include("config.php");
$connection = new mysqli($SERVER, $LOGIN, $PASSWORD, $DB);
if ($connection->connect_errno) {
    printf("Failed to connect to MySQL: ", $connection->connect_error);
    exit();
}

if (isset($_POST["name"]) && isset($_POST["points"]) && isset($_POST["secret"])) {
    $name = strtolower($_POST["name"]);
    $points = $_POST["points"];
    $secret = $_POST["secret"];

    $query = "SELECT points from leaderboard WHERE name=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $query = "INSERT INTO leaderboard (name, points) VALUES (?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("si", $name, $points);
    } else {
        $stmt->bind_result($lastScore);
        $stmt->fetch();

        if ($lastScore < $points) {
            $query = "UPDATE leaderboard SET points=?, date=CURRENT_TIMESTAMP() WHERE name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("is", $points, $name);
        }
    }

    if ($secret == $SECRET) {
        $stmt->execute();
    }

    $stmt->close();
}

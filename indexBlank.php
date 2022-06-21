<!DOCTYPE html>
<html lang="en">

<!-- 
	File with no secrets filled
-->

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="style.css">
	<title>Asteroids</title>
</head>

<body>
	<h1 style="text-align: center;">Asteroids by Macieson</h1>
	<div id="container">
		<p id="gameDescription">
			Destroy as many asteroids as you can with your spaceship's missiles.
			Use arrows to move, spacebar to shoot, N for new game, ESC for entering and exiting menu and Enter for
			choosing menu entry.
			<a href="Asteroids.zip">Download game</a>
		</p>

		<table id="leaderBoard">
			<thead>
				<tr>
					<th class='posTD'>Pos</th>
					<th class='nameTD'>Name</th>
					<th class='ptsTD'>Points</th>
				</tr>
			</thead>
			<tbody>

				<?php
				//Read and show data from server
				$server = "";
				$login = "";
				$password = "";
				$db = "";

				$connection = new mysqli($server, $login, $password, $db);

				if ($mysqli->connect_errno) {
					printf("Failed to connect to MySQL: ", $mysqli->connect_error);
					exit();
				}

				if (isset($_GET["pos"]))
					$pos = $_GET["pos"];
				else
					$pos = 0;

				$query = "SELECT name, points FROM leaderboard ORDER BY `leaderboard`.`points` DESC, `leaderboard`.`name` DESC LIMIT " . $pos . ",10";
				$result = $connection->query($query);
				$i = $pos + 1;
				while ($line = $result->fetch_assoc()) {
					echo "<tr>
					<td class='posTD'>" . $i . "</td>
					<td class='nameTD'>" . $line["name"] . "</td>
					<td class='ptsTD'>" . $line["points"] . "</td>
					</tr>";
					$i = $i + 1;
				}

				//Save data to server
				if (isset($_POST["name"]) && isset($_POST["points"]) && isset($_POST["secret"])) {
					if ($secret = "") {
						$name = strtolower($_POST["name"]);
						$points = $_POST["points"];

						$query = "SELECT points from leaderboard WHERE name='$name'";
						$result = $connection->query($query);

						if (mysqli_num_rows($result) == 0) {
							$query = "INSERT INTO leaderboard VALUES ('" . $name . "', '" . $points . "')";
						} else {
							$row = mysqli_fetch_array($reqsult);
							$lastScore = $row[0];
							if ($lastScore < $points) {
								$query = "UPDATE leaderboard SET points=" . $points . " WHERE name='$name'";
							}
						}

						$result = $connection->query($query);
					}
				}
				?>
				<tr>
					<td>
						<?php
						//Handle going back in leaderboard
						$prevpos = $pos - 10;
						if ($prevpos >= 0) {
							echo '<a href="index.php?pos=' . $prevpos . '">Back</a>';
						}
						?>
					</td>
					<td></td>
					<td>
						<?php
						//Handle going to next page in leaderboard
						$query = "SELECT * FROM leaderboard";
						$result = $connection->query($query);
						$rowcount = mysqli_num_rows($result);
						$maxValue = ceil($rowcount / 10) * 10;
						$nextpos = $pos + 10;
						if ($nextpos < $maxValue) {
							echo '<a href="index.php?pos=' . $nextpos . '">Next</a>';
						}

						$connection->close();
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

</body>

</html>
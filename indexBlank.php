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
					<th class='dateTD'>Date</th>
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

				if ($connection->connect_errno) {
					printf("Failed to connect to MySQL: ", $connection->connect_error);
					exit();
				}

				if (isset($_GET["pos"]))
					$pos = $_GET["pos"];
				else
					$pos = 0;

				$query = "SELECT name, points, date FROM leaderboard ORDER BY `leaderboard`.`points` DESC, `leaderboard`.`name` DESC LIMIT " . $pos . ",10";
				$result = $connection->query($query);
				$i = $pos + 1;
				while ($line = $result->fetch_assoc()) {

					$line["date"] = substr($line["date"], 2);

					echo "<tr>
					<td class='posTD'>" . $i . "</td>
					<td class='nameTD'>" . $line["name"] . "</td>
					<td class='ptsTD'>" . $line["points"] . "</td>
					<td class='dateTD'>" . $line["date"] . "</td>
					</tr>";
					$i = $i + 1;
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
						//$maxValue =  round($rowcount, -1, PHP_ROUND_HALF_UP);
						$maxValue = ceil($rowcount / 10) * 10;
						//$maxValue =  ceil($rowcount)
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
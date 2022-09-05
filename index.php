<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="style.css">
	<title>Asteroids</title>
</head>

<body>
	<h1 style="text-align: center; font-size: 1.5em">Asteroids by Macieson</h1>
	<div id="container">
		<p id="gameDescription">
			Destroy as many asteroids as you can with your spaceship's missiles.<br>
			Be carefull! Asteroids aren't the only danger in space. Watch out for evil UFO trying to shoot you down.
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
		<br><br>
	</div>
	<div id="links">
		<a href="https://github.com/NasiadkaMaciej/Asteroids"><img src="GitHub-Mark-Light-64px.png" alt="My GitHub"></a>
		<a href="https://maciej.ml"><img src="Home-icon.png" alt="My home page"></a>
	</div><br>
	<p id="kb">Keybindings:</p>
	<div id="manual">
		<p>Keyboard<br>
			Arrows or WASD - Move<br>
			Spacebar - Shoot<br>
			N - New game<br>
			ESC - Entering and exiting menu<br>
			Enter - Choosing menu entry<br>
			F11 - Toggling fullscreen<br>
			M - Toggle music at any time<br><br>
		</p>
		<p>
			Gamepad (PS3)<br>
			Left joystick - Rotate<br>
			R2 - Thrust<br>
			X - Shoot<br>
			Triangle - New game<br>
			START - Entering menu<br>
			Arrows - Navigate in menu O - Exiting menu<br>
			X - Choosing menu entry<br>
			L1 - Toggling fullscreen<br>
			R1 - Toggle music at any time</p>
	</div>
	<p style="text-align: center;"><video src="Asteroids.mp4" type="video/mp4" controls alt="Movie presenting game" width="90%"></p>
	<p id="completeGameDescription">
		Asteroids game clone. Written in C++ with SFML.<br>
		Destroy as many asteroids as you can with your spaceship's missiles, but be careful, they come from all sides, at different speeds and, when destroyed, they break into more, even faster and more dangerous fragments. Be cautious! Once in a while, a mysterious evil UFO shows up and starts shooting to you. Each level ends after destroying all visible asteroids. After that, you get into the next level, which has 2 more asteroids than the previous one. You start the game with 3 lives, after you lose one, you enter idle state, in which asteroids cannot hurt you, but you can't shoot them. To continue the game, simply move your ship. When you loose all your lives you can save your score and send it to Asteroids online leaderboard
	</p>
</body>

</html>
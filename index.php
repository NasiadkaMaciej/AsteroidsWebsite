<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<meta name="description" content="Asteroids game clone written in C++ with SFML - Maciej Nasiadka" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Asteroids - Maciej Nasiadka" />
	<meta property="og:description" content="Asteroids game clone written in C++ with SFML - Maciej Nasiadka" />
	<meta property="og:image" content="https://nasiadka.pl/asteroids/favicon.png" />
	<link rel="stylesheet" href="style.css">
	<link rel="canonical" href="https://nasiadka.pl/asteroids/">
	<title>Asteroids - Maciej Nasiadka</title>
</head>

<body>
	<h1 style="text-align: center; font-size: 1.5em">Asteroids by Macieson</h1>
	<div id="container">
		<p id="gameDescription">
			Destroy as many asteroids as you can with your spaceship's missiles.<br>
			Be careful! Asteroids aren't the only danger in space. Watch out for evil UFO trying to shoot you down.<br>
			<a href="https://github.com/NasiadkaMaciej/Asteroids/releases/download/v1.1.1/AsteroidsWindows.zip">Download game (Windows)</a><br>
			<a href="https://github.com/NasiadkaMaciej/Asteroids/releases/download/v1.1.1/AsteroidsLinux.zip">Download game (Linux)</a>
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
				// Read and show data from server
				include("config.php");
				$connection = new mysqli($SERVER, $LOGIN, $PASSWORD, $DB);
				if ($connection->connect_errno) {
					printf("Failed to connect to MySQL: ", $connection->connect_error);
					exit();
				}

				$pos = isset($_GET["pos"]) ? intval($_GET["pos"]) : 0;

				$stmt = $connection->prepare("SELECT name, points, date FROM leaderboard ORDER BY points DESC, name DESC LIMIT ?, 10");
				$stmt->bind_param("i", $pos);
				$stmt->execute();
				$result = $stmt->get_result();

				$i = $pos + 1;
				while ($line = $result->fetch_assoc()) {
					$line["date"] = substr($line["date"], 2);

					echo "<tr>
						<td class='posTD'>" . htmlspecialchars($i) . "</td>
						<td class='nameTD'>" . htmlspecialchars($line["name"]) . "</td>
						<td class='ptsTD'>" . htmlspecialchars($line["points"]) . "</td>
						<td class='dateTD'>" . htmlspecialchars($line["date"]) . "</td>
					</tr>";
					$i++;
				}
				?>

				<tr>
					<td>
						<?php
						// Handle going back in leaderboard
						$prevpos = $pos - 10;
						if ($prevpos > 0)
							echo '<a href="/asteroids/?pos=' . $prevpos . '">Back</a>';
						else if($prevpos == 0)
							echo '<a href="/asteroids/">Back</a>';
						?>
					</td>
					<td></td>
					<td>
						<?php
						// Handle going to the next page in leaderboard
						$query = "SELECT COUNT(*) FROM leaderboard";
						$result = $connection->query($query);
						$rowcount = $result->fetch_row()[0];
						$maxValue = ceil($rowcount / 10) * 10;
						$nextpos = $pos + 10;

						if ($nextpos < $maxValue)
							echo '<a href="/asteroids/?pos=' . $nextpos . '">Next</a>';
						$connection->close();
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<br><br>
	</div>
	<br>
	<div id="links">
		<a href="https://github.com/NasiadkaMaciej/Asteroids"><img src="GitHub-Mark-Light-64px.png" alt="My GitHub"></a>
		<a href="https://nasiadka.pl"><img src="Home-icon.png" alt="My home page"></a>
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
			Arrows - Navigate in menu<br>
			O - Exiting menu<br>
			X - Choosing menu entry<br>
			L1 - Toggling fullscreen<br>
			R1 - Toggle music at any time
		</p>
	</div>
	<p style="text-align: center;">
		<iframe style="width: 84vw; height: 36vw" src="https://www.youtube.com/embed/zexjrqSEklM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</p>
	<p id="completeGameDescription">
		Asteroids game clone. Written in C++ with SFML.<br>
		Destroy as many asteroids as you can with your spaceship's missiles, but be careful, they come from all sides, at different speeds and, when destroyed, they break into more, even faster and more dangerous fragments. Be cautious! Once in a while, a mysterious evil UFO shows up and starts shooting at you. Each level ends after destroying all visible asteroids. After that, you get into the next level, which has 2 more asteroids than the previous one. You start the game with 3 lives; after you lose one, you enter idle state, in which asteroids cannot hurt you, but you can't shoot them. To continue the game, simply move your ship. When you lose all your lives, you can save your score and send it to the Asteroids online leaderboard.
	</p>
	<footer>
		<p><a href="https://nasiadka.pl/">Maciej Nasiadka © 2023</a></p>
	</footer>
</body>

</html>

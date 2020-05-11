<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	if (isset($_POST['form-action']) && $_POST['form-action'] == 'logout'){
		session_unset();
		session_destroy();
	}
?>
<html>
	<head>
		<title>Home Test</title>
		<link id="cssLoad" type="text/css" rel="stylesheet" href="..\css\default.css" />
	</head>
	<body>
		<h1 class="title">Homepage</h1>
		<a class="nav" href="#">Home Page</a>
		<a class="nav" href="login.php">Login page</a>
		<a class="nav" href="register.php">Register page</a>
		<?php
			if (isset($_SESSION["logged"]) && $_SESSION["logged"] == 'True') {
				$loggedUser = $_SESSION['loggedUser'];
				$accLvl = $_SESSION['accLvl'];
				$accCreate = $_SESSION['accCreate'];
				if(isset($_GET['logged']) && $_GET['logged'] == 'True'){
					echo "<p class='msg err'>You cannot be logged in to view that page.</p>";
				}
				echo "<p class='msg info'>You are currently logged in as: $loggedUser.</p>";
				echo "<p class='msg info'>This is a level $accLvl account.</p>";
				echo "<p class='msg info'>This account was created on: $accCreate.</p>";
				echo "<form class='logout' action='";echo htmlspecialchars($_SERVER['PHP_SELF']);echo"' method='post'><input type='hidden' name='form-action' value='logout'><input type='submit' name='submit' class='form' value='Logout'></form><br>";
			}
			elseif (!isset($_SESSION["logged"])){
				echo "<p class='msg info'>You are currently not logged in.</p>";
			}
		?>
		<a href="adminTest.php">Account Level Test</a>
	</body>
</html>

<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	if (isset($_POST['form-action']) && $_POST['form-action'] == 'logout'){
		session_unset();
		session_destroy();
	}
	elseif (!isset($_SESSION["logged"])){
				header("location: login.php?invalidPermission=True");
			}
?>
<html>
	<head>
		<title>Home Test</title>
		<link id="cssLoad" type="text/css" rel="stylesheet" href="..\css\default.css" />
	</head>
	<body>
		<h1 class="title">Homepage</h1>
		<a class="nav" href="index.php">Home Page</a>
		<a class="nav" href="login.php">Login page</a>
		<a class="nav" href="register.php">Register page</a>
		<?php
			if (isset($_SESSION["logged"]) && $_SESSION["logged"] == 'True') {

				if(isset($_SESSION['accLvl']) && $_SESSION['accLvl'] == 3){
					echo"<p>This text is only visible if you are logged in with<br> an administrator account (Level 3).</p>";
				}
				else{
					$accLvl = $_SESSION['accLvl'];
					echo "<p class='msg info'>This is a level $accLvl account. <br>You must be account level 3 to view this page.</p>";
				}
			}
		?>
	</body>
</html>

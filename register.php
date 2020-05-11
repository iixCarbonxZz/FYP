<?php
	/*Starts the session to load previously stored variables*/
	session_start();


	if(isset($_SESSION['logged']) && $_SESSION['logged'] == 'True'){
		header('location:index.php?logged=True');
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		require "src\connect.php";
	}
?>
<html>
<head>
  <link id="cssLoad" type="text/css" rel="stylesheet" href="css\default.css" />
</head>
<body class="color-dark-bg">
	<?php
		include 'navbar.php';
	?>
  <div class="buffer">

  </div>
	<form class="formWrapper" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" style="margin-top:50px;">
		<input type="hidden" name="form-action" value="register">
		<div class="formHeader color-light-bg">
			<p>Register</p>
		</div>
		<input class="formInput" type="text" name="user-name" placeholder="Username" autofocus></input>
		<input class="formInput" type="email" name="user-mail" placeholder="Email Address"></input>
		<input class="formInput" type="email" name="user-mail-confirm" placeholder="Confirm Email Address"></input>
		<input class="formInput" type="password" name="user-pass" placeholder="Password"></input>
		<input class="formInput" type="password" name="user-pass-confirm" placeholder="Confirm Password"></input>
		<a href="#">
		<input class="formButton color-light-bg" type="submit" value="Register"></input>
		</a>

	</form>
	<?php
		if (isset($_GET["existingUser"]) && $_GET["existingUser"] == 'True') {
			echo "<p class='msg err'>An account with this username already exists. Please choose another.</p>";
		}
		elseif (isset($_GET["badPassword"]) && $_GET["badPassword"] == 'True') {
			echo "<p class='msg err'>Passwords do not match, Please try again.</p>";
		}
		elseif (isset($_GET["badEmail"]) && $_GET["badEmail"] == 'True') {
			echo "<p class='msg err'>Email Addresses do not match, Please try again.</p>";
		}
		elseif (isset($_GET["badCredentials"]) && $_GET["badCredentials"] == 'True') {
			echo "<p class='msg err'>Usernames can only consist of alphanumeric characters (A-Z, 0-9) and underscores (_), Please try again.</p>";
		}
		elseif (isset($_GET["blankCredentials"]) && $_GET["blankCredentials"] == 'True') {
			echo "<p class='msg err'>Username / Password cannot be blank, Please try again.</p>";
		}
		elseif (isset($_GET["registerError"]) && $_GET["registerError"] == 'True') {
			echo "<p class='msg err'>There has been an error registering the account, Please try again.</p>";
		}
	?>
</body>
</html>

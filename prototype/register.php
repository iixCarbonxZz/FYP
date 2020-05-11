<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	if(isset($_SESSION['logged']) && $_SESSION['logged'] == 'True'){
		header('location:index.php?logged=True');
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		require "src/connect.php";
	}
?>
<html>
	<head>
		<title>Register Test</title>
		<link id="cssLoad" type="text/css" rel="stylesheet" href="..\css\default.css" />
	</head>
	<body>
		<h1 class="title">Register page</h1>
		<a class="nav" href="index.php">Home Page</a>
		<a class="nav" href="login.php">Login page</a>
		<a class="nav" href="#">Register page</a>
		<br>
		<br>
		<br>
		<h3 class="subtitle">Register</h3>
		<form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<input type="hidden" name="form-action" value="register">
			<table class="table">
				<tr>
					<td class="formLabel">
						<p>Username: </p>
					</td>
					<td class="formInput">
						<input type="text" name="user-name" class="form" placeholder="username" maxlength="15" autofocus><br>
					</td>
				</tr>
				<tr>
					<td class="formLabel">
						<p>Password: </p>
					</td>
					<td class="formInput">
						<input type="password" name="user-pass" class="form" placeholder="password" maxlength="25"><br>
					</td>
				</tr>
				<tr>
					<td class="formLabel">
						<p>Confirm Password: </p>
					</td>
					<td class="formInput">
						<input type="password" name="user-pass-confirm" class="form" placeholder="confirm password" maxlength="25"><br>
					</td>
				</tr>
				<tr colspan="2">
					<td class="formSubmit" colspan="2">
						<input type="submit" name="submit" class="form" value="Login"><br>
					</td>
				</tr>
				</table>
					<?php
						if (isset($_GET["existingUser"]) && $_GET["existingUser"] == 'True') {
							echo "<p class='msg err'>An account with this username already exists. Please choose another.</p>";
						}
						elseif (isset($_GET["badPassword"]) && $_GET["badPassword"] == 'True') {
							echo "<p class='msg err'>Passwords do not match, Please try again.</p>";
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
		</form>
	</body>
</html>

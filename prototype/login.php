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
		<title>Login Test</title>
		<link id="cssLoad" type="text/css" rel="stylesheet" href="..\css\default.css" />
	</head>
	<body>
		<h1 class="title">Login page</h1>
		<a class="nav" href="index.php">Home Page</a>
		<a class="nav" href="#">Login page</a>
		<a class="nav" href="register.php">Register page</a>
		<br>
		<br>
		<br>
		<h3 class="subtitle">Login</h3>
		<form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<input type="hidden" name="form-action" value="login">
			<table class="table">
				<tr>
					<td class="formLabel">
						<p>Username: </p>
					</td>
					<td class="formInput">
						<input type="text" name="user-name" class="form" placeholder="username" maxlength="25" autofocus><br>
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
				<tr colspan="2">
					<td class="formSubmit" colspan="2">
						<input type="submit" name="submit" class="form" value="Login"><br>
					</td>
				</tr>
				</table>
					<?php
						if (isset($_GET["registered"]) && $_GET["registered"] == 'True') {
							echo "<p class='msg pass'>Your account has been created, please try and log in.</p>";
						}
						elseif (isset($_GET["loginFailed"]) && $_GET["loginFailed"] == 'True') {
							echo "<p class='msg err'>Invalid Username / Password. Please try again.</p>";
						}
						elseif (isset($_GET["invalidPermission"]) && $_GET["invalidPermission"] == 'True') {
							echo "<p class='msg err'>You must be logged in to view that page.</p>";
						}
					?>
		</form>
	</body>
</html>

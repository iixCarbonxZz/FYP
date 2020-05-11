<?php

//Server info
$serverUser = "root";
$serverPass = "";

//DB Info
$dbName = "fyp_db_proto";

//connect to the server
$connect = mysqli_connect("localhost", $serverUser, $serverPass) or ("Connection Failed!");

//Select the database from the MySQL server
@mysqli_select_db($connect, $dbName) or ("Database Not Found!");

if($_POST['form-action'] == "login"){
	//User login
	$inputUser = $_POST['user-name'];
	$inputPass = $_POST['user-pass'];

	//Properly escapes the user input to prevent SQL Injection
	$inputUser = mysqli_escape_string($connect, $inputUser);
	$inputPass = mysqli_escape_string($connect, $inputPass);

	//Select the rows from the database where they match the submitted username
	$queryUser = "SELECT * FROM user WHERE username = '$inputUser'";

	//queries a result against the database
	$resultQueryUser = mysqli_query($connect, $queryUser);

	//fetch a row from the database and store as an array
	$rowQueryUser = mysqli_fetch_array($resultQueryUser);

	//Salt the hashed password retrieved from the database
	$checkPass = password_verify($inputPass, $rowQueryUser['password']);

	//if input password is equal to server queried password and input username is equal to server queried username: start session to save global variables. Save username as loggedUser, 
	//short username as loggedUserShort, set loggedIn to True and redirect to homepage. Otherwise redirect to same page (reload login.php) with variable login set to failed
	//(displays error message for user upon retry)
	if($checkPass && $inputUser == $rowQueryUser['username'] && $inputUser != "" && $inputPass != ""){
			session_unset();
			$_SESSION["loggedUser"] = $rowQueryUser['username'];
			$_SESSION["logged"] = True;
			$_SESSION['accLvl'] = $rowQueryUser['acc_level'];
			$_SESSION['accCreate'] = $rowQueryUser['create_time'];
			
			mysqli_close($connect);
			header("location:index.php");
	}
	else{
		mysqli_close($connect);
		header("location:login.php?loginFailed=True");
	}
}
elseif($_POST['form-action'] == "register"){
	
	
	$newUser = $_POST["user-name"];
	$newPass = $_POST["user-pass"];
	$conPass = $_POST["user-pass-confirm"];
	$createDate = date("d/m/Y");
	
	if($newUser == "" || $newPass == ""){
		header("Location: register.php?blankCredentials=True");
		die();
	}
	
	elseif ($newPass !== $conPass){
		header("Location: register.php?badPassword=True");
		die();
	}
	
	$userCheck = (preg_match('/\W+/', $_POST["user-name"]));
		
	if ($userCheck == 1){
		header("Location: register.php?badCredentials=True");
		die();
	}

	//check database to see if account with registered username already exists
	$checkUser = ("SELECT * FROM user WHERE username = '$newUser'");
	
	$queryUser = mysqli_query($connect, $checkUser);

	$rowUser = mysqli_affected_rows($connect);
	
	if ($rowUser >=1){
		header("Location: register.php?existingUser=True");
		die();
	}
	
	$hashPass = password_hash($newPass, PASSWORD_DEFAULT);
	
	$insertAccount = "INSERT INTO user (user_id, username, password, create_time) VALUES (NULL, '$newUser', '$hashPass', '$createDate')";
	
	if (mysqli_query($connect, $insertAccount)) {
		header("Location: login.php?registered=True");
		mysqli_close($connect);
	}
	else{
		header("Location: register.php?registerError=True");
		mysqli_close($connect);
	}
}
?>
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

////////////////////////////////////////////////////////////////////////////////
//LOGIN/////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['form-action']) && $_POST['form-action'] == "login"){
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
			$_SESSION["userID"] 		= $rowQueryUser['user_id'];
			$_SESSION["loggedUser"] = $rowQueryUser['username'];
			$_SESSION['userEmail'] 	= $rowQueryUser['email'];
			$_SESSION['accLvl'] 		= $rowQueryUser['acc_level'];
			$_SESSION['accCreate'] 	= $rowQueryUser['create_time'];
			$_SESSION['basket'] 		= Array();
			$_SESSION['lastPage'] 	= 'index.php';
			$_SESSION["logged"] 		= True;

			$getBasket = 'SELECT itemID FROM basket WHERE userID =' . $rowQueryUser['user_id'];
			$queryBasket = mysqli_query($connect, $getBasket);

			while($row = mysqli_fetch_assoc($queryBasket)){
				array_push($_SESSION['basket'], $row['itemID']);
			}

			$emptySavedBasket = 'DELETE FROM basket WHERE userID = ' . $rowQueryUser['user_id'];
			$queryEmpty = mysqli_query($connect, $emptySavedBasket);

			//If user account is a knitter account get the ir knitterID also.
			if($rowQueryUser['acc_level'] == 2){
				$queryKnitter = 'SELECT knitterID from knitter WHERE ' . $_SESSION['userID'] . ' = userID';
				$queryResult = mysqli_query($connect, $queryKnitter);
				$rowResult = mysqli_fetch_array($queryResult);

				$_SESSION['knitterID'] = $rowResult['knitterID'];
			}

			mysqli_close($connect);
			header("location:index.php");
	}
	else{
		mysqli_close($connect);
		header("location:login.php?loginFailed=True");
	}
}

////////////////////////////////////////////////////////////////////////////////
//REGISTER//////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
elseif(isset($_POST['form-action']) && $_POST['form-action'] == "register"){


	$newUser = htmlspecialchars($_POST["user-name"]);
	$newMail = htmlspecialchars($_POST['user-mail']);
	$conMail = htmlspecialchars($_POST['user-mail-confirm']);
	$newPass = htmlspecialchars($_POST["user-pass"]);
	$conPass = htmlspecialchars($_POST["user-pass-confirm"]);
	$createDate = date("d/m/Y");

	if($newUser == "" || $newPass == "" || $newMail == ""){
		header("Location: register.php?blankCredentials=True");
		die();
	}

	elseif ($newPass !== $conPass){
		header("Location: register.php?badPassword=True");
		die();
	}
	elseif ($newMail !== $conMail){
		header("Location: register.php?badEmail=True");
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

	$insertAccount = "INSERT INTO user (user_id, username, email, password, create_time) VALUES (NULL, '$newUser', '$newMail', '$hashPass', '$createDate')";

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

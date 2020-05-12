<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';

	if(!isset($_SESSION["logged"])){
		$noAccess = true;
	}
	elseif($_SESSION['accLvl'] !== '3'){
		$noAccess = true;
	}

	if(isset($noAccess) && $noAccess == true){
		header("Location: notLogged.php");
	}

	if(isset($_GET['action']) && $_GET['action'] !== 'listUsers' && isset($_POST['lvlEdit']) && $_POST['lvlEdit'] == 'true'){

		$getAccLvl = mysqli_fetch_array(mysqli_query($connect, 'SELECT acc_level FROM user WHERE user_id = ' . $_GET['userID']));
		//Check if they are going to/from being a knitter.
		if($getAccLvl['acc_level'] !== 2 && $_POST['accLvlSelect'] == 2){
			//They are becoming a knitter.
			$knitterPromote = true;
		}
		elseif($getAccLvl['acc_level'] == 2 && $_POST['accLvlSelect'] !== 2){
			//They are being demoted from knitter
			$knitterDemote = true;
		}

		$updateLvl = 'UPDATE user SET acc_level = \'' . $_POST['accLvlSelect'] . '\'WHERE user_id = \'' . $_GET['userID'] . '\'';
		$updateQuery = mysqli_query($connect, $updateLvl);
		$updatedLvl = true;
		$_POST['lvlEdit'] = 'false';

		//If an account is changed to a knitter account, check they are in the knitter table and add if neccessary.
		if(isset($knitterPromote)){
			$checkKnitters = mysqli_query($connect, 'SELECT * FROM knitter WHERE userID = ' . $_GET['userID']);
			$checkKnitterTable = mysqli_affected_rows($connect);
			if($checkKnitterTable == 0){
				//Knitter is not in table.
				$userID = $_GET['userID'];
				$nameStr = 'ADD NAME';
				$descStr = 'ADD DESC';
				$insertQuery = "INSERT INTO knitter (userID, knitterID, knitterName, knitterDesc) VALUES ('$userID', NULL, '$nameStr', '$descStr')";
				$insertKnitter = mysqli_query($connect, $insertQuery);
				}
			}
			elseif(isset($knitterDemote)){
				$checkKnitters = mysqli_query($connect, 'SELECT * FROM knitter WHERE userID = ' . $_GET['userID']);
				$checkKnitterTable = mysqli_affected_rows($connect);
				echo $checkKnitterTable;
				if($checkKnitterTable !== 0){
					//Knitter is in table.
					$removeKnitter = mysqli_query($connect, 'DELETE FROM knitter WHERE userID = ' . $_GET['userID']);
				}
			}
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
	<form class="adminForm formWrapper" enctype="multipart/form-data" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top:50px;">
		<div class="formHeader color-light-bg">
			<p>Admin Panel</p>
		</div>
	<?php
				echo'
						<input class="formButton color-light-bg" type="submit" value="List all Users" onclick="javascript: form.action=\'?action=listUsers\'"></input>
						<input class="formButton color-light-bg" type="submit" value="List all Knitters" onclick="javascript: form.action=\'?action=listUsers&knitter=true\'"></input>
						<div class="formDivider"></div>
						';
			if(!isset($_GET['action'])){
				echo'
					<div class="labelContainer color-light-bg">
					</div>
				';
			}
			if(isset($_GET['action'])){
				if($_GET['action'] == 'listUsers'){
					if(isset($_GET['knitter'])){
							$getUsers = 'SELECT user_id, username, create_time, acc_level FROM user WHERE acc_level = 2';
					}
					else{
						$getUsers = 'SELECT user_id, username, create_time, acc_level FROM user';
					}
					$queryUsers = mysqli_query($connect, $getUsers);
					echo'
						<div class="labelContainer color-light-bg">
							<p class="labelInfo left">User ID</p>
							<p class="labelInfo left">Username</p>
							<p class="labelInfo right">Create Time</p>
							<p class="labelInfo right">Account Level</p>
						</div>
					';

					while($resultUsers = mysqli_fetch_assoc($queryUsers)){
						echo'
							<a class="user-item" href="?action=selectUser&userID=' . $resultUsers['user_id'] . '">
								<div class="userContainer color-light-bg color-light-hv">
									<p class="userInfo left userID">' . $resultUsers['user_id'] . '</p>
									<p class="userInfo left username">' . $resultUsers['username'] . '</p>
									<p class="userInfo right create">' . $resultUsers['create_time'] . '</p>
									<p class="userInfo right level">' . $resultUsers['acc_level'] . '</p>
								</div>
							</a>
						';
					}

				}
				elseif($_GET['action'] == 'selectUser'){

					$getUser = 'SELECT user_id, username, create_time, acc_level FROM user WHERE user_id = ' . $_GET['userID'];
					$queryUser = mysqli_query($connect, $getUser);
					$resultUser = mysqli_fetch_array($queryUser);

					$filePath = 'img\profile\user\\' . $_GET['userID'] . '.png';
					echo'
						<div class="userInfoContainer color-light-bg">
							<div class="halfContainer left">
								<p class="userInfo left username">' . $resultUser['user_id'] . ' - </p>
								<p class="userInfo left username">' . $resultUser['username'] . '</p>
								<div class="userImageContainer">
								';
								if(file_exists($filePath)){
								echo'<img src="img\profile\user\\' . $_GET['userID'] . '.png" />';
								}
								else{
									echo'<img src="img\profile\default\1.png" />';
								}
								echo'
								</div>
								<div class="infoListContainer">
									<p class="labelInfo left">Create Date: ' . $resultUser['create_time'] . '</p>
								</div>
							</div>
							<div class="halfContainer right">
							<select class="infoDropdown right" name="accLvlSelect">
								<optgroup label="Regular User">
									<option value="1"';if($resultUser['acc_level']==1){echo'selected';}echo'>1</option>
								</optgroup>
								<optgroup label="Knitter">
									<option value="2"';if($resultUser['acc_level']==2){echo'selected';}echo'>2</option>
								</optgroup>
								<optgroup label="Administrator">
									<option value="3"';if($resultUser['acc_level']==3){echo'selected';}echo'>3</option>
								</optgroup>
							</select>
							<p class="labelInfo right labelDrop">Account Level:</p>
							<input type="hidden" name="lvlEdit" value="true"></input>
							<input type="submit" class="linkInfo right accLvlSub" value="Save"></input>
							';
							if(isset($updatedLvl) && $updatedLvl == true){
								echo'
							<p class="labelInfo right labelDrop fade">Level Updated</p>
							';
							}
							echo'
							</div>
						</div>
					';
				}
			}

			//
			//
			// FINISH ADMIN IMPLEMENTATION, INFORMATION OF PROFILES AND METHOD TO CHANGE ACC LEVEL!
			//
			//


			echo'
			</form>
			';
	?>
</body>
</html>

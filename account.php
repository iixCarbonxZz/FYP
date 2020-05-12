<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require "src\connect.php";

	$deleteText = '';

	if(isset($_GET['deleteAccount']) && $_GET['deleteAccount'] == 'true'){
		$deleteQuery = 'DELETE FROM user WHERE user_id = ' . $_SESSION['userID'];
		$deleteAccount = mysqli_query($connect, $deleteQuery);
		if($deleteAccount){
			$filePath = 'img\profile\user\\' . $_SESSION['userID'] . '.png';
			if(file_exists($filePath)){
				unlink($filePath);
			}
			unset($_SESSION['logged']);
			$deleteText = "?deleted=true";
		}
		else{
			$notDeleted = true;
		}
	}


	if(!isset($_SESSION["logged"])){
		header("Location: notLogged.php" . $deleteText);
	}

	include 'src\logout.php';
	include 'src\popups.php';

	if(!empty($_POST)){
		if(!empty($_POST['submit'])){
				$updateQuery = "UPDATE user SET ";


				//Sanitation and query building
				if(!empty($_POST['username'])){
					$sanitisedInput = htmlspecialchars($_POST['username']);
					//if username includes illegal characters.
					$validCheck = (preg_match('/\W+/', $_POST["username"]));
					//check database to see if account with registered username already exists
					$checkUser = ("SELECT * FROM user WHERE username = '$sanitisedInput'");
					$queryUser = mysqli_query($connect, $checkUser);
					$rowUser = mysqli_affected_rows($connect);

					if($validCheck == 1){
						$badUser = true;
						$err = true;
					}
					elseif($rowUser >= 1){
						$existUser = true;
						$err = true;
					}
					elseif(strlen($sanitisedInput > 15)){
						$longUser = true;
						$err = true;
					}
					else{
					$updateQuery .= " username = '$sanitisedInput' ,";
					}
				}
				if(!empty($_POST['emailAdd'])){
					$sanitisedInput = htmlspecialchars($_POST['emailAdd']);
					$updateQuery .= " email = '$sanitisedInput' ,";
				}
				if(!empty($_POST['curPass']) && strlen($_POST['curPass']) !== 0){

					$sanitisedInput = htmlspecialchars($_POST['curPass']);
					$getPass = mysqli_fetch_array(mysqli_query($connect, "SELECT password FROM user WHERE user_id =" . $_SESSION['userID']));
					//If password to account is not correct.
					if(!password_verify($sanitisedInput, $getPass['password'])){
						$badPass = true;
						$err = true;
					}
					else{
						$sanitisedInput = htmlSpecialChars($_POST['newPass']);
						$validCheck = (preg_match('/\W+/', $_POST["newPass"]));
						//If new password has illegal characters.
						if($validCheck == 1){
							$badNewPass = true;
							$err = true;
						}
						else{
							//If the confirmation is incorrect.
							if($sanitisedInput !== $_POST['conPass']){
								$badConPass = true;
								$err = true;
							}
							elseif(!empty($_POST['newPass'])){

								$sanitisedInput = htmlspecialchars($_POST['newPass']);
								$newPass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
								$updateQuery .= "password = '$newPass' ,";
							}
						}
					}
				}
				if($_FILES['profileImage']['size'] !== 0){
						$imageDir = "img/profile/user/";
						$temp = explode(".", $_FILES["profileImage"]["name"]);
						$newFileName = $_SESSION['userID'] . '.' . end($temp);
						$newFileLocation = $imageDir . $newFileName;
						if(move_uploaded_file($_FILES['profileImage']['tmp_name'], $newFileLocation)){
							$image = true;
						}
						else{
							$image = false;
							$err = true;
						}
				}
				if(!isset($err)){
					$updateQuery = trim($updateQuery, ',');
					$updateQuery .= "WHERE user_id = " . $_SESSION['userID'];
					$updateUser = mysqli_query($connect, $updateQuery);
					if($updateUser == TRUE){
						$rowQueryUser = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM user WHERE user_id = " . $_SESSION['userID']));

						$_SESSION['loggedUser'] = $rowQueryUser['username'];
						$_SESSION['userEmail'] = $rowQueryUser['email'];
					}
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
  <div class="content-wrapper color-mid-bg">
    <h1 class="content-header color-dark-bg">Account</h1>
    <table class="account-table">
			<tr>
				<td class="profile-nameplate">
					<?php
						$filePath = 'img\profile\user\\' . $_SESSION['userID'] . '.png';
						if(file_exists($filePath)){
							echo'
							<img alt="Profile Picture" class="profile-picture color-dark-border" src="' . $filePath . '" />
							';
						}
						else{
							echo'
							<img alt="Profile Picture" class="profile-picture color-dark-border" src="img\profile\default\1.png" />
							';
						}
					?>
					<div class="nameplate color-dark-bg"><?php echo $_SESSION['loggedUser'];?></div>
				</td>
				<td class="account-detail-td">
					<h1 class="table-header">Profile Details</h1>
					<?php
					if(!isset($_GET['edit'])){
						echo'
							<a href="account.php?edit=true"><h1 class="table-header-edit color-mid-fg">- Edit</h1></a>
						';
					}
					?>
					<div class="divider"></div>
					<?php

					if(!isset($_GET['edit'])){
						echo'
						<ul class="account-list">
							<li><b>Username: </b>' . $_SESSION['loggedUser'] . '</li>
							<li><b>Email Address: </b>' . $_SESSION['userEmail'] . '</li>
							<li><b>Account Level: </b>' . $_SESSION['accLvl'] . '</li>
							<li><b>Date of Account Creation: </b>' . $_SESSION['accCreate'] . '</li>
							<li class="linkInfo" onclick="openPopup(\'delete\', \'\');">Delete Account</li>
						</ul>
						';
						if(isset($notDeleted) && $notDeleted = true){
							echo'
								<p class="labelInfo right labelDrop fade">Account Not Deleted, Please try again.</p>
							';
						}
					}
					elseif(isset($_GET['edit']) && $_GET['edit'] == true){
						echo'
						<ul class="account-list">
							<form class="account-edit" enctype="multipart/form-data" method="post" target="_self">
							<li><b>Username: </b><input type="textbox" name="username" placeholder="' . $_SESSION['loggedUser'] . '"></input></li>
							<li><b>Email Address: </b><input type="email" name="emailAdd" placeholder="' . $_SESSION['userEmail'] . '"></input></li>
							<li><b>Password: </b><input type="password" name="curPass" placeholder="Current Password"></input></li>
							<li><b>&nbsp;</b><input type="password" name="newPass" placeholder="New Password"></input></li>
							<li><b>&nbsp;</b><input type="password" name="conPass" placeholder="Confirm New Password"></input></li>
							<li><b>Change Account image: </b><input class="formInput accImageSelect right" type="file" name="profileImage" accept="image/png"></input></li>
							<li><b>&nbsp;</b><input type="submit" name="submit"></input></li>
						';
					}
					if(!empty($_POST)){
						//If the error vars above are empty we are good.
						if(isset($badUser)){
							echo'<p class="form-errmsg msg-red">This username contains illegal characters, Please try another.</p>';
							echo'<p class="form-errmsg msg-blu">Usernames can only contain alphanumeric characters (A-Z, 0-9)</p>';
						}
						elseif(isset($longUser)){
							echo'<p class="form-errmsg msg-red">This username is too long, Please try another.</p>';
							echo'<p class="form-errmsg msg-blu">Usernames must be 1-15 characters long.</p>';
						}
						elseif(isset($existUser)){
							echo'<p class="form-errmsg msg-red">An account with this username already exists, Please try another.</p>';
						}
						elseif(isset($badPass)){
							echo'<p class="form-errmsg msg-red">Your password does not match our records, please try again.</p>';
							echo'<p class="form-errmsg msg-blu">Passwords can only contain alphanumeric characters (A-Z, 0-9)</p>';
						}
						elseif(isset($badNewPass)){
							echo'<p class="form-errmsg msg-red">Your chosen new password contains illegal characters, please try another.</p>';
						}
						elseif(isset($badConPass)){
							echo'<p class="form-errmsg msg-red">Your new passwords do not match, please try again.</p>';
						}
						elseif(isset($image) && $image != true){
							echo'<p class="form-errmsg msg-red">Your image has failed to upload, please try another.</p>';
							echo'<p class="form-errmsg msg-blu">Pimages should be ".png" format and recommended (200x200)px in size.</p>';
						}
						else{
							if($updateUser == TRUE){
								echo'
								<p class="form-errmsg msg-grn fade">Edits Succesfully saved.</p>
								';
							}
						}
					}
			echo'
				</form>
			</ul>
			';
					?>
				</td>
			</tr>
			<?php
				if(!isset($_GET['edit'])){
					echo'
					<tr>
						<td colspan="2">
							<h1 class="table-header">My Orders</h1>
							<div class="divider"></div>
						</td>
					</tr>
					';
				}
				else{
					echo'
					<tr>
						<td colspan="2">
							<div class="divider"></div>
						</td>
					</tr>
					';
				}
				?>
		</table>
		<?php
			$getOrders = 'SELECT orderID, statusID, orderTotal, orderPlace FROM ordertable WHERE userID = ' . $_SESSION['userID'];
			$getOrdersResult = mysqli_query($connect, $getOrders);

			if(!$getOrdersResult){
				echo '
				<div class="catalog-item color-dark-bg color-dark-hv">
				    <div class="catalog-item-content-wrapper">
				      <p id="item-title" class="catalog-content-err">Your orders could not be displayed, please try again later.</p>
				    </div>
				  </div>
				';
			}
			else{
				if(mysqli_num_rows($getOrdersResult) == 0){
					echo '
					<div class="catalog-item color-dark-bg color-dark-hv">
					    <div class="catalog-item-content-wrapper">
					      <p id="item-title" class="catalog-content-err">You do not have any orders currently, please try again later.</p>
					    </div>
					  </div>
					';
				}
				else{
					while($row = mysqli_fetch_assoc($getOrdersResult)){
						$getStatus = 'SELECT statusDesc FROM orderstatus WHERE statusID = ' . $row['statusID'];
						$queryStatus = mysqli_query($connect, $getStatus);
						$resultStatus = mysqli_fetch_assoc($queryStatus);
						echo'
					<a id="order-item" class="item-link" href="order.php?orderID=' . htmlspecialchars($row['orderID']) . '"><div class="order-item color-dark-bg color-dark-hv">
							<div class="catalog-item-content-wrapper">
								<p id="item-title" class="order-text catalog-content-title">Order ID - ' . htmlspecialchars($row['orderID']) . '</p>
								<p id="item-price" class="order-text catalog-content-title catalog-content-price">£' . htmlspecialchars($row['orderTotal']) . '</p>
								<p id="item-descr" class="order-text catalog-content-sub">' . htmlspecialchars($resultStatus['statusDesc']) . '</p>
							</div>
						</div></a>
						';
					}
				}
			}
?>
</div>
		<div id="deletePopup" class="removePopup color-light-bg">
				<div id="paymentTop" class="popupTop">
					<a class="closePopup" onclick="closePopup('delete', '');"> X </a>
				</div>
				<div class="popupMain">
					<!-- <div id="paypal-button-container" style="width: 70%;background-color:#F0F3FC;border-radius:5px;padding: 5%;"></div> -->
					<p class="paymentText popupText">Are you sure you want to delete your account? <br>This is permanent and cannot be undone.<br><br>Delete Your Account?</p>
					<a href="account.php?deleteAccount=true"><input type="submit" value="Yes" class="deleteButton formButton color-dark-bg" onclick="javascript: form.action=\'account.php?deleteAccount=true'"></input></a>
					<a href="#"><input type="button" value="No" class="deleteButton formButton color-dark-bg" onclick="closePopup('delete', '');"></input></a>
				</div>
			</div>
</body>
</html>

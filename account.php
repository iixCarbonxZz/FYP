<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require "src\connect.php";

	$deleteText = '';

	if(isset($_GET['deleteAccount']) && $_GET['deleteAccount'] == 'true'){
		$deleteQuery = 'DELETE FROM user WHERE user_id = ' . $_SESSION['userID'];
		$deleteAccount = mysqli_query($connect, $deleteQuery);
		if($deleteAccount){
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
							<form class="account-edit" method="post" target="_self">
							<li><b>Username: </b><input type="textbox" name="username" placeholder="' . $_SESSION['loggedUser'] . '"></input></li>
							<li><b>Email Address: </b><input type="textbox" name="emailAdd" placeholder="' . $_SESSION['userEmail'] . '"></input></li>
							<li><b>Password: </b><input type="password" name="curPass" placeholder="Current Password"></input></li>
							<li><b>&nbsp;</b><input type="password" name="newPass" placeholder="New Password"></input></li>
							<li><b>&nbsp;</b><input type="password" name="conPass" placeholder="Confirm New Password"></input></li>
							<li><b>&nbsp;</b><input type="submit" name="submit"></input></li>
						';
					}
					if(!empty($_POST)){
						if(isset($_POST['submit'])){
							if($_POST['username'] != "" && $_POST['emailAdd'] != "" && $_POST['curPass'] != "" && $_POST['newPass'] != "" && $_POST['conPass'] != ""){

								//Sanitation
								$username = htmlspecialchars($_POST['username']);
								$email = htmlspecialchars($_POST['emailAdd']);
								$curPass = htmlspecialchars($_POST['curPass']);
								$newPass = htmlspecialchars($_POST['newPass']);
								$conPass = htmlspecialchars($_POST['conPass']);
								//Check user and Pass for chars other than alphanumeric
								$userCheck = (preg_match('/\W+/', $_POST["username"]));
								$passCheck = (preg_match('/\W+\[@\/\\%&£#]+/', $_POST["username"]));

								//Get password hash from database for password checks.
								$getPass = 'SELECT password FROM user WHERE username = ' . $SESSION['loggedUser'];
								$passQuery = mysqli_query($connect, $getPass);
								$passFetch = mysqli_fetch_array($passQuery);
								$passHash = $passFetch['password'];

								//check database to see if account with registered username already exists
								$checkUser = ("SELECT * FROM user WHERE username = '$newUser'");
								$queryUser = mysqli_query($connect, $checkUser);
								$rowUser = mysqli_affected_rows($connect);

								//Check username
								if(strlen($username) < 1){
									echo'<p class="form-errmsg msg-red">Your username is too short.</p>';
								}
								elseif(strlen($username) > 15){
									echo'<p class="form-errmsg msg-red">Your username is too short.</p>';
								}
								elseif($rowUser >= 1){
									echo'<p class="form-errmsg msg-red">This username already exists, Please try another.</p>';
								}
								//Check Email
								elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
									echo'<p class="form-errmsg msg-red">Invalid Email, Please try again.</p>';
								}
								elseif($userCheck == 1 or $passCheck == 1){
									echo'<p class="form-errmsg msg-red">Your username/password contained an invalid character, Please try again.</p>';
								}
								//Check Password
								elseif(password_hash($curPass) !== $passHash){
									echo'<p class="form-errmsg msg-pink">Your current password is incorrect, please try again.</p>';
								}
							}
							else{
								echo'
								<p class="form-errmsg msg-red">One or more values were left blank. Please try again.</p>
								';
							}

							//
							//
							//IS INCORRECTLY LAID OUT, NEEDS TO ALLOW BLANK ENTRIES BUT IGNORE THEM AT THE END.
							//consider making an array of things to be changed and their values and insert directly
							//into a query at the end.
							//
							echo'
							<p class="form-errmsg msg-grn fade">Edits Succesfully saved.</p>
							';

						}
						echo'
							</form>
						</ul>
						';
					}
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

<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';

	if(!isset($_SESSION["logged"])){
		header("Location: notLogged.php");
	}

	if(isset($_POST['statusEdit']) && $_POST['statusEdit'] == 'true'){
		$updateLvl = 'UPDATE orderitems SET statusID = \'' . $_POST['statusSelect'] . '\'WHERE orderItemID = \'' . $_GET['orderItemID'] . '\'';
		$updateQuery = mysqli_query($connect, $updateLvl);
		$updatedStatus = true;
		$_POST['statusEdit'] = 'false';
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
			<p>Items on Order</p>
		</div>
	<?php
			if(isset($_GET['action'])){
				if($_GET['action'] == 'selectItem'){

					$query = 'SELECT * FROM orderitems WHERE orderItemID = ' . $_GET['orderItemID'];
					$getKnittersQuery = mysqli_query($connect, $query);
					$resultItems = mysqli_fetch_array($getKnittersQuery);
					//get status descriptions
					$getStatus = 'SELECT statusDesc FROM itemStatus WHERE statusID = ' . $resultItems['statusID'];
					$queryGetStatus = mysqli_query($connect, $getStatus);
					$statusResult = mysqli_fetch_array($queryGetStatus);
					//get item names
					$getItemInfo = 'SELECT itemName from catalog WHERE itemID =' . $resultItems['itemID'];
					$queryItemInfo = mysqli_query($connect, $getItemInfo);
					$itemInfoResult = mysqli_fetch_array($queryItemInfo);

					$filePath = 'img\catalog\item\\item' . $_GET['itemID'] . '.png';
					echo'
						<div class="userInfoContainer color-light-bg">
							<div class="halfContainer left">
								<p class="userInfo left username">' . $resultItems['itemID'] . ' - </p>
								<p class="userInfo left username">' . $itemInfoResult['itemName'] . '</p>
								<div class="userImageContainer">
								';
								if(file_exists($filePath)){
								echo'<img src="img\catalog\item\\item' . $_GET['itemID'] . '.png" />';
								}
								else{
									echo'<img src="img\profile\user\default.png" />';
								}
								echo'
								</div>
								<div class="infoListContainer">
									<p class="labelInfo left">Order Item Unique ID: ' . $resultItems['orderItemID'] . '</p>
								</div>
							</div>
							<div class="halfContainer right">
							<select class="infoDropdown right" name="statusSelect">
								<optgroup label="Item Not Complete">
									<option value="1"';if($resultItems['statusID']==1){echo'selected';}echo'>1</option>
								</optgroup>
								<optgroup label="Item Complete">
									<option value="2"';if($resultItems['statusID']==2){echo'selected';}echo'>2</option>
								</optgroup>
							</select>
							<p class="labelInfo right labelDrop">Item Status:</p>
							<input type="hidden" name="statusEdit" value="true"></input>
							<input type="submit" class="linkInfo right accLvlSub" value="Save"></input>
							';
							if(isset($updatedStatus) && $updatedStatus == true){
								echo'
							<p class="labelInfo right labelDrop fade">Status Updated</p>
							';
							}
							echo'
							</div>
						</div>
					';
				}
			}
			else{
				$queryItemIDs = mysqli_query($connect, 'SELECT itemID FROM catalog WHERE knitterID = ' . $_SESSION['knitterID']);
				$IDlist = array();
				while($getItemIDs = mysqli_fetch_array($queryItemIDs)){
				array_push($IDlist, $getItemIDs['itemID']);
			}
			$knitterID = $_SESSION['knitterID'];
			$query = 'SELECT * FROM orderitems WHERE itemID IN (SELECT itemID FROM catalog WHERE knitterID = ' .  $knitterID . ')';
			$getKnittersQuery = mysqli_query($connect, $query);
				echo'
					<div class="labelContainer color-light-bg">
						<p class="labelInfo left">item ID</p>
						<p class="labelInfo left">Item Name</p>
						<p class="labelInfo right">Item Status</p>
					</div>
				';

				while($resultItems = mysqli_fetch_array($getKnittersQuery)){
					//get status descriptions
					$getStatus = 'SELECT statusDesc FROM itemStatus WHERE statusID = ' . $resultItems['statusID'];
					$queryGetStatus = mysqli_query($connect, $getStatus);
					$statusResult = mysqli_fetch_array($queryGetStatus);
					//get item names
					$getItemInfo = 'SELECT itemName from catalog WHERE itemID =' . $resultItems['itemID'];
					$queryItemInfo = mysqli_query($connect, $getItemInfo);
					$itemInfoResult = mysqli_fetch_array($queryItemInfo);

					echo'
						<a class="user-item" href="?action=selectItem&orderItemID=' . $resultItems['orderItemID'] . '&itemID=' .$resultItems['itemID'] . '">
							<div class="userContainer color-light-bg color-light-hv">
								<p class="userInfo left userID">' . $resultItems['itemID'] . '</p>
								<p class="userInfo left username">' . $itemInfoResult['itemName'] . '</p>
								<p class="userInfo right create">' . $statusResult['statusDesc'] . '</p>
							</div>
						</a>
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

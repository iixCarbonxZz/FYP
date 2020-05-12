<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';

	if(!isset($_SESSION["logged"])){
		header("Location: notLogged.php");
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_POST['form-action'] !== 'delete'){
			if($_POST['form-action'] == 'edit'){
				$operation = 'edit';
				$updateItem = 'UPDATE catalog SET knitterID = "' . $_SESSION['knitterID'] . '", itemName = "' . $_POST['itemName'] . '", itemColor = "' . $_POST['itemColor'] . '", itemPrice = "' . $_POST['itemPrice'] . '", itemDesc = "' . $_POST['itemDesc'] . '" WHERE itemID = "' . $_POST['itemID'] . '"';
				$queryUpdate = mysqli_query($connect, $updateItem);
				if($queryUpdate == false){
					$success = 'false';
				}
				elseif($queryUpdate == true){
					$success = 'true';
				}
				$URLitemID = '&itemID=' . $_POST['itemID'];
				$itemID = $_POST['itemID'];
			}
			elseif($_POST['form-action'] == 'add'){
				$operation = 'add';
				$insertItem = 'INSERT INTO catalog (knitterID, itemName, itemColor, itemPrice, itemDesc) VALUES ("' . $_SESSION['knitterID'] . '", "' . $_POST['itemName'] . '", "' . $_POST['itemColor'] . '", "' . $_POST['itemPrice'] . '", "' . $_POST['itemDesc'] . '")';
				$queryInsert = mysqli_query($connect, $insertItem);
				if($queryInsert == false){
					$success = 'false';
				}
				elseif($queryInsert == true){
					$success = 'true';
					$itemID = mysqli_insert_id($connect);
				}

				$URLitemID = "";
				$getRows = 'SELECT * FROM catalog';
				$queryRows = mysqli_query($connect, $getRows);
			}
			$deleted = 'false';
				//Image Upload Handling.
			if(isset($_FILES['itemImage']) && $_FILES['itemImage']['size'] == 0 && $operation == 'edit'){
			}
			else{
				$imageDir = "img/catalog/item/";
				$temp = explode(".", $_FILES["itemImage"]["name"]);
				$newFileName = 'item' . $itemID . '.' . end($temp);
				$newFileLocation = $imageDir . $newFileName;
				if(move_uploaded_file($_FILES['itemImage']['tmp_name'], $newFileLocation)){
					$success = 'true';
				}
				else {
		   		$success = 'false';
				}
			}
				header('location:itemEdit.php?operation=' . $operation . $URLitemID . '&success=' . $success . '&deleted=' . $deleted);
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
	<form class="formWrapper" enctype="multipart/form-data" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top:50px;">
	<?php

			if(isset($_GET['operation']) && $_GET['operation'] !== 'delete'){
				if(isset($_GET['operation']) && $_GET['operation'] == 'add'){
					echo'
						<input type="hidden" name="form-action" value="add">
						<div class="formHeader color-light-bg">
							<p>Add Item</p>
						</div>
						<input class="formInput" type="text" name="itemName" placeholder="Item Name" autofocus></input>
						<input class="formInput" type="text" name="itemColor" placeholder="Item Color"></input>
						<input class="formInput" type="text" name="itemPrice" placeholder="Item Price"></input>
						<textarea class="formInput textarea" name="itemDesc" placeholder="Item Description" maxlength="250"></textarea>
					';
				}
				elseif(isset($_GET['operation']) && $_GET['operation'] == 'edit'){

					$getItemInfo = 'SELECT itemName, itemColor, itemDesc, itemPrice FROM catalog WHERE itemID = ' . $_GET['itemID'];
					$queryItemInfo = mysqli_query($connect, $getItemInfo);
					$itemInfo = mysqli_fetch_array($queryItemInfo);

					echo'
						<input type="hidden" name="form-action" value="edit">
						<input type="hidden" name="itemID" value="' . $_GET['itemID'] . '">
						<div class="formHeader color-light-bg">
							<p>Edit Item</p>
						</div>
						<input class="formInput" type="text" name="itemName" placeholder="Item Name" value="' . $itemInfo['itemName'] . '" autofocus></input>
						<input class="formInput" type="text" name="itemColor" placeholder="Item Color" value="' . $itemInfo['itemColor'] . '"></input>
						<input class="formInput" type="text" name="itemPrice" placeholder="Item Price" value="' . $itemInfo['itemPrice'] . '"></input>
						<textarea class="formInput textarea" name="itemDesc" placeholder="Item Description" maxlength="250">' . $itemInfo['itemDesc'] . '</textarea>
					';
				}
				echo'
					<label class="formLabel" for="itemImage">Select a .PNG Image to upload (Recommended 200x200)</label>
					<input class="formInput imageSelect" type="file" name="itemImage" accept="image/png"></input>
					<a href="#">
					<input class="formButton color-light-bg" type="submit" value="Submit"></input>
					</a>
				</form>
				';
			}
			if(isset($_GET['operation']) && $_GET['operation'] == 'delete'){
				$getItemInfo = 'SELECT itemName FROM catalog WHERE itemID = ' . $_GET['itemID'];
				$queryItemInfo = mysqli_query($connect, $getItemInfo);
				$itemInfo = mysqli_fetch_array($queryItemInfo);

				if(!isset($_GET['delete'])){
					echo'
						<input type="hidden" name="form-action" value="delete">
						<input type="hidden" name="itemID" value="' . $_GET['itemID'] . '">
						<div class="formHeader color-light-bg">
							<p>Delete Item</p>
						</div>
						<label class="confLabel confirm" for="itemImage">Are you sure you want to delete the item "' . $itemInfo['itemName'] . '"?</label>
						<a href="#">
						<input class="formButton queryButton color-light-bg" type="submit" value="Yes" onclick="javascript: form.action=\'itemEdit.php?operation=delete&delete=true&itemID=' . $_GET['itemID'] . '\'"></input>
						<input class="formButton queryButton color-light-bg" type="submit" value="No" onclick="javascript: form.action=\'itemEdit.php?operation=delete&delete=false&itemID=' . $_GET['itemID'] . '\'"></input>
						</a>
					</form>
					';
				}
				else{

					$deleteItem = 'DELETE FROM catalog WHERE itemID = ' . $_GET['itemID'];
				  if($_GET['delete'] == 'true'){
				    $queryDelete = mysqli_query($connect, $deleteItem);
				    if(!$queryDelete){
				      $delete = 'false';
							echo mysqli_error($connect);
				    }
				    else{
							$filePath = 'img\catalog\item\item' . $_GET['itemID'] . '.png';
							if(file_exists($filePath)){
								unlink($filePath);
							}
				      $delete = 'true';
				    }
					}
					else{
						$delete = 'false';
					}
					echo'
						<div class="formHeader color-light-bg">
							<p>Delete Item</p>
						</div>
						';
						if($delete == 'true'){
							echo'<label class="confLabel confirm" for="itemImage">The item has been deleted successfully. You will now be redirected.</label>';
						}
						else{
							echo'<label class="confLabel confirm" for="itemImage">The item has not been deleted. You will now be redirected.</label>';
						}
						echo'
						<a href="#">

						<input class="formButton color-light-bg" type="submit" value="Go Back" onclick="javascript: form.action=\'' . $_SESSION['lastPage'] . '\'"></input>
						</a>
					</form>
					';
				}
			}

		if(isset($_GET['operation']) && $_GET['operation'] !== 'delete'){
			if (isset($_GET["success"]) && $_GET["success"] == 'false') {
				echo "<p class='msg err'>The item failed to update, Please try again.</p>";
			}
			elseif (isset($_GET["success"]) && $_GET["success"] == 'true') {
				echo "<p class='msg pass'>The item has updated successfully.</p>";
			}
		}
	?>
</body>
</html>

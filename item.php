<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';
	//Login Check -- Disabled on forum pages to allow guests to view posts.
	//if(!isset($_SESSION["logged"])){
	//	header("Location: notLogged.php");
	//}
	if(isset($_GET['add'])){
		for($x = 1; $x <= $_POST['quantity']; $x++){
			array_push($_SESSION['basket'], $_GET['itemID']);
		}
		header('location: item.php?itemID='.$_GET['itemID'] . '&added=true');
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
		<?php

		$getItem = 'SELECT itemID, knitterID, itemName, itemColor, itemDesc, itemPrice FROM catalog WHERE itemID = ' . $_GET['itemID'];
		$getItemResult = mysqli_query($connect, $getItem);

		//
		//TRYING TO GET KNITTER INFORMATION FROM knitter TABLE USING knitterID FK IN catalog TABLE!
		//

		if(!$getItemResult){
			echo '<p style="margin-top: 75px;">The catalog item could not be displayed, please try again later.</p>';
		}
		else{
				while($row = mysqli_fetch_assoc($getItemResult)){

					$getKnitter = 'SELECT * FROM knitter WHERE knitterID = ' . $row['knitterID'];
					$getKnitterResult = mysqli_query($connect, $getKnitter);
					$row2 = mysqli_fetch_assoc($getKnitterResult);

			    echo'<h1 class="content-header color-dark-bg">' . $row['itemName'] . '</h1>
			    <table class="account-table">
						<tr>
							<td class="item-image-td">
							';
							$filePath = 'img\catalog\item\item' . $row['itemID'] . '.png';
							if(file_exists($filePath)){
								echo'
								<img class="profile-picture color-dark-border" src="img\catalog\item\item' . $row['itemID'] . '.png"></img>
								';
							}
							else{
								echo'
								<img class="profile-picture color-dark-border" src="img\catalog\item\default.png"></img>
								';
							}
							echo'
							</td>
							<td class="item-description-td item-info">
								<p class="item-description">' . $row['itemDesc'] . '</p>
							</td>
							<td class="item-description-td buy-td">
								<form name="itemForm" class="itemForm" method="POST" action="?itemID=' . $_GET['itemID'] . '&add=true">
								<input type="hidden" name="purchase" value="true"></input>
								<b><p class="item-price right">£' . $row['itemPrice'] . '</p></b>
								<p class="numLabel">Quantity: </p>
								<input class="quantitySelector right" name="quantity" type="number" min="0" max="20" value="1"></input>
								<input type="submit" class="item-button right color-dark-bg" value="Add to Basket"></input>
								';
								if(isset($_GET['added']) && $_GET['added'] == 'true'){
									echo'
										<p class="labelInfo right labelDrop fade">Added to basket</p>
									';
								}
								echo'
								</form>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<h1 class="table-header">Information</h1>
								<div class="divider"></div>
							</td>
						</tr>
						<tr>
						<td>
							<p class="item-description"><b>Knitted By: </b><a href="portfolio.php?userID=' . $row2['userID'] . '">' . $row2['knitterName'] . '</a></p>
						</td>
						</tr>
					</table>
					';
					}
				}
			?>
  </div>
</body>
</html>

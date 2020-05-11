<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';
	include 'src\popups.php';

	if(!isset($_SESSION["logged"])){
		header("Location: notLogged.php");
	}

	//function to search associative array by key and return value.
	//Used below to get quantity of items for basket.
	function searchByKey($keyVal, $array) {
	 foreach ($array as $key => $val) {
			 if ($keyVal == $key) {
				 return $val;
			 }
	 }
	 return null;
	}

	if(isset($_GET['remove'])){
		for($x = 1; $x <= $_POST['removeNumber' . $_GET['itemID'] . '']; $x++){
			$index = array_search($_GET['itemID'], $_SESSION['basket']);
			unset($_SESSION['basket'][$index]);
			//resets the array keys by reassigning the arrays values to itself.
			$_SESSION['basket'] = array_values($_SESSION['basket']);
		}
	}
?>
<html>
<head>
  <link id="cssLoad" type="text/css" rel="stylesheet" href="css\default.css" />
	<!-- <script>
		// var popup = document.getElementById("removePopup");
		function openPopup(type, itemID){
			var popup = document.getElementById(type + "Popup" + itemID);
			popup.style.display = "block";


		}
		function closePopup(type, itemID){
			var popup = document.getElementById(type + "Popup" + itemID);
			popup.style.display = "none";
		}
	</script> -->
</head>
<script
    src="https://www.paypal.com/sdk/js?client-id=AciSLvflxo6zkqKDwY9P46jzg1bcFnV8h3EC7s4fYYt2xbEjaRC9haFCMnjPfpDfAVzDpz4-SyYiIoFA&currency=GBP"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
  </script>
<body class="color-dark-bg">
	<?php
		include 'navbar.php';
	?>
  <div class="buffer">

  </div>
	<form class="adminForm formWrapper" enctype="multipart/form-data" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top:50px;">
		<div class="formHeader color-light-bg">
			<p>View Basket</p>
		</div>
	<?php
				echo'
						<input class="formButton color-light-bg" type="submit" value="Continue Shopping" onclick="javascript: form.action=\'catalog.php\'"></input>
						<input class="formButton color-light-bg" type="button" value="Checkout" onclick="openPopup(\'payment\', \'\');"></input>
						<div class="formDivider"></div>
						';

					$getBasket = 'SELECT itemID, itemName, itemColor, itemPrice FROM catalog WHERE itemID IN (' . implode(',', $_SESSION['basket']) . ')';
					$queryBasket = mysqli_query($connect, $getBasket);
					$countBasket = array_count_values($_SESSION['basket']);
					echo'
						<div class="labelContainer color-light-bg">
							<p class="labelInfo left">Quantity</p>
							<p class="labelInfo left">Item Name</p>
							<p class="labelInfo right">Item Price</p>
							<p class="labelInfo right">item Color</p>
						</div>
					';
					if(!empty($_SESSION['basket'])){
						$total = 0;
						while($resultBasket = mysqli_fetch_assoc($queryBasket)){
							$quantity = searchByKey($resultBasket['itemID'], $countBasket);
							echo'
								<a class="user-item" href="item.php?itemID=' . $resultBasket['itemID'] . '">
									<div class="userContainer color-light-bg color-light-hv">
										<p class="userInfo left itemID">' . $quantity . '</p>
										';
										if(mb_strlen($resultBasket['itemName']) > 25){
											echo'
											<p class="userInfo left username">' . substr($resultBasket['itemName'], 0, 25) . '... <a class="smallLink" onclick="openPopup(\'remove\', ' . $resultBasket['itemID'] . ');">remove</a></p>
											';
										}
										else{
											echo'
											<p class="userInfo left username">' . $resultBasket['itemName'] . ' <a class="smallLink" onclick="openPopup(\'remove\', ' . $resultBasket['itemID'] . ');">remove</a></p>
											';
										}
										echo'
										<p class="userInfo right create">£' . ($resultBasket['itemPrice'] * $quantity) . '</p>
										<p class="userInfo right level">' . $resultBasket['itemColor'] . '</p>
									</div>
								</a>
								<div id="removePopup' . $resultBasket['itemID'] . '" class="removePopup color-light-bg">
										<div class="popupTop">
											<a class="closePopup" onclick="closePopup(\'remove\', ' . $resultBasket['itemID'] . ')"> X </a>
										</div>
										<div class="popupMain">
											<div class="removeForm">
												<p class="popupText left">How many would you like to remove?</p>
												<input name="removeNumber' . $resultBasket['itemID'] .'" type="number" min="1" max="' . $quantity . '"></input>
												<input type="submit" class="formButton color-dark-bg" onclick="javascript: form.action=\'?itemID=' . $resultBasket['itemID'] . '&remove=true\'"></input>
											</div>
										</div>
								</div>
								';
								$total += ($resultBasket['itemPrice'] * $quantity);
								$_SESSION['orderTotal'] = $total;
						}
						echo'
						<div class="labelContainer color-light-bg">
							<p class="total labelInfo right">Total:&nbsp;&nbsp;£' . $total . '</p>
						</div>
					';
					}
					else{
						echo'
							<div class="userContainer color-light-bg color-light-hv">
									<p class="userInfo left itemID">Your basket is empty.</p>
							</div>
							';
					}
			echo'
			</form>
			';
	?>
</form>
	<div id="paymentPopup" class="removePopup color-light-bg">
			<div id="paymentTop" class="popupTop">
				<a class="closePopup" onclick="closePopup('payment', '');"> X </a>
			</div>
			<div class="popupMain">
				<!-- <div id="paypal-button-container" style="width: 70%;background-color:#F0F3FC;border-radius:5px;padding: 5%;"></div> -->
				<p class="paymentText popupText"><i>Note: implementation of proper paypal payment was not found to be useable so the step is skipped.</i></p>
				<a href="checkout.php"><input type="submit" value="Checkout" class="paymentButton formButton color-dark-bg" onclick="javascript: form.action=\'checkout.php?success=true'"></input></a>
				</div>
			</div>
			<!-- <script>
			var total = <?php //echo $total ?>;
			console.log(total);
			paypal.Buttons({
			createOrder: function(data, actions) {
				// This function sets up the details of the transaction, including the amount and line item details.
				return actions.order.create({
					purchase_units: [{
						amount: {
							value: ''
						}
					}]
				});
			},
			onApprove: function(data, actions) {
				// This function captures the funds from the transaction.
				return actions.order.capture().then(function(details) {
					// This function shows a transaction success message to your buyer.
					alert('Transaction completed by ' + details.payer.name.given_name);
				});
			}
		}).render('#paypal-button-container');
				// This function displays Smart Payment Buttons on your web page.
				paypal.buttons.render({style:{size:'small'}})
			</script> -->
</body>

</html>

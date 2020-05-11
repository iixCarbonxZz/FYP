<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';

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
	<script>
		var popup = document.getElementById("removePopup");
		function openPopup(type, itemID){
			var popup = document.getElementById(type + "Popup" + itemID);
			popup.style.display = "block";


		}
		function closePopup(type, itemID){
			var popup = document.getElementById(type + "Popup" + itemID);
			popup.style.display = "none";
		}
	</script>
</head>
<body class="color-dark-bg">
	<?php
		include 'navbar.php';
	?>
  <div class="buffer">

  </div>
	<form class="adminForm formWrapper" enctype="multipart/form-data" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top:50px;">
		<div class="formHeader color-light-bg">
			<p>Order Summary</p>
		</div>
	<?php
				echo'
						<input class="formButton color-light-bg" type="submit" value="Continue Shopping" onclick="javascript: form.action=\'catalog.php\'"></input>
						<input class="formButton color-light-bg" type="button" value="Checkout" onclick="openPopup(\'payment\', \'\');"></input>
						<div class="formDivider"></div>
						';

					$getOrderInfo = 'SELECT statusID, orderTotal, orderPlace FROM ordertable WHERE orderID =' . $_GET['orderID'];
					$getOrderItems = 'SELECT itemID, statusID FROM orderitems WHERE orderID =' . $_GET['orderID'];
					$queryOrderInfo = mysqli_query($connect, $getOrderInfo);
					$queryOrderItems = mysqli_query($connect, $getOrderItems);
					$resultInfo = mysqli_fetch_array($queryOrderInfo);
					echo'
						<div class="labelContainer color-light-bg">
							<p class="labelInfo left">Order ID - ' . $_GET['orderID'] . '</p>
							';
							$orderStatus = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT statusDesc FROM orderstatus WHERE statusID = ' . $resultInfo['statusID']));
							echo'
							<p class="labelInfo">Status: ' . $orderStatus['statusDesc'] . '</p>
							<p class="labelInfo right">Order Total: £' . $resultInfo['orderTotal'] . '</p>
						</div>
					';
						// $itemList = Array();
						// $getItemList = 'SELECT itemID FROM orderitems WHERE orderID = ' . $_GET['orderID'];
						// $queryItemList = mysqli_query($connect, $getItemList);
						// while($resultItemList = mysqli_fetch_array($queryItemList)){
						// 	array_push($itemList, $resultItemList['itemID']);
						// };
						// // print_r($itemList);
						while($resultItems = mysqli_fetch_assoc($queryOrderItems)){
							$resultItemInfo = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT itemName, itemDesc FROM catalog WHERE itemID = ' . $resultItems['itemID']));
							$getStatus = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT statusDesc FROM itemStatus WHERE statusID = ' . $resultItems['statusID']));
							echo'
								<a class="user-item" href="item.php?itemID=' . $resultItems['itemID'] . '">
									<div class="userContainer color-light-bg color-light-hv">
										<p class="userInfo left itemID">' . $resultItemInfo['itemName'] . '</p>
										<p class="userInfo right username">Status: ' . $getStatus['statusDesc'] . '</p>
									</div>
								</a>
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
				<p class="paymentText popupText">I'll pretend you paid for this if you do...</p>
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

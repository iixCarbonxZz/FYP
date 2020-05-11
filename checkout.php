<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';

	// //Function to get frequency of an item in an array and then return that frequency.
	// function getFreqInArray($array){
	// 	$quantity = 0;
	// 	$checkID = $array[0];
	// 	for($x = 0; $x <= sizeof($array); $x++){
	// 		if($array[$x] == $checkID){
	// 			$quantity += 1;
	// 			unset($array[$x]);
	// 		}
	// 	}
	// 	//resets the array keys by reassigning the arrays values to itself.
	// 	$array = array_values($array);
	// 	$_SESSION['basket'] = $array;
	// 	return $quantity;
	// };

	//Creating the order and getting the orderID back to store the order items.
	date_default_timezone_set("Factory");
	$date = date("d/m/Y H:i", strtotime("now"));
	if(!empty($_SESSION['basket'])){
		$createOrder = 'INSERT INTO ordertable (orderID, userID, statusID, orderTotal, orderPlace) VALUES (NULL, ' . $_SESSION['userID'] . ',\'2\', \'' . $_SESSION['orderTotal'] . '\', \'' .$date.'\')';
		if(isset($_SESSION['orderTotal'])){
			unset($_SESSION['orderTotal']);
		}
		$queryOrder = mysqli_query($connect, $createOrder);
		$getRows = 'SELECT * FROM ordertable';
		$queryRows  = mysqli_query($connect, $getRows);
		$resultRows = mysqli_affected_rows($connect);
		$resultRows = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT orderID FROM ordertable ORDER BY orderID DESC LIMIT 1'));
		for($x = 0; $x < sizeof($_SESSION['basket']); $x++){
			$storeBasket = 'INSERT INTO orderitems (itemID, orderID, statusID)
											VALUES (' . $_SESSION['basket'][$x]. ',\'' . $resultRows['orderID'] . '\', \'1\')';
			$queryStore = mysqli_query($connect, $storeBasket);
		}
		$_SESSION['basket'] = Array();
	}
	else{
		$queryStore = false;
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
		if($queryStore != false){
			echo'
	    <h1 class="content-header color-dark-bg">Order Placed Successfully</h1>
			<p class="logged-message">
				Your order has been placed successfully and can be viewed on your account page <a href="account.php" class="checkoutLink">Here</a>
			</p>
			';
		}
		else{
			echo'
	    <h1 class="content-header color-dark-bg">Order Not Placed</h1>
			<p class="logged-message">
				Your order has not been placed successfully. You will now be redirected to try again.
			</p>
			<script>window.setTimeout(function(){window.location.href=\'basket.php\';}, 5000);</script>
			';
		}
		?>
  </div>
</body>
</html>

<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';
	
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
  <div class="content-wrapper catalog-wrapper color-mid-bg">
    <h1 class="content-header color-dark-bg">Catalog</h1>
	<?php
		$getItems = 'SELECT itemID, knitterID, itemName, itemColor, itemDesc, itemPrice FROM catalog';
		$getItemsResult = mysqli_query($connect, $getItems);

		if(!$getItemsResult){
			echo '<p style="margin-top: 75px;">The catalog items could not be displayed, please try again later.</p>';
		}
		else{
			if(mysqli_num_rows($getItemsResult) == 0){
				echo '<p style="margin-top: 75px;">No catalog items defined yet, please try again later.</p>';
			}
			else{
				while($row = mysqli_fetch_assoc($getItemsResult)){
					echo'
				<a id="catalog-item" class="item-link" href="item.php?itemID=' . htmlspecialchars($row['itemID']) . '"><div class="catalog-item color-dark-bg color-dark-hv">
						<div class="catalog-item-picture-wrapper">
						';
						$filePath = 'img\catalog\item\item' . $row['itemID'] . '.png';
						if(file_exists($filePath)){
						  echo'
						  <img class="catalog-item-image" src="img\catalog\item\item' . htmlspecialchars($row['itemID']) . '.png" />
						  ';
						}
						else{
						  echo'
						  <img class="catalog-item-image" src="img\catalog\item\default.png" />
						  ';
						}
						echo'
						</div>
						<div class="catalog-item-content-wrapper">
							<p id="item-title" class="catalog-content-title">' . htmlspecialchars($row['itemName']) . '</p>
							<p id="item-price" class="catalog-content-title catalog-content-price">£' . htmlspecialchars($row['itemPrice']) . '</p>
							<p id="item-color" class="catalog-content-sub"><b>color: </b>' .  htmlspecialchars($row['itemColor']) . '</p>
							<p id="item-descr" class="catalog-content-sub">' . htmlspecialchars($row['itemDesc']) . '</p>
						</div>
					</div></a>
					';
				}
			}
		}

	?>
</div>
</body>
</html>

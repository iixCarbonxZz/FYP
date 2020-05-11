<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';
	include 'src\sessionUpdate.php';
	//Login Check -- Disabled on forum pages to allow guests to view posts.
	//if(!isset($_SESSION["logged"])){
	//	header("Location: notLogged.php");
	//}
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
    <h1 class="content-header color-dark-bg">Portfolio</h1>
    <table class="account-table">
			<tr>
				<td class="profile-nameplate">
					<?php

					$getKnitterInfo = 'SELECT knitterID, knitterName, knitterDesc FROM knitter WHERE userID = ' . $_GET['userID'];
					$getQuery = mysqli_query($connect, $getKnitterInfo);
					$knitterResult = mysqli_fetch_array($getQuery);

					$filePath = 'img\profile\user\\' . $_GET['userID'] . '.png';

					if(file_exists($filePath)){
						echo '<img class="profile-picture color-dark-border" src="' . $filePath . '"></img>';
					}
					else{
						echo '<img class="profile-picture color-dark-border" src="img\profile\default\1.png"></img>';
					}
					?>
					<div class="nameplate color-dark-bg"><?php echo $knitterResult['knitterName'];?></div>
				</td>
				<td class="account-detail-td">
					<h1 class="table-header">Profile Details</h1>
					<?php
					?>
					<div class="divider"></div>
					<?php

						echo'
						<ul class="account-list">
							<li><b>Name: </b>' . $knitterResult['knitterName'] . '</li>
							<li><b>About: </b>' . $knitterResult['knitterDesc'] . '</li>
						</ul>
						';
					?>
				</td>
			</tr>
			<?php
					echo'
					<tr>
						<td colspan="2">
							<h1 class="table-header">My Items</h1>
					';
					if(isset($_SESSION['userID']) && $_SESSION['userID'] == $_GET['userID']){
						echo'
							<a href="itemEdit.php?operation=add"><h1 class="table-header-edit color-mid-fg">+Add Item</h1></a>
						';
					}
					echo'
								<div class="divider"></div>
							</td>
						</tr>
					';
				?>
		</table>
		<?php
			$getItems = 'SELECT itemID, knitterID, itemName, itemColor, itemDesc, itemPrice FROM catalog WHERE knitterID = ' . $knitterResult['knitterID'];
			$getItemsResult = mysqli_query($connect, $getItems);

			if(!$getItemsResult){
				echo '
				<div class="catalog-item color-dark-bg color-dark-hv">
				    <div class="catalog-item-content-wrapper">
				      <p id="item-title" class="catalog-content-err">The catalog items could not be displayed, please try again later.</p>
				    </div>
				  </div>
				';
			}
			else{
				if(mysqli_num_rows($getItemsResult) == 0){
					echo '
					<div class="catalog-item color-dark-bg color-dark-hv">
					    <div class="catalog-item-content-wrapper">
					      <p id="item-title" class="catalog-content-err">No catalog items defined yet, please try again later.</p>
					    </div>
					  </div>
					';
				}
				else{
					while($row = mysqli_fetch_assoc($getItemsResult)){
						echo'
					<a id="catalog-item" class="item-link" href="item.php?itemID=' . htmlspecialchars($row['itemID']) . '"><div class="catalog-item color-dark-bg color-dark-hv">
							<div class="catalog-item-picture-wrapper">';
							$filePath = 'img\catalog\item\item' . $row['itemID'] . '.png';

							if(file_exists($filePath)){
								echo'<img class="catalog-item-image" src="img\catalog\item\item' . htmlspecialchars($row['itemID']) . '.png" />';
							}
							else{
								echo '<img class="catalog-item-image" src="img\catalog\item\default.png"></img>';
							}
							echo'
							</div>
							<div class="catalog-item-content-wrapper">
								<p id="item-title" class="catalog-content-title">' . htmlspecialchars($row['itemName']) . '</p>
								';
								if(isset($_SESSION['userID']) && $_SESSION['userID'] == $_GET['userID'] || $_SESSION['accLvl'] == 3){
									echo'
									<a href="itemEdit.php?operation=edit&itemID=' . htmlspecialchars($row['itemID']) . '"><p class="catalog-edit color-mid-fg right btn-rgt">edit</p></a>
									<a href="itemEdit.php?operation=delete&itemID=' . htmlspecialchars($row['itemID']) . '"><p class="catalog-edit color-mid-fg right btn-lft">delete</p></a>
									';
								}
								echo'
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

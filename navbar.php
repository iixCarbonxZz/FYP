<?php
	//Check if their user account level has changed since entering this page
	if(isset($_SESSION['logged'])){
		$getAccLvl = mysqli_fetch_array(mysqli_query($connect, "SELECT acc_level FROM user WHERE user_id =" . $_SESSION['userID']));
		$_SESSION['accLvl'] = $getAccLvl['acc_level'];
	}
?>
<div class="navigation-bar">
	<a class="navigation-logo" href="index.php"></a>
		<div class="navigation-link-list">
			<div class="navigation-link-item navigation-link-cap">&nbsp;</div>
			<a class="navigation-link-item navigation-link-left" href="index.php">Home</a>
			<a class="navigation-link-item navigation-link-middle" href="catalog.php">Catalog</a>
			<div class="navigation-bar-dropdown navigation-link-right">
				<?php
					if (isset($_SESSION['logged']) && $_SESSION['logged'] == "True"){
						echo'<a class="navigation-link-item navigation-drop-button" id="userButton">';
							echo $_SESSION['loggedUser'], "&#9660";
						echo'</a>';
					}
					else{
						echo'<a class="navigation-link-item navigation-drop-button" id="button">
							Menu &#9660
						</a>';
					}

				 ?>
				<div class="navigation-bar-dropdown-content">
					<?php
					if (isset($_SESSION['logged']) && $_SESSION['logged'] == "True"){
						if($_SESSION['accLvl'] == 2){
						echo "<a class='navigation-bar-dropdown-link' href='portfolio.php?userID=" . $_SESSION['userID'] . "'>Portfolio</a>";
						echo "<a class='navigation-bar-dropdown-link' href='orders.php'>Orders</a>";
						}
						elseif($_SESSION['accLvl'] == 3){
						echo "<a class='navigation-bar-dropdown-link' href='adminpanel.php'>Admin Panel</a>";
						}
						echo "<a class='navigation-bar-dropdown-link' href='basket.php'>Basket (" . sizeof($_SESSION['basket']) . ")</a>";
						echo "<a class='navigation-bar-dropdown-link' href='account.php'>Account</a>";
						echo "<form class='logout' action='";echo htmlspecialchars($_SERVER['PHP_SELF']);echo"' method='post'><input type='hidden' name='form-action' value='logout'><input type='submit' name='submit' class='navigation-bar-dropdown-link' value='Logout'></form>";

					}
					else{
						echo "<a class='navigation-bar-dropdown-link' href='login.php'>Login</a>";
						echo "<a class='navigation-bar-dropdown-link' href='register.php'>Register</a>";
					}

					?>
				</div>
			</div>
		</div>
</div>

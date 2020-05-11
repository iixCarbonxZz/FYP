<?php
	/*Starts the session to load previously stored variables*/
	session_start();


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
		if(isset($_GET['deleted']) && $_GET['deleted'] == 'true'){
			echo'
    		<h1 class="content-header color-dark-bg">Your account has been successfully deleted</h1>
			';
		}
		else{
			echo'
    		<h1 class="content-header color-dark-bg">You are not authorised to view this content</h1>
			';
		}
		if(!isset($_SESSION["logged"]) && !isset($_GET['deleted']) || isset($_GET['auth']) && $_GET['auth'] = false){
					echo '<p class="logged-message">
			      You are either not logged in or do not have the required privileges to view this resource. You will be redirected shortly to the homepage. If you are not redirected automatically, <a href="index.php">Click Here!</a>
			    </p>';

					echo "<script>window.setTimeout(function(){window.location.href=\"index.php\";}, 5000);</script>";
			}
		elseif(isset($_GET['deleted']) && $_GET['deleted'] = 'true'){
			echo'<p class="logged-message">
				You will be redirected shortly to the homepage. If you are not redirected automatically, <a href="index.php">Click Here!</a>
			</p>';
			echo "<script>window.setTimeout(function(){window.location.href=\"index.php\";}, 5000);</script>";
		}
			else{
				echo'<p class="logged-message">
		      You have been redirected here accidentally. You will be redirected shortly to the homepage. If you are not redirected automatically, <a href="index.php">Click Here!</a>
		    </p>';
				echo "<script>window.setTimeout(function(){window.location.href=\"index.php\";}, 5000);</script>";
			}
		?>
  </div>
</body>
</html>

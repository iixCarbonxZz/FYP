<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	if(isset($_SESSION['logged']) && $_SESSION['logged'] == 'True'){
		header('location:index.php?logged=True');
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		require "src\connect.php";
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
    <form class="formWrapper" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" style="margin-top:50px;">
      <input type="hidden" name="form-action" value="login">
      <div class="formHeader color-light-bg">
        <p>Login</p>
      </div>
      <input class="formInput" type="text" name="user-name" placeholder="Username" autofocus></input>
      <input class="formInput" type="password" name="user-pass" placeholder="Password"></input>
      <a href="#">
      <input class="formButton color-light-bg" type="submit" value="Login"></input>
    </a>
    </form>
    <?php
      if (isset($_GET["registered"]) && $_GET["registered"] == 'True') {
        echo "<p class='msg pass'>Your account has been created, please try and log in.</p>";
      }
      elseif (isset($_GET["loginFailed"]) && $_GET["loginFailed"] == 'True') {
        echo "<p class='msg err'>Invalid Username / Password. Please try again.</p>";
      }
      elseif (isset($_GET["invalidPermission"]) && $_GET["invalidPermission"] == 'True') {
        echo "<p class='msg err'>You must be logged in to view that page.</p>";
      }
    ?>
</body>
</html>

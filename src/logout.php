<?php
if (isset($_POST['form-action']) && $_POST['form-action'] == 'logout'){

  //Save basket if one exists.
  if(!empty($_SESSION['basket'])){
    for($x = 0; $x < sizeof($_SESSION['basket']); $x++){
      $storeBasket = 'INSERT INTO basket (userID, itemID)
                      VALUES (' . $_SESSION['userID'] . ',' . $_SESSION['basket'][$x]. ')';
      $queryStore = mysqli_query($connect, $storeBasket);
    }
  }


  //Remove all session vars and destroy the session.
  session_unset();
  session_destroy();
  header("location:index.php");
}
?>

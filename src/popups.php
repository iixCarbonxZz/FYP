<?php
echo'
<script>
function openPopup(type, itemID){
  var popup = document.getElementById(type + "Popup" + itemID);
  popup.style.display = "block";


}
function closePopup(type, itemID){
  var popup = document.getElementById(type + "Popup" + itemID);
  popup.style.display = "none";
}
</script>
';
?>

<?php
include ('header.php');
include ('connect.php');

if ($wb != 1){
  echo "No webhook data found. Configure webhook data first<br>";
}

include ('footer.php');
?>

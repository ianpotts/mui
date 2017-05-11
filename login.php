<?php
session_start();
$uid = $_POST['username'];
$key = $_POST['secret'];

$_SESSION['ver'] = "false";

echo "$uid <br>";
echo "$key <br>";

// Change this to something more secure at some point before publishing
if (($uid == "admin") && ($key == "admin")){
  $_SESSION['ver'] = "true";
  echo "Allowing access for $uid <br>";
  echo $_SESSION['ver'];
}

header('Location: index.php');

exit;


?>

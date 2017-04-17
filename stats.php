<?php
include ('header.php');

echo "<h1>Messaging Statistics</h1><br>";

if ($a != 2){
  echo "No data available";

}
echo "<br>";

$output = shell_exec('cat /var/log/ecelerity/mainlog.ec |grep "@D@" |grep wc -l');
if ($output < 1){$output = 0;}
echo "<b>Deliveries today: </b>$output <br>";

$output = shell_exec('bzcat /var/log/ecelerity/mainlog.ec.1.bz2 |grep "@D@" |grep wc -l');
if ($output < 1){$output = 0;}
echo "<b>Deliveries yesterday: </b>$output <br>";



include ('footer.php');
?>

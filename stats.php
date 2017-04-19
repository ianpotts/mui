<?php
include ('header.php');

echo "<h1>Messaging Statistics</h1>";

$output1 = shell_exec('cat /var/log/ecelerity/mainlog.ec |grep "@R@" |grep wc -l');
$output2 = shell_exec('cat /var/log/ecelerity/mainlog.ec |grep "@D@" |grep wc -l');
$output3 = shell_exec('cat /var/log/ecelerity/mainlog.ec |grep "@P@" |grep wc -l');
if ($output1 < 1){$output1 = 0;}
if ($output2 < 1){$output2 = 0;}
if ($output3 < 1){$output3 = 0;}
echo "<table border=1><tr><td>&nbsp;</td><td>Deliveries</td><td>Receptions</td><td>Bounces</td></tr>";
echo "<tr><td><b>Today: </b></td><td>$output1</td><td>$output2</td><td>$output3</td></tr>";

$output1 = shell_exec('bzcat /var/log/ecelerity/mainlog.ec.1.bz2 |grep "@R@" |grep wc -l');
$output2 = shell_exec('bzcat /var/log/ecelerity/mainlog.ec.1.bz2 |grep "@D@" |grep wc -l');
$output3 = shell_exec('bzcat /var/log/ecelerity/mainlog.ec.1.bz2 |grep "@P@" |grep wc -l');
if ($output1 < 1){$output1 = 0;}
if ($output2 < 1){$output2 = 0;}
if ($output3 < 1){$output3 = 0;}
echo "<tr><td><b>Yesterday: </b></td><td>$output1</td><td>$output2</td><td>$output3</td></tr>";
echo "</table>";


include ('footer.php');
?>

<?php
include ('header.php');


$output = shell_exec('echo "summary" |/opt/msys/ecelerity/bin/ec_console');
echo "<PRE>$output</PRE>";


$output = shell_exec('echo "active 0" |/opt/msys/ecelerity/bin/ec_console');
echo "<b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Active Queue size</b><br><PRE>$output</PRE>";

echo "<br>";

$output = shell_exec('echo "delayed 0" |/opt/msys/ecelerity/bin/ec_console');
echo "<b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Delayed Queue size</b><br><PRE>$output</PRE>";



$output = shell_exec('echo "adaptive suspensions" |/opt/msys/ecelerity/bin/ec_console');
echo "<b>Adaptive Suspensions</b><br><PRE>$output</PRE>";

$output = shell_exec('echo "adaptive list" |/opt/msys/ecelerity/bin/ec_console');
echo "<b>Adaptive List</b><br><PRE>$output</PRE>";

$output = shell_exec('cat /var/log/ecelerity/adaptive.summary');
echo "<b>Adaptive Summary</b><br><PRE>$output</PRE>";


include ('footer.php');
?>

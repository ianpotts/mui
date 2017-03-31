<?php
include ('header.php');

echo "<h1>SUMMARY PAGE</h1><br>";
$output = shell_exec'"summary" |/opt/msys/ecelerity/bin/ec_console';
echo "<PRE>$output</PRE>;

include ('footer.php');
?>

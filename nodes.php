<?php
include ('header.php');

echo "<h1>Running Nodes list</h1><br>";
$output = shell_exec'"show cluster membership" |/opt/msys/ecelerity/bin/ec_console';
echo "<PRE>$output</PRE>;

include ('footer.php');
?>

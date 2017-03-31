<?php
include ('header.php');

echo "<h1>Welcome to MUI, a custom, open-source WebUI for Momentum 4.2.28-MTA-Only</h1><br>";
$output = shell_exec'"version" |/opt/msys/ecelerity/bin/ec_console';
echo "This is running on :<br>";
echo "<PRE>$output</PRE>;

include ('footer.php');
?>

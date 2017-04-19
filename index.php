<?php
include ('header.php');

echo "<h1>Welcome to MUI</h1> 
      <h2>A custom, open-source WebUI for <br>
          Momentum 4.2.28-MTA-Only</h2><br>";
$output = shell_exec('echo "version" |/opt/msys/ecelerity/bin/ec_console');
$output1 = shell_exec('uname -a');

echo "This is running on :<br>";
echo "<PRE>
$output
$output1
</PRE>";

include ('footer.php');
?>

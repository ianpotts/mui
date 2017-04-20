<?php
include ('header.php');

if ($singlenode == "true"){
  $output = shell_exec('df -h');
  echo "Storage volumes: <PRE>$output</PRE>";

  $output = shell_exec('du -sh /var/log/ecelerity/*');
  echo "Files in /var/log/ecelerity<br>SIZE &nbsp; &nbsp; &nbsp; Filename <PRE>$output</PRE>";

}
else {
  echo "<h1>Running Nodes list</h1><br>";
  $output = shell_exec('echo "cluster nodename" |/opt/msys/ecelerity/bin/ec_console');
  echo "This node: <PRE>$output</PRE>";

  $output = shell_exec('echo "cluster membership" |/opt/msys/ecelerity/bin/ec_console');
  echo "All nodes: <PRE>$output</PRE>";
}

include ('footer.php');
?>

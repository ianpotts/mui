<?php
// Telemetry module
// to be run on every node in the Momentum cluster 
// collects data from the system and pushed to the WebUI

$dbhost = "mui.trymsys.net"; # db Hostname 
$dbport = "5432"; # db port
$dbname = "muidata"; # database name
$dbuser = "muiuser"; # database username
$dbpass = "#mu1u53r!"; # database password


// ############# DO NOT CHANGE ANYTHING BELOW THIS LINE ######## //
// Get raw node info
// Defaulting to PST
date_default_timezone_set('America/Los_Angeles');

$momover = shell_exec('echo "version" |/opt/msys/ecelerity/bin/ec_console');
$msummary = shell_exec('echo "summary" |/opt/msys/ecelerity/bin/ec_console');
$df = shell_exec('df -h');
$mhost = gethostname();
$lastupdate = date("Y-m-d H:m:s", time()); 

// Sanitize

preg_match('/vendor_perl/',$momover,$matches);
if ($matches) {
  $mstatus = "offline";
}
else{
  $mstatus = "running";
}
preg_match('/version: (.*) r/',$momover,$matches);
if ($matches){
  $momover = $matches[1];
}
else{
  $momover = "unavailable";
}


$df = preg_replace('/\n/','<br>',$df);

//echo $df;
//exit;

if ($dbhost == ""){
  echo "No DB COnnection information - cannot send telemetry to WebUI.";
  exit;
}

// push the data!
$dbconn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
    or die('<font color=red>Could not connect: </font>' . pg_last_error() );
$query = "SELECT * from nodestatus WHERE Nodename='" . $mhost . "'";
//$query = "SELECT * from nodestatus WHERE Nodename='node1'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_all($result);
pg_free_result($result);

foreach ($row as $r){
  $foundnode = $r['nodename'] ;
  echo "Found = " . $foundnode . "\n<br>";
}


if ($foundnode != $mhost){
$query = "insert into nodestatus (nodename, status, version, volume, lastupdate) VALUES ('$mhost','$mstatus','$momover','$df','$lastupdate')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

//  $result = pg_prepare($dbconn, "my_query", 'insert into nodestatus (nodename, status, version, volume, lastupdate) VALUES ($1,$2,$3,$4,$5)');
//  $result = pg_execute($dbconn, "my_query", array($mhost,$mstatus,$momover,$df,$lastupdate));

  echo "DB INSERT <br>\n";
}
else{
  $result = pg_prepare($dbconn, "my_query", 'update nodestatus set nodename = $1, status = $2, version=$3, volume=$4, lastupdate=$5');
  $result = pg_execute($dbconn, "my_query", array($mhost,$mstatus,$momover,$df,$lastupdate));
  echo "DB UPDATE <br>\n";
}

pg_close($dbconn);




?>

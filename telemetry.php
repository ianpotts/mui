<?php
// Telemetry module
// to be run on every node in the Momentum cluster 
// collects data from the system and pushed to the WebUI
// THis script should first be generated from the WebUI 
// installer in order to get the correct DB credentials.
// Then, it can be copied into each Momentum node.  
// Ideally, you will place it under /conf/default so it 
// can be replicated to each node in the cluster automatically.
// You should also add a cron job to run the script automatically.
// a sample is found in the repo as mui-cron

$dbhost = ""; # db Hostname 
$dbport = ""; # db port
$dbname = ""; # database name
$dbuser = ""; # database username
$dbpass = ""; # database password


// ############# DO NOT CHANGE ANYTHING BELOW THIS LINE ######## //
// Get raw node info
// Defaulting to PST
date_default_timezone_set('America/Los_Angeles');

// if we can't make a db connection, don't run the script
if ($dbhost == ""){
  echo "No DB Connection information - cannot send telemetry to WebUI.";
  exit;
}

$momover = shell_exec('echo "version" |/opt/msys/ecelerity/bin/ec_console');
$msummary = shell_exec('echo "summary" |/opt/msys/ecelerity/bin/ec_console');
$df = shell_exec('df -h');
$mhost = gethostname();
$lastupdate = date("Y-m-d H:i:s", time()); 

echo "MUI telemetry running at $lastupdate \n";

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

//echo $msummary;

preg_match('/Outbound Concurrency:\s* (.*)/',$msummary,$matches);
$O_Concurrency = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Inbound Concurrency:\s* (.*)/',$msummary,$matches);
$I_Concurrency = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Active Domains:\s* (.*)/',$msummary,$matches);
$Active_Domains = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Active Queue Size:\s* (.*)/',$msummary,$matches);
$AQS = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Delayed Queue Size:\s* (.*)/',$msummary,$matches);
$DQS = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Total Queue Size:\s* (.*)/',$msummary,$matches);
$TQS = $matches[1];
//echo $matches[1] ."\n";

preg_match('/DNS Resolver:\s* (.*)/',$msummary,$matches);
$DNSResolver = $matches[1];
//echo $matches[1] ."\n";

preg_match('/DNS A Queries:\s* (.*)/',$msummary,$matches);
$DNS_A_Queries = $matches[1];
//echo $matches[1] ."\n";

preg_match('/DNS AAAA Queries:\s* (.*)/',$msummary,$matches);
$DNS_AAAA_Queries = $matches[1];
//echo $matches[1] ."\n";

preg_match('/DNS MX Queries:\s* (.*)/',$msummary,$matches);
$DNS_MX_Queries = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Pending DNS Queries:\s* (.*)/',$msummary,$matches);
$Pending_DNS_Queries = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Query Rate:\s* (.*)qu/',$msummary,$matches);
$Query_Rate = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Successfully Delivered Messages:\s* (.*)/',$msummary,$matches);
$Delivered = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Failed Messages:\s* (.*)/',$msummary,$matches);
$Failed = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Transient Failures:\s* (.*)/',$msummary,$matches);
$Transient = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Rejected Messages:\s* (.*)/',$msummary,$matches);
$Rejected = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Received Messages:\s* (.*)/',$msummary,$matches);
$Received = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Delivery Rate:\s* (.*)me/',$msummary,$matches);
$Delivery_Rate = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Reception Rate:\s* (.*)me/',$msummary,$matches);
$Reception_Rate = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Rejection Rate:\s* (.*)me/',$msummary,$matches);
$Rejection_Rate = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Rejection Percentage:\s* (.*)%/',$msummary,$matches);
$Rejection_Percentage = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Statistics Last Reset:\s* (.*)se/',$msummary,$matches);
$Last_Reset = $matches[1];
//echo $matches[1] ."\n";

preg_match('/Uptime: (.*)se/',$msummary,$matches);
$Uptime = $matches[1];
//echo $matches[1] ."\n";

$df = preg_replace('/\n/','<br>',$df);


// push the data!
$dbconn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
    or die('<font color=red>Could not connect: </font>' . pg_last_error() );
$query = "SELECT * from nodestatus WHERE Nodename='" . $mhost . "'";
//$query = "SELECT * from nodestatus WHERE Nodename='node1'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_all($result);
pg_free_result($result);

//foreach ($row as $r){
  $foundnode = $row[0]['nodename'] ;
//  echo "Found = " . $foundnode . "\n<br>";
//}


if ($foundnode != $mhost){
$query = "insert into nodestatus (nodename, status, version, volume, lastupdate) VALUES ('$mhost','$mstatus','$momover','$df','$lastupdate')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$query = "insert into summary (
        O_Concurrency,
        I_Concurrency,
        Active_Domains,
        AQS,
        DQS,
        TQS,
        DNSResolver,
        DNS_A_Queries,
        DNS_AAAA_Queries,
        DNS_MX_Queries,
        Pending_DNS_Queries,
        Query_Rate,
        Delivered,
        Failed,
        Transient,
        Rejected,
        Received,
        Delivery_Rate,
        Reception_Rate,
        Rejection_Rate,
        Rejection_Percentage,
        Last_Reset,
        Uptime,
        Nodename)
   VALUES (       
        $O_Concurrency,
        $I_Concurrency,
        $Active_Domains,
        $AQS,
        $DQS,
        $TQS,
        '$DNSResolver',
        $DNS_A_Queries,
        $DNS_AAAA_Queries,
        $DNS_MX_Queries,
        $Pending_DNS_Queries,
        $Query_Rate,
        $Delivered,
        $Failed,
        $Transient,
        $Rejected,
        $Received,
        $Delivery_Rate,
        $Reception_Rate,
        $Rejection_Rate,
        $Rejection_Percentage,
        $Last_Reset,
        $Uptime,
        '$mhost')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

//  echo "DB INSERT <br>\n";
}
else{
  $query = "update nodestatus set nodename = '$mhost', status = '$mstatus', version = '$momover', volume = '$df', lastupdate = '$lastupdate' WHERE nodename = '$mhost'";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());

$query = "update summary set 
        O_Concurrency = $O_Concurrency,
        I_Concurrency = $I_Concurrency,
        Active_Domains = $Active_Domains,
        AQS = $AQS,
        DQS = $DQS,
        TQS = $TQS,
        DNSResolver = '$DNSResolver',
        DNS_A_Queries = $DNS_A_Queries,
        DNS_AAAA_Queries = $DNS_AAAA_Queries,
        DNS_MX_Queries = $DNS_MX_Queries,
        Pending_DNS_Queries = $Pending_DNS_Queries,
        Query_Rate = $Query_Rate,
        Delivered = $Delivered,
        Failed = $Failed,
        Transient = $Transient,
        Rejected = $Rejected,
        Received = $Received,
        Delivery_Rate = $Delivery_Rate,
        Reception_Rate = $Reception_Rate,
        Rejection_Rate = $Rejection_Rate,
        Rejection_Percentage = $Rejection_Percentage,
        Last_Reset = $Last_Reset,
        Uptime = $Uptime 
  WHERE nodename = '$mhost'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());


//  echo "DB UPDATE <br>\n";
}

pg_close($dbconn);

?>

<?php
include ('header.php');
include ('connect.php');
$n = $_GET['n'];
if ($n == "") {$n="total";}

if ($dbhost == ""){
  header('Location: init.php');
}

// Get the data!
$dbconn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
    or die('<font color=red>Could not connect: </font>' . pg_last_error() );

// If looking at TOTAL tab, get sum and average instead
if ($n == "total"){
  $query = "SELECT * from summary";
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());
  $row = pg_fetch_all($result);
  pg_free_result($result);

  $sumArray = array();
  foreach ($row as $k=>$subArray) {
    foreach ($subArray as $id=>$value) {
      $sumArray[$id]+=$value;
    }
  }
$row[0]['o_concurrency'] = $sumArray['o_concurrency'];
$row[0]['i_concurrency'] = $sumArray['i_concurrency'];
$row[0]['active_domains'] = $sumArray['active_domains'];
$row[0]['aqs'] = $sumArray['aqs'];
$row[0]['dqs'] = $sumArray['dqs'];
$row[0]['tqs'] = $sumArray['tqs'];
$row[0]['dns_a_queries'] = $sumArray['dns_a_queries'];
$row[0]['dns_aaaa_queries'] = $sumArray['dns_aaaa_queries'];
$row[0]['dns_mx_queries'] = $sumArray['dns_mx_queries'];
$row[0]['pending_dns_queries'] = $sumArray['pending_dns_queries'];
$row[0]['delivered'] = $sumArray['delivered'];
$row[0]['failed'] = $sumArray['failed'];
$row[0]['transient'] = $sumArray['transient'];
$row[0]['rejected'] = $sumArray['rejected'];
$row[0]['received'] = $sumArray['received'];
$row[0]['query_rate'] = sumArray['query_rate'] / count($sumArray);
$row[0]['query_rate'] = sprintf("%01.2f", $row[0]['query_rate']);
$row[0]['delivery_rate'] = $sumArray['delivery_rate'] / count($sumArray);
$row[0]['delivery_rate'] = sprintf("%01.2f", $row[0]['delivery_rate']);
$row[0]['reception_rate'] = $sumArray['reception_rate'] / count($sumArray);
$row[0]['reception_rate'] = sprintf("%01.2f", $row[0]['reception_rate']);
$row[0]['rejection_rate'] = $sumArray['rejection_rate'] / count($sumArray);
$row[0]['rejection_rate'] = sprintf("%01.2f", $row[0]['rejection_rate']);
$row[0]['rejection_percentage'] = $sumArray['rejection_percentage'] / count($sumArray);
$row[0]['rejection_percentage'] = sprintf("%01.2f", $row[0]['rejection_percentage']);

$row[0]['dnsresolver'] = "Per Node";
$row[0]['last_reset'] = $sumArray['last_reset'] / count($sumArray);;
$row[0]['last_reset'] = sprintf("%01.2f", $row[0]['last_reset']);
$row[0]['uptime'] = $sumArray['uptime'] / count($sumArray);;
$row[0]['uptime'] = sprintf("%01.2f", $row[0]['uptime']);
}
else{
$query = "SELECT * from summary WHERE Nodename='" . $n . "'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_all($result);
pg_free_result($result);
}

$query = "SELECT nodename from nodestatus";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$nnames = pg_fetch_all($result);
pg_free_result($result);

$query = "SELECT * from nodestatus WHERE nodename='" . $n ."'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$ns = pg_fetch_all($result);
pg_free_result($result);

pg_close($dbconn);

//Clean up the file space result
$ns[0]['volume'] = preg_replace('/\\n/','<br>',$ns[0]['volume']);


// node tabs
echo "
<table border=1>
  <tr>
  <td";

 if ($n == 'total'){
    echo " bgcolor=ltgreen ";
  }

echo "><a href='./summary.php?n=total'>TOTAL</a>&nbsp;</td>";
foreach ($nnames as $nn){
  echo "<td";
  if ($n == $nn['nodename']){
    echo " bgcolor=ltgreen ";
  }
  echo "><a href=\"./summary.php?n=".$nn['nodename']."\">". strtoupper($nn['nodename']) . "</a>&nbsp;</td>";
}
echo "
  </tr>
</table>
";


echo "
<table cellpadding=5>
<tr><td>

<table>
  <tr><th colspan=3>Collective Cluster Summary</th></tr>
  <tr><th>Datapoint</th><th>|</th><th>Value</th></tr>
<tr><td>Outbound Concurrency</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['o_concurrency'] . "</td></tr>
<tr><td>Inbound Concurrency</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['i_concurrency'] . "</td></tr>
<tr><td>Active Domains</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['active_domains'] . "</td></tr>
<tr><td>Active Queue Size</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['aqs'] . "</td></tr>
<tr><td>Delayed Queue Size</td><td>&nbsp;  &nbsp;</td><td> " . $row[0]['dqs'] . "</td></tr>
<tr><td>Total Queue Size</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['tqs'] . "</td></tr>
<tr><td>DNS Resolver</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['dnsresolver'] . "</td></tr>
<tr><td>DNS A Queries</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['dns_a_queries'] . "</td></tr>
<tr><td>DNS AAAA Queries</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['dns_aaaa_queries'] . "</td></tr>
<tr><td>DNS MX Queries</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['dns_mx_queries'] . "</td></tr>
<tr><td>Pending DNS Queries</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['pending_dns_queries'] . "</td></tr>
<tr><td>Query Rate</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['query_rate'] . " queries/second";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Delivered Messages</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['delivered'] . "</td></tr>
<tr><td>Failed Messages</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['failed'] . "</td></tr>
<tr><td>Transient Messages</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['transient'] . "</td></tr>
<tr><td>Rejected Messages</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['rejected'] . "</td></tr>
<tr><td>Received Messages</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['received'] . "</td></tr>
<tr><td>Delivery Rate</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['delivery_rate'] . " messages/second";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Reception Rate</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['reception_rate'] . " messages/second";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Rejection Rate</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['rejection_rate'] . " messages/second";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Rejection Percentage</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['rejection_percentage'] . " %";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Stats Last Reset</td><td> &nbsp;  &nbsp; </td><td> " . $row[0]['last_reset'] . " seconds";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
<tr><td>Node Uptime</td><td> &nbsp;  &nbsp; </td><td>" . $row[0]['uptime'] ." seconds";
if ($n == 'total'){echo " (average)";}
echo "</td></tr>
</table>
</td>
<td> &nbsp;  &nbsp; </td>
<td valign=top>
<table>
";

if ($n == 'total'){
  echo "<tr><th colspan=2>Nodes Reporting</th></tr>";
  foreach ($nnames as $nn){
    echo "<tr><td>". strtoupper($nn['nodename']) . "</a>&nbsp;</td></tr>";
  }
}
else{
echo "
<tr><th colspan=2>Node Status </th></tr>
<td>nodename</td><td> " . $ns[0]['nodename'] . "</td></tr>
<tr><td>status </td><td> " . $ns[0]['status'] . "</td></tr>
<tr><td>version </td><td> " . $ns[0]['version'] . "</td></tr>
<tr><td colspan=2><pre>" . $ns[0]['volume'] . "</pre></td></tr>
<tr><td>Last Update </td><td> " . $ns[0]['lastupdate'] . " PST</td></tr>
";
}

echo "
</table>

</td>
</tr>
</table>";




include ('footer.php');
?>

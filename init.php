<?php
include('header.php');
include('connect.ini');
// Collect data for install 

$dbhost1 = $_POST['dbhost'];
$dbport1 = $_POST['dbport'];
$dbname1 = $_POST['dbname'];
$dbuser1 = $_POST['dbuser'];
$dbpass1 = $_POST['dbpass'];


if ($dbhost1 == ""){$dbhost = $dbhost1;}
if ($dbport1 == ""){$dbport = $dbport1;}
if ($dbname1 == ""){$dbname = $dbname1;}
if ($dbuser1 == ""){$dbuser = $dbuser1;}
if ($dbpass1 == ""){$dbpass = $dbpass1;}


if($dbhost == ""){
  echo '
<p>It appears that you are running this for the first time.  Please answer the following questions to set up MUI</p>

<form name=f1 action="" method=POST>
<table>
<tr><td>Database server hostname</td><td><input type=text value="'.$dbhost.'" name=dbhost size=50></td></tr>
<tr><td>Port for DB access</td><td><input type=text value="'.$dbport.'" name=dbport></td></tr>
<tr><td>Database name</td><td><input type=text value="'.$dbname.'" name=dbname size=50></td></tr>
<tr><td>DB username</td><td><input type=text value="'.$dbuser.'" name=dbuser size=50></td></tr>
<tr><td>DB user password</td><td><input type=password value="'.$dbpass.'" name=dbpass size=50></td></tr>
<tr><td>&nbsp;</td><td><input type=submit name=submit value="Find DB"</td></tr>
</table>
</form>
';
}


//Validate field values
$validated = "true";

echo "<font color=red>";
// CHECK DB HOSTNAME
if (preg_match_all('/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/', $dbhost, $matches) == ""){
  echo "<br> DB Hostname contains invalid characters.  Valid entries are alphanumeric.<br>";
//  var_dump($matches[0]);
  $validated = "false";
}

// CHECK DB PORT
if (preg_match_all('/[^0-9]/', $dbport, $matches)){
  echo "<br> DB Port contains invalid characters.  Valid entries are 1-65535.<br>";
//  var_dump($matches[0]);
  $validated = "false";
}

// CHECK DB NAME
if (preg_match_all('/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/', $dbhost, $matches) == ""){
  echo "<br> DB Name contains invalid characters.  Valid entries are alphanumeric.<br>";
//  var_dump($matches[0]);
  $validated = "false";
}

// CHECK DB USER
if (preg_match_all('/[^0-9a-zA-Z]/', $dbuser, $matches)){
  echo "<br> DB Username contains invalid characters.  Valid entries are alphanumeric.<br>";
//  var_dump($matches[0]);
  $validated = "false";
}

echo "</font>";

if ($validated == "true"){
// Connect and check for a viable data source
$dbconn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
    or die('<font color=red>Could not connect: </font>' . pg_last_error() . '<br><input type=button name=reload value="Try Again" onClick="location.href = \'init.php\';">');

// Create database
$query = 'CREATE TABLE nodestatus (nodename varchar(50), status varchar(10), version varchar(20), volume text)';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$query = 'CREATE TABLE summary (
	O_Concurrency int,
	I_Concurrency int,
	Active_Domains int,
	AQS int,
	DQS int,
	TQS int,
	DNSResolver varchar(20),
	DNS_A_Queries int,
	DNS_AAAA_Queries int,
	DNS_MX_Queries int,
	Pending_DNS_Queries int,
	Query_Rate float
	Delivered int,
	Failed int,
	Transient int,
	Rejected int,
	Received int,
	Delivery_Rate float,
	Reception_Rate float,
	Rejection_Rate float,
	Rejection_Percentage float,
	Last_Reset int,
	Uptime int)';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$query = 'CREATE TABLE ad_supensions (gb varchar(100), domain varchar(100), ttl int, enable int)';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$query = 'CREATE TABLE ad_list (gb varchar(100), domain varchar(100), opt  varchar(100), value varchar(10), last_change varchar(50))';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$query = 'CREATE TABLE e_mess (
        rcpt_to varchar(100),
        subject text,
        customer_id int,
        transmission_id varchar(100),
        event_id varchar(100),
        timestamp varchar(100),
        friendly_from varchar(200),
        routing_domain varchar(100),
        template_version int,
        ip_pool varchar(100),
        type varchar(100),
        rcpt_meta text,
        template_id varchar(100),
        msg_from text,
        rcpt_tags text,
        message_id varchar(100),
        sending_ip varchar(100),
        msg_size int,
        raw_rcpt_to varchar(100)
)';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());




// Free resultset
pg_free_result($result);

// Closing connection
pg_close($dbconn);
}

if ($validated == "false"){
  $dbhost = "";
  $dbport = "";
  $dbname = "";
  $dbuser = "";
  $dbpass = "";

  echo "<input type=button name=reload value=\"Try Again\" onClick=\"location.href = 'init.php';\">";
} 
else{
$fcontent = '
<?php 

$dbhost = "'.$dbhost.'"; # db Hostname 
$dbport = "'.$dbport.'"; # db port
$dbname = "'.$dbname.'"; # database name
$dbuser = "'.$dbuser.'"; # database username
$dbpass = "'.$dbpass.'"; # database password

?>
';
  
file_put_contents('./connect.ini', $fcontent);
mkdir('./adreports');
header('Location: index.php');

}

?>

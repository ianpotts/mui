<?php
$filepattern = "ad*.txt";
$filecount=2;

$directory = '/var/www/momostatus/adreports/';
$files = glob($directory . $filepattern);

if ( $files !== false ){
    $filecount = count( $files );
}
else{
    $filecount = 0;
}

for ($i = 1; $i <= $filecount; $i ++){
  $adreport = file_get_contents($directory . 'ad'.$i.'.txt', true);
  preg_match_all('/^From: (.*)To:/sim', $adreport, $matches);
  $nodename[$i]=$matches[1][0];

  preg_match_all('/^Top 20 bi.*:(.*)\nTop/sim', $adreport, $matches);
  $bindings[$i]=$matches[1][0];

  preg_match_all('/^Top 20 do.*:(.*)\nOverall/sim', $adreport, $matches);
  $domains[$i]=$matches[1][0];

  preg_match_all('/^Overall stat.*:(.*)\nBounce/sim', $adreport, $matches);
  $overall[$i]=$matches[1][0];
}


include ('header.php');
echo "<pre>";
    
echo "<b>CONSOLIDATED ADAPTIVE DELIVERY REPORT </b><br>"; 
echo "Nodes reporting = " . $filecount ."<br>";
print "===================================================================================================<br><br>";
print "Top 20 bindings by number of receptions:<br>";
for ($i = 1; $i <= $filecount; $i ++){
  print "<b>Reporter = $nodename[$i]</b>";
  print_r($bindings[$i]);
  print "<br>";
}

print "===================================================================================================<br>";
print "Top 20 domains by number of receptions:<br>";
for ($i = 1; $i <= $filecount; $i ++){
  print "<b>Reporter = $nodename[$i]</b>";
  print_r($domains[$i]);
  print "<br>";
}

print "===================================================================================================<br>";
print "Overall statistics:<br>";
for ($i = 1; $i <= $filecount; $i ++){
  print "<b>Reporter = $nodename[$i]</b>";
  print_r($overall[$i]);
  print "<br>";
}
echo "</pre>";

include ('footer.php');
?>

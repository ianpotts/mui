<?php
session_start();
$verified = $_SESSION['ver'];

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
    <title>MUI</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />
    <meta http-equiv=\"cache-control\" content=\"no-cache\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style/styles.css\" />
    <link href=\"style/favicon.ico\" rel=\"icon\" type=\"image/x-icon\" />
    <link href=\"style/favicon.ico\" rel=\"shortcut icon\" />
</head>
<body>
  <p>
    <hr>
      <b><font size=16>MUI</font> - This is unsupported software - use at your own risk.</b>
    <hr>
  </p>
  <p>
   <hr>
   <a href=\"index.php\">home</a> |
   <a href=\"summary.php\">summary</a> |
   <a href=\"nodes.php\">nodes</a> |
   <a href=\"info.php\">info</a> |
   <a href=\"login.php\">logout</a>
   <hr>
  </p>

";


// If not logged in, request credentials

if ($verified != "true"){
    echo "
<form action=\"login.php\" method=\"POST\" id=\"form1\">
<table>
  <tr>
    <td colspan=2>
      <h2>You need to log in to use this site</h2>
    </td> 
  </tr>
  <tr>
    <td>Username: </td><td><input type=\"text\" name=\"username\"> </td>
  </tr>
  <tr>
    <td>Password: </td><td><input type=\"password\" name=\"secret\"></td>
  </tr>
  <tr>
    <td>&nbsp;</td><td align=center><button type=\"submit\" form=\"form1\" value=\"Submit\">Validate</button></td>
  </tr>   
</table>
      </form>
    ";
exit(0);
}

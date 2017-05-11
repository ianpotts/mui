<?php
include ('header.php');
?>

<h2>A custom, open-source WebUI for Momentum 4.2.28+</h2>
<p>
Version 2 of this Web-UI is designed to be installed in a detatched environment specifically for reporting on Momentum 4.2.28+.  Any other use may have unpredictable results.  Use at your own risk.  <b>**THIS IS UNSUPPORTED CODE**</b>.  Feel free to contribute or replicate, but dont complain if it does not work, as I may not have time to fix it.
</p><p>
== HOW IT WORKS ==<br>
There is a telemetry module that installs in every momentum node in a cluster.  The module can provide environmental and log data from delivery nodes as well as the log aggregator / cluster manager.  Telemetry data is shipped to the WebUI for reporting.  Optionally, Webhook data can be streamed to the WebUI as well for real-time event reporting.
</p><p>
== INSTALLATION ==<br>
First install the WebUI in a separate environment.  This can be an Azure or AWS instance, or another server in the Momentum cluster's network.  It can be Linux or Windows based, Apache or Nginx, but is expected to be a standard Web environment with the ability to support PHP.  If you want to use Git for deployment and updates, you should install the latest version.
</p><p>
After you build the server and install and test Apache or Nginx, change directories to /var/www/html/ and clone/fetch this Git repo to that location.
</p><p>
Once the initial install is done, the updater should just pull the current version of the UI from this repo.  The package includes the telemetry module that is intended to run on the Momentum nodes.  Copy that file (telemetry.sh) to /opt/msys/ecelerity/etc/conf/default then add a cron job to execue it every 5 minutes.  Alternately cp telem.ins to /tmp on each node then execute it and it will do the install for you.
</p>

<?php 
include ('footer.php');
?>

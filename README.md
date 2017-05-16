# mui
A Momentum 4.2x WebUI

Version 2 of this Web-UI is designed to be installed in a detatched environment specifically for reporting on Momentum 4.2.28+.  Any other use may have unpredictable results.  Use at your own risk.  **THIS IS UNSUPPORTED CODE**.  Feel free to contribute or replicate, but dont complain if it does not work, as I may not have time to fix it.

##HOW IT WORKS
There is a telemetry module that installs in every momentum node in a cluster.  The module can provide environmental and log data from delivery nodes as well as the log aggregator / cluster manager.  Telemetry data is shipped to the WebUI for reporting.  Optionally, Webhook data can be streamed to the WebUI as well for real-time event reporting.

##INSTALLATION
First install the WebUI in a separate environment.  This can be an Azure or AWS instance, or another server in the Momentum cluster's network.  It can be Linux or Windows based, Apache or Nginx, but is expected to be a standard Web environment with the ability to support PHP.  If you want to use Git for deployment and updates, you should install the latest version.

After you build the server and install and test Apache or Nginx, change directories to /var/www/html/ and clone/fetch this Git repo to that location. 

Once the initial install is done, the updater should just pull the current version of the UI from this repo.  The package includes the telemetry module that is intended to run on the Momentum nodes.  Copy that file (telemetry.sh) to /opt/msys/ecelerity/etc/conf/default then add a cron job to execue it every 5 minutes.  Alternately cp telem.ins to /tmp on each node then execute it and it will do the install for you.

##EXTRAS
Feel free to add your own skin with a custom CSS or your own favicon.  The deployment already accounts for that, you just need to crop in the files.  
To add your own CSS place it in the /style dir as "skin.css".
To add your own ICON place it in the /style dir  as "favicon.ico".


	<link rel=\"stylesheet\" type=\"text/css\" href=\"style/skin.css\" />
	<link href=\"style/favicon.ico\" rel=\"icon\" type=\"image/x-icon\" />
	<link href=\"style/favicon.ico\" rel=\"shortcut icon\" />

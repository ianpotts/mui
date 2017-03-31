# mui
A Momentum 4.2x WebUI

This is a Web-UI designed to be installed on the Momentum 4.2.28 MTA-ONLY release on CentOS 6.5.  Any other use may have unpredictable results.  Use at your own risk.  **THIS IS UNSUPPORTED CODE**.  Feel free to contribute or replicate, but dont complain if it does not work, as I may not have time to fix it.

There is a Bash installer you need to run first that adds packages, modifies configs and places the initial PHP placeholder.  You must be updating and **existing** Momentum 4.2.28 MTA-ONLY deployment.  If not, this may break your existing deployment - **YOU HAVE BEEN WARNED**.

Once the initial install is done, the updater should just pull the current version of the UI form this repo.

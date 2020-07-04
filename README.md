Rest-Inventory
==============
For 1.4 compatibility look at https://github.com/popstarfreas/Rest-Inventory/tree/1.4-variant, please note that branch requires the use of another plugin to work.

REQUIREMENT: Uses tShock's REST API (with tShock v4.3.x and above) to display a users inventory

Setup:
 * Download the files into their own directory on your web server
 * Visit said directory via your browser to run the index.php script
  * First, it will unzip the items.zip in the items directory which contains all the item images
  * Next, it will present you with a form for the REST Credentials for the initial settings setup
 * Then you're done!
 * Accessing the index.php post-setup will list users online. You can then select a user and it will display the users inventory.

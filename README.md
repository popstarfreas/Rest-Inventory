Rest-Inventory
==============

REQUIREMENT: Uses tShock's REST API (with commit #835) to display a users inventory

Simply download the files into their own directory on your web server, and then visit said directory via your browser to run the index.php script. Firstly, it will unzip the items.zip in the items directory which contains all the item images. Next, it will present you with a form for the REST Credentials for the initial settings setup. After which the program is setup.

Accessing the index.php post-setup will list users online. You can then select a user and it will display the users inventory.

The DB_Module does not work at this time. It's intent is to display a background image that is most relevant to the persons location.

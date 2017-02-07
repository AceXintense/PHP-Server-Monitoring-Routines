# PHP-Server-Monitoring-Routines
Routines needed on the servers that will communicate with the PHP Server Monitoring Software.

#PHPSM Routines Purpose?
PHPSM Routines collate all the data on the server and sends an API request to the main PHPSM application where PHPSM gets that data and deals with processing it.

#PHPSM Routines Overhead
PHPSM Routines have the overhead of PHP which on the cli can be rather slow. The idea is to have another repository which will include Python code that has the same effect as the PHP Routines.

#How PHPSM Routines work?
PHPSM Routines work with a main PHPSM.PHP file that calls all the coresponding Routines.

	initalizeServer.php
	uploadCPU.php
	uploadMemory.php
	uploadDisk.php

These Routines all get called on startup but then get called every 5 minutes so that the servers will not get bogged down.

#PHPSM Routine Improvements
As PHPSM improves and allows for more data to be sent across PHPSM Routines will follow with the updates to the framework.

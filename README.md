# football

#Install

 create a database and changed the configration on src/Subash/Classes/Database.php
 
 import football.sql
 
 if you didn't have composer ,install composer
 
 https://getcomposer.org/doc/00-intro.md
 
 after that do composer update
 
#Setup poller

open two command line and run the following script

php job_fixture_import.php

php job_player_import.php

you can choose the csv heards from uploads forder
 
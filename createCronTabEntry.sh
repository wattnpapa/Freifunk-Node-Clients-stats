#/usr/bin/bash

crontab -l | { cat; echo "* * * * * /usr/bin/php5 /var/www/html/Freifunk-Node-Clients-stats/fillData.php"; } | crontab -
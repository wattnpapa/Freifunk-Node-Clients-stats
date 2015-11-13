#/usr/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

crontab -l | { cat; echo "* * * * * /usr/bin/php5 $DIR/fillData.php"; } | crontab -
#!/usr/bin/env bash

if ! [ -L /var/www/public ]; then
    rm -rf /var/www/public
    sudo sed -i s,/var/www/public,/var/www,g /etc/apache2/sites-available/000-default.conf
    sudo sed -i s,/var/www/public,/var/www,g /etc/apache2/sites-available/scotchbox.local.conf
    sudo service apache2 restart
fi

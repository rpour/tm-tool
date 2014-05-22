#!/bin/bash
sudo apt-get install curl sqlite \
    php5-xdebug php5 php5-cli php5-sqlite php5-curl php5-intl \
    php-pear

sudo pear install PHP_CodeSniffer

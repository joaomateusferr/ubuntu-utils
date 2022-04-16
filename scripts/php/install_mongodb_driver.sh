#!/bin/bash

#https://github.com/arnaud-lb/php-rdkafka

PHP_DIR='/etc/php/'

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt-get update

    apt install php-dev 
    apt install php-pear

    apt-get install -y libmongoc-dev
    pecl install mongodb

    if [ ! -e $PHP_DIR ];then
        echo 'PHP not installed'
    else
        NEWER_VERSION_INSTALLED=$(ls -1r $PHP_DIR | head -1)

        if [ -z "$NEWER_VERSION_INSTALLED" ];then
            echo 'No version found!'
        else
            echo "Changing the settings for the version $NEWER_VERSION_INSTALLED"

            PHP_INI_PATH="$PHP_DIR$NEWER_VERSION_INSTALLED/apache2/php.ini"
            echo "" >> $PHP_INI_PATH
            echo "extension=mongodb.so" >> $PHP_INI_PATH

            PHP_CLI_INI_PATH="$PHP_DIR$NEWER_VERSION_INSTALLED/cli/php.ini"
            echo "" >> $PHP_CLI_INI_PATH
            echo "extension=mongodb.so" >> $PHP_CLI_INI_PATH
        fi
    fi

    service apache2 restart
fi
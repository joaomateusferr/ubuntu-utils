#!/bin/bash

#src -> https://github.com/php/php-src/tree/master/ext/gmp
#manual -> https://www.php.net/manual/en/book.gmp.php

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt-get update
    apt-get install libgmp-dev php-gmp
    service apache2 restart
fi
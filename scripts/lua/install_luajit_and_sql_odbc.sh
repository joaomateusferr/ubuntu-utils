#!/bin/bash

sudo apt-get update
if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt-get update
    apt-get install luajit lua-sql-odbc 
fi
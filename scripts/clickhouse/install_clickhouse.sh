#!/bin/bash

#This script requires user input and it's based on the digitalocean article below
#https://www.digitalocean.com/community/tutorials/how-to-install-and-use-clickhouse-on-ubuntu-20-04

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt-get update
    apt-key adv --keyserver keyserver.ubuntu.com --recv E0C56BD4
    echo "deb http://repo.yandex.ru/clickhouse/deb/stable/ main/" | sudo tee /etc/apt/sources.list.d/clickhouse.list
    apt update
    apt install clickhouse-server clickhouse-client #the password for default user will be required during installation
    service clickhouse-server start
fi

#TESTS

#check if clickhouse is active
#sudo service clickhouse-server status

#test the conection flow
#clickhouse-client --password

#create test table
#CREATE TABLE visits ( id UInt64, duration Float64, url String, created DateTime ) ENGINE = MergeTree() PRIMARY KEY id ORDER BY id;

#inset test
#INSERT INTO visits VALUES (1, 12.5, 'http://example.com', NOW())

#select test
#SELECT duration, url, created  FROM default.visits LIMIT 100

#tabix (recommended GUI)
#https://github.com/tabixio/tabix

#default connection values
#http://host:port: http://0.0.0.0:8123/
#login: default
#password: default_password
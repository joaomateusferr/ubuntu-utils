#!/bin/bash

#This script requires user input and it's based on the digitalocean article below
#https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-redis-on-ubuntu-20-04

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt update
    apt install redis-server
fi

#sudo luarocks install redis-lua
#sudo luarocks install luasocket

#sudo nano /etc/redis/redis.conf - supervised no -> supervised systemd
#sudo systemctl restart redis.service
#sudo systemctl status redis

#redis-cli
#HMSET post post_id 1 title "Hello world title." author_name "Linuxhint" publish_date "02/02/2022" categpgry "linux"
#HMSET post post_id 2 title "Hello world title2." author_name "Linuxhint" publish_date "02/02/2022" categpgry "linux"
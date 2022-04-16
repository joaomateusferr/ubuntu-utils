#!/bin/bash

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt update
    apt-get install openssh-server
    systemctl enable ssh
    systemctl start ssh
fi

#run "sudo systemctl status ssh" to check if ssh is active
#how to use "ssh userName@Your-server-name-IP"
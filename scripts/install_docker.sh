#!/bin/bash

#This script requires user input and it's based on the digitalocean article below
#https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt update
    apt install -y apt-transport-https ca-certificates curl software-properties-common
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"
    apt update
    apt-cache policy docker-ce
    apt install -y docker-ce

    #groupadd docker #creating the group is not necessary on most distros, if necessary, add this line

    for MACHINE_USER in $(ls /home); do
        sudo usermod -aG docker $MACHINE_USER
    done

    echo 'Now, restart and log back in so that your group membership is re-evaluated and then you can use docker without sudo'
fi

#run "sudo systemctl status docker" to check if docker is active
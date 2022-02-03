#!/bin/bash

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    docker pull ns1labs/flame
    #run "sudo docker run ns1labs/flame --help" to check if everything is ok
fi
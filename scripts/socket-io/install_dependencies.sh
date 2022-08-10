#!/bin/bash

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt update
    apt-get install python3
    apt-get install python-pip3
    pip install python-socketio
fi
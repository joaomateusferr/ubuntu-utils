#!/bin/bash

#This script requires user input and it's based on the digitalocean article below
#https://www.digitalocean.com/community/tutorials/how-to-install-mongodb-on-ubuntu-20-04-pt

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    curl -fsSL https://www.mongodb.org/static/pgp/server-4.4.asc | sudo apt-key add -
    apt-key list
    echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/4.4 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.4.list
    apt update
    apt install mongodb-org
    systemctl start mongod.service
fi

#sudo systemctl status mongod
#sudo systemctl enable mongod
#mongo --eval 'db.runCommand({ connectionStatus: 1 })'

#mongo
#db.testCollection.insertMany([{"name": "navindu", "age": 22, "email": "nav@gmail.com"}, {"name": "kovid", "age": 27, "email": "kovig@gmail.com" }, {"name": "john doe", "age": 25, "city": "Hyderabad"}])
#db.testCollection.find().pretty()
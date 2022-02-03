#!/bin/bash

FULL_NAME=''
EMAIL=''

if [ -z "$FULL_NAME" ] || [ -z "$EMAIL" ];then

    if [ -z "$FULL_NAME" ];then
        echo "Please, Fill in the FULL_NAME variable with the developer's full name"
    fi

    if [ -z "$EMAIL" ];then
        echo "Please, Fill in the EMAIL variable with the developer's email"
    fi
    
else

    git config --global user.name "$FULL_NAME"
    git config --global user.email "$EMAIL"
    
fi

#run "git config --global --list" to check if everthing is set

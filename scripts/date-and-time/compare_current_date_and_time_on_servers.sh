#!/bin/bash

SERVERS="server1 server2"
TIME="5" #acceptable delay in seconds
UNIT="%s" #timestamp

for SERVER in $SERVERS
do
    DIFFERENCE=$(echo $(ssh -l john ${SERVER} "( date +${UNIT} )") "-" $(date +${UNIT}) | bc)

    if [[ ${DIFFERENCE#-} -le ${TIME} ]] ; then
        echo $SERVER - IN SYNC
    else
        echo $SERVER - NOT IN SYNC 
    fi

done

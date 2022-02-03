#!/bin/bash

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    #Settings
    DELETEFOLDER=1
    DELETEFILE=0

    URL='https://github.com/ClickHouse/clickhouse-odbc/releases/download/v1.1.6.20200320/clickhouse-odbc-1.1.6-Linux.tar.gz'
    FOLDER='/tmp/obdc_driver'
    FILENAME="$(basename $URL)"
    DRIVERFOLDER="${FILENAME%.*.*}" #remove .tar.gz

    STATUS=$(curl -I --write-out %{http_code} --silent --output /dev/null "$URL")

    if [ $STATUS -eq '200' ] || [ $STATUS -eq '301' ] || [ $STATUS -eq '302' ];then
        ISAVAILABLE=1
    else
        ISAVAILABLE=0
    fi

    if [ $ISAVAILABLE -eq 0 ];then
        echo 'Download not available!'
    else
        echo 'Download available ...'

        CONTENTLENGTH=$(curl -sI "$URL" | grep content-length)
        #FILELENGTH=$(echo $CONTENTLENGTH | grep -o -E '[0-9]+')    #does not work for github download pages
        FILELENGTH=''

        if [ ! -d "$FOLDER" ];then
            mkdir -p "$FOLDER";
        fi

        echo 'Starting download ...'

        curl $URL -s -L -o "$FOLDER/$FILENAME" > /dev/null 2>&1
        DOWNLOADSTATUS=$?;

        if [ $DOWNLOADSTATUS -eq 0 ];then
            echo 'Download completed ...'

            if [ -e "$FOLDER/$FILENAME" ];then

                echo 'Validating download!'

                DOWNLOADLENGTH=$(wc -c "$FOLDER/$FILENAME" | awk '{print $1}')

                if [ ! -z "$FILELENGTH" ];then
                    echo 'Information about the file size is available ...'

                    if [ "$DOWNLOADLENGTH" -eq "$FILELENGTH" ];then
                        echo 'Validated file -> OK'
                    else
                        echo 'It looks like something is wrong with the file'
                    fi    
                else
                    echo 'Information about the file size is not available to validate the file!'
                fi
            fi
        else
            echo 'Error while downloading!'
        fi

    fi

    cd $FOLDER
    tar zxf $FILENAME

    if [ $? -eq 0 ];then
        echo 'File unziped'
    else
        echo 'Error, file not unziped'
    fi

    cp $DRIVERFOLDER/lib64/libclickhouse* /usr/local/lib

    if [ $? -eq 0 ];then
        echo 'ODBC drivers installed'
    else
        echo 'Error, ODBC drivers not installed'
    fi

    #If the folder is deleted the files in it will also be deleted
    if [ $DELETEFOLDER -eq 1 ];then
        DELETEFILE=0
    fi

    if [ $DELETEFILE -eq 1 ];then
        echo 'Deleting file ...'
        rm -rf "$FOLDER/$FILENAME" > /dev/null 2>&1

        if [ $? -eq 0 ];then
            echo 'File deleted'
        else
            echo 'Error, file not deleted'
        fi
    fi

    if [ $DELETEFOLDER -eq 1 ];then
        echo 'Deleting folder ...'
        rm -rf "$FOLDER" > /dev/null 2>&1

        if [ $? -eq 0 ];then
            echo 'Folder deleted'
        else
            echo 'Error, folder not deleted'
        fi
    fi
fi
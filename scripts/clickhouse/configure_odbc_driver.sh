#!/bin/bash

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else

    CLEAR_INI_FILES=1
    ODBC_INI_PATH='/etc/odbc.ini'
    ODBCINST_INI_PATH='/etc/odbcinst.ini'


    if [ $CLEAR_INI_FILES -eq 1 ]; then
        echo "" > $ODBC_INI_PATH
        echo "" > $ODBCINST_INI_PATH
    else
        echo "" >> $ODBC_INI_PATH
        echo "" >> $ODBCINST_INI_PATH
    fi

    echo "[ODBC Data Sources]" >> $ODBC_INI_PATH
    echo "ClickHouse DSN (ANSI)    = ClickHouse ODBC Driver (ANSI)" >> $ODBC_INI_PATH
    echo "ClickHouse DSN (Unicode) = ClickHouse ODBC Driver (Unicode)" >> $ODBC_INI_PATH
    echo "" >> $ODBC_INI_PATH
    echo "[ClickHouse DSN (ANSI)]" >> $ODBC_INI_PATH
    echo "Driver      = ClickHouse ODBC Driver (ANSI)" >> $ODBC_INI_PATH
    echo "Description = DSN (localhost) for ClickHouse ODBC Driver (ANSI)" >> $ODBC_INI_PATH
    echo "" >> $ODBC_INI_PATH
    echo "[ClickHouse DSN (Unicode)]" >> $ODBC_INI_PATH
    echo "Driver      = ClickHouse ODBC Driver (Unicode)" >> $ODBC_INI_PATH
    echo "Description = DSN (localhost) for ClickHouse ODBC Driver (Unicode)" >> $ODBC_INI_PATH
    
    echo "[ODBC Drivers]" >> $ODBCINST_INI_PATH
    echo "ClickHouse ODBC Driver (ANSI)    = Installed" >> $ODBCINST_INI_PATH
    echo "ClickHouse ODBC Driver (Unicode) = Installed" >> $ODBCINST_INI_PATH
    echo "" >> $ODBCINST_INI_PATH
    echo "[ClickHouse ODBC Driver (ANSI)]" >> $ODBCINST_INI_PATH
    echo "Description = ODBC Driver (ANSI) for ClickHouse" >> $ODBCINST_INI_PATH
    echo "Driver      = /usr/local/lib/libclickhouseodbc.so" >> $ODBCINST_INI_PATH
    echo "Setup       = /usr/local/lib/libclickhouseodbc.so" >> $ODBCINST_INI_PATH
    echo "UsageCount  = 1" >> $ODBCINST_INI_PATH
    echo "" >> $ODBCINST_INI_PATH
    echo "[ClickHouse ODBC Driver (Unicode)]" >> $ODBCINST_INI_PATH
    echo "Description = ODBC Driver (Unicode) for ClickHouse" >> $ODBCINST_INI_PATH
    echo "Driver      = /usr/local/lib/libclickhouseodbcw.so" >> $ODBCINST_INI_PATH
    echo "Setup       = /usr/local/lib/libclickhouseodbcw.so" >> $ODBCINST_INI_PATH
    echo "UsageCount  = 1" >> $ODBCINST_INI_PATH  
fi
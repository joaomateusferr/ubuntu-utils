#!/bin/bash

SAMBA_USER='smb-user'
SAMBA_CONF_PATH='/etc/samba/smb.conf'

if [ $(id -u) -ne 0 ]; then #this screipt equire root privileges (root id is 0)
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
else
    apt update
    apt-get install samba

    mkdir /home/$SAMBA_USER/smb
    chmod -R 777 /home/$SAMBA_USER/smb

    echo "" >> $SAMBA_CONF_PATH
    echo "[smb-share]" >> $SAMBA_CONF_PATH
    echo "   comment = Samba Share" >> $SAMBA_CONF_PATH
    echo "   path = /home/$SAMBA_USER/smb" >> $SAMBA_CONF_PATH
    echo "   read only = no" >> $SAMBA_CONF_PATH
    echo "   browseable = yes" >> $SAMBA_CONF_PATH

    systemctl restart smbd
    ufw allow samba

    smbpasswd -a $SAMBA_USER

fi

#run "sudo systemctl status smbd" to check if smb is active
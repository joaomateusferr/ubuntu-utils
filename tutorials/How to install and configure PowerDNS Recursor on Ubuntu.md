# How to install and configure PowerDNS Recursor on Ubuntu

## Official Documentation

Take a look at the official PowerDNS Recursor documentation:

https://doc.powerdns.com/recursor/indexTOC.html


## Install PowerDNS Recursor

Install the recursor itself

```
sudo apt-get install pdns-recursor
```

## Configure

Find out what the config folder is

```
pdns_recursor --no-config --config | grep config-dir
```

You should see something like this...

```
# api-config-dir	Directory where REST API stores config and zones
# api-config-dir=
# config-dir	Location of configuration directory (recursor.conf)
# config-dir=/etc/powerdns
```

keep in mind that the **folder path may vary** depending on which linux distribution you are using

In my case the configuration folder is in **/etc/powerdns** I will use the command below to open and edit the configuration file

```
sudo nano /etc/powerdns/recursor.conf
```

Add the keys and values ​​that are below

```
#Bind (these are the values ​​I use you can use any ip or port value here)
allow-from=127.0.0.1
local-address=127.0.0.1:5050

#Log e Debug
#trace=on
#new-domain-log=yes
log-common-errors=yes
log-rpz-changes=yes
log-timestamp=yes
loglevel=9
logging-facility=0
quiet=no

#Dir Config
config-dir=/etc/powerdns

#Packet-cache
disable-packetcache=yes

#Lua Filtering (not necessary if you don't want to change dns query responses)
lua-dns-script=/etc/powerdns/script.lua
```

restart the recursor to apply new settings

```
sudo systemctl restart pdns-recursor
```

Check if recursor is active

```
sudo service pdns-recursor status
```

## Tests

Let's test if everything is working

```
dig @localhost -p 5050 google.com
```

You should see something like this...

```
; <<>> DiG 9.16.1-Ubuntu <<>> @localhost -p 5050 google.com
; (1 server found)
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 36495
;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 1

;; OPT PSEUDOSECTION:
; EDNS: version: 0, flags:; udp: 512
;; QUESTION SECTION:
;google.com.			IN	A

;; ANSWER SECTION:
google.com.		1	IN	A	0.0.0.0

;; Query time: 364 msec
;; SERVER: 127.0.0.1#5050(127.0.0.1)
;; WHEN: dom mar 06 14:30:42 -03 2022
;; MSG SIZE  rcvd: 55
```

## Debug

Use the command below to identify configuration issues or debug while developing a dns filter

```
journalctl -u pdns-recursor -f
```
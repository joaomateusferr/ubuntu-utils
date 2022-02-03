# How to deploy a ClickHouse database and configure odbc drivers

## Official Repositorys

Take a look at the official ClickHouse repositorys:

https://github.com/ClickHouse/ClickHouse

https://github.com/ClickHouse/clickhouse-odbc

## Official Documentation

Take a look at the official ClickHouse documentation:

https://clickhouse.com/docs/en/


## Install ClickHouse

Take a look at [install_clickhouse.sh](../scripts/clickhouse/install_clickhouse.sh)


Check if clickhouse is active

```
sudo service clickhouse-server status
```


Create table

```
CREATE TABLE visits ( id UInt64, duration Float64, url String, created DateTime ) ENGINE = MergeTree() PRIMARY KEY id ORDER BY id;
```

Inset

```
INSERT INTO visits VALUES (1, 12.5, 'http://example.com', NOW())
```

Select

```
SELECT duration, url, created  FROM default.visits LIMIT 100
```


## Recommended Visual Interface

tabix -> https://github.com/tabixio/tabix


## Default connection values

http://host:port: http://0.0.0.0:8123/

login: default

password: default_password


## Install odbc drivers dependencies

Take a look at [install_odbc_drivers_dependencies.sh](../scripts/clickhouse/install_odbc_drivers_dependencies.sh)


## Download and install odbc drivers

Take a look at [download_and_install_odbc_drivers.sh](../scripts/clickhouse/download_and_install_odbc_drivers.sh)


Test driver and check dependencies

```
ldd /usr/local/lib/libclickhouseodbcw.so
```


## Configure odbc driver

Check odbcinst paths

```
odbcinst -j
```


Take a look at [configure_odbc_driver.sh](../scripts/clickhouse/configure_odbc_driver.sh)


check if the odbc driver is working

```
isql -v "ClickHouse DSN (Unicode)"
```
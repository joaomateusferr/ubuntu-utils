# How to deploy a ClickHouse database and configure odbc drivers

## Install ClickHouse

Take a look at [install_clickhouse.sh](../scripts/clickhouse/install_clickhouse.sh)

## Tests

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


**http://host:port**: http://0.0.0.0:8123/

**login**: default

**password**: default_password


<?php

    //sudo apt-get install php-odbc

    $Connection = odbc_connect('ClickHouse DSN (Unicode)', 'default','1401');
    $Sql = 'SELECT * FROM visits WHERE id = 80';
    $Result = odbc_exec($Connection, $Sql);

    if($Result){
        $Visits = [];

        while ($Row = odbc_fetch_array($Result)) {
            $Visits[] = $Row;
        }
    }

    var_dump($Visits);

    $Sql = "INSERT INTO visits VALUES (1, 12.8, 'http://example.com', NOW())";
    $Result = odbc_exec($Connection, $Sql);

    $Sql = 'SELECT duration, url FROM default.visits LIMIT 100';
    $Result = odbc_exec($Connection, $Sql);
    
    if($Result){
        $Visits = [];

        while ($Row = odbc_fetch_array($Result)) {
            $Visits[] = $Row;
        }
    }

    var_dump($Visits);

    odbc_close($Connection);
?>
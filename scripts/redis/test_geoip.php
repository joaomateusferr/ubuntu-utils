<?php

$Ip = isset($argv[1]) ? $argv[1] : exit("IP!\n");

$Redis = new \Redis();
$Redis->connect('127.0.0.1', 6379, 3.5);

//$All = $Redis->zRange('geo-ip-v4', 0, -1, true);

$GeoIp = $Redis->zRangeByScore('geo-ip-v4', $Ip, '+inf', ['withscores' => TRUE, 'limit' => [0, 1]]);

if(empty($GeoIp)){
    $Result = null;
} else {
    $ArrayKeys = array_keys($GeoIp);

    $GeoIpKey = $ArrayKeys[0];
    $GeoIpValue = $GeoIp[$GeoIpKey];

    $GeoIpInfo = explode('-', $GeoIpKey);

    if($GeoIpInfo[1] == 'START' && $Ip != $GeoIpValue)
        $Result = null;
    else
        $Result = $GeoIpInfo[0];
}

$Redis->close();

var_dump($Result);

//var_dump($All);
?>

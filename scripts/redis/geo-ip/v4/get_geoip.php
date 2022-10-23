<?php

$Ip = isset($argv[1]) ? (is_numeric($argv[1]) ? intval($argv[1]) : ip2long($argv[1])) : exit("Fill IP!\n");

if(!$Ip)
    exit("Invalid IP!\n");

$Redis = new \Redis();
$Redis->connect('127.0.0.1', 6379, 3.5);

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

?>

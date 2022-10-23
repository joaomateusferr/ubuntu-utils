<?php

function ip62long($ipv6) {
    return (string) gmp_import(inet_pton($ipv6));
}

$Ip = isset($argv[1]) ? $argv[1] : exit("Fill IP!\n");
$Ip = ip62long($Ip);
$Ip = str_repeat('0', 39 - strlen($Ip)) . $Ip;

$Redis = new \Redis();
$Redis->connect('127.0.0.1', 6379, 3.5);

$GeoIp = $Redis->zRangeByLex('geo-ip-v6', '['.$Ip, '+', 0, 1);
var_dump($GeoIp);exit;

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

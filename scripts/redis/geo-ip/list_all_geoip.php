<?php

$Redis = new \Redis();
$Redis->connect('127.0.0.1', 6379, 3.5);

$All = $Redis->zRange('geo-ip-v4', 0, -1, true);

$Redis->close();

var_dump($All);
?>

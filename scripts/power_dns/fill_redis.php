<?php
//sudo apt-get install php-redis 
$client = new Redis();
$client->connect( '127.0.0.1', 6379, 3.5 );

for ($i = 1; $i <= 10000; $i++) {
    $obj = (['id' => $i, 'name' => "user $i", 'email' => "user$i@email.com"]);
    $client->hMSet("user-$i",$obj);
}

$client->close();
?>
<?php 

//sudo apt-get install php-redis

$client = new Redis();
$client->connect( '127.0.0.1', 6379, 3.5 );

$obj = ['user-01', 'email', 'joao@test01@gmail.com'];
$client->hset($obj[0],$obj[1],$obj[2]);

$obj = (['email' => 'joao@test01@outlook.com', 'name' => 'jao']);
$client->hMSet('user-01',$obj);

$value = $client->hgetall('user-01');
$client->close();
var_dump($value);exit;

?>

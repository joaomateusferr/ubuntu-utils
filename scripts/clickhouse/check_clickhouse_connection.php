<?php 

//https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04

//composer require smi2/phpclickhouse

require __DIR__ . '/vendor/autoload.php';

$config = [
    'host' => '0.0.0.0',
    'port' => '8123',
    'username' => 'default',
    'password' => '1401'
];
$db = new ClickHouseDB\Client($config);
$db->database('default');
$db->insert('visits',
    [
        [1, 61.5, 'http://example.com', time()],
        [1, 61.8, 'http://example.com', time()],
    ],
    ['id', 'duration', 'url', 'created']
);
$result = $db->select('SELECT duration, url FROM visits LIMIT 1');
var_dump($result->rows());

?>
<?php

set_time_limit(0);

$IP = '127.0.0.1';
$Port = '3306';
$User = 'root';
$Password = 'my-secret-pw';
$SSLCertificatePath = ''; //"path\/to\/.pem"

$Database = '';
$TesterSelect = 'SELECT 1 AS Value';    //test select

$Sleep = 20;
$TesterSelectSleep = "SELECT 1 AS Value, SLEEP($Sleep) AS Time";    //test select with sleep in the db

$Options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set binary encoding to UTF8
    PDO::ATTR_TIMEOUT => 30, // Set timeout to 30s
    //PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
];

if(!empty($SSLCertificatePath)){
    $Options[PDO::MYSQL_ATTR_SSL_CA] = $SSLCertificatePath;
    $Options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

$DSN = "mysql:host=$IP;port=$Port;dbname=$Database;charset=utf8";

try {

    $Connection = new PDO($DSN, $User, $Password, $Options);

    //----- Playground Start -----
    $Response = executeTestSelect($Connection, $TesterSelectSleep);
    var_dump($Response);
    sleep(30);
    $Response = executeTestSelect($Connection, $TesterSelectSleep);
    var_dump($Response);
    //----- Playground End -----

} catch (Exception $Exception) {

    echo($Exception->getMessage()."\n");
}

function executeTestSelect(PDO $Connection, string $TesterSelect) : array {

    $Sql = $TesterSelect;
    $Stmt = $Connection->prepare($Sql);
    $Result = $Stmt->execute();

    $Response = [];

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){
            $Response[] = $Row;
        }

    }

    return $Response;

}


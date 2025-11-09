<?php

$Host = '';
$Port = 3306;
$User = '';
$Password = '';

$Options = [
    PDO::ATTR_PERSISTENT => false,
    PDO::ATTR_TIMEOUT => 30,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
];


try {

    $Numbers = [rand(1, 10), rand(1, 10)];
    $DSN = "mysql:host=$Host;port=$Port;charset=utf8";
    $PDO = new PDO($DSN, $User, $Password, $Options);
    $Sql = "SELECT ? + ? AS Sum";
    $Stmt = $PDO->prepare($Sql);
    $Result = $Stmt->execute($Numbers);

    if($Result && $Stmt->rowCount() > 0){
        echo $Numbers[0].' + '.$Numbers[1].' = ' . $Stmt->fetch()['Sum']."\n";
    }

} catch (Exception $Exception) {

    echo "Error: " . $e->getMessage();

} finally {

    try{
        $PDO->query('KILL CONNECTION_ID()');
    } catch (Exception $Exception){
        //this will generate an error anyway we only handle the error when killing the connection
    }

    $PDO = null;

}
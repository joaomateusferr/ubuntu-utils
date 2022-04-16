<?php

$Manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$Database = 'ipgeo';
$Colection = 'ipsus';
$IpLong = 18350086;
$Sort = 1;

$StartStandardQuery = microtime(true);

$Filter = ['$and' => [['start' => ['$lte' => $IpLong], 'end' => ['$gte' => $IpLong]]]];
$Options = [];

$Query = new MongoDB\Driver\Query($Filter,$Options); 
$Response = $Manager->executeQuery($Database.'.'.$Colection, $Query);
$Ranges = $Response->toArray();

echo "\nStandard Query:\n";
var_dump($Ranges);
echo "\n";

$EndStandardQuery = microtime(true) - $StartStandardQuery;

$StartGetMedian = microtime(true);

$Command = new MongoDB\Driver\Command(["count" => $Colection]);
$Result = $Manager->executeCommand($Database, $Command);
$Response = current($Result->toArray());
$CountDocuments = $Response->n;

$Filter = [];
$Options = ['sort' => ['start' => 1], 'skip' => $CountDocuments/2, 'limit' => 1];

$Query = new MongoDB\Driver\Query($Filter,$Options);
$Response = $Manager->executeQuery($Database.'.'.$Colection, $Query);
$Median = $Response->toArray()[0];

$EndGetMedian = microtime(true) - $StartGetMedian;

$StartMedianQuery = microtime(true);

if($IpLong > $Median->start){
    $Sort = -1;
}

$Filter = ['$and' => [['start' => ['$lte' => $IpLong], 'end' => ['$gte' => $IpLong]]]];
$Options = ['sort' => ['start' => $Sort], 'limit' => 1];

$Query = new MongoDB\Driver\Query($Filter,$Options); 
$Response = $Manager->executeQuery($Database.'.'.$Colection, $Query);
$Ranges = $Response->toArray();

echo "\nMedian Query:\n";
var_dump($Ranges);
echo "\n";

$EndMedianQuery = microtime(true) - $StartMedianQuery;

$StartSortAscendingQuery = microtime(true);

$Filter = ['$and' => [['start' => ['$lte' => $IpLong], 'end' => ['$gte' => $IpLong]]]];
$Options = ['sort' => ['start' => 1], 'limit' => 1];

$Query = new MongoDB\Driver\Query($Filter,$Options); 
$Response = $Manager->executeQuery($Database.'.'.$Colection, $Query);
$Ranges = $Response->toArray();

echo "\nSort Ascending Query:\n";
var_dump($Ranges);
echo "\n";

$EndtSortAscendingQuery = microtime(true) - $StartSortAscendingQuery;

$StartSortDescendingQuery = microtime(true);

$Filter = ['$and' => [['start' => ['$lte' => $IpLong], 'end' => ['$gte' => $IpLong]]]];
$Options = ['sort' => ['start' => -1], 'limit' => 1];

$Query = new MongoDB\Driver\Query($Filter,$Options); 
$Response = $Manager->executeQuery($Database.'.'.$Colection, $Query);
$Ranges = $Response->toArray();

echo "\nSort Descending Query:\n";
var_dump($Ranges);
echo "\n";

$EndtSortDescendingQuery = microtime(true) - $StartSortDescendingQuery;

echo "Results:\n";
echo "Get Median: ".$EndGetMedian."\n";
echo "Median Query: ".$EndMedianQuery."\n";
echo "Sort Ascending Query: ".$EndtSortAscendingQuery."\n";
echo "Sort Descending Query: ".$EndtSortDescendingQuery."\n";
echo "Standard Query: ".$EndStandardQuery."\n";
?>

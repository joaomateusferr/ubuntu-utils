<?php 

function ip62long($ipv6) {
    return (string) gmp_import(inet_pton($ipv6));
}

$LocalFilePath = isset($argv[1]) ? $argv[1] : exit("CSV path!\n");

$File = fopen($LocalFilePath, 'r');

$LineCount = $LineSkippedCount = 0;

$Countrys = [];

$Redis = new \Redis();
$Redis->connect('127.0.0.1', 6379, 3.5);


while (($Line = fgetcsv($File)) !== FALSE) {
    
    $LineCount++;
        
    if($LineCount == 1) 
        continue;

    $Start = ip62long($Line[0]);
    $Start = str_repeat('0', 39 - strlen($Start)) . $Start;
    $End = ip62long($Line[1]);
    $End = str_repeat('0', 39 - strlen($End)) . $End;
    $Country = $Line[3];

    var_dump("$Start-$End-$Country");

    if($Start && $End){

        if(isset($Countrys[$Country]))
            $Countrys[$Country]++;
        else
            $Countrys[$Country] = 1;

        $Redis->zAdd('geo-ip-v6', $Start, $Country.'-START-'.$Countrys[$Country], $End, $Country.'-END-'.$Countrys[$Country]);

    } else {
        $LineSkippedCount++;
    }
            
}

echo "$LineCount lines added\n$LineSkippedCount lines skipped\n"

?>

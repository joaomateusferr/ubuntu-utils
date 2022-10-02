<?php 

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

    $Start = ip2long($Line[0]);
    $End = ip2long($Line[1]);
    $Country = $Line[3];

    if($Start && $End){

        if(isset($Countrys[$Country]))
            $Countrys[$Country]++;
        else
            $Countrys[$Country] = 1;

        $Redis->zAdd('geo-ip-v4', $Start, $Country.'-START-'.$Countrys[$Country], $End, $Country.'-END-'.$Countrys[$Country]);
    } else {
        $LineSkippedCount++;
    }
            
}

echo "$LineCount lines added\n$LineSkippedCount lines skipped\n"

?>

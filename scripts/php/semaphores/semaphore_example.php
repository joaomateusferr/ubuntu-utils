<?php

$Key         = 1;
$Max         = 2;
$Permissions = 0666;
$AutoRelease = 1;
 
$Semaphore = sem_get($Key, $Max, $Permissions, $AutoRelease);

if (!$Semaphore) {
    echo "Failed on sem_get().\n";
    exit;
}
 

echo "\nAttempting to acquire semaphore...\n";
sem_acquire($Semaphore);

echo "Aquired.\nEnter some text: ";
$Handler = fopen("php://stdin", "r");
$Text = fgets($Handler);
 
fclose($Handler);
sem_release($Semaphore);
 
echo "Got: $Text \n";

if(trim($Text) == 'remove'){
    echo "sem_remove";
    sem_remove($Semaphore);
}

//use "ipcs -s" to list semaphore arrays
//use ipcs -s -i $key to list semaphore info
//use "ipcrm -S $key" to remove a semaphore
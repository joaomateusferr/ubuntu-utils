<?php

$Key         = 1;
$Max         = 1;
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
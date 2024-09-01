<?php

//ps axo command | grep "db-tester.php" | grep -v grep | wc -l  //check number of active workers

set_time_limit(0);

$Workers = 1;
$Command = 'php '.dirname(__FILE__).'/db-tester.php >/dev/null 2>/dev/null &';

for($I=0; $I<$Workers; $I++){
    shell_exec($Command);
}

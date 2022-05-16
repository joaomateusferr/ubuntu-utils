<?php

$Command = 'git show --stat';

$Result = shell_exec($Command);

var_dump($Result);

?>
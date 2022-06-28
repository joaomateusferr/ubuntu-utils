<?php

$FilePath = isset($argv[0]) ? $argv[0] : exit;

$Command = 'ps axo command | grep "'.$FilePath.'" | grep -v grep | awk \'{print $3}\' | grep -wc "'.$ServerName.'"';
$CountSimilarProcessesRunning = ((int) trim(shell_exec($Command))) - 1; // -1 -> this process

if($CountSimilarProcessesRunning > 0) 
    exit;
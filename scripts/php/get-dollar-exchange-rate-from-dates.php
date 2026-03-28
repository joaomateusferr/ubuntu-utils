<?php

$DatesFilePath = '/home/john/Documents/dates.txt';
$ExportFile = dirname($DatesFilePath).'/exchange-rates.txt';
$DollarCode = 220;
$Url = "https://www3.bcb.gov.br/bc_moeda/rest/cotacao/fechamento/ultima/1/$DollarCode/";

if(file_exists($ExportFile))
    unlink($ExportFile);

$Lines = file($DatesFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach($Lines as $Line){

    $Date = trim($Line);
    $Tokens = explode('/',$Date);

    foreach($Tokens as $index => $Token){

        $Tokens[$index] = str_pad($Tokens[$index], 2, "0", STR_PAD_LEFT);

    }

    $Tokens = array_reverse($Tokens);
    $Date = implode('-', $Tokens);

    $Xml = file_get_contents($Url.$Date);
    $Xml = simplexml_load_string($Xml);
    $Content = json_decode(json_encode($Xml), true);
    $ExchangeRate = (float) $Content['cotacoes']['taxaVenda'];
    file_put_contents($ExportFile, "$ExchangeRate\n", FILE_APPEND);

}
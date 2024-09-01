<?php

$Page = getPage('https://investidor10.com.br/etfs/ndiv11/');
$TableData = parseTable($Page, "@class, 'table-dividends-history'");
var_dump($TableData);


function parseTable (string $Page, string $TableQuery) : array {

    $TableData = [];
    $TableFields = [];

    $Document = new DOMDocument();
    @$Document->loadHTML($Page);
    $XPath = new DOMXPath($Document);

    $Table = $XPath->query("//table[contains($TableQuery)]");

    $TableHead = $XPath->query(".//thead/tr", $Table[0]);

    foreach($TableHead[0] as $ColumnsContent){

        $TableColumnsLines = $XPath->query(".//th", $ColumnsContent);

        foreach($TableColumnsLines as $ColumnsLines){

            $TableFields[] = trim($ColumnsLines->nodeValue);

        }

    }

    $TableRows = $XPath->query(".//tbody/tr", $Table[0]);

    foreach ($TableRows as $TableRowsContent) {

        $TableDataLines = $XPath->query(".//td", $TableRowsContent);

        $LineInformation = [];

        foreach($TableDataLines as $Index => $DataLine){

            $LineInformation[$TableFields[$Index]] = trim($DataLine->nodeValue);

        }

        $TableData[] = $LineInformation;

    }

    return $TableData;

}

function getPage (string $Url) : string {

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $Url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;

}


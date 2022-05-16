<?php

$Command = 'git show --stat';
$Result = shell_exec($Command);
$Lines = explode("\n", $Result);
$CommitInfo = [];
$CommitInfo['Author'] = [];
$CommitInfo['Author']['Email'] = '';
$CommitInfo['Author']['Name'] = '';
$CommitInfo['Messages'] = [];
$CommitInfo['Changes'] = [];
$FirstBlank = $SecondBlank = $ThirdBlank = false;

foreach ($Lines as $Key => $Line){

    if($Key == 0){

        $Commit = explode(" ", $Line);
        $CommitInfo['Id'] = $Commit[1];

    } elseif($Key == 1){

        $Author = explode(" ", $Line);
        $AuthorSize = count($Author);

        foreach($Author as $AuthorKey => $AuthorInfo){

            if($AuthorKey == 0){
                continue;
            } elseif($AuthorKey == $AuthorSize-1){
                $CommitInfo['Author']['Email'] = substr($AuthorInfo, 1, -1);
            } else{
                $CommitInfo['Author']['Name']  = $CommitInfo['Author']['Name'].$AuthorInfo." ";
            }

        }

        $CommitInfo['Author']['Name'] = trim($CommitInfo['Author']['Name'], " ");

    } elseif($Key == 2){

        $DateTime = explode(" ", $Line);
        $CommitInfo['Date'] = $DateTime[4]." ".$DateTime[5]." ".$DateTime[7];
        $CommitInfo['Date'] = date('Y-m-d', strtotime($CommitInfo['Date']));
        $CommitInfo['Time'] = $DateTime[6];
        $CommitInfo['TimeZone'] = $DateTime[8];

    } else {

        $LineWithoutSpaces = trim($Line," ");

        if($LineWithoutSpaces == ""){

            if($FirstBlank == false){
                $FirstBlank = true;
                continue;
            } elseif($SecondBlank == false){
                $SecondBlank = true;
                continue;
            } elseif($ThirdBlank == false){
                $ThirdBlank = true;
                continue;
            }
        }
        
        if($FirstBlank == true && $SecondBlank == false){
            $CommitInfo['Messages'][] = $LineWithoutSpaces;

        } elseif($FirstBlank == true && $SecondBlank == true && $ThirdBlank == false){
            $CommitInfo['Changes'][] = $LineWithoutSpaces; 
        }
    }
}

var_dump($CommitInfo);

?>
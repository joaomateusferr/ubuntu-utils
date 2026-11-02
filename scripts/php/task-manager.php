<?php

class APIRequestHelper {

    public static function sendRequest(string $Method, string $Endpoint, array $Content = [], array $Headers = []) : ?array {

        $Url = 'https://app.asana.com/api/1.0'.$Endpoint;
        $Timeout = 10;

        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_HEADER, true);
        curl_setopt($Curl, CURLOPT_TIMEOUT, $Timeout);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, $Method);

        if(!empty($Content))
            curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode($Content));

        if(!empty($Headers))
            curl_setopt($Curl, CURLOPT_HTTPHEADER, $Headers);

        $Response = curl_exec($Curl);

        if (curl_errno($Curl)) {

            $ErrorMessage = curl_error($Curl);
            $ErrorCode = curl_errno($Curl);

            echo "[cURL Error] code: $ErrorCode | Message: $ErrorMessage";

            if($Response === false)
                return null;

        } else {

            $HeaderSize = curl_getinfo($Curl, CURLINFO_HEADER_SIZE);
            $Headers = self::parseHeaders(substr($Response, 0, $HeaderSize));
            $Response = substr($Response, $HeaderSize);

            if(!json_validate($Response))
                return null;

            $HTTPCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
            $Response = json_decode($Response, true);
            $Response['HTTPCode'] = $HTTPCode;
            $Response['Headers'] = $Headers;

        }

        return $Response;

    }

    private static function parseHeaders(string $RawHeaders) : array {

        $Headers = [];

        foreach (explode("\r\n", trim($RawHeaders)) as $Line) {

            if (strpos($Line, ':') !== false) {

                [$Name, $Value] = explode(':', $Line, 2);
                $Headers[trim($Name)] = trim($Value);

            }

        }

        return $Headers;

    }

}

class TaskManager {

    private static function getToken() : string {

        return '';

    }

    public static function getWorkspaces() : array {

        $Token = self::getToken();

        $Response = APIRequestHelper::sendRequest('GET', '/workspaces', [], ['Accept: application/json', 'Authorization: Bearer '.$Token]);

        if(empty($Response))
            return [];

        if($Response['HTTPCode'] != 200)
            return [];

        if(empty($Response['data']))
            return [];

        return $Response['data'];

    }

    public static function getProjects(string $WorkspaceGid) : array {

        $Token = self::getToken();
        $Endpoint = "/workspaces/$WorkspaceGid/projects";
        $Response = APIRequestHelper::sendRequest('GET', $Endpoint, [], ['Accept: application/json', 'Authorization: Bearer '.$Token]);

        if(empty($Response))
            return [];

        if($Response['HTTPCode'] != 200)
            return [];

        if(empty($Response['data']))
            return [];

        return $Response['data'];

    }

    public static function getSections(string $ProjectGid) : array {

        $Token = self::getToken();
        $Endpoint = "/projects/$ProjectGid/sections";
        $Response = APIRequestHelper::sendRequest('GET', $Endpoint, [], ['Accept: application/json', 'Authorization: Bearer '.$Token]);

        if(empty($Response))
            return [];

        if($Response['HTTPCode'] != 200)
            return [];

        if(empty($Response['data']))
            return [];

        return $Response['data'];

    }

    public static function listObjects(array $Objects) : void {

        foreach($Objects as $Index => $Object){

            $String = "$Index -> ";

            foreach($Object as $Key => $Value){

                $String .= "$Key - $Value, ";

            }

            $String = substr($String, 0, -2);
            $String .= "\n";

            echo $String;

        }

    }

    public static function addTask(array $Projects, string $SectionGid, array $Data) : bool {

        //Validating required fields

        if(empty($Projects[0]))
            return false;

        if(empty($SectionGid))
            return false;

        if(empty($Data['name']))
            return false;

        if(empty($Data['notes']))
            return false;

        //If a task belongs to multiple projects, the project to which it belongs must always be the first one in the array.
        $Object = ['data' => array_merge($Data, ['projects' => $Projects, 'memberships' => [['project' => $Projects[0],'section' => $SectionGid]]])];

        $Token = self::getToken();
        $Endpoint = "/tasks";
        $Response = APIRequestHelper::sendRequest('POST', $Endpoint, $Object, ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer '.$Token]);

        if(empty($Response))
            return false;

        if(!in_array($Response['HTTPCode'], [200, 201]))
            return false;

        return true;

    }

}

//Get your api token from the link below and replace it using the getToken method
//https://app.asana.com/0/my-apps

//Get gids from url, WorkspaceGid it’s the ID after "1" and Project Gid it’s the ID after "project" in the board URL
//https://app.asana.com/1/1208680700625002/project/1208680699908980...

$ProjectGid = '1208680699908980';

//Use the code snippet below to list the board sessions and find the ID of the one you want to use.
$Sections = TaskManager::getSections($ProjectGid);
TaskManager::listObjects($Sections);

$SectionGid = '1208680699908981';
$Payload = ['name' => 'My new task', 'notes' => 'Task description'];
$Result = TaskManager::addTask([$ProjectGid],$SectionGid,$Payload);

var_dump($Result);exit;




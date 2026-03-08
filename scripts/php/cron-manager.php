<?php

class CronJob {

    private array $Scripts;
    private string $Minute;
    private string $Hour;
    private string $Host;

    public function __construct(array $Scripts, ?int $Minute = null, ?int $Hour = null, ?string $Host = null){

        if(empty($Scripts))
            throw new Exception("Script array cannot be empty in a cron job!");

        $this->Scripts = $Scripts;
        $this->Minute = is_null($Minute) ? '*' :  str_pad($Minute, 2, '0', STR_PAD_LEFT);
        $this->Hour = is_null($Hour) ? '*' :  str_pad($Hour, 2, '0', STR_PAD_LEFT);
        $this->Host = is_null($Host) ? '*' : $Host;

    }

    public function getHost() : string {
        return $this->Host;
    }

    public function getHour() : string {
        return $this->Hour;
    }

    public function getMinute() : string {
        return $this->Minute;
    }

    public function getScripts() : array {
        return $this->Scripts;
    }

    public function getContentArray() : array {
        return [
            'Host' => $this->getHost(),
            'Hour' => $this->getHour(),
            'Minute' => $this->getMinute(),
            'Scripts' => $this->getScripts(),
        ];
    }

}

class CronSchedules {

    private array $Schedules;

    public function __construct(){

        $this->Schedules = [];

    }

    public function addJob(CronJob $Job){

        $this->Schedules[] = $Job;

    }

    public function getContentArray() : array {

        $Result = [];

        foreach($this->Schedules as $Job){

            $Result[] = $Job->getContentArray();

        }

        return $Result;

    }

}

class CronManager {

    private CronSchedules $Schedules;
    private string $Hour;
    private string $Minute;
    private ?string $Host;

    public function __construct(CronSchedules $Schedules, $Hour, int $Minute, ?string $Host = null) {

        $this->Schedules = $Schedules;
        $this->Hour = str_pad($Hour, 2, '0', STR_PAD_LEFT);
        $this->Minute = str_pad($Minute, 2, '0', STR_PAD_LEFT);
        $this->Host = is_null($Host) ? '*' : $Host;

    }

    private function isWildcard(string $Value): bool{
        return $Value === '*';
    }

    public function getScriptsToRun(): array {

        $Scripts = [];

        foreach($this->Schedules as $Job){

            var_dump($Job);exit;

            $HostMatches = $this->isWildcard($Job->getHost()) || $this->isWildcard($this->Host) || $Job->getHost() === $this->Host;
            $HourMatches   = $this->isWildcard($Job->getHour()) || $Job->getHour() === $this->Hour;
            $MinuteMatches = $this->isWildcard($Job->getMinute()) || $Job->getMinute() === $this->Minute;

            if ($HostMatches && $HourMatches && $MinuteMatches)
                $Scripts = array_merge($Scripts, $Job->getScripts());


        }

        return $Scripts;

    }

}

$Schedules = new CronSchedules();
$Schedules->addJob(new CronJob(['test-1.php'], null, null, 'test-1'));

$CronManager = new CronManager($Schedules, 0, 0);
var_dump($CronManager->getScriptsToRun());



<?php

class CronJob {

    private array $Scripts;
    private string $Minute;
    private string $Hour;
    private string $Host;
    private string $Product;

    public function __construct(array $Scripts, ?int $Minute = null, ?int $Hour = null, ?string $Host = null, ?string $Product = null){

        if(empty($Scripts))
            throw new Exception("Script array cannot be empty in a cron job!");

        $this->Scripts = $Scripts;
        $this->Minute = is_null($Minute) ? '*' :  str_pad($Minute, 2, '0', STR_PAD_LEFT);
        $this->Hour = is_null($Hour) ? '*' :  str_pad($Hour, 2, '0', STR_PAD_LEFT);
        $this->Host = is_null($Host) ? '*' : $Host;
        $this->Product = is_null($Product) ? '*' : $Product;

    }

    public function getProduct() : string {
        return $this->Product;
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

}
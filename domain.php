<?php
require "whm.php";
require "Comodo.php";

class Dom
{
    public function __construct() {

        $this->whm1                       = new WHM;
    
        $this->csrInputData               = new \stdClass();
        $this->csrInputData->domainName   = false;
        $this->csrInputData->emailAdd     = false;
        $this->csrInputData->locality     = false;
        $this->csrInputData->state        = false;
        $this->csrInputData->country      = false;
        $this->csrInputData->org          = false;
        $this->csrInputData->unit         = false;
        
        $this->testing                    = new \stdClass();
        $this->testing->testMode          = false;
        $this->testing->path              = '/root/gitprojects/sslinstallphp/test';

        $this->getInputVars();
        //file_put_contents($this->testing->path . "/testCsrInputData.json", json_encode($this->csrInputData));
        
        $this->com                        = new Comodo;

        if ($this->testing->testMode == true){

            $this->csrInputData           = json_decode(file_get_contents($this->testing->path . "/testCsrInputData.json"));
            $this->domainData             = json_decode(file_get_contents($this->testing->path . "/testDomainData.json"));
            $this->csrHashes              = json_decode(file_get_contents($this->testing->path . "/testCsrHashes.json"));
            $this->csrData                = json_decode(file_get_contents($this->testing->path . "/testCsrData.json"));
            
        } else{

            
            $this->domainData             = $this->whm1->getDomainData($this->csrInputData->domainName);
            $this->csrData                = $this->whm1->getCsrData($this->csrInputData);
            $this->csrHashes              = $this->com->getCsrHashes($this->csrData->data->csr);
        }

        print_r($this->csrInputData);
        print_r($this->domainData);
        print_r($this->csrData);        
        print_r($this->csrHashes);
        
        //print_r($this->csrHashes);
    }

    // loop through each element in the $argv array
    public function getInputVars() {

        $val = getopt("d:e::l::s::c::o::u::t");
       // var_dump($val);
        if (array_key_exists("d", $val)) {
            $this->csrInputData->domainName = $val["d"];
        } else {
            $this->csrInputData->domainName = "";
        }

        if (array_key_exists("e", $val)) {
            $this->csrInputData->emailAdd = $val["e"];
        } else {
            $this->csrInputData->emailAdd = "";
        }

        if (array_key_exists("l", $val)) {
            $this->csrInputData->locality = $val["l"];
        } else {
            $this->csrInputData->locality = "";
        }

        if (array_key_exists("s", $val)) {
            $this->csrInputData->state = $val["s"];
        } else {
            $this->csrInputData->state = "";
        }

        if (array_key_exists("c", $val)) {
            $this->csrInputData->country = $val["c"];
        } else {
            $this->csrInputData->country = "";
        }

        if (array_key_exists("o", $val)) {
            $this->csrInputData->org = $val["o"];
        } else {
            $this->csrInputData->org = "";
        }

        if (array_key_exists("u", $val)) {
            $this->csrInputData->unit = $val["u"];
        } else {
            $this->csrInputData->unit = "";
        }

        if (array_key_exists("t", $val)) {
            $this->testing->testMode = true;
        } else {
            $this->testing->testMode = false;
        }

        //var_dump($this->csrInputData);
    }

}
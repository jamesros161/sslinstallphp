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

        $this->testMode                   = false;
        
        $this->getInputVars();
        print_r("Test Mode : ");
        var_dump($this->testMode);
        
        $this->csrData                    = $this->whm1->getCsrData($this->csrInputData);
        $this->domainData                 = $this->whm1->getDomainData($this->csrInputData->domainName);

        //$this->com                        = new Comodo;

        //$this->csrHashes                  = $this->com->getCsrHashes($this->csrData->data->csr);
        
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
            $this->testMode = true;
        } else {
            $this->testMode = false;
        }

        //var_dump($this->csrInputData);
    }

}
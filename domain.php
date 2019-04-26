<?php
require "whm.php";
//require "Comodo.php";

class Dom
{
    public function __construct() {

        $this->val = getopt("d:e::l::s::c::o::u::tT");
        $this->whm1 = new WHM;
        //$this->com = new Comodo;
        $this->csrInputData = new \stdClass();
        $this->csrInputData->domainName   = $this->$val["d"];
        $this->csrInputData->emailAdd     = $this->$val["e"];
        $this->csrInputData->locality     = $this->$val["l"];
        $this->csrInputData->state        = $this->$val["s"];
        $this->csrInputData->country      = $this->$val["c"];
        $this->csrInputData->org          = $this->$val["o"];
        $this->csrInputData->unit         = $this->$val["u"];

        //$this->getInputVars();   

        $this->csrData                    = $this->whm1->getCsrData($this->csrInputData);
        $this->domainData                 = $this->whm1->getDomainData($this->csrInputData->domainName);
    }

    public function printComodoObj() {
        print_r($this->com);
    }

    // loop through each element in the $argv array
    public function getInputVars() {

        $val = getopt("d:e::l::s::c::o::u::tT");
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

        echo "csrInputData: \n";
        //var_dump($this->csrInputData);
    }

}
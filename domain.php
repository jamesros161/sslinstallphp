<?php
require "whm.php";
//require "Comodo.php";

class Dom
{
    public function __construct() {
        $this->whm1 = new WHM;
        //$this->com = new Comodo;

        $this->csrInputData->domainName   = "";
        $this->csrInputData->emailAdd     = "";
        $this->csrInputData->locality     = "";
        $this->csrInputData->state        = "";
        $this->csrInputData->country      = "";
        $this->csrInputData->org          = "";
        $this->csrInputData->unit         = "";   

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
        }
        if (array_key_exists("e", $val)) {
            $this->csrInputData->emailAdd = $val["e"];
        }
        if (array_key_exists("l", $val)) {
            $this->csrInputData->locality = $val["l"];
        }
        if (array_key_exists("s", $val)) {
            $this->csrInputData->state = $val["s"];
        }
        if (array_key_exists("c", $val)) {
            $this->csrInputData->country = $val["c"];
        }
        if (array_key_exists("o", $val)) {
            $this->csrInputData->org = $val["o"];
        }
        if (array_key_exists("u", $val)) {
            $this->csrInputData->unit = $val["u"];
        }
        echo "csrInputData: \n";
        //var_dump($this->csrInputData);
    }

}
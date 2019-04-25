<?php
require "whm.php";

class Dom
{
    public function __construct() {
        $this->whm1 = new WHM;
        $this->csrData = "TEST CSR DAT VAR";
        $this->sslCert = "TEST SSL CERT VAR";

        $this->csrInputData = array(
            "domainName"=> "",
            "emailAdd"=> "",
            "locality"=> "",
            "state"=> "",
            "country"=> "",
            "org"=> "",
            "unit"=> ""
        );
    }
    // loop through each element in the $argv array
    public function getInputVars() {

        $val = getopt("d:e::l::s::c::o::u::tT");
       // var_dump($val);
        if (array_key_exists("d", $val)) {
            $this->csrInputData["domainName"] = $val["d"];
        }
        if (array_key_exists("e", $val)) {
            $this->csrInputData["emailAdd"] = $val["e"];
        }
        if (array_key_exists("l", $val)) {
            $this->csrInputData["locality"] = $val["l"];
        }
        if (array_key_exists("s", $val)) {
            $this->csrInputData["state"] = $val["s"];
        }
        if (array_key_exists("c", $val)) {
            $this->csrInputData["country"] = $val["c"];
        }
        if (array_key_exists("o", $val)) {
            $this->csrInputData["org"] = $val["o"];
        }
        if (array_key_exists("u", $val)) {
            $this->csrInputData["unit"] = $val["u"];
        }
        echo "csrInputData: \n";
        var_dump($this->csrInputData);
    }

    public function getDomainData() {
        $domainData = $this->whm1->call("domainuserdata", $this->csrInputData["domainName"]);
        var_dump($domainData);
    }
}
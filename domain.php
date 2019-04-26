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
            $this->csrData                = json_decode(file_get_contents($this->testing->path . "/testCsrData.json"));
            $this->csrHashes              = json_decode(file_get_contents($this->testing->path . "/testCsrHashes.json"));

            //print_r($this->csrInputData);
            //print_r($this->domainData);
            //print_r($this->csrData);        
            //print_r($this->csrHashes);
            
        } else{

            
            $this->domainData             = $this->whm1->getDomainData($this->csrInputData->domainName);
            $this->csrData                = $this->whm1->getCsrData($this->csrInputData);
            $this->csrHashes              = $this->com->getCsrHashes($this->csrData->data->csr);
        }

        $this->dcv = new \stdClass();
        $this->dcv->subdir = "/.well-known/pki-validation";
        $this->dcv->dir = $this->domainData->data->userdata->documentroot . $this->dcv->subdir;
        $this->dcv->fileName = "/" . $this->csrHashes->md5 . ".txt";
        $this->dcv->filePath = $this->dcv->dir . $this->dcv->fileName;
        $this->dcv->url = $this->csrInputData->domainName . $this->dcv->subdir . $this->dcv->fileName;
        $this->dcv->httpUrl = "http://" . $this->dcv->url;
        $this->dcv->httpsUrl = "https://" . $this->dcv->url;
        $this->dcv->dcvContent = $this->csrHashes->sha256 . ' comodoca.com\n' . $this->com->args->uniqueValue;

        $this->selfDCV();
        
    }

    function selfDCV(){
        
        $this->mkDcvDir();
        
        file_put_contents($this->dcv->filePath,$this->dcv->dcvContent);

        $this->validateDcv();

    }

    function validateDcv() {
        $chdcv = curl_init();
        curl_setopt($chdcv,CURLOPT_URL, $this->dcv->httpUrl);
        curl_setopt($chdcv,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chdcv,CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($chdcv,CURLOPT_HEADER, true);
             
        $result = curl_exec($chdcv);
        curl_close($chdcv);
        if (strpos($result, '301 Moved') !== false){
            die("\nDCV File has a Redirect\n");
        } elseif(strpos($result, '404') !== false){
            die("\nDCV File Not Found");
        } elseif(strpos($result, '403') !== false){
            die("\nDCV File Permission Denied");
        } elseif(strpos($result, '200') !== false){
            echo"\nDCV File Exists w/o Redirect\n";
        }

        //return explode("\n", $result);
        return $result;
    }

    function mkDcvDir(){
        if (!is_dir($this->dcv->dir)) {
            if (!mkdir($this->dcv->dir, 0755, true)) {
                die('\nFailed to create folders...\n');
            }
            echo "\nDCV Path Created : " . $this->dcv->dir . "\n";
        } else {
            echo "\nDCV Path Already Exists : " . $this->dcv->dir . "\n";
        }
    }

    // loop through each element in the $argv array
    public function getInputVars() {

        $val = getopt("d:e::l::s::c::o::u::t");
        //var_dump($val);
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
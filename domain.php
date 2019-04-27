<?php
require "whm.php";
require "Comodo.php";
require "Validator.php";

class Dom
{
    public function __construct() {

        $val                                = getopt("d:e::l::s::c::o::u::t");

        $this->whm1                         = new WHM;
    
        $this->csrInputData                 = new \stdClass();
        $this->csrInputData->domainName     = false;
        $this->csrInputData->emailAdd       = false;
        $this->csrInputData->locality       = false;
        $this->csrInputData->state          = false;
        $this->csrInputData->country        = false;
        $this->csrInputData->org            = false;
        $this->csrInputData->unit           = false;

        $this->csrInputData->domainName     = $val["d"];
        $this->csrInputData->emailAdd       = $val["e"];
        $this->csrInputData->locality       = $val["l"];
        $this->csrInputData->state          = $val["s"];
        $this->csrInputData->country        = $val["c"];
        $this->csrInputData->org            = $val["o"];
        $this->csrInputData->unit           = $val["u"];

        $this->testing                      = new \stdClass();
        $this->testing->testMode            = false;
        $this->testing->path                = '/root/gitprojects/sslinstallphp/test';

        if (array_key_exists("t", $val)) {
            $this->testing->testMode        = true;
        } else {
            $this->testing->testMode        = false;
        }
        
        //$this->getInputVars();
        //print_r($this->csrInputData);
        
        $this->com                          = new Comodo;

        if ($this->testing->testMode == true){

            $this->csrInputData             = json_decode(file_get_contents($this->testing->path . "/testCsrInputData.json"));
            $this->domainData               = json_decode(file_get_contents($this->testing->path . "/testDomainData.json"));
            $this->csrData                  = json_decode(file_get_contents($this->testing->path . "/testCsrData.json"));
            $this->csrHashes                = json_decode(file_get_contents($this->testing->path . "/testCsrHashes.json"));

            //print_r($this->csrInputData);
            //print_r($this->domainData);
            //print_r($this->csrData);        
            //print_r($this->csrHashes);
            
        } else{

            
            $this->domainData               = $this->whm1->getDomainData($this->csrInputData->domainName);
            $this->csrData                  = $this->whm1->getCsrData($this->csrInputData);
            $this->csrHashes                = $this->com->getCsrHashes($this->csrData->data->csr);
        }

        $this->dcv                          = new \stdClass();
        $this->dcv->subdir                  = "/.well-known/pki-validation";
        $this->dcv->dir                     = $this->domainData->data->userdata->documentroot . $this->dcv->subdir;
        $this->dcv->fileName                = "/" . $this->csrHashes->md5 . ".txt";
        $this->dcv->filePath                = $this->dcv->dir . $this->dcv->fileName;
        $this->dcv->url                     = $this->csrInputData->domainName . $this->dcv->subdir . $this->dcv->fileName;
        $this->dcv->httpUrl                 = "http://" . $this->dcv->url;
        $this->dcv->httpsUrl                = "https://" . $this->dcv->url;
        $this->dcv->dcvContent              = $this->csrHashes->sha256 . " comodoca.com\n" . strtoupper($this->com->args->uniqueValue);

        $this->mkDcvDir();

        file_put_contents($this->dcv->filePath,$this->dcv->dcvContent);

        $this->validator                    = new Validator($this->dcv->httpUrl, 
                                                            $this->dcv->httpsUrl, 
                                                            $this->dcv->dcvContent);

        if($this->validator->isValid) {
            echo "\nDCV Validator is True\n";
        } else {
            die("\nDCV Validator is False\n");
        }

        if ($this->testing->testMode == true){

            $this->sslOrder                  = json_decode(file_get_contents($this->testing->path . "/testSslOrder.json")); 
            $this->certificate               = json_decode(file_get_contents($this->testing->path . "/testCertificate.json"));

        } else {

            $this->sslOrder                  = $this->com->orderSsl($this->csrData->data->csr);        
            $this->certificate               = $this->com->collectSsl($this->sslOrder);
        }

        $this->whm1->sslInstall($this->csrInputData->domainName, $this->csrData->data->key, $this->certificate);
        $this->certificate->fingerprint = $this->getFingerPrint();
        $this->com->sslChecker($this->csrInputData->domainName, $this->certificate->fingerprint);
    }

    public function getFingerPrint() {
        $temp = tmpfile();
        fwrite($temp, $this->certificate->cert);
        $shellExecStr = "openssl x509 -noout -fingerprint -sha256 -inform pem -in " . stream_get_meta_data($temp)['uri'];
        $output = shell_exec($shellExecStr);
        fclose($temp);
        //print_r($output);
        $jsonoutput = json_decode($output);
        $output = str_replace(":", "", $output);
        $output = ltrim($output, "SHA256 Fingerprint=");
        //$this->isValidApiCall($jsonoutput->metadata);
        return $output;
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

}
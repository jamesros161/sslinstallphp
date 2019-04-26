<?php
class Validator
{
    public function __construct($httpUrl, $httpsUrl, $dcvContent) {
        $this->httpUrl      = $httpUrl;
        $this->httpsUrl     = $httpsUrl;
        $this->dcvContent   = $dcvContent;
        $this->httpResult   = $this->curlDcv($this->httpUrl);
        $this->isValid      = $this->validateDcv();
        $this->httpsResult  = false;
    
    }

    function curlDcv($url) {
        $chdcv = curl_init();
        curl_setopt($chdcv,CURLOPT_URL, $url);
        curl_setopt($chdcv,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chdcv,CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($chdcv,CURLOPT_HEADER, true);
             
        $result = curl_exec($chdcv);
        curl_close($chdcv);
        return $result;
    }

    function validateDcv() {
              
        if (strpos($this->httpResult, '301 Moved') !== false){
            echo "\nDCV File has a Redirect\n";
            $this->httpsResult = $this->curlDcv($this->httpsUrl);
            if ($this->curlResultChecks($this->httpsResult)){
                echo "\nwith HTTPS redirect\n";
                return true;
            }
        } else if ($this->curlResultChecks($this->httpResult)){
            return true;
        } 

    }

    function curlResultChecks($result) {
        
        if(strpos($result, '200') !== false){
            if(strpos($result, $this->dcvContent) !== false) {
                echo "\nDCV Validation Passed\n";
                return true;
            } else {
                die("\nbut DCV File Contentes do not match CSR Hashes\n");
            }
        }

        if(strpos($result, '404') !== false){
            die("\nDCV File Not Found\n");
        } 
        
        if(strpos($result, '403') !== false){
            die("\nDCV File Permission Denied\n");

        } else {
            die("\nDCV File Verification Failed\n");
        } 
    }

}

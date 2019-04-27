<?php
class WHM
{
    public function getDomainData($domainName) {

        $argument           = "domain=" . $domainName;
        $domainuserdata     = $this->call("domainuserdata", $argument);
        
        return $domainuserdata;
    }

    public function getCsrData($csrInputData) {

        $argument = ' domains='               . urlencode($csrInputData->domainName)
                .   ' emailAddress='          . urlencode($csrInputData->emailAdd)
                .   ' countryName='           . urlencode($csrInputData->country)
                .   ' stateOrProvinceName='   . urlencode($csrInputData->state)
                .   ' localityName='          . urlencode($csrInputData->locality)
                .   ' organizationName='      . urlencode($csrInputData->org)
                .   ' unitName='              . urlencode($csrInputData->unit)
                .   ' keysize='               . 2048
                .   ' skip_certificate='      . 1;
        
        $csrData            = $this->call("generatessl", $argument);
        
        return $csrData;
    }

    public function sslInstall($domain, $key, $certificate) {

        $argument = 'domain='                . urlencode($domain)
                .   ' crt='                  . urlencode($certificate->cert)
                .   ' key='                  . urlencode($key)   
                .   ' cab='                  . urlencode($certificate->caCert);

        $sslInstall         = $this->call("installssl", $argument);

        echo "\nSSL Certificate Installed Successfully\n";
        
        return $sslInstall;
    }

    function call($whmCommand, $whmParams) {
        $shellExecStr = "whmapi1 " 
                . $whmCommand 
                . " " 
                . $whmParams 
                . " --output=json";

        $output             = shell_exec($shellExecStr);
        $jsonoutput         = json_decode($output);
        
        $this->isValidApiCall($jsonoutput->metadata);
        
        return $jsonoutput;
    }

    function isValidAPiCall($resultMetadata) {

        if ($resultMetadata->result == 0) {

            echo "\nWHMAPI Call Failed\n";
            echo "\t" . $resultMetadata->reason . "\n";
            die("\nWHMAPI Failure\n");
        }

        if ($resultMetadata->result == 1) {

            return true;
        }
    }

}
<?php

class WHM

{
    public function getDomainData($domainName) {
        $argument = "domain=" . $domainName;
        $domainuserdata = $this->call("domainuserdata", $argument);
        return $domainuserdata;
    }

    public function getCsrData($csrInputData) {
        $argument = 'domains='                . urlencode($csrInputData->domainName)
                .   ' emailAddress='          . urlencode($csrInputData->emailAdd)
                .   ' countryName='           . urlencode($csrInputData->country)
                .   ' stateOrProvinceName='   . urlencode($csrInputData->state)
                .   ' localityName='          . urlencode($csrInputData->locality)
                .   ' organizationName='      . urlencode($csrInputData->org)
                .   ' unitName='              . urlencode($csrInputData->unit)
                .   ' keysize='               . 2048
                .   ' skip_certificate='      . 1;
        
        //print_r($argument);
        $csrData = $this->call("generatessl", $argument);
        return $csrData;
    }

    public function sslInstall($domain, $certificate) {
        $argument = 'domain='               . urlencode($domain)
                .   ' crt='                  . urlencode($certificate->cert)
                .   ' cab='                  . urlencode($csrInputData->caCert);
        print_r($argument);
        $sslInstall = $this->call("installssl", $argument);
        print_r($sslInstall);
        return $sslInstall;
    }

    public function call($whmCommand, $whmParams) {
        $shellExecStr = "whmapi1 " . $whmCommand . " " . $whmParams . " --output=json";
        $output = shell_exec($shellExecStr);
        $jsonoutput = json_decode($output);
        //print_r($jsonoutput);
        return $jsonoutput;
    }

}
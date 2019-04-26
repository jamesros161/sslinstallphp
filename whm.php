<?php

class WHM

{
    public function testEcho($domainData, $csrData, $sslCert) {
        echo "DomainData : " . $domainData . "\n";
        echo "csrData : " . $csrData . "\n";
        echo "sslCert : " . $sslCert . "\n";
    }

    public function getDomainData($domainName) {
        $argument = "domain=" . $domainName;
        $domainuserdata = $this->call("domainuserdata", $argument);
        return $domainuserdata;
    }

    public function getCsrData($csrInputData) {
        print_r($csrInputData);
        $argument = 'domain='                 . urlencode($csrInputData->domainName)
                .   ' emailAddress='          . urlencode($csrInputData->emailAdd)
                .   ' countryName='           . urlencode($csrInputData->locality)
                .   ' stateOrProvinceName='   . urlencode($csrInputData->state)
                .   ' localityName='          . urlencode($csrInputData->country)
                .   ' organizationName='      . urlencode($csrInputData->org)
                .   ' unitName='              . urlencode($csrInputData->unit)
                .   ' keysize='               . 2048
                .   ' skip_certificate='      . 1;
           
        print_r($argument);
        $domainuserdata = $this->call("generatessl", $argument);
        return $domainuserdata;
    }

    public function call($whmCommand, $whmParams) {
        $shellExecStr = "whmapi1 " . $whmCommand . " " . $whmParams . " --output=json";
        $output = shell_exec($shellExecStr);
        $jsonoutput = json_decode($output);
        return $jsonoutput;
    }

}
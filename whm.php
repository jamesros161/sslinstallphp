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
        $argument = 'domain='                 . curl_escape($csrInputData->domainName)
                .   ' emailAddress='          . curl_escape($csrInputData->emailAdd)
                .   ' countryName='           . curl_escape($csrInputData->locality)
                .   ' stateOrProvinceName='   . curl_escape($csrInputData->state)
                .   ' localityName='          . curl_escape($csrInputData->country)
                .   ' organizationName='      . curl_escape($csrInputData->org)
                .   ' unitName='              . curl_escape($csrInputData->unit)
                .   ' keysize='               . 2048
                .   ' skip_certificate='      . 1;

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
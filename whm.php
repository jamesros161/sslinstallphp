<?php

class WHM

{
    public function getDomainData($domainName) {
        $argument = "domain=" . $domainName;
        $domainuserdata = $this->call("domainuserdata", $argument);
        return $domainuserdata;
    }

    public function getCsrData($csrInputData) {
        $argument = 'domains='                 . urlencode($csrInputData->domainName)
                .   ' emailAddress='          . urlencode($csrInputData->emailAdd)
                .   ' countryName='           . urlencode($csrInputData->country)
                .   ' stateOrProvinceName='   . urlencode($csrInputData->state)
                .   ' localityName='          . urlencode($csrInputData->locality)
                .   ' organizationName='      . urlencode($csrInputData->org)
                .   ' unitName='              . urlencode($csrInputData->unit)
                .   ' keysize='               . 2048
                .   ' skip_certificate='      . 1;
           
        $csrData = $this->call("generatessl", $argument);
        print_r($csrData);
        return $csrData;
    }

    public function call($whmCommand, $whmParams) {
        $shellExecStr = "whmapi1 " . $whmCommand . " " . $whmParams . " --output=json";
        $output = shell_exec($shellExecStr);
        $jsonoutput = json_decode($output);
        return $jsonoutput;
    }

}
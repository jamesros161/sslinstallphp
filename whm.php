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

    public function call($whmCommand, $whmParams) {
        $shellExecStr = "whmapi1 " . $whmCommand . " " . $whmParams . " --output=json";
        $output = shell_exec($shellExecStr);
        $jsonoutput = json_decode($output);
        return $jsonoutput;
    }

}
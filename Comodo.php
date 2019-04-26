<?php

class Comodo
{
    public function __construct(){
        $this->urls                         = new \stdClass();
        $this->urls->decode                 = 'https://secure.comodo.net/products/%21DecodeCSR';
        $this->urls->autoApplySsl           = 'https://secure.comodo.net/products/!AutoApplySSL';
        $this->urls->collectSsl             = 'https://secure.comodo.net/products/download/CollectSSL';

        $this->headers                      = new \stdClass();
        $this->headers->contentType         = 'Content-Type="application/x-www-form-urlencoded"';
        $this->headers->userAgent           = '"User-Agent=Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0"';

        $this->args                         = new \stdClass();
        $this->args->showCSR                = "N";
        $this->args->showErrorCodes         = "N";
        $this->args->showErrorMessages      = "Y";
        $this->args->showFieldNames         = "N";
        $this->args->showEmptyFields        = "N";
        $this->args->showCN                 = "N";
        $this->args->showAddress            = "N";
        $this->args->showCSRHashes2         = "Y";
        $this->args->product                = "488";
        $this->args->years                  = "1";
        $this->args->serverSoftware         = "22";
        $this->args->uniqueValue            = $this->randomString();
        $this->args->dcvMethod              = "HTTP_CSR_HASH";
        $this->args->isCustomerValidated    = "Y";
        $this->args->test                   = "Y";

        $this->creds = new \stdClass();
        $this->creds->path                  = '/opt/dedrads/sslinstall';
        $this->creds->filename              = '/comodocreds.json';
        $strJsonFileContents                = file_get_contents($this->creds->path . $this->creds->filename);
        $this->credentials                  = json_decode($strJsonFileContents);
    }

    public function randomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getCsrHashes($csr){
        
        $argsArray = array(
            "loginName"         =>  $this->credentials->loginName,
            "loginPassword"     =>  $this->credentials->loginPassword,
            "showCSR"           =>  $this->args->showCSR,
            "showErrorCodes"    =>  $this->args->showErrorCodes,
            "showErrorMessages" =>  $this->args->showErrorMessages,
            "showFieldNames"    =>  $this->args->showFieldNames,
            "showEmptyFields"   =>  $this->args->showEmptyFields,
            "showCN"            =>  $this->args->showCN,
            "showAddress"       =>  $this->args->showAddress,
            "showCSRHashes2"    =>  $this->args->showCSRHashes2,
            "product"           =>  $this->args->product,
            "csr"               =>  $csr );
        $argsQuery = http_build_query($argsArray);
        $callResult = $this->call([$this->urls->decode, $argsQuery, count($argsArray)]);
        print_r($callResult);
        $csrHashes = new \stdClass();
        $csrHashes->md5     = ltrim($callResult[1], "md5=");
        $csrHashes->sha256  = ltrim($callResult[3], "sha256=");
        return $csrHashes;
    }
    public function call($argsArray) {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $argsArray[0]);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $argsArray[1]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        return explode("\n", $result);
    }
}
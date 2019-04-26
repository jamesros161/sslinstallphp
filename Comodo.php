<?php

class Comodo
{
    public function __construct(){

        $this->urls->decode                 = 'https://secure.comodo.net/products/%21DecodeCSR';
        $this->urls->autoApplySsl           = 'https://secure.comodo.net/products/!AutoApplySSL';
        $this->urls->collectSsl             = 'https://secure.comodo.net/products/download/CollectSSL';

        $this->headers->contentType         = 'Content-Type="application/x-www-form-urlencoded"';
        $this->headers->userAgent           = '"User-Agent=Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0"';

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

        $this->uniqueValue                  = $this->randomString();

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

    public function call($url, $fields, )

}
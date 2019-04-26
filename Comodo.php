<?php

class Comodo
{
    public function __construct(){

        $this->urls->decode = 'https://secure.comodo.net/products/%21DecodeCSR';
        $this->urls->autoApplySsl = 'https://secure.comodo.net/products/!AutoApplySSL';
        $this->urls->collectSsl = 'https://secure.comodo.net/products/download/CollectSSL';

        $this->creds->path = '/opt/dedrads/sslinstall';
        $this->creds->filename = '/comodocreds.json';
        $strJsonFileContents = file_get_contents($this->creds->path . $this->creds->filename);
        $this->credentials = json_decode($strJsonFileContents);
    }
}
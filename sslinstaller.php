#!/usr/bin/php
<?php

    require "domain.php";
    
    $domain = new Dom;
    $domain->getInputVars();
    $domain->getDomainData();
?>
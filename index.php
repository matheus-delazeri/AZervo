<?php

use App\AZervo;

require_once('vendor/autoload.php');
session_start();

AZervo::getModel('core')->connect();
if(defined('STDIN') && $argv[1] == "config") {
    AZervo::getModel("core")->createTables();
} else {
    AZervo::runActionByUrl();
}

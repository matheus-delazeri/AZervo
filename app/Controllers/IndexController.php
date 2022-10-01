<?php

namespace App\Controllers;

use App\AZervo;

class IndexController
{
    public function indexAction()
    {
        AZervo::loadView();
    }
}
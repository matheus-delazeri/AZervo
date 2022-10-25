<?php

namespace App\Controllers;

use App\AZervo;

class AboutController
{
    public function indexAction()
    {
        AZervo::loadView('about');
    }
}

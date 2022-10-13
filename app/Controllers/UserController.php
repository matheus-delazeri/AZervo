<?php

namespace App\Controllers;

use App\AZervo;

class UserController
{
    public function accountAction()
    {
        if (isset($_SESSION["user_id"])) {
            AZervo::loadView("user/myAccount");
        } else {
            AZervo::loadView("user/login");
        }
    }

    public function loginAction()
    {
        echo (int)AZervo::getModel("user")->login();
    }

    public function registerAction()
    {
        AZervo::loadView("user/register");
    }

    public function createAction()
    {
        echo (int)AZervo::getModel("user")->register();
    }

    public function logoutAction()
    {
        AZervo::getModel("user")->logout();
        AZervo::redirect();
    }
}
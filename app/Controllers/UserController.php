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

    public function saveDocumentAction()
    {
        $_document = json_decode($_POST['document'], true);
        AZervo::getModel("user")->saveDocument($_document);
    }

    public function unsaveDocumentAction()
    {
        $_id = $_POST['id'];
        AZervo::getModel("user")->unsaveDocument($_id);
    }
}
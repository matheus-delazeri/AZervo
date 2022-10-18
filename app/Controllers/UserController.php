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
        $_doi = $_POST['doi'];
        $_links = explode(";", $_POST['links']);
        AZervo::getModel("user")->saveDocument($_doi, $_links);
    }

    public function unsaveDocumentAction()
    {
        $_id = $_POST['id'];
        AZervo::getModel("user")->unsaveDocument($_id);
    }
}
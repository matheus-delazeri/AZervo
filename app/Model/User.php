<?php

namespace App\Model;

use App\AZervo;

class User extends Core
{
    public function login($credentials = array())
    {
        if (empty($credentials)) {
            if ($_POST['email'] != "" && $_POST['password'] != "") {
                $email = $_POST['email'];
                $password = $_POST['password'];
            } else {
                AZervo::addError("Digite o e-mail e a senha do usuário para realizar login!");
                return false;
            }
        } else {
            $email = $credentials['email'];
            $password = $credentials['password'];
        }

        if ($user = $this->getRegisterByUniqueField('users', 'email', $email)) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                AZervo::addSuccess("Seja bem vindo, {$user['name']}");
                return $user['id'];
            }
            AZervo::addError("Senha inválida! Tente novamente...");
            return false;
        }

        AZervo::addError("O e-mail digitado não pertence a nenhuma conta.");
        return false;
    }

    public function register()
    {
        if ($_POST['email']!="" && $_POST['password']!="" && $_POST['name']!="") {
            if (!$this->getRegisterByUniqueField('users', 'email', $_POST['email'])) {
                $data = array(
                    "email" => $_POST['email'],
                    "password_hash" => $this->hashPassword($_POST['password']),
                    "name" => $_POST['name']
                );
                if($this->addNewRegister("users", $data)) {
                    return $this->login(array(
                        'email' => $data['email'],
                        'password' => $_POST['password']
                    ));
                }
            }
            AZervo::addError("Já há uma conta registrada com este e-mail!");
            return false;
        }

        AZervo::addError("Preencha todos os campos para realizar o cadastro!");
        return false;
    }

    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function load($id)
    {
        return $this->getRegisterById('users', $id);
    }

    public function logout()
    {
        AZervo::addSuccess("Até mais tarde!");
        unset($_SESSION['user_id']);
    }
}

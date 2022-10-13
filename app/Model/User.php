<?php

namespace App\Model;

class User extends Core
{
    public function login($credentials = array())
    {
        if (empty($credentials)) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
            } else {
                return false;
            }
        } else {
            $email = $credentials['email'];
            $password = $credentials['password'];
        }

        if ($user = $this->getRegisterByUniqueField('users', 'email', $email)) {
            if ($user["password"] == $password) {
                $_SESSION['user_id'] = $user['id'];
                return $user['id'];
            }
        }

        return false;
    }

    public function register()
    {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {
            if (!$this->getRegisterByUniqueField('users', 'email', $_POST['email'])) {
                $data = array(
                    "email" => $_POST['email'],
                    "password" => $_POST['password'],
                    "name" => $_POST['name']
                );
                if($this->addNewRegister("users", $data)) {
                    return $this->login(array(
                        'email' => $data['email'],
                        'password' => $data['password']
                    ));
                }
            }
        }

        return false;
    }

    public function load($id)
    {
        return $this->getRegisterById('users', $id);
    }

    public function logout()
    {
        session_unset();
    }


}

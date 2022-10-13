<?php

namespace App;

class AZervo
{
    const PATH_SEPARATOR = "_";
    const BASE_URL_LIMIT = "azervo/";

    static public function getModel($path)
    {
        $modelPrefix = "App\Model\\";
        $pathArr = explode(self::PATH_SEPARATOR, $path);
        $pathArr = array_map('ucfirst', $pathArr);
        $namespace = $modelPrefix . implode("\\", $pathArr);

        return new $namespace;
    }

    static public function redirect($controllerPath = 'index', $action = 'index')
    {
        header("Location:" . self::getUrl($controllerPath, $action));
        exit();
    }

    static public function loadView($path = "index", $extension = "phtml")
    {
        self::loadBlock("header");
        require_once("View/$path.$extension");
        self::loadBlock("footer");
    }

    static public function loadBlock($path, $extension = "phtml")
    {
        require_once("View/Block/$path.$extension");
    }

    static public function getUrl($controllerPath, $action = "index")
    {
        $controllerPath = $controllerPath == "index" ? "" : $controllerPath . "/";
        $action = $action == "index" ? "" : $action . "/";
        return self::getBaseUrl() . $controllerPath . $action;
    }

    static public function getSkinUrl($path)
    {
        return self::getBaseUrl() . "skin/" . $path;
    }

    static public function getBaseDir()
    {
        return __DIR__;
    }

    static public function runActionByUrl()
    {
        $baseUrl = self::getBaseUrl();
        $requestString = substr(self::getCurrentUrl(), strlen($baseUrl));

        $urlParams = explode('/', $requestString);

        $controllerPrefix = "App\Controllers\\";
        $controllerPath = explode(self::PATH_SEPARATOR, array_shift($urlParams));
        $controllerPath = !$controllerPath[0] ? array("Index") : $controllerPath;
        $controllerPath = array_map('ucfirst', $controllerPath);
        $namespace = $controllerPrefix . implode("\\", $controllerPath) . "Controller";

        $actionName = ucfirst(array_shift($urlParams));
        $actionName = $actionName == "" ? "IndexAction" : $actionName . "Action";

        $controller = new $namespace;
        $controller->$actionName();
    }

    static public function getProtocol()
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        return $isSecure ? 'https://' : 'http://';
    }

    static public function getCurrentUrl()
    {
        return self::getProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    static public function getBaseUrl()
    {
        $requestUrl = self::getCurrentUrl();
        if (strpos($requestUrl, self::BASE_URL_LIMIT) !== false) {
            $url = substr($requestUrl, 0, strpos($requestUrl, self::BASE_URL_LIMIT)) . self::BASE_URL_LIMIT;
        } else {
            $url = self::getProtocol() . $_SERVER['HTTP_HOST'] . '/';
        }

        return $url;
    }

}


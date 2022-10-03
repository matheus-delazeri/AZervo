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

    static public function loadView($path = "index", $extension = "phtml")
    {
        return require_once("View/$path.$extension");
    }

    static public function loadBlock($path, $extension = "phtml")
    {
        return self::loadView("Block/$path", $extension);
    }

    static public function getUrl($controllerPath, $action = "index")
    {
        $controllerPath = $controllerPath == "index" ? "" : $controllerPath . "/";
        $action = $action == "index" ? "" : $action;
        return self::getBaseUrl() . $controllerPath . $action;
    }

    static public function getSkinUrl($path)
    {
        return self::getBaseUrl() . "skin/" . $path;
    }

    public function runActionByUrl()
    {
        $baseUrl = $this->getBaseUrl();
        $requestString = substr($this->getCurrentUrl(), strlen($baseUrl));

        $urlParams = explode('/', $requestString);

        $controllerPrefix = "App\Controllers\\";
        $controllerPath = explode(self::PATH_SEPARATOR, array_shift($urlParams));
        $controllerPath = !$controllerPath[0] ? array("Index") : $controllerPath;
        $controllerPath = array_map('ucfirst', $controllerPath);
        $namespace = $controllerPrefix . implode("\\", $controllerPath)."Controller";

        $actionName = ucfirst(array_shift($urlParams));
        $actionName = $actionName == "" ? "IndexAction" : $actionName."Action";

        $controller = new $namespace;
        $controller->$actionName();
    }

    static public function getCurrentUrl()
    {
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    static public function getBaseUrl()
    {
        $requestUrl = self::getCurrentUrl();
        $url = substr($requestUrl, 0, strpos($requestUrl, self::BASE_URL_LIMIT)).self::BASE_URL_LIMIT;

        return $url;
    }

}
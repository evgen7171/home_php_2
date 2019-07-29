<?php
/**
 * Created by PhpStorm.
 * User: Админ
 * Date: 28.07.2019
 * Time: 22:18
 */

namespace App\services;


class Request
{
    private $requestString;
    private $controllerName;
    private $actionName;
    private $id;
    private $params;

    public function __construct()
    {
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->parseRequest();
    }

    private function parseRequest()
    {
        $pattern = "#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?[?]?(?P<params>.*)#ui";
        if (preg_match_all($pattern, $this->requestString, $matches)) {
            $this->controllerName = $matches['controller'][0];
            $this->actionName = $matches['action'][0];

            $this->params = [
                'get' => $_GET,
                'post' => $_POST
            ];

            $this->id = (int)$_GET['id'];
        }
    }

    public function get($param = '')
    {
        $params = $this->params['get'];
        if ($param == '') {
            return $params;
        }
        foreach ($params as $key => $value) {
            if ($key == $param) {
                return $value;
            }
        }
        return null;
    }

    public function post($param = [])
    {
        $params = $this->params['post'];
        if ($param == '') {
            return $params;
        }
        foreach ($params as $key => $value) {
            if ($key == $param) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getRequestString()
    {
        return $this->requestString;
    }

    /**
     * @param mixed $requestString
     */
    public function setRequestString($requestString)
    {
        $this->requestString = $requestString;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @param mixed $controllerName
     */
    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param mixed $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

}
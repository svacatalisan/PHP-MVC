<?php

class Router implements RouterInterface {

    //properties declaration
    private $schema = null;
    private $controllerName = null;
    private $actionName = null;
    private $params = array();
    private $requestType = null;

    //constructors
    public function __construct() {
        $this->createRoute();
    }

    /**
     * this function manipulates an url that has the following pattern:
     * http(s)://www.index.com?controller="controllerName"&action="actionName"&
     * param1="param1"&...&paramN="paramN"
     */
    private function createRoute() {
        global $config;
        $method = $_SERVER['REQUEST_METHOD'];  //get the request method (get/post)
        $this->setRequestType($method);        //set the request method 
        (isset($_SERVER['HTTPS'])) ? $this->setSchema('https') : $this->setSchema('http');

        if ($method == 'GET') {
            $params = array();
            foreach ($_GET as $key => $value) {
                switch ($key) {
                    case 'schema':
                        break;
                    case 'controller':
                        $this->setControllerName($value);
                        break;
                    case 'action':
                        $this->setActionName($value);
                        break;
                    case 'requestType':
                        break;
                    default:
                        $params[$key] = $value;
                }
            }
            $this->setParams($params);

            if (!isset($_GET['controller'])) {
                $this->setControllerName($config['defaultController']);
            }
            if (!isset($_GET['action'])) {
                $this->setActionName($config['defaultAction']);
            }
        } else if ($method == 'POST') {
            $params = array();
            foreach ($_POST as $key => $value) {
                if (($key != 'controller') & ($key != 'action') & ($key != 'schema') & ($key != 'method')) {
                    $params[$key] = $value;
                }
            }
            $this->setParams($_POST['params']);
        }
    }

    //setters and getters for each propriety
    public function setSchema($schema) {
        $this->schema = $schema;
    }

    public function getSchema() {
        if (isset($this->schema)) {
            return $this->schema;
        }
        return null;
    }

    public function setControllerName($name) {
        $this->controllerName = $name;
    }

    public function getControllerName() {
        if (isset($this->controllerName)) {
            return $this->controllerName;
        }
        return null;
    }

    public function setActionName($name) {
        $this->actionName = $name;
    }

    public function getActionName() {
        if (isset($this->actionName)) {
            return $this->actionName;
        }
        return null;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        if (isset($this->params)) {
            return $this->params;
        }
        return null;
    }

    public function setRequestType($request) {
        $this->requestType = $request;
    }

    public function getRequestType() {
        if (isset($this->requestType)) {
            return $this->requestType;
        }
        return null;
    }

}

?>

<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * Incoming request control.
 * 
 */
class Request {

    /**
     * @var string root path of the site
     */
    public $root_uri;

    /**
     * @var string get, post etc
     */
    public $request_method;

    /**
     * @var string current url
     */
    public $uri;

    /**
     * @var string get params
     */
    public $params;

    /**
     * @var string post params or body
     */
    public $post;

    /**
     * @var string class name of current controller
     */
    public $controller;

    /**
     * @var string name of method of current controller
     */
    public $method;

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct($root_uri = '/')
    {
        // base path of current site
        $this->root_uri = substr($root_uri, -1) === '/'? $root_uri : $root_uri . '/';

        // request method
        $this->request_method = $_SERVER['REQUEST_METHOD'];

        // request uri and query
        $this->uri = $_SERVER['REQUEST_URI'];
        $parameters = parse_url($this->uri);
        if (isset($parameters['query']))
            parse_str($parameters['query'], $query);
        else
            $query = '';
        $this->uri = $parameters['path'];
        $this->params = $query;

        // sub site root from uri
        $rootlen = strlen($this->root_uri);
        if ($rootlen > 1 && substr_compare($this->uri, $this->root_uri, 0, $rootlen) === 0)
            $this->uri = substr($this->uri, $rootlen - 1);
        
        // determine post body based on request header 'Content-Type'
        $headers = getallheaders();
        if (isset($headers['Content-Type'])) {
            $postData = file_get_contents('php://input');

            if ($headers['Content-Type'] == 'application/json') {
                $this->post = json_decode($postData, true);

            } elseif ($headers['Content-Type'] == 'application/x-www-form-urlencoded' || $headers['Content-Type'] == 'multipart/form-data') {
                $this->post = parse_str($postData);

            } else {
                $this->post = $_POST;
            }
            
        } else {
            $this->post = $_POST;
        }
    }
    
    /**
     * 
     * @param string name of parameter
     * 
     * @return integer incoming parameter by name.
     */
    public function getIntParam($name)
    {
        if (!empty($this->params[$name])) {
            return (int) $this->params[$name];
        } else {
            throw new \Exception('Params '.$name.' not found');
        }
    }

    /**
     * 
     * Set a controller class and method for current request.
     * 
     * @param array [string,string] a controller class and method names
     */
    public function setRoute($route)
    {
        if (empty($route[0])) {
            throw new \Exception('Not found controller');
        }
        $this->controller = 'My\Controllers\\'.$route[0];
        if (empty($route[1])) {
            throw new \Exception('Not found method controller');
        }
        $this->method = $route[1];
    }
}

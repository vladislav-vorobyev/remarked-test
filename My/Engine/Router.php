<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * An object to determine an executor for incoming request.
 * 
 */
class Router {

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array A collection of route-matching rules to iterate through.
     */
    private $map = ['GET' => [], 'POST' => []];

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->request = Storage::get('Request');
    }

    /**
     * 
     * Set a GET route.
     * 
     * @param string site uri
     * @param string controller and method names
     */
    public function get($path, $params)
    {
        $this->map['GET'][$path] = $params;
    }

    /**
     * 
     * Set a POST route.
     * 
     * @param string site uri
     * @param string controller and method names
     */
    public function post($path, $params)
    {
        $this->map['POST'][$path] = $params;
    }

    /**
     * 
     * Determine an executor for incumming request.
     * 
     * @return Request current request object.
     */
    public function getCurrent()
    {
        // Get routes map for current request method
        if (empty($this->map[$this->request->request_method])) {
            throw new \Exception('Not found method');
        }
        $current_map = $this->map[$this->request->request_method];

        // Determine a controller and assign it
        $current_route = empty($current_map[$this->request->uri])? $current_map['/404'] : $current_map[$this->request->uri];
        $this->request->setRoute($current_route);

        // Return request object
        return $this->request;
    }
}

<?php
/**
 * 
 * This file is part of remarked-test API project.
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
    private $map = [];

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->request = Storage::get('Request');

        // fill default 404 route
        $this->map['/404'] = [];
        $this->map['/404']['GET'] = ['SystemController', 'notFound'];
    }

    /**
     * 
     * Set a route.
     * 
     * @param string request method
     * @param string site uri
     * @param string controller and method names
     */
    public function setRoute($method, $path, $params)
    {
        if (!isset($this->map[$path])) $this->map[$path] = [];
        $this->map[$path][$method] = $params;
    }

    /**
     * 
     * Determine an executor for incumming request.
     * 
     * @return Request current request object.
     */
    public function getCurrent()
    {
        // Get current request method and path
        $method = $this->request->request_method;
        $path = $this->request->uri;

        // Determine a route from map
        if (empty($this->map[$path])) {
            $path = '/404';
        }
        if (empty($this->map[$path][$method])) {
            $current_route = $this->map['/404']['GET'];
        } else {
            $current_route = $this->map[$path][$method];
        }

        // Assign determined route
        $this->request->setRoute($current_route);

        // Return request object
        return $this->request;
    }
}

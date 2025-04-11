<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * A main object to run the application.
 * 
 */
class App {

    /**
     * @var Router
     */
    private $router;

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct()
    {
        // Get this application router object
        $this->router = Storage::get('Router');
    }

    /**
     * 
     * To run the application.
     * 
     */
    public function run()
    {
        // Determine current request
        $current_request = $this->router->getCurrent();

        // Execute the request controller method
        $controller = new $current_request->controller;
        $response = $controller->{$current_request->method}();

        // Output the response
        $response->render();
    }
}

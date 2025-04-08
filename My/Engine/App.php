<?php
/**
 * 
 * This file is part of test1 API project.
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

        // Set not found handler
        $this->router->get('/404', ['SystemController', 'notFound']);
        $this->router->post('/404', ['SystemController', 'notFound']);
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

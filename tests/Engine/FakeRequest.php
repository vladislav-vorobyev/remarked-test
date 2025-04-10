<?php
namespace My\Engine;

/**
 * 
 * Fake request class for tests
 * 
 */
class FakeRequest extends Request {

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct($method = 'GET', $uri = '/', $body = '')
    {
        // request method
        $this->request_method = $method;

        // request uri and query
        $this->uri = $uri;
        $parameters = parse_url($this->uri);
        if (isset($parameters['query']))
            parse_str($parameters['query'], $query);
        else
            $query = '';
        $this->uri = $parameters['path'];
        $this->params = $query;

        $this->post = $body;
    }
}
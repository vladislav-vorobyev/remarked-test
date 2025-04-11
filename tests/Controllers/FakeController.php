<?php
namespace My\Controllers;

use My\Engine\Response;
use My\Engine\Storage;

/**
 * 
 * Controller to do tests.
 * 
 */
class FakeController {

    /**
     * @var Response
     */
    private $response;

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->response = Storage::get('Response');
    }

    /**
     * 
     * Symple handler.
     * 
     */
    public function foo()
    {
        return $this->response->json([
            'content' => 'foo'
        ], 200);
    }
}
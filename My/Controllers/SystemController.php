<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Controllers;

use My\Engine\Response;
use My\Engine\Storage;

/**
 * 
 * System controller.
 * 
 */
class SystemController {

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
     * Not found handler.
     * 
     */
    public function notFound()
    {
        return $this->response->json([
            'error' => 'not found'
        ], 404);
    }
}
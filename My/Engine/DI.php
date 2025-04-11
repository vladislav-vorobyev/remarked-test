<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */
namespace My\Engine;

/**
 * Dependency Injection module.
 */
class DI {
    
    /**
     * Run the initialization.
     * 
     * @param string root path of the site
     */
    static public function start($root_uri = '/')
    {
        Storage::set('Request', new Request($root_uri));
        Storage::set('Response', new Response());
        Storage::set('Router', new Router());
        Storage::set('App', new App());
    }
}

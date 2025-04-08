<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Engine;

/**
 * Dependency Injection module.
 */
class DI {
    
    /**
     * Run the initialization.
     */
    static public function start()
    {
        Storage::set('Request', new Request());
        Storage::set('Response', new Response());
        Storage::set('Router', new Router());
        Storage::set('App', new App());
    }
}

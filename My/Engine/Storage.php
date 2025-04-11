<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * Single object to collect all global objects.
 * 
 */
class Storage {

    /**
     * @var array storage of dependency
     */
    static private $map = [];

    /**
     * 
     * Get an object from storage.
     * 
     * @param string object name
     * 
     * @return Object an object from storage
     */
    static public function get($name)
    {
        return self::$map[$name];
    }

    /**
     * 
     * Put an object to storage.
     * 
     * @param string object name
     * @param Object an object to put
     */
    static public function set($name, $dependency)
    {
        self::$map[$name] = $dependency;
    }
}

<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * Abstruct class for all models.
 * 
 */
abstract class Model {

    /**
     * @var Object structured data
     */
    protected $data;

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->validate();
    }

    /**
     * 
     * Data structure verification.
     * 
     * @param array structure to check
     * @param mixed data to check
     * @param string name of data for message
     */
    public static function verify_structure($struct, $data, $data_name = 'data')
    {
        if (!is_array($data))
            throw new \Exception("Wrong structure, $data_name is not array.");

        foreach ($struct as $key => $value) {
            if (!isset($data[$key]))
                throw new \Exception("Wrong structure, $key is not found.");

            if (is_array($value))
                self::verify_structure($value, $data[$key], $key);

            switch ($value) {
                case 's':
                    if (!is_string($data[$key]))
                        throw new \Exception("Wrong structure, $key is not a string.");
                    break;

                case 'i':
                    if (!is_int($data[$key]))
                        throw new \Exception("Wrong structure, $key is not an integer.");
                    break;

                case 'b':
                    if (!is_bool($data[$key]))
                        throw new \Exception("Wrong structure, $key is not a boolean.");
                    break;
            }
        }
    }

    /**
     * 
     * Data verification.
     * 
     */
    abstract public function validate();
}

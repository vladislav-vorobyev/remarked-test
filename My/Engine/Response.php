<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */
namespace My\Engine;

/**
 * 
 * Output control.
 * 
 */
class Response {

    /**
     * @var int response code
     */
    public $code = 200;

    /**
     * @var string output content
     */
    public $content;

    /**
     * 
     * Output a data as json.
     * 
     * @param mixed data
     */
    public function json($data, $code = 200) {
        $this->code = $code;
        // $node = getenv('NODE');
        // $data = [
        //     'content' => $data,
        //     'node' => $node
        // ];
        $this->content = json_encode($data);
        return $this;
    }

    /**
     * 
     * Send the content to output.
     * 
     */
    public function render()
    {
        http_response_code($this->code);
        header('Content-Type: application/json');
        echo $this->content;
    }
}

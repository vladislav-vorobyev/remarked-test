<?php
/**
 * 
 * This file is part of test1 API project.
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
     * @var string output content
     */
    public $content;

    /**
     * 
     * Output a data as json.
     * 
     * @param mixed data
     */
    public function json($data) {
        // $node = getenv('NODE');
        $data = [
            'content' => $data,
            // 'node' => $node
        ];
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
        header('Content-Type: application/json');
        echo $this->content;
    }
}

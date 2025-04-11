<?php
namespace My\Controllers;

use PHPUnit\Framework\TestCase;
use My\Engine\Storage;
use My\Engine\Response;

class SystemControllerTest extends TestCase
{
    public function testNotFound() {
        Storage::set('Response', new Response());
        $controller = new SystemController();
        $response = $controller->notFound();
        $this->assertInstanceof('My\Engine\Response', $response);
        $this->assertEquals(404, $response->code);
        $content = json_decode($response->content);
        $this->assertNotEmpty($content->error);
    }
}

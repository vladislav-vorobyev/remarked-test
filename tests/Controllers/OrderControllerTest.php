<?php
namespace My\Controllers;

use PHPUnit\Framework\TestCase;
use My\Engine\Storage;
use My\Engine\Response;
use My\Engine\FakeRequest;

class OrderControllerTest extends TestCase
{
    public function testPriceCalc() {
        Storage::set('Response', new Response());
        Storage::set('Request', new FakeRequest('POST', '/api/v1/orders/price-calc/', [
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 10, "quantity" => 2]]
        ]));
        $controller = new OrderController();
        $response = $controller->price_calc();
        $this->assertInstanceof('My\Engine\Response', $response);
        $this->assertEquals(200, $response->code);
        $content = json_decode($response->content);
        $this->assertNotEmpty($content->price);
    }
}

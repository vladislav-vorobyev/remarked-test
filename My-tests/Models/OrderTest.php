<?php
namespace My\Models;

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use \DateTime;

class OrderTest extends TestCase
{
    /**
     * Print DateTime as date and time
     */
    protected function printDatetime(DateTime $dt)
    {
        return $dt->format('Y-m-d H:i');
    }

    /**
     * Print DateTime as date
     */
    protected function printDate(DateTime $dt)
    {
        return $dt->format('Y-m-d');
    }


    public function testSelfprintDatetime()
    {
        $datetime = "2025-01-01 01:01";
        $printed = $this->printDatetime( new DateTime($datetime) );
        $this->assertSame($datetime, $printed);
        $datetime = "2025-12-12 22:22";
        $printed = $this->printDatetime( new DateTime($datetime) );
        $this->assertSame($datetime, $printed);
    }

    public function testSelfprintDate()
    {
        $datetime = "2025-01-01";
        $printed = $this->printDate( new DateTime($datetime) );
        $this->assertSame($datetime, $printed);
        $datetime = "2025-12-12";
        $printed = $this->printDate( new DateTime($datetime) );
        $this->assertSame($datetime, $printed);
    }


    public function testWrongStruct1()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct2()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct3()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
        ]);
    }

    public function testWrongStruct4()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["quantity" => 20]]
        ]);
    }

    public function testWrongStruct5()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 5]]
        ]);
    }

    public function testWrongStruct6()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "wrong",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct7()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2000-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct8()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "wrong", "gender" => "F"],
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct9()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "A"],
            "basket" => [["price" => 5, "quantity" => 20]]
        ]);
    }

    public function testWrongStruct10()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => "5", "quantity" => 20]]
        ]);
    }

    public function testWrongStruct11()
    {
        $this->expectException('\Exception');
        $order = new Order([
            "datetime" => "2025-12-12 12:12",
            "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            "basket" => [["price" => 5, "quantity" => 20.0]]
        ]);
    }


    /**
     * No discount
     */
    public function testNoDiscount()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-10 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 10, "quantity" => 9],
                ["price" => 5, "quantity" => 2]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertEquals($price, 100);
    }

    /**
     * Discount 5% for male age
     */
    public function testAgeMDiscount()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-63 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertEquals($price, 95);
    }

    /**
     * Discount 5% for female age
     */
    public function testAgeFDiscount()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-58 years') ),
                "gender" => "F"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertEquals($price, 95);
    }

    /**
     * Discount 4% for order date
     */
    public function testOrderDateDiscount()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+7 day 1 minute') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-10 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertEquals($price, 96);
    }

    /**
     * Discount 3% for quantity
     */
    public function testQuantityDiscount()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-10 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 10, "quantity" => 10],
                ["price" => 5, "quantity" => 20]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertEquals($price, 194);
    }

    /**
     * Discount 3% + 4% + 5%
     */
    public function testAllDiscounts()
    {
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+7 day 1 minute') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-63 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 10, "quantity" => 10],
                ["price" => 5, "quantity" => 20]
            ]
        ]);
        $price = $order->price_calc();
        $this->assertSame($price, 176.93);
    }
}

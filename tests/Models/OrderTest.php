<?php
namespace My\Models;

use PHPUnit\Framework\TestCase;
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


    public function wrongStructsProvider () {
        return [
            [ // 1
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 2
                "datetime" => "2025-12-12 12:12",
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 3
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
            ],
            [ // 4
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["quantity" => 20]]
            ],
            [ // 5
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => 5]]
            ],
            [ // 6
                "datetime" => "wrong",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 7
                "datetime" => "2000-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 8
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "wrong", "gender" => "F"],
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 9
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "A"],
                "basket" => [["price" => 5, "quantity" => 20]]
            ],
            [ // 10
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => "5", "quantity" => 20]]
            ],
            [ // 11
                "datetime" => "2025-12-12 12:12",
                "customer" => ["birthday" => "1962-04-08", "gender" => "F"],
                "basket" => [["price" => 5, "quantity" => 20.0]]
            ]
        ];
    }

    /**
     * @dataProvider wrongStructsProvider
     */
    public function testWrongStruct($post)
    {
        $this->expectException('\Exception');
        $order = new Order($post);
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
                ["price" => 22.99, "quantity" => 1],
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->priceCalc();
        $this->assertEquals($price, 122.99);
    }

    /**
     * Discount 5% of male age
     */
    public function testMaleAgeDiscount()
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
        $price = $order->priceCalc();
        $this->assertEquals($price, 95);
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-63 years')->modify('+1 day') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->priceCalc();
        $this->assertEquals($price, 100);
    }

    /**
     * Discount 5% of female age
     */
    public function testFemaleAgeDiscount()
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
        $price = $order->priceCalc();
        $this->assertEquals($price, 95);
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+1 day') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-58 years')->modify('+1 day') ),
                "gender" => "F"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->priceCalc();
        $this->assertEquals($price, 100);
    }

    /**
     * Discount 4% of order date
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
        $price = $order->priceCalc();
        $this->assertEquals($price, 96);
        $order = new Order([
            "datetime" => $this->printDatetime( (new DateTime('now'))->modify('+7 day')->modify('-1 minute') ),
            "customer" => [
                "birthday" => $this->printDate( (new DateTime('now'))->modify('-10 years') ),
                "gender" => "M"
            ],
            "basket" => [
                ["price" => 20, "quantity" => 5]
            ]
        ]);
        $price = $order->priceCalc();
        $this->assertEquals($price, 100);
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
                ["price" => 10, "quantity" => 9],
                ["price" => 10, "quantity" => 10]
            ]
        ]);
        $price = $order->priceCalc();
        $this->assertEquals($price, 187);
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
        $price = $order->priceCalc();
        $this->assertSame($price, 176.93);
    }
}

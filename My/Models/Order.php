<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Models;

use My\Engine\Model;
use \DateTime;

/**
 * 
 * Order object.
 * 
 */
class Order extends Model {
    
    /**
     * 
     * Order validation.
     * 
     */
    public function validate()
    {
        // Проверка структуры данных
        $this->verify_structure(
            [
                'datetime' => 's',
                'customer' => ['birthday' => 's', 'gender' => 's'],
                'basket' => []
            ],
            $this->data
        );

        // Проверка даты и времени
        try {
            $this->data['datetime'] = new DateTime($this->data['datetime']);
        } catch (\Exception $e) {
            throw new \Exception('Order datetime format is wrong.');
        }

        if ($this->data['datetime'] < new DateTime('now'))
            throw new \Exception('Order datetime can\'t be in past.');

        // Проверка данных покупателя
        try {
            $this->data['customer']['birthday'] = new DateTime($this->data['customer']['birthday']);
        } catch (\Exception $e) {
            throw new \Exception('Order customer.birthday format is wrong.');
        }

        if (!in_array($this->data['customer']['gender'], ['M','F']))
            throw new \Exception('Order customer.gender format is wrong.');

        // Проверка корзины
        foreach ($this->data['basket'] as $item) {
            $this->verify_structure(['price' => 'p', 'quantity' => 'i'], $item);
        }
    }

    /**
     * 
     * Order price calculation.
     * 
     */
    public function price_calc()
    {
        $price = 0;

        // 3. Скидка на количество товаров - если их больше 10 (не 10 разных 
        // видов товаров, а 10 единиц, например если выбрано 10 единиц пиццы, то 
        // скидка уже дается), дается скидка 3 %.
        foreach ($this->data['basket'] as $item) {
            $price += $item['price'] * $item['quantity'] * ($item['quantity'] >= 10? 0.97 : 1);
        }

        // 1. Скидка для пенсионеров 5 % (мужчины старше 63 лет включительно, 
        // женщины - старше 58 включительно)
        $age = ( new DateTime('now') )->diff( $this->data['customer']['birthday'] )->y;
        if (($this->data['customer']['gender'] === 'M' && $age >= 63) || ($this->data['customer']['gender'] === 'F' && $age >= 58)) {
            $price *= 0.95;
        }

        // 2. Скидка на ранний заказ - если заказ сделан за неделю и более, 
        // скидка составит 4 %
        $discount_from_date = ( new DateTime('now') )->modify('+7 days');
        if ($this->data['datetime'] >= $discount_from_date) {
            $price *= 0.96;
        }

        // Округляем до копеек
        $price = round($price * 100) / 100;

        return $price;
    }
    
}

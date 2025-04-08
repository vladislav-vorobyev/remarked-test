<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */
namespace My\Controllers;

use My\Engine\Storage;
use My\Models\Order;

/**
 * 
 * Order controller.
 * 
 */
class OrderController {

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;
    
    /**
     * 
     * Constructor.
     * 
     */
    public function __construct()
    {
        $this->response = Storage::get('Response');
        $this->request = Storage::get('Request');
    }

    /**
     * 
     * Price calculation handler.
     * 
     * @return Response current response
     */
    public function price_calc()
    {
        // Создаем объект на основе запроса
        $order = new Order($this->request->post);

        // Вычисляем скидку
        $price = $order->price_calc();

        // Ответ
        return $this->response->json([
            'price' => $price
        ]);
    }
}
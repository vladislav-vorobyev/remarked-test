<?php
/**
 * 
 * This file is part of remarked-test API project.
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
    public function priceCalc()
    {
        // Создаем объект на основе запроса
        $order = new Order($this->request->post);

        // Вычисляем скидку
        $price = $order->priceCalc();

        // Ответ
        return $this->response->json([
            'price' => $price
        ]);
    }
}
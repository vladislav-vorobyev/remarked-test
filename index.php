<?php
/**
 * 
 * This file is part of test1 API project.
 * 
 */

require './vendor/autoload.php';

use My\Engine\DI;
use My\Engine\Storage;

try {
    // создание глобальных сущностей
    DI::start();

    // задание обработчиков запросов
    $router = Storage::get('Router');
    $router->post('/api/v1/orders/price-calc/', ['OrderController', 'price_calc']);

    // запуск обработки
    $app = Storage::get('App');
    $app->run();
    
} catch (\Exception $e) {
    $response = Storage::get('Response');
    $response->json([
       'error' => $e->getMessage() 
    ], 400);
    $response->render();
}

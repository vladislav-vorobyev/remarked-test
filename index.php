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
    echo json_encode([
       'error' => $e->getMessage() 
    ]);
}

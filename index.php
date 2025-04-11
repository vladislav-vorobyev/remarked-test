<?php
/**
 * 
 * This file is part of remarked-test API project.
 * 
 */

require './vendor/autoload.php';

use My\Engine\DI;
use My\Engine\Storage;

try {
    // корень сайта
    $root_uri = getenv('ROOT_URI');
    if (empty($root_uri)) $root_uri = '/api/v1/';

    // создание глобальных сущностей
    DI::start($root_uri);

    // задание обработчиков запросов
    $router = Storage::get('Router');
    $router->setRoute('POST', '/orders/price-calc/', ['OrderController', 'priceCalc']);

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

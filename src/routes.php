<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', 'App\Controller\IndexController:index');


/**
 * API
 */
$app->group('/api/v1', function () use ($app) {

    $app->get('[/]', 'App\Controller\Api\IndexController:index');

    $app->group('/search', function () use ($app) {
        $app->get('[/[page/{page:\d+}]]', 'App\Controller\Api\ItemsController:search');
        $app->get('/{id:\d+}', 'App\Controller\Api\ItemsController:getItem');
        $app->post('/{id:\d+}', 'App\Controller\Api\ItemsController:saveItem');
    });
});
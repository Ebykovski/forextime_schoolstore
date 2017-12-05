<?php
$app->get('/', 'App\Controller\IndexController:index');

/**
 * API
 */
$app->group('/api/v1', function () use ($app) {

    $app->get('[/]', 'App\Controller\Api\IndexController:index');

    $app->group('/goods', function () use ($app) {
        $app->get('/search[/[page/{page:\d+}]]', 'App\Controller\Api\GoodsController:search');
        $app->get('[/[page/{page:\d+}]]', 'App\Controller\Api\GoodsController:listItems');
        $app->get('/{id:\d+}', 'App\Controller\Api\ItemsController:getItem');
        $app->post('/{id:\d+}', 'App\Controller\Api\ItemsController:saveItem');
    });
});

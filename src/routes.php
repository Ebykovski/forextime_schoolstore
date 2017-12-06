<?php
$app->get('/', 'App\Controller\IndexController:index');

/**
 * API
 */
$app->group('/api/v1', function () use ($app) {

    $app->get('[/]', 'App\Controller\Api\IndexController:index');

    $app->group('/goods', function () use ($app) {
        $app->get('[/[page/{page:\d+}]]', 'App\Controller\Api\GoodsController:listItems');
        $app->post('[/]', 'App\Controller\Api\GoodsController:addItem');
        $app->get('/{id:\d+}', 'App\Controller\Api\GoodsController:getItem');
        $app->post('/{id:\d+}', 'App\Controller\Api\GoodsController:saveItem');
        $app->any('/search[/[page/{page:\d+}]]', 'App\Controller\Api\GoodsController:search');
    });

    $app->group('/categories', function () use ($app) {
        $app->get('[/]', 'App\Controller\Api\CategoriesController:listItems');
        $app->get('/{id:\d+}', 'App\Controller\Api\CategoriesController:getItem');
        $app->get('/{id:\d+}/options', 'App\Controller\Api\CategoriesController:getOptions');
    });
});

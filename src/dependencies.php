<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    if ($c->get('request')->isXhr()) {
        return new \Slim\Views\JsonView();
    } else {
        $settings = $c->get('settings')['renderer'];
        return new Slim\Views\PhpRenderer($settings['template_path']);
    }
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger   = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Database
$container['db'] = function ($c) {
    $db_settings = $c->get('settings')['database'];

    $pdo = new \PDO(
        $db_settings['driver'].":"
        ."dbname=".$db_settings['dbname']
        .";host=".$db_settings['host']
        .($db_settings['port'] ? ";port=".$db_settings['port'] : '')
//        .($db_settings['charset'] ? ";charset=".$db_settings['charset'] : '')
        , $db_settings['user']
        , $db_settings['password']
    );

    return $pdo;
};


// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};
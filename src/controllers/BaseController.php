<?php

namespace App\Controller;

use Slim\Container;

abstract class BaseController
{

    protected $renderer;
    protected $logger;
    protected $flash;
    protected $db;
    protected $settings;

    public function __construct(Container $c)
    {
        $this->renderer = $c->get('renderer');
        //$this->logger = $c->get('logger');
        //$this->flash = $c->get('flash');
        $this->db = $c->get('db');
        $this->settings = $c->get('settings');
    }

}

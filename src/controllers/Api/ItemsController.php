<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Description of IndexController
 *
 * @author ebykovski
 */
final class ItemsController extends \App\Controller\BaseController
{

    public function search(Request $request, Response $response, $args)
    {
        $token = $request->getHeader('X-Auth-Token');

        $this->renderer->render($response,[
            'data'   => [
                'token' => $token
            ],
            'status' => 200
            ], 200);

        return $response;
    }

}

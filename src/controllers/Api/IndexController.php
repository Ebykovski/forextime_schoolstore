<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Description of IndexController
 *
 * @author ebykovski
 */
final class IndexController extends \App\Controller\BaseController
{
    public function index(Request $request, Response $response, $args)
    {

        //@TODO need realisation for work with tokens

        $token = $_SESSION['authToken'] ?:md5(mt_rand());

        $_SESSION['authToken'] = $token;

        $this->renderer->render($response, [
                'token' => $token,
                'expired' => 'session'
        ], 200);

        return $response;
    }
}

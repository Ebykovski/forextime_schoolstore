<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controller\AbstractController;

/**
 * IndexController
 *
 * @author ebykovski
 */
final class IndexController extends AbstractController
{

    /**
     * Get Auth token
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function index(Request $request, Response $response, $args)
    {

        //@TODO need realisation for work with tokens

        $token = $_SESSION['authToken'] ?: md5(mt_rand());

        $_SESSION['authToken'] = $token;

        return $this->jsonRenderer->render($response, [
                'token'   => $token,
                'expired' => 'session'
                ], 200);
    }

}

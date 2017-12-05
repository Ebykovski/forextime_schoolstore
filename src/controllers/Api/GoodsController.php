<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Model\GoodsMapper;

/**
 * Api GoodsController
 *
 * @author ebykovski
 */
final class GoodsController extends \App\Controller\BaseController
{

    public function listItems(Request $request, Response $response, $args)
    {
        $iPerPage = 25;
        $iPage = key_exists('page', $args) ? (int) $args['page'] : 1;

        $oGoodsMapper = new GoodsMapper($this->db);

        $aGoods = $oGoodsMapper
                ->setLimit($iPerPage)
                ->setPage($iPage)
                ->fetchAll();

        $this->renderer->render($response, [
            'data' => $aGoods,
            'status' => 200
                ], 200);

        return $response;
    }

    public function search(Request $request, Response $response, $args)
    {
        $iPerPage = 25;
        $iPage = key_exists('page', $args) ? (int) $args['page'] : 1;

        $oGoodsMapper = new GoodsMapper($this->db);

        $aGoods = $oGoodsMapper
                ->setLimit($iPerPage)
                ->setPage($iPage)
                ->fetchAll();

        $this->renderer->render($response, [
            'data' => $aGoods,
            'status' => 200
                ], 200);

        return $response;
    }

}

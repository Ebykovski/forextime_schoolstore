<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Model\CategoryMapper;

/**
 * Api CategoriesController
 *
 * @author ebykovski
 */
final class CategoriesController extends \App\Controller\BaseController
{

    public function listItems(Request $request, Response $response, $args)
    {
        $aItems = (new CategoryMapper($this->db))->fetchAll();

        $this->renderer->render($response, [
            'data' => $aItems
            ], 200);

        return $response;
    }

    public function getItem(Request $request, Response $response, $args)
    {
        $aCategory = (new CategoryMapper($this->db))->fetchById((int) $args['id']);

        $this->renderer->render($response, [
            'data' => $aCategory
            ], ($aCategory ? 200 : 404)
        );

        return $response;
    }

    public function getOptions(Request $request, Response $response, $args)
    {
        $iPerPage = 25;
        $iPage    = key_exists('page', $args) ? (int) $args['page'] : 1;

        $oGoodsMapper = new GoodsMapper($this->db);

        $aGoods = $oGoodsMapper
            ->setLimit($iPerPage)
            ->setPage($iPage)
            ->fetchAll();

        $this->renderer->render($response, [
            'data'   => $aGoods,
            'status' => 200
            ], 200);

        return $response;
    }

}

<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Model\CategoryMapper;
use App\Model\OptionMapper;

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

        return $this->renderer->render($response, [
                    'data' => $aItems
                        ], 200);
    }

    public function getItem(Request $request, Response $response, $args)
    {
        $aCategory = (new CategoryMapper($this->db))->fetchById((int) $args['id']);

        return $this->renderer->render($response, [
                    'data' => $aCategory
                        ], ($aCategory ? 200 : 404)
        );
    }

    public function getOptions(Request $request, Response $response, $args)
    {
        $category = (new CategoryMapper($this->db))->fetchById((int) $args['id']);

        $aData = [];

        if ($category) {
            $aOptions = (new OptionMapper($this->db))->getCategoryOptions($category);

            foreach ($aOptions as $item) {
                $aData[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'value' => $item->getValue()
                ];
            }
        }

        return $this->renderer->render($response, [
                    'data' => $aData
                        ], 200
        );
    }

}

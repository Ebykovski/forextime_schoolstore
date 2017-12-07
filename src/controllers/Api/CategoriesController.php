<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controller\AbstractController;
use App\Model\CategoryMapper;
use App\Model\OptionMapper;

/**
 * Api CategoriesController
 *
 * @author ebykovski
 */
final class CategoriesController extends AbstractController
{

    /**
     * List of all Categories
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function listItems(Request $request, Response $response, $args)
    {
        $aItems = (new CategoryMapper($this->db))->fetchAll();

        $aData = [];

        foreach ($aItems as $item) {
            $aData[] = [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData
                        ], 200);
    }

    /**
     * Single Category by id
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getItem(Request $request, Response $response, $args)
    {
        $aCategory = (new CategoryMapper($this->db))->fetchById((int) $args['id']);

        $aData = [];

        if($aCategory){
            $aData = [
                    'id' => $item->getId(),
                    'name' => $item->getName()
                ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData
                        ], ($aCategory ? 200 : 404)
        );
    }

    /**
     * Options for Category
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getOptions(Request $request, Response $response, $args)
    {
        $aCategory = (new CategoryMapper($this->db))->fetchById((int) $args['id']);

        $aData = [];

        if ($aCategory) {
            $aOptions = (new OptionMapper($this->db))->getCategoryOptions($aCategory);

            foreach ($aOptions as $item) {
                $aData[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'value' => $item->getValue()
                ];
            }
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData
                        ], ($aCategory ? 200 : 404)
        );
    }

}

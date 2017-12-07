<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controller\AbstractController;
use App\Model\GoodsMapper;
use App\Model\Goods;

/**
 * Api GoodsController
 *
 * @author ebykovski
 */
final class GoodsController extends AbstractController
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

        $aData = [];

        foreach ($aGoods as $item) {

            $aOptions = [];

            foreach ($item->getOptions() as $option) {

                switch ($item->getCategoryId()) {
                    // 1 - Books
                    case 1:
                        // name, authors, isbn
                        if (in_array($option->getId(), [1, 2, 4])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    // 2 - Pens
                    case 2:
                        // manufacturer, color
                        if (in_array($option->getId(), [5, 7])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    // 3 - Notebooks
                    case 3:
                        // manufacturer, cover
                        if (in_array($option->getId(), [5, 8])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    default:
                        break;
                }
            }

            $aData[] = [
                'id' => $item->getId(),
                'category' => [
                    'id' => $item->getCategory()->getId(),
                    'name' => $item->getCategory()->getName()
                ],
                'options' => $aOptions
            ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData,
                    'page' => $iPage,
                    'per_page' => $iPerPage,
                    'total' => $oGoodsMapper->foundRows()
                        ], 200);
    }

    public function getItem(Request $request, Response $response, $args)
    {
        $item = (new GoodsMapper($this->db))->fetchById((int) $args['id']);

        $aData = false;

        if ($item) {
            $aOptions = [];

            foreach ($item->getOptions() as $option) {

                $aOptions[] = [
                    'id' => $option->getId(),
                    'name' => $option->getName(),
                    'value' => $option->getValue()
                ];
            }

            $aData = [
                'id' => $item->getId(),
                'category' => [
                    'id' => $item->getCategory()->getId(),
                    'name' => $item->getCategory()->getName()
                ],
                'options' => $aOptions
            ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData
                        ], ($aData ? 200 : 404)
        );
    }

    public function saveItem(Request $request, Response $response, $args)
    {
        $item = (new GoodsMapper($this->db))->fetchById((int) $args['id']);

        $aOptionValues = $request->getParam('option');
        $iCategoryId = $request->getParam('category_id');


        //(new GoodsMapper($this->db))->save($item);

        return $this->jsonRenderer->render($response, [
                    'data' => $item
                        ], 200
        );
    }

    public function addItem(Request $request, Response $response, $args)
    {
        $item = new Goods($this->db);
        (new GoodsMapper($this->db))->save($item);

        return $this->jsonRenderer->render($response, [
                    'data' => 1
                        ], 500
        );
    }

    public function search(Request $request, Response $response, $args)
    {
        $iPerPage = 25;
        $iPage = key_exists('page', $args) ? (int) $args['page'] : 1;

        $sQueryString = $request->getParam('q');
        $iCategoryId = $request->getParam('category', false);

        $oGoodsMapper = new GoodsMapper($this->db);

        $aGoods = $oGoodsMapper
                ->setLimit($iPerPage)
                ->setPage($iPage)
                ->search($sQueryString, $iCategoryId);

        $aData = [];

        foreach ($aGoods as $item) {

            $aOptions = [];

            foreach ($item->getOptions() as $option) {

                switch ($item->getCategoryId()) {
                    // 1 - Books
                    case 1:
                        // name, authors, isbn
                        if (in_array($option->getId(), [1, 2, 4])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    // 2 - Pens
                    case 2:
                        // manufacturer, color
                        if (in_array($option->getId(), [5, 7])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    // 3 - Notebooks
                    case 3:
                        // manufacturer, cover
                        if (in_array($option->getId(), [5, 8])) {
                            $aOptions[] = [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue()
                            ];
                        }
                        break;
                    default:
                        break;
                }
            }

            $aData[] = [
                'id' => $item->getId(),
                'category' => [
                    'id' => $item->getCategory()->getId(),
                    'name' => $item->getCategory()->getName()
                ],
                'options' => $aOptions
            ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData,
                    'page' => $iPage,
                    'per_page' => $iPerPage,
                    'total' => $oGoodsMapper->foundRows()
                        ], 200);
    }

}

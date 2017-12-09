<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controller\AbstractController;
use App\Model\GoodsMapper;
use App\Model\Goods;
use App\Model\CategoryMapper;
use App\Model\OptionMapper;

/**
 * Api GoodsController
 *
 * @author ebykovski
 */
final class GoodsController extends AbstractController
{

    /**
     * Paginated list of all Goods
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function listItems(Request $request, Response $response, $args)
    {
        $iPerPage = 25;
        $iPage = key_exists('page', $args) ? (int) $args['page'] : 1;

        $oGoodsMapper = new GoodsMapper($this->db);

        $aGoods = $oGoodsMapper
                ->setLimit($iPerPage)
                ->setPage($iPage)
                ->fetchAll();

//        $aData = [];
//
//        foreach ($aGoods as $item) {
//
//            $aOptions = $this->filterOptionsForListByCategoryId($item->getOptions(), $item->getCategoryId());
//
//            $aData[] = [
//                'id' => $item->getId(),
//                'category' => [
//                    'id' => $item->getCategory()->getId(),
//                    'name' => $item->getCategory()->getName()
//                ],
//                'options' => $aOptions
//            ];
//        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aGoods,
                    'page' => $iPage,
                    'per_page' => $iPerPage,
                    'total' => $oGoodsMapper->foundRows()
                        ], 200);
    }

    /**
     * Single Goods by id
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
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

    /**
     * Save existing Goods
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function saveItem(Request $request, Response $response, $args)
    {

        $error = false;

        try {
            $oGoodsMapper = new GoodsMapper($this->db);
            $item = $oGoodsMapper->fetchById((int) $args['id']);

            if (!$item) {
                throw new \Exception('Goods not found');
            }

            $aOptionValues = $request->getParam('option', []);
            $iCategoryId = $request->getParam('category_id');

            $oCategory = (new CategoryMapper($this->db))->fetchById($iCategoryId);

            if (!$oCategory) {
                throw new Exception('Category not found');
            }

            $aCategoryOptions = (new OptionMapper($this->db))->getCategoryOptions($oCategory);

            foreach ($aCategoryOptions as &$option) {
                $option->setValue($aOptionValues[$option->getId()]);

                //@TODO add option value validation
            }

            $item->setCategory($oCategory);
            $item->setOptions($aCategoryOptions);

            $oGoodsMapper->save($item);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->jsonRenderer->render($response, [
                    'data' => [], //@TODO return inserted Goods?
                    'error' => $error
                        ], ($error ? 500 : 200)
        );
    }

    /**
     * Create new Goods
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function addItem(Request $request, Response $response, $args)
    {
        $error = false;

        try {
            $aOptionValues = $request->getParam('option', []);
            $iCategoryId = $request->getParam('category_id');

            $oCategory = (new CategoryMapper($this->db))->fetchById($iCategoryId);

            if (!$oCategory) {
                throw new Exception('Category not found');
            }

            $aCategoryOptions = (new OptionMapper($this->db))->getCategoryOptions($oCategory);

            foreach ($aCategoryOptions as &$option) {
                $option->setValue($aOptionValues[$option->getId()]);

                //@TODO add option value validation
            }

            $item = new Goods($this->db);

            $item->setCategory($oCategory);
            $item->setOptions($aCategoryOptions);

            (new GoodsMapper($this->db))->save($item);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->jsonRenderer->render($response, [
                    'data' => [], //@TODO return inserted Goods?
                    'error' => $error
                        ], ($error ? 500 : 200)
        );
    }

    /**
     * Delete Goods by id
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteItem(Request $request, Response $response, $args)
    {

        return $this->jsonRenderer->render($response, [
                    'error' => 'Not implemented'
                        ], 501
        );
    }

    /**
     * Search Goods
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
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

            $aOptions = $this->filterOptionsForListByCategoryId($item->getOptions(), $item->getCategoryId());

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

    /**
     *
     * @param array \App\Model\Option
     * @param integer $category_id
     * @return array
     */
    private function filterOptionsForListByCategoryId($options_list, $category_id)
    {
        $aOptions = [];

        foreach ($options_list as $option) {

            switch ($category_id) {
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

        return $aOptions;
    }

}

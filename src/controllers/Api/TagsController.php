<?php

namespace App\Controller\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controller\AbstractController;
use App\Model\TagsMapper;

/**
 * Api TagsController
 *
 * @author ebykovski
 */
final class TagsController extends AbstractController
{

    /**
     * Top 50 Tags
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function listItems(Request $request, Response $response, $args)
    {
        $aItems = (new TagsMapper($this->db))->fetchTop();

        $aData = [];

        foreach ($aItems as $item) {
            $aData[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'weight' => $item->getCount()
            ];
        }

        return $this->jsonRenderer->render($response, [
                    'data' => $aData
                        ], 200);
    }

}

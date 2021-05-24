<?php

namespace TheliaCollection\Controller\Front;

use OpenApi\Controller\Front\BaseFrontOpenApiController;
use OpenApi\Model\Api\ModelFactory;
use OpenApi\Service\OpenApiService;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Thelia\Core\HttpFoundation\Request;
use TheliaBlocks\Model\BlockGroupQuery;
use TheliaCollection\Model\TheliaCollection;
use TheliaCollection\Model\TheliaCollectionQuery;

/**
 * @Route("/open_api/collection", name="collection")
 */
class TheliaCollectionController extends BaseFrontOpenApiController
{
    /**
     * @Route("", name="_get", methods="GET")
     *
     * @OA\Get(
     *     path="/collection",
     *     tags={"Collection"},
     *     summary="Get collections",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="order",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"id", "id_reverse", "manual", "manual_reverse"},
     *              default="manual"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          description="The collection id",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="itemType",
     *          description="The type of the item linked to the collection",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="itemId",
     *          description="The id of the item linked to the collection (itemType has too be defined too)",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="code",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="onlyVisible",
     *          in="query",
     *          @OA\Schema(
     *              type="boolean",
     *              default="true"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="locale",
     *          in="query",
     *          description="Current locale by default",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/TheliaCollection")
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad request",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function getTheliaCollections(
        Request $request,
        ModelFactory $modelFactory
    ) {
        $theliaCollectionQuery = TheliaCollectionQuery::create();

        if (null !== $limit = $request->get('limit')) {
            $theliaCollectionQuery->limit($limit);
        }

        if (null !== $offset = $request->get('offset')) {
            $theliaCollectionQuery->offset($offset);
        }

        if (null !== $id = $request->get('id')) {
            $theliaCollectionQuery->filterById($id);
        }

        if (null !== $itemType = $request->get('itemType')) {
            $theliaCollectionQuery->filterByItemType($itemType);

            if (null !== $itemId = $request->get('itemId')) {
                $theliaCollectionQuery->filterByItemId($itemId);
            }
        }

        if (null !== $code = $request->get('code')) {
            $theliaCollectionQuery->filterByCode($code);
        }

        if (true === (bool) json_decode(strtolower($request->get('onlyVisible')))) {
            $theliaCollectionQuery->filterByVisible(true);
        }

        $order = $request->get('order');

        switch ($order) {
            case 'id':
                $theliaCollectionQuery->orderById(Criteria::ASC);
                break;
            case 'id_reverse':
                $theliaCollectionQuery->orderById(Criteria::DESC);
                break;
            case 'manual':
                $theliaCollectionQuery->orderByPosition(Criteria::ASC);
                break;
            case 'manual_reverse':
                $theliaCollectionQuery->orderByPosition(Criteria::DESC);
                break;
            default:
                $theliaCollectionQuery->orderByPosition(Criteria::ASC);
        }

        $theliaCollections = $theliaCollectionQuery->find();

        if (empty($theliaCollections)) {
            return OpenApiService::jsonResponse([], 404);
        }

        return OpenApiService::jsonResponse(array_map(
            fn (TheliaCollection $theliaCollection) => $modelFactory->buildModel('TheliaCollection', $theliaCollection, $request->get('locale')),
            iterator_to_array($theliaCollections)
        ));
    }
}

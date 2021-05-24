<?php

namespace TheliaCollection\Controller\Admin;

use OpenApi\Controller\Admin\BaseAdminOpenApiController;
use OpenApi\Model\Api\ModelFactory;
use OpenApi\Service\OpenApiService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use TheliaCollection\Model\Api\TheliaCollection;
use TheliaCollection\Service\CollectionService;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/open_api/collection", name="collection")
 */
class TheliaCollectionController extends BaseAdminOpenApiController
{
    /**
     * @Route("", name="_create", methods="POST")
     *
     * @OA\Post(
     *     path="/collection",
     *     tags={ "Collection"},
     *     summary="Create a collection",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                   @OA\Property(
     *                      property="itemId",
     *                      type="integer",
     *                  ),
     *                   @OA\Property(
     *                      property="itemType",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="code",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="title",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="visible",
     *                      type="boolean",
     *                  ),
     *                   @OA\Property(
     *                      property="locale",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="position",
     *                      type="integer",
     *                  )
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
    public function createCollection(
        Request $request,
        ModelFactory $modelFactory,
        CollectionService $collectionService
    )
    {
        $data = json_decode($request->getContent(), true);
        $locale = $this->findLocale($request, $data);

        /** @var TheliaCollection $openApiTheliaCollection */
        $openApiTheliaCollection = $modelFactory->buildModel('TheliaCollection', $data);
        $openApiTheliaCollection->validate(self::GROUP_CREATE);

        $collection = $collectionService->createCollection(
            $openApiTheliaCollection->getItemId(),
            $openApiTheliaCollection->getItemType(),
            $openApiTheliaCollection->getCode(),
            $openApiTheliaCollection->getTitle(),
            $openApiTheliaCollection->isVisible(),
            $locale,
            $openApiTheliaCollection->getPosition()
        );

        return OpenApiService::jsonResponse($modelFactory->buildModel('TheliaCollection', $collection, $locale));
    }

    /**
     *
     * @Route("/{collectionId}", name="_update", methods="PATCH", requirements={"collectionId"="\d+"})
     *
     * @OA\Patch(
     *     path="/collection/{collectionId}",
     *     tags={ "Collection"},
     *     summary="Update a collection",
     *     @OA\Parameter(
     *          name="collectionId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                   @OA\Property(
     *                      property="code",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="title",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="visible",
     *                      type="boolean",
     *                  ),
     *                   @OA\Property(
     *                      property="locale",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="position",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="positionMovement",
     *                      type="string",
     *                      enum={"up", "down"}
     *                  )
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
    public function updateCollection(
        $collectionId,
        Request $request,
        ModelFactory $modelFactory,
        CollectionService $collectionService
    )
    {
        $data = json_decode($request->getContent(), true);
        $locale = $this->findLocale($request, $data);

        $collection = $collectionService->updateCollection(
            $collectionId,
            $data['code']?? null,
            $data['title']?? null,
            $data['visible']?? null,
            $locale,
            $data['position']?? null,
            $data['positionMovement']?? null,
        );

        return OpenApiService::jsonResponse($modelFactory->buildModel('TheliaCollection', $collection, $locale));
    }

    /**
     *
     * @Route("/{collectionId}", name="_delete", methods="DELETE", requirements={"collectionId"="\d+"})
     *
     * @OA\Delete(
     *     path="/collection/{collectionId}",
     *     tags={ "Collection"},
     *     summary="Delete a collection",
     *     @OA\Parameter(
     *          name="collectionId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success"
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Bad request",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function deleteCollection(
        $collectionId,
        CollectionService $collectionService
    )
    {
        $collectionService->deleteCollection($collectionId);

        return new JsonResponse("Success", 204);
    }

    /**
     *
     * @Route("/{collectionId}/item", name="_item_add", methods="POST", requirements={"collectionId"="\d+"})
     *
     * @OA\Post(
     *     path="/collection/{collectionId}/item",
     *     tags={ "Collection"},
     *     summary="Add an item to a collection",
     *     @OA\Parameter(
     *          name="collectionId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                  required={"itemType", "itemId"},
     *                   @OA\Property(
     *                      property="itemType",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="itemId",
     *                      type="integer",
     *                  ),
     *                   @OA\Property(
     *                      property="position",
     *                      type="integer",
     *                  )
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
    public function associateItem(
        $collectionId,
        Request $request,
        CollectionService $collectionService,
        ModelFactory $modelFactory
    ) {
        $data = json_decode($request->getContent(), true);

        $collectionItem = $collectionService->associateItemToCollection(
            $data['itemId'],
            $data['itemType'],
            $collectionId,
            $data['position'] ?? null
        );

        return OpenApiService::jsonResponse($modelFactory->buildModel('TheliaCollection', $collectionItem->getTheliaCollection(), $this->findLocale($request)));
    }

    /**
     *
     * @Route("/{collectionId}/item", name="_item_update", methods="PATCH", requirements={"collectionId"="\d+"})
     *
     * @OA\Patch (
     *     path="/collection/{collectionId}/item",
     *     tags={ "Collection"},
     *     summary="Update an item association",
     *     @OA\Parameter(
     *          name="collectionId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                   required={"itemType", "itemId"},
     *                   @OA\Property(
     *                      property="itemType",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="itemId",
     *                      type="integer",
     *                  ),
     *                   @OA\Property(
     *                      property="position",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="positionMovement",
     *                      type="string",
     *                      enum={"up", "down"}
     *                  )
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
    public function updateItemAssociation(
        $collectionId,
        Request $request,
        CollectionService $collectionService,
        ModelFactory $modelFactory
    ) {
        $data = json_decode($request->getContent(), true);

        $collectionItem = $collectionService->updateItemAssociation(
            null,
            $data['itemId'],
            $data['itemType'],
            $collectionId,
            $data['position'] ?? null,
            $data['positionMovement'] ?? null,
        );

        return OpenApiService::jsonResponse($modelFactory->buildModel('TheliaCollection', $collectionItem->getTheliaCollection(), $this->findLocale($request)));
    }

    /**
     *
     * @Route("/{collectionId}/item", name="_item_delete", methods="DELETE", requirements={"collectionId"="\d+"})
     *
     * @OA\Delete (
     *     path="/collection/{collectionId}/item",
     *     tags={ "Collection"},
     *     summary="Delete an item association",
     *     @OA\Parameter(
     *          name="collectionId",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                   required={"itemType", "itemId"},
     *                   @OA\Property(
     *                      property="itemType",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="itemId",
     *                      type="integer",
     *                  )
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
    public function deleteItemAssociation(
        $collectionId,
        Request $request,
        CollectionService $collectionService,
        ModelFactory $modelFactory
    ) {
        $data = json_decode($request->getContent(), true);

        $collectionService->deleteItemAssociation(
            null,
            $data['itemId'],
            $data['itemType'],
            $collectionId
        );

        return OpenApiService::jsonResponse([]);
    }

    protected function findLocale(Request $request, $data = null)
    {
        $locale = $request->get('locale');

        if (null == $locale) {
            $locale = $data['locale'] ?? null;
        }

        if (null == $locale) {
            $locale = $request->getSession()->getAdminEditionLang()->getLocale();
        }

        return $locale;
    }
}

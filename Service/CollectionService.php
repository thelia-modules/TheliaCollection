<?php

namespace TheliaCollection\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\UpdatePositionEvent;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Translation\Translator;
use TheliaCollection\Model\TheliaCollection;
use TheliaCollection\Model\TheliaCollectionItem;
use TheliaCollection\Model\TheliaCollectionItemQuery;
use TheliaCollection\Model\TheliaCollectionQuery;

class CollectionService
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var Session */
    protected $session;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $requestStack->getCurrentRequest()->getSession();
    }

    public function createCollection(
        ?int $itemId = null,
        ?string $itemType = null,
        ?string $code = null,
        ?string $title = null,
        ?bool $visible = true,
        ?string $locale = null,
        ?int $position = null
    ): TheliaCollection {
        if (null === $title && null !== $code) {
            $title = $code;
        }

        return $this->createOrUpdateCollection(
            $itemId,
            $itemType,
            null,
            $code,
            $title,
            $visible,
            $locale,
            $position
        );
    }

    public function updateCollection(
        int $collectionId,
        ?string $code = null,
        ?string $title = null,
        ?bool $visible = true,
        ?string $locale = null,
        ?int $position = null,
        ?string $positionMovement = null
    ): TheliaCollection {
        return $this->createOrUpdateCollection(
            null,
            null,
            $collectionId,
            $code,
            $title,
            $visible,
            $locale,
            $position,
            $positionMovement
        );
    }

    public function deleteCollection(
        $collectionId
    ): bool {
        $collection = TheliaCollectionQuery::create()
            ->filterById($collectionId)
            ->findOne();

        if (null === $collection) {
            return false;
        }

        $collection->delete();

        return true;
    }

    public function associateItemToCollection(
        int $itemId,
        string $itemType,
        int $collectionId,
        ?int $position = null
    ): TheliaCollectionItem {
        $collectionItem = (new TheliaCollectionItem())
            ->setItemId($itemId)
            ->setItemType($itemType)
            ->setTheliaCollectionId($collectionId)
            ->setPosition($position);

        $collectionItem->save();

        return $collectionItem;
    }

    public function updateItemAssociation(
        ?int $collectionItemId = null,
        ?int $itemId = null,
        ?string $itemType = null,
        ?int $collectionId = null,
        ?int $position = null,
        ?string $positionMovement = null
    ) {
        $collectionItem = $this->findCollectionItem($collectionItemId, $itemId, $itemType, $collectionId);

        if (null === $collectionItem) {
            throw new \Exception(Translator::getInstance()->trans("Can't update a collection item that doesn't exist"));
        }

        if (null != $position) {
            $collectionItem->changeAbsolutePosition($position);
        }

        if ("up" === strtolower($positionMovement)) {
            $collectionItem->movePositionUp();
        }

        if ("down" === strtolower($positionMovement)) {
            $collectionItem->movePositionDown();
        }

        return $collectionItem;
    }

    public function deleteItemAssociation(
        ?int $collectionItemId = null,
        ?int $itemId = null,
        ?string $itemType = null,
        ?int $collectionId = null
    ) {
        $collectionItem = $this->findCollectionItem($collectionItemId, $itemId, $itemType, $collectionId);

        if (null === $collectionItem) {
            throw new \Exception(Translator::getInstance()->trans("Can't remove a collection item that doesn't exist"));
        }

        $collectionItem->delete();
    }

    protected function findCollectionItem(
        ?int $collectionItemId = null,
        ?int $itemId = null,
        ?string $itemType = null,
        ?int $collectionId = null
    ) {
        $collectionItemQuery = TheliaCollectionItemQuery::create();
        $filtered = false;

        if (null !== $collectionItemId) {
            $filtered = true;
            $collectionItemQuery->filterById($collectionItemId);
        }

        if (null !== $itemType) {
            $filtered = true;
            $collectionItemQuery->filterByItemType($itemType)
                ->filterByItemId($itemId)
                ->filterByTheliaCollectionId($collectionId);
        }

        if (!$filtered) {
            throw new \Exception(Translator::getInstance()->trans("Missing arguments to find the collection item to update"));
        }

        return $collectionItemQuery->findOne();
    }

    protected function createOrUpdateCollection(
        ?int $itemId = null,
        ?string $itemType = null,
        ?int $collectionId = null,
        ?string $code = null,
        ?string $title = null,
        ?bool $visible = true,
        ?string $locale = null,
        ?int $position = null,
        ?string $positionMovement = null
    ) {
        $collection = null !== $collectionId
            ? TheliaCollectionQuery::create()->filterById($collectionId)->findOne()
            : (new TheliaCollection())->setItemId($itemId)->setItemType($itemType);

        if (null === $collection) {
            throw new \Exception(Translator::getInstance()->trans("Can't update a collection that doesn't exist"));
        }

        if (null == $locale) {
            $locale = $this->session->getAdminEditionLang()->getLocale();
        }

        $collection->setLocale($locale);

        if (null != $code) {
            $collection->setCode($code);
        }

        if (null != $title) {
            $collection->setTitle($title);
        }

        if (null != $visible) {
            $collection->setVisible($visible);
        }

        if (null != $position) {
            $collection->changeAbsolutePosition($position);
        }

        if ("up" === strtolower($positionMovement)) {
            $collection->movePositionUp();
        }

        if ("down" === strtolower($positionMovement)) {
            $collection->movePositionDown();
        }

        $collection->save();

        return $collection;
    }

    public function updateItemPosition(
        ?int $itemId = null,
        ?int $position = null,
        ?int $positionMovement = null
    ) {
        $item = TheliaCollectionItemQuery::create()->findPk($itemId);

        if (null === $item) {
            throw new \Exception(Translator::getInstance()->trans("Can't update an item that doesn't exist"));
        }

        if (null != $position) {
            $item->changeAbsolutePosition($position);
        }

        if (UpdatePositionEvent::POSITION_UP === $positionMovement) {
            $item->movePositionUp();
        }

        if (UpdatePositionEvent::POSITION_DOWN === $positionMovement) {
            $item->movePositionDown();
        }

        $item->save();

        return $item;
    }
}

<?php

namespace TheliaCollection\Model;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Tools\PositionManagementTrait;
use TheliaCollection\Model\Base\TheliaCollectionItem as BaseTheliaCollectionItem;

/**
 * Skeleton subclass for representing a row from the 'thelia_collection_item' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class TheliaCollectionItem extends BaseTheliaCollectionItem
{
    use PositionManagementTrait;

    /**
     * Calculate next position relative to our product.
     */
    protected function addCriteriaToPositionQuery($query): void
    {
        /** @var $query TheliaCollectionItemQuery */
        $query->filterByTheliaCollectionId($this->getTheliaCollectionId());
    }

    /**
     * {@inheritDoc}
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        $this->setPosition($this->getNextPosition());

        parent::preInsert($con);

        return true;
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        parent::preDelete($con);

        $this->reorderBeforeDelete(
            [
                'thelia_collection_id' => $this->getTheliaCollectionId()
            ]
        );

        return true;
    }
}

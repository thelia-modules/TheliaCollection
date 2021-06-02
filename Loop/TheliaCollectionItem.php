<?php

namespace TheliaCollection\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaCollection\Model\TheliaCollectionItemQuery;

/**
 * Class TheliaCollectionItem.
 *
 * @method int    getId()
 * @method string getItemType()
 * @method int    getItemId()
 * @method int    getTheliaCollectionId()
 */
class TheliaCollectionItem extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id'),
            Argument::createIntTypeArgument('item_id'),
            Argument::createAlphaNumStringTypeArgument('item_type'),
            Argument::createIntTypeArgument('thelia_collection_id')
        );
    }

    public function buildModelCriteria()
    {
        $query = TheliaCollectionItemQuery::create();

        if (null !== $id = $this->getId()) {
            $query->filterById($id);
        }

        if (null !== $theliaCollectionId = $this->getTheliaCollectionId()) {
            $query->filterByTheliaCollectionId($theliaCollectionId);
        }

        if (null !== $itemId = $this->getItemId()) {
            $query->filterByItemId($itemId);
        }

        if (null !== $itemType = $this->getItemType()) {
            $query->filterByItemType($itemType);
        }

        $query->orderByPosition(Criteria::ASC);

        return $query;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \TheliaCollection\Model\TheliaCollectionItem $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow($entry);
            $row
                ->set('ID', $entry->getId())
                ->set('THELIA_COLLECTION_ID', $entry->getTheliaCollectionId())
                ->set('POSITION', $entry->getPosition())
                ->set('ITEM_TYPE', $entry->getItemType())
                ->set('ITEM_ID', $entry->getItemId())
            ;

            $this->addOutputFields($row, $entry);

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}

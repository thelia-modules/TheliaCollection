<?php

namespace TheliaCollection\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaCollection\Model\TheliaCollectionQuery;

/**
 * Class TheliaCollection.
 *
 * @method int    getId()
 * @method int    getItemId()
 * @method string getItemType()
 * @method string getCode()
 * @method bool   getOnlyVisible()
 * @method bool   getOrder()
 */
class TheliaCollection extends BaseI18nLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id'),
            Argument::createIntTypeArgument('item_id'),
            Argument::createAlphaNumStringTypeArgument('item_type'),
            Argument::createAlphaNumStringTypeArgument('code'),
            Argument::createBooleanTypeArgument('only_visible'),
            Argument::createAlphaNumStringTypeArgument('order'),
        );
    }

    public function buildModelCriteria()
    {
        $query = TheliaCollectionQuery::create();

        $this->configureI18nProcessing(
            $query,
            [
                'TITLE',
            ]
        );

        if (null !== $id = $this->getId()) {
            $query->filterById($id);
        }

        if (null !== $itemId = $this->getItemId()) {
            $query->filterByItemId($itemId);
        }

        if (null !== $itemType = $this->getItemType()) {
            $query->filterByItemType($itemType);
        }

        if (null !== $code = $this->getCode()) {
            $query->filterByCode($code);
        }

        if (true === $this->getOnlyVisible()) {
            $query->filterByVisible(true);
        }

        switch ($this->getOrder()) {
            case 'id':
                $query->orderById(Criteria::ASC);
                break;
            case 'id_reverse':
                $query->orderById(Criteria::DESC);
                break;
            case 'manual':
                $query->orderByPosition(Criteria::ASC);
                break;
            case 'manual_reverse':
                $query->orderByPosition(Criteria::DESC);
                break;
            default:
                $query->orderByPosition(Criteria::ASC);
        }

        return $query;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var \TheliaCollection\Model\TheliaCollection $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow($entry);
            $row
                ->set('ID', $entry->getId())
                ->set('TITLE', $entry->getVirtualColumn('i18n_TITLE'))
                ->set('CODE', $entry->getCode())
                ->set('VISIBLE', $entry->getVisible())
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

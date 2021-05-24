<?php

namespace TheliaCollection\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;
use TheliaCollection\TheliaCollection;

class TheliaCollectionBackHook extends BaseHook
{
    public function onProductTab(HookRenderBlockEvent $event): void
    {
    }

    public function onCategoryTab(HookRenderBlockEvent $event): void
    {
    }

    public function onContentTab(HookRenderBlockEvent $event): void
    {
        $itemId = $event->getArgument('id');
        $event->add(
            [
                'id' => 'theliaCollection_item_details',
                'title' => $this->trans('Thelia Collection', [], TheliaCollection::DOMAIN_NAME),
                'content' => $this->render(
                    'manage-collection.html',
                    [
                        'itemId' => $itemId,
                        'itemType' => 'content',
                    ]
                ),
            ]
        );
    }

    public function onFolderTab(HookRenderBlockEvent $event): void
    {
    }
}

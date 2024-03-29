<?php

namespace TheliaCollection\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TheliaCollection\TheliaCollection;

class TheliaCollectionBackHook extends BaseHook
{
    public function onModuleConfig(HookRenderEvent $event)
    {
        $event->add($this->render('thelia-collection-configuration.html'));
    }

    public function onProductTab(HookRenderBlockEvent $event): void
    {
        $this->renderHookTab($event, 'product');
    }

    public function onCategoryTab(HookRenderBlockEvent $event): void
    {
        $this->renderHookTab($event, 'category');
    }

    public function onContentTab(HookRenderBlockEvent $event): void
    {
        $this->renderHookTab($event, 'content');
    }

    public function onFolderTab(HookRenderBlockEvent $event): void
    {
        $this->renderHookTab($event, 'folder');
    }

    private function renderHookTab(HookRenderBlockEvent $event, string $itemType): void
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
                        'itemType' => $itemType,
                    ]
                ),
            ]
        );
    }
}

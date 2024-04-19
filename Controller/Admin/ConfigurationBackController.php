<?php

namespace TheliaCollection\Controller\Admin;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\UpdatePositionEvent;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use TheliaCollection\Event\CollectionUpdateObjectPositionEvent;
use TheliaCollection\Event\TheliaCollectionEvents;
use TheliaCollection\Form\CreateCollectionForm;
use TheliaCollection\Model\TheliaCollectionQuery;
use Symfony\Component\Routing\Annotation\Route;
use TheliaCollection\Service\CollectionService;

class ConfigurationBackController extends BaseAdminController
{
    /**
     * @Route("/admin/module/TheliaCollection/view", name="view", methods="GET")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('thelia_collection_id', 0);
        $theliaCollection = $this->getExistingObject($id);
        if (null !== $theliaCollection) {
            return $this->render('thelia-collection-view', ['thelia_collection_id' => $theliaCollection->getId()]);
        }

        return $this->pageNotFound();
    }

    /**
     * @Route("/admin/module/TheliaCollection/collection-list", name="view-list", methods="GET")
     */
    public function listCollectionAction(Request $request)
    {
        $id = $request->get('thelia_collection_id', 0);
        $html = $this->renderRaw('includes/collection-list', ['thelia_collection_id' => $id]);

        return (new JsonResponse())
            ->setContent(json_encode(['html' => $html]));
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject($id)
    {
        $theliaCollection = TheliaCollectionQuery::create()
            ->findOneById($id);

        if (null !== $theliaCollection) {
            $theliaCollection->setLocale($this->getCurrentEditionLocale());
        }

        return $theliaCollection;

    }

    /**
     * @Route("/admin/module/TheliaCollection/update-position", name="update_object_position_action") /
     */
    public function updateObjectPositionAction(EventDispatcherInterface $dispatcher, Request $request, CollectionService $collectionService)
    {
        try {
            $mode = $request->get('mode', null);

            if ($mode == 'up')
                $mode = UpdatePositionEvent::POSITION_UP;
            elseif ($mode == 'down')
                $mode = UpdatePositionEvent::POSITION_DOWN;
            else
                $mode = UpdatePositionEvent::POSITION_ABSOLUTE;

            $position = $request->get('position', null);
            $collectionId = $request->get('collection_id', null);
            $itemId = $request->get('item_id', null);
            $collectionService->updateItemPosition($itemId, $position, $mode);
        } catch (\Exception $ex) {
            // Any error
            return $this->errorPage($ex);
        }

        return $this->generateRedirect('/admin/module/TheliaCollection/view?thelia_collection_id=' . $collectionId);
    }

    /**
     * @Route("/admin/module/TheliaCollection/create-collection", name="create_collection_action") /
     */
    public function createCollectionAction(EventDispatcherInterface $dispatcher, Request $request, CollectionService $collectionService)
    {
        $createForm = $this->createForm(CreateCollectionForm::getName());

        try {
            $form = $this->validateForm($createForm, 'post');
            $data = $form->getData();
            $data;

            $collection = $collectionService->createCollection(
                null,
                $data['item_type'],
                $data['code'],
                $data['name'],
                true,
                $data['locale']
            );
        } catch (\Exception $ex) {
            // Any error
            return $this->errorPage($ex);
        }

        return $this->generateRedirect('/admin/module/TheliaCollection');
    }
}

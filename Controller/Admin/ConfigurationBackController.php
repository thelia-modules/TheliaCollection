<?php

namespace TheliaCollection\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use TheliaCollection\Model\TheliaCollectionQuery;
use Symfony\Component\Routing\Annotation\Route;

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
}

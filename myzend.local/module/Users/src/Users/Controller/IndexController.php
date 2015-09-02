<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController{

    public function indexAction(){
        $view = new ViewModel();
/*
        $collectionModel = new ViewModel();
        // Set the sidebar template
        $collectionGoodsModel->setTemplate('view/manager/index.phtml');
        // layout plugin returns the layout model instance
        // First parameter must be a model instance
        // and the second is the variable name you want to capture the content
        $view->addChild($collectionGoodsModel, 'goods');
*/
        return $view;
    }

}

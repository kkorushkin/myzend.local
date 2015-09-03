<?php

namespace Admin\Controller;

use Admin\Form\AdminCollectionManagerForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminViewModel;

class AdminOrdersManagerController extends AbstractActionController {

    public function indexAction(){
        $vm =  new AdminViewModel();
        return $vm->setTerminal(true);
    }

} 
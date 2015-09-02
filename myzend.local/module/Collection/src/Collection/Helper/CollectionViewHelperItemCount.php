<?php
namespace Collection\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class CollectionViewHelperItemCount extends AbstractHelper implements ServiceLocatorAwareInterface{

    public function __invoke($item_category = null){
        return $this->getServiceLocator()->getServiceLocator()->get('CollectionTable')->countByCategory($item_category);
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

} 
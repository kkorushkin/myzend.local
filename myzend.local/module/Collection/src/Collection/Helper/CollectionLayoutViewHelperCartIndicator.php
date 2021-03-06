<?php

namespace Collection\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Placeholder;
use Zend\Session\Container;

class CollectionLayoutViewHelperCartIndicator  extends  AbstractHelper implements ServiceLocatorAwareInterface{

    protected $serviceLocator;

    public function __invoke(){
        $user_session = new Container();
        $cart_id = $user_session->getDefaultManager()->getId();
        $result = $this->getServiceLocator()->getServiceLocator()->get('CartTable')->selectCartItemById($cart_id, $item_id = null, 'item_id');
        $result = $this->toArray($result);
        return (! is_null($result)) ? 'img/cart-header-full.png' : 'img/cart-header-empty.png' ;
    }

    public function toArray($obj){
        foreach($obj as $k=>$v){
            $myArray[$k] = $v;
        }
        return $myArray;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

}
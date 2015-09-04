<?php

namespace Collection\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Collection\Model\Cart;
use Collection\Model\CollectionViewModel;

class CollectionController extends AbstractActionController{

    protected $collectionTable;
    protected $cartTable;

    public function indexAction(){
        $session_user = new Container('user');
        $cart_id = $session_user->getDefaultManager()->getId();
        //$searchForm = $this->getServiceLocator()->get('SearchForm'); // already passed through LayoutViewHelper
        return new CollectionViewModel(array(
            'collection' => $this->getCollectionTable()->fetchAll(),
        ));
    }

    public function getCollectionTable(){
        if (! $this->collectionTable) {
            $this->collectionTable = $this->getServiceLocator()->get('CollectionTable');
        }
        return $this->collectionTable;
    }

    public function getCartTable(){
        if(! $this->cartTable){
            $this->cartTable = $this->getServiceLocator()->get('CartTable');
        }
        return $this->cartTable;
    }
/*
    public function formDbAdapterAction(){
        $vm = new ViewModel();
        $vm->setTemplate('collection/collection/add.phtml');
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $formDbSel = new DbAdapterForm($dbAdapter);
        return $vm->setVariables(array(
            'formDbAdapterActionFromCollectionController' => $formDbSel
        ));
    }
*/
    public function itemDetailsAction(){
        $item_id = strip_tags($this->params()->fromRoute('id'));
        $details = $this->getCollectionTable()->fetchById($item_id, 'group');
        $details = $this->toArray($details);
        $imgArray = $this->toArrayImg($details, 'img_link');
//die(var_dump($imgArray));
        $session_user = new Container();
        $ses_id = $session_user->getDefaultManager()->getId();

        $viewModel = new CollectionViewModel(array(
            'details' => $details[0],
            'img_s' => $imgArray,
            'is_in_cart' => $this->isInCart($ses_id, $item_id),
        ));
        return $viewModel;
    }

    public function isInCart($cart_id, $item_id){
        $result = $this->getServiceLocator()->get('CartTable')->selectCartItemById($cart_id, $item_id);
        return ($this->toArray($result)) ? '/img/already-in-cart.png' : '/img/zf2-my-logo-2.png';
    }

    public function toCartConfirmAction(){
        $item_id = $this->getRequest()->getPost()->value;
        $guest_session = new Container();
        $cart_id =$guest_session->getDefaultManager()->getId();
        $item = $this->getCollectionTable()->fetchById($item_id)->toArray();
        $vm = new ViewModel(array(
            'details' => $item[0],
            'is_in_cart' => $this->isInCart($cart_id, $item_id),
        ));
        $vm->setTerminal(true);
        return $vm;
    }
// NOTICE: get post data from collection page to-cart link via ajax
    public function toCartAction(){
        $item_id = $this->getRequest()->getPost()->item_id;
        $item_quantity = $this->getRequest()->getPost()->item_quantity;
        $item_price = $this->getRequest()->getPost()->item_price;
//die($item_id.'_'.$item_quantity.'_'.$item_price);
        //$item_id = $this->params()->fromRoute('id'); // need if non-ajax request
        $guest_session = new Container();
        $guest_session->sessid = $guest_session->getDefaultManager()->getId();

        $toExchange = $this->getCollectionTable()->fetchById($item_id)->toArray();
        //$toExchange = $this->toArray($details);
        $toExchange[0]['cart_id'] = $guest_session->sessid;
        $toExchange[0]['item_quantity'] = $item_quantity;
        $toExchange[0]['item_price'] = $item_price;

        $cart_item = new Cart();
        $cart_item->exchangeArray($toExchange[0]);
        $this->getCartTable()->insertCart($cart_item);
/*
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'collection',
            'action' => 'index',
        ));
*/
/*                                              // just check for workability
        $viewModel = new ViewModel(array(
            'details' => $details,
            'guest_session' => $guest_session
        ));
        return $viewModel;
*/
    }

    public function sortCollectionAjaxAction(){ // via ajax
        $value = $this->getRequest()->getPost()->value;
        $type = $this->getRequest()->getPost()->type;
        $collection =  $this->getServiceLocator()->get('CollectionTable')->fetchAll($value);
        $sort_type = $value;
        $vm = new CollectionViewModel(array(
            'collection' => $collection,
            'sort_type' => $type,
            'sort_price' => $sort_price,
        ));
        $vm->setTemplate('collection/collection/index-sorted.php');
        $vm->setTerminal(true); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        return $vm;
    }

    public function searchAction(){
        $char = strip_tags($this->getRequest()->getPost()->search_target);
//die($char);
        $details = $this->getServiceLocator()->get('CollectionTable')->fetchByName($char);
        $details = $this->toArray($details);
        $imgArray = $this->toArrayImg($details, 'img_link');
//die(var_dump($details[0]));
        $viewModel = new CollectionViewModel(array(
            'details' => $details[0],
            'img_s' => $imgArray,
        ));
        $viewModel->setTemplate('collection/collection/item-details.phtml');
        return $viewModel;

    }

    public function toArray($obj){
        foreach($obj as $k=>$v){
            $myArray[$k] = $v;
        }
        return $myArray;
    }

    public function toArrayImg($obj, $param){
        foreach($obj as $k=>$v){
            $i++;
            $myArray[$i] = $v->$param;
        }
//die(var_dump($myArray));
        return $myArray;
    }

    public function arrayToView(){}

}
?>
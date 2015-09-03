<?php

namespace Collection\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Collection\Model\Carts;
use Collection\Model\CollectionViewModel;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;

class CollectionController extends AbstractActionController{

    protected $collectionTable;
    protected $cartsTable;

    public function indexAction(){
        $session_user = new Container('user');
        $cart_id = $session_user->getDefaultManager()->getId();
        //$searchForm = $this->getServiceLocator()->get('SearchForm'); // already passed through LayoutViewHelper
        //$who_is = $this->whoIs();
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

    public function getCartsTable(){
        if(! $this->cartsTable){
            $this->cartsTable = $this->getServiceLocator()->get('CartsTable');
        }
        return $this->cartsTable;
    }
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
            //'who_is' => $this->whoIs()
        ));
        return $viewModel;
    }

    public function isInCart($cart_id, $item_id){
        $result = $this->getServiceLocator()->get('CartsTable')->selectCartItemById($cart_id, $item_id);
        return ($this->toArray($result)) ? '/img/already-in-cart.png' : '/img/zf2-my-logo-2.png';
    }

    public function toCartConfirmAction(){
        $item_id = $this->getRequest()->getPost()->value;
        $guest_session = new Container();
        $cart_id =$guest_session->getDefaultManager()->getId();
        $item = $this->getCollectionTable()->fetchById($item_id);
        $vm = new ViewModel(array(
            'details' => $item,
            'is_in_cart' => $this->isInCart($cart_id, $item_id),
        ));
        $vm->setTerminal(true);
        return $vm;
    }
// NOTICE: get post data from collection page to-cart link via ajax
    public function toCartAction(){
        $item_id = $this->getRequest()->getPost()->item_id;
        $order_quantity = $this->getRequest()->getPost()->item_quantity;
        $table = $this->getCollectionTable()->fetchById($item_id);
// check stock
        /*
        $item_qty = $table->item_quantity;
        if($order_quantity > $item_qty){
        }
        echo $item_qty;
die();
        */
        $item_price = $table->item_price;
        $order_total= $item_price * $order_quantity;
        //$item_id = $this->params()->fromRoute('id'); // need if non-ajax request
        $guest_session = new Container();
        $guest_session->sessid = $guest_session->getDefaultManager()->getId();
//die($guest_session->sessid);
        $user_id = $this->getUserId($guest_session->sessid);
//die(' id '.$item_id.' qty '.$item_quantity.' price '.$item_price.' user_id '.$user_id);
// object Collection
        $toExchange = $this->getCollectionTable()->fetchById($item_id);
        $toExchange = (array)$toExchange;
//die($toExchange['item_id']);
        $toExchange['cart_id'] = $guest_session->sessid;
        $toExchange['user_id'] = $user_id;
        $toExchange['item_quantity'] = $order_quantity;
        $toExchange['item_price'] = $order_total;

        $cart_item = new Carts();
        $cart_item->exchangeArray($toExchange);
        $this->getCartsTable()->insertCart($cart_item);
    }
    protected function getUserId($cart_id){
//die($cart_id);
        if(! is_null($this->identity())){
            $identity = $this->identity();
//die($identity);
            $user = $this->getUsersTable()->getUserByEmail($identity);
//die($user->user_id);
            $user_id = $user->user_id;
        }else{
            $user_id = $cart_id;
        }
        return $user_id;
    }

    public function getUsersTable(){
        return $usersTable = $this->getServiceLocator()->get('UsersTable');
    }

    public function sortCollectionAjaxAction(){
        $method = $this->getRequest()->getPost()->method;
        $category = $this->getRequest()->getPost()->category;
        $price_equal_to = $this->getRequest()->getPost()->price_equal_to;
        $more_then = $this->getRequest()->getPost()->more_then;
        $less_then = $this->getRequest()->getPost()->less_then;
        $color = $this->getRequest()->getPost()->color;
//die(__METHOD__."<br />\n\r".$category."<br />\n\r".$method."<br />\n\r".$price_equal_to."<br />\n\r".$more_then."<br />\n\r".$less_then);
        $collection = $this->getServiceLocator()->get('CollectionTable')->fetchAll($method, $category, $price_equal_to, $more_then, $less_then, $color);
        $vm = new CollectionViewModel(array(
            'collection' => $collection,
        ));
        return $vm->setTemplate('collection/collection/partial/collection.phtml')->setTerminal(true);
    }
    /*
    public function sortCollectionByTypeAction(){
        $type = $this->getRequest()->getPost()->type;
        $vm = new CollectionViewModel();
        switch($type):
            case 'SHOES':
                $this->shoesAction();
                break;
            case 'CLOTHES':
                $this->clothesAction();
                break;
            case 'GEAR':
                $this->gearAction();
                break;
            default:
                $vm->setTemplate('collection/collection/index-sorted.php');
                break;
        endswitch;
        $vm->setTerminal(true); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        return $vm;
    }
// Child-Routes Controller Methods
    public function shoesAction(){
        $value = '1';
        $type = 'SHOES';
        $collection = $this->getServiceLocator()->get('CollectionTable')->fetchAll($value);

        $vm = new CollectionViewModel(array(
            'collection' => $collection,
            'sort_type' => $type,
            //'color_picker' => $color_picker,
        ));

        //$this->getColorPikerTemplate($vm);
        $vm->setTemplate('collection/collection/shoes.phtml');
        return $vm;
    }

    public function clothesAction(){
        $value = '2';
        $type = 'CLOTHES';
        $collection =  $this->getServiceLocator()->get('CollectionTable')->fetchAll($value);
        $vm = new CollectionViewModel(array(
            'collection' => $collection,
            'sort_type' => $type,
        ));
        $vm->setTemplate('collection/collection/clothes.phtml');
        return $vm;
    }

    public function gearAction(){
        $value = '3';
        $type = 'GEAR';
        $collection =  $this->getServiceLocator()->get('CollectionTable')->fetchAll($value);
        $vm = new CollectionViewModel(array(
            'collection' => $collection,
            'sort_type' => $type,
        ));
        $vm->setTemplate('collection/collection/gear.phtml');
        return $vm->setTerminal(false);
    }
    */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
/*
    public  function  whoIs(){
            if($this->getAuthService()->hasIdentity()){
            return $this->getAuthService()->getIdentity().'<br /><a href="collection/logoutMe">[ logout ]</a><a href="">[ your profile ]</a>';
        }else{
            return 'welcome&nbsp;guest<br /><a href="users/login">[ login]</a><a href="users/registration">[ registration ]</a>';
        }
    }
*/
    protected   function getAuthService(){
        //print_r($this->request->getPost());
        if (! $this->authservice) {
            //$this->flashMessenger()->setNamespace('NotLogin')->addMessage('error');
            $dbAdapter = $this->getServiceLocator()->get(
                'Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter(
                $dbAdapter,'users','user_email','user_password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        return $this->authservice;
    }

    protected function  logoutMeAction(){
        $auth = $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(NULL, array(
            'controller' => 'collection',
            'action' => 'index'
        ));
    }

    protected function getColorPikerTemplate($vm){
        $color_picker = new ViewModel();
        $color_picker->setTemplate('collection/collection/color-picker.phtml');
        return $vm->addChild($color_picker, 'color_picker');
    }

    public function priceSliderAction(){
        return new CollectionViewModel();
    }

}

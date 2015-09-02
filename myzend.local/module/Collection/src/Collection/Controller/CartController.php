<?php

namespace Collection\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Collection\Model\Carts;
use Collection\Model\CartsTables;

use Zend\Session\Container;

class CartController  extends AbstractActionController{

    public function indexAction(){
        $sessid = new Container();
        $cart_id = $sessid->getDefaultManager()->getId();
        $cart = $this->getServiceLocator()->get('CartsTable')->selectCart($cart_id);
        $count = $cart->count();
        $is_empty = $this->isEmpty($cart);
        $disable_checkout = $this->disableCheckout($cart);

        $viewModel = new ViewModel(array(
            'is_empty' => $is_empty,
            'sessid' => $cart_id,
            'cart' => $cart,
            'count' => $count,
            'disable_checkout' => $disable_checkout
        ));

        $viewModel->setTerminal(true);
        return $viewModel;

    }
//  @param: string $cart_id
//  @return: recordSet $cart
    protected function getUserIdBySessionId($cart_id){
        if(! is_null($this->identity())){
            $identity = $this->identity();
            $user= $this->getUsersTable()->getUserByEmail($identity);
            $user_id = $user->user_id;
            return $cart = $this->getCartsTable()->selectCart($cart_id, $user_id);
        }else{
            return $cart = $this->getCartsTable()->selectCart($cart_id, $cart_id);
        }

    }

    private  function disableCheckout($cart){
        return ((bool)$this->isEmpty($cart) == 1) ? 'disabled="disabled"' : '';
    }

   public function  isEmpty($result){
      return ($result->count() == 0) ? '<div class="cart-informer">Your cart is empty.<br /><a>Continue shopping</a></div>' : '' ;
   }

    public function cartIteratorAction(){

    }

    public function cartShowAction(){

    }

    public function cartRemoveAction(){
        $item_id = $this->getRequest()->getPost('item_id');
        return $this->getServiceLocator()->get('CartsTable')->deleteCartById($item_id);
    }

    public function cartOrderAction(){

    }

    public function cartRefreshInformerAction(){

    }

    public function getCartsTable(){
        return $cartsTable = $this->getServiceLocator()->get('CartsTable');
    }


    public function getUsersTable(){
        return $usersTable = $this->getServiceLocator()->get('UsersTable');
    }

} 